# Wealth Legacy Alliance (WLA) – Full Technical Specification

> Stack: Laravel 12 + Livewire + Tailwind + MySQL 8 + Redis + Sanctum

---

# 1. Executive Product Summary

## Overview

Wealth Legacy Alliance (WLA) is a centralized recruitment, genealogy, and enrollment‑routing platform for multi-company network marketing participation.

## Core Idea

* Join once → maintain a **universal alliance tree**
* Join companies later → system resolves **company-specific sponsor** via eligibility rules

## Key Differentiator

Dual-layer logic:

* **Alliance Tree (immutable truth)**
* **Company Routing (dynamic, rule-driven)**

## Primary Risks

* Placement disputes → must be fully auditable
* Compliance → must avoid hard-coded compensation logic

---

# 2. Core Business Rules

## Immutable

* Every member has exactly one alliance sponsor
* Alliance genealogy never changes (except audited admin override)

## Company Participation

* Member may be: inactive | pending | active | suspended per company

## Eligibility Routing

```
function resolveSponsor(user, company):
    current = user.directSponsor
    while current != null:
        if isEligible(current, company):
            return current
        current = current.parent
    return SYSTEM_FALLBACK
```

## Configurable (per company)

* qualification_rules (JSON)
* max_depth
* allow_spillover (bool)
* required_rank
* active_definition

---

# 3. Architecture

## Style

Modular Laravel Monolith → service extraction later

## Layers

* UI: Livewire + Blade + Tailwind
* App: Controllers, Actions
* Domain: Services (Genealogy, Eligibility, Rules)
* Infra: MySQL, Redis, Queues

## Domains

```
Domain/
 ├── Genealogy/
 ├── Eligibility/
 ├── Company/
 ├── Membership/
```

## Queues

* ResolveEligibilityJob
* SendNotificationJob
* RebuildTreeJob

## Events

* MemberRegistered
* CompanyJoined
* PlacementResolved

---

# 4. Modules

## Public

* Pages, Join with sponsor lookup

## Auth

* Register/Login/Verify

## Member

* Dashboard, referrals, alerts

## Genealogy

* Tree (React optional), ancestry path, logs

## Companies

* CRUD, rule builder (JSON editor)

## Membership

* join/leave, status lifecycle

## Routing Engine

* resolution, logs, simulation

## Notifications

* in-app + email (SMS-ready)

## Admin

* users, companies, overrides, logs

---

# 5. Database (Core Schema)

## users

* id PK
* username (unique, indexed)
* email (unique)
* password
* status
* created_at

## member_profiles

* id PK
* user_id FK
* full_name
* phone

## alliance_relationships

* id PK
* user_id FK (unique)
* sponsor_id FK
* path VARCHAR (materialized path, indexed)
* depth INT

## companies

* id PK
* name
* slug (unique)
* status

## company_memberships

* id PK
* user_id FK
* company_id FK
* status ENUM(active,inactive,pending)
* joined_at
* left_at
* UNIQUE(user_id, company_id)

## company_rule_sets

* id PK
* company_id FK
* rules_json JSON

## eligibility_resolutions

* id PK
* user_id FK
* company_id FK
* resolved_sponsor_id FK
* depth_checked
* created_at

## placement_logs (append-only)

* id PK
* user_id
* company_id
* original_sponsor_id
* assigned_to_id
* decision_path JSON
* reason
* created_at

## spillover_logs

* id PK
* skipped_user_id
* company_id
* reason

## notifications

* id PK
* user_id
* type
* payload JSON
* read_at

## audit_logs (immutable)

* id PK
* actor_id
* action
* entity_type
* entity_id
* meta JSON
* created_at

## Indexing

* users.username
* alliance_relationships.sponsor_id
* alliance_relationships.path (prefix index)
* company_memberships (user_id, company_id)

---

# 6. Genealogy Design

## Strategy

Materialized Path (fast traversal)

Example:

```
/1/5/20/33/
```

## Benefits

* Fast ancestor lookup
* Easy depth calculation

## Rebuild Strategy

* Recompute path via BFS
* Run via queued job

---

# 7. Workflows

## Register

* validate sponsor
* create user + relationship
* emit MemberRegistered

## Join Company

* create membership (pending)
* dispatch ResolveEligibilityJob

## Resolve Placement

* traverse path upward
* store eligibility_resolutions
* write placement_logs

## Override

* admin sets assigned_to
* write audit_logs + placement_logs

## Missed Opportunity

* detect when upline inactive
* send notification

---

# 8. Roles & Permissions

## Roles

* SuperAdmin
* AllianceAdmin
* CompanyAdmin
* Support
* Member

## Permissions (examples)

* users.manage
* companies.manage
* placements.override
* logs.view

---

# 9. UI/UX

## Member Sidebar

* Dashboard
* My Network
* My Companies
* Notifications

## Admin Sidebar

* Users
* Companies
* Rules
* Logs

## Tree UX

* lazy load children
* highlight active vs inactive

---

# 10. Security

* Sanctum auth
* RBAC via Spatie
* CSRF/XSS protection
* Rate limiting
* Encrypted fields (if PII)
* Full audit logging

---

# 11. Roadmap

## Phase 1 (MVP)

* Auth + sponsor registration
* Alliance tree
* Company membership
* Eligibility resolver
* Admin overrides

## Phase 2

* Notifications
* Reporting
* Tree UI (React optional)

## Phase 3

* Automation
* Analytics
* Scaling

---

# 12. Laravel Structure

```
app/
 ├── Domain/
 │    ├── Genealogy/
 │    ├── Eligibility/
 │    ├── Company/
 │    ├── Membership/
 ├── Services/
 ├── Actions/
 ├── Jobs/
 ├── Events/
 ├── Listeners/
 ├── Models/
 ├── Livewire/
 ├── Http/Controllers/
```

---

# 13. Engineering Standards

* Thin controllers
* Domain services for logic
* Event-driven side effects
* Queues for heavy ops
* Append-only logs

## Testing

* Unit: services
* Feature: workflows
* Integration: eligibility routing

---

# 14. API (Initial)

## Auth

* POST /api/register
* POST /api/login

## Members

* GET /api/me
* GET /api/tree

## Companies

* GET /api/companies
* POST /api/companies/join

## Admin

* POST /api/admin/override-placement

---

# 15. MVP Deliverables

## Core Tables

1. users
2. alliance_relationships
3. companies
4. company_memberships
5. placement_logs

## Core Screens

1. Register (with sponsor)
2. Dashboard
3. Tree view
4. Company join
5. Admin users

---

# 16. Next Steps

1. Generate full Laravel migrations
2. Build eligibility resolver service
3. Scaffold Livewire UI
4. Implement genealogy engine

---

# 17. Next Prompt

**Generate Laravel migrations, models, and relationships for Phase 1 (MVP), including indexes and foreign keys.**

---

# 18. Phase 1 Detailed Implementation Plan

## 18.1 Phase 1 Objective

Phase 1 is the foundation release of Wealth Legacy Alliance. Its purpose is to launch the core platform safely without introducing payout complexity, compensation calculations, or advanced automation too early.

The goal of Phase 1 is to deliver a working MVP that allows:

* public visitors to learn about the alliance
* prospects to register under a sponsor
* members to log in and view their account
* the system to maintain the alliance genealogy
* members to join affiliated companies
* the system to resolve company-specific eligible sponsors
* admins to manage users, companies, and overrides
* all important routing decisions to be auditable

## 18.2 What Phase 1 Includes

### Included

* Public marketing website
* Authentication
* Sponsor-based registration
* Member profile setup
* Alliance genealogy storage
* Company master data management
* Company membership enrollment
* Basic eligibility resolution
* Placement logging
* Admin override tools
* Audit logging
* Basic member dashboard
* Basic admin dashboard

### Explicitly Excluded from Phase 1

* Commission calculations
* Wallets and payouts
* Rank engines beyond simple qualification flags
* Deep analytics dashboards
* SMS integration
* Multi-language support
* Mobile app
* Full React genealogy visualization
* Advanced marketing automation
* Third-party company API integrations

## 18.3 Phase 1 Success Criteria

Phase 1 is considered successful when:

* a new member can register with a valid sponsor
* the alliance tree is stored correctly with depth and path
* admins can create and manage companies
* a member can join a company
* the system can resolve the next eligible sponsor for that company
* the resolution is logged with full traceability
* admins can override the placement with a required reason
* members can log in and view their basic network and company participation
* core workflows are covered by automated tests

## 18.4 Phase 1 Core Functional Scope

### A. Public Website

Purpose: establish credibility and route prospects into the registration flow.

Required pages:

* Home
* About WLA
* How It Works
* Affiliated Companies
* FAQ
* Contact
* Legal pages
* Join page with sponsor lookup

Required functionality:

* sponsor username entry on join page
* validation that sponsor exists and is active
* persistent sponsor capture in session/query string
* clean CTA paths into registration

### B. Authentication and Access

Required functionality:

* registration with sponsor username or sponsor referral code
* login/logout
* email verification
* forgot password / reset password
* role-aware redirect after login

Validation rules:

* unique email
* unique username
* sponsor required except approved root/admin accounts
* password policy enforced

### C. Member Onboarding

Required fields:

* username
* email
* password
* sponsor username
* first name
* last name
* mobile number
* country
* timezone
* terms acceptance
* privacy acceptance

Outputs:

* user created
* member profile created
* alliance relationship created
* audit log written
* onboarding completion status stored

### D. Alliance Genealogy

Purpose: preserve the universal sponsor relationship.

Phase 1 requirements:

* every member has one sponsor
* root accounts allowed only by admin seeding/configuration
* materialized path stored on create
* depth stored on create
* ancestry retrievable efficiently
* direct referrals queryable efficiently

### E. Company Management

Purpose: allow admin to manage affiliated companies and their base rules.

Required company fields:

* name
* slug
* description
* website_url
* enrollment_url
* logo_path
* status
* sort_order

Required rule set fields:

* company_id
* qualification_rules JSON
* max_depth
* allow_spillover
* fallback_mode
* active_definition

### F. Company Membership

Purpose: track which members joined which companies.

Required states:

* pending
* active
* inactive
* suspended

Required actions:

* member requests to join company
* admin optionally approves if manual review is needed
* system resolves eligible sponsor
* resolution saved
* placement logged

### G. Eligibility Resolver

Purpose: find the nearest eligible sponsor upward from the member's direct alliance sponsor.

Phase 1 rules should stay intentionally simple:

* user must have an active company membership in that company
* user must not be suspended
* depth search must not exceed company max_depth if provided
* if no eligible sponsor is found, use configured fallback account/system placement

### H. Audit and Logging

Must log:

* member registration
* sponsor assignment
* company join request
* eligibility resolution result
* placement override
* company activation/deactivation
* admin actions on critical records

### I. Admin Portal

Required sections:

* dashboard
* users
* companies
* company rules
* company memberships
* placement logs
* audit logs
* manual override screen

### J. Member Portal

Required sections:

* dashboard
* profile
* my sponsor
* direct referrals
* my companies
* notifications placeholder

## 18.5 Phase 1 User Stories

### Prospect Stories

* As a prospect, I can visit the WLA website and understand how it works.
* As a prospect, I can join under a sponsor using their username or referral link.

### Member Stories

* As a member, I can log in and see my sponsor.
* As a member, I can view my direct referrals.
* As a member, I can see which companies I have joined.
* As a member, I can request to join a company.

### Admin Stories

* As an admin, I can create affiliated companies.
* As an admin, I can define simple routing rules per company.
* As an admin, I can review placement logs.
* As an admin, I can override a placement and provide a reason.
* As an admin, I can suspend a member or a company membership.

## 18.6 Phase 1 Database Build Order

Recommended order of migrations:

1. users
2. member_profiles
3. companies
4. company_rule_sets
5. alliance_relationships
6. company_memberships
7. eligibility_resolutions
8. placement_logs
9. audit_logs
10. notifications

Why this order:

* users is foundational
* companies and rule sets are needed before company membership
* alliance relationships should be available before enrollment routing
* logs come after core entities

## 18.7 Phase 1 Model List

Primary Eloquent models:

* User
* MemberProfile
* Company
* CompanyRuleSet
* AllianceRelationship
* CompanyMembership
* EligibilityResolution
* PlacementLog
* AuditLog
* Notification

Recommended relationships:

* User hasOne MemberProfile
* User hasOne AllianceRelationship
* User belongsToMany Company through CompanyMembership (conceptually)
* Company hasMany CompanyMembership
* Company hasOne CompanyRuleSet
* EligibilityResolution belongsTo User
* EligibilityResolution belongsTo Company
* PlacementLog belongsTo User
* PlacementLog belongsTo Company

## 18.8 Phase 1 Domain Services

The following service classes should exist in Phase 1.

### RegistrationService

Responsibilities:

* validate sponsor
* create user
* create profile
* create alliance relationship
* emit MemberRegistered event

### GenealogyService

Responsibilities:

* compute member path
* compute depth
* fetch ancestors
* fetch descendants (basic)
* rebuild path when needed

### CompanyMembershipService

Responsibilities:

* join company
* activate/deactivate membership
* guard duplicate joins

### EligibilityResolverService

Responsibilities:

* resolve nearest eligible sponsor
* return decision trace
* enforce company max depth
* support fallback behavior

### PlacementService

Responsibilities:

* persist eligibility resolution
* persist placement log
* dispatch post-resolution events

### AuditLogService

Responsibilities:

* write immutable logs for critical changes

## 18.9 Phase 1 Jobs and Events

### Events

* MemberRegistered
* CompanyJoinRequested
* CompanyMembershipActivated
* PlacementResolved
* PlacementOverridden

### Jobs

* ResolveEligibilityJob
* SendWelcomeEmailJob
* RebuildGenealogyPathsJob

In Phase 1, jobs may initially run sync in local development and async in production.

## 18.10 Phase 1 Route Plan

### Public Web Routes

* GET /
* GET /about
* GET /how-it-works
* GET /companies
* GET /faq
* GET /contact
* GET /join

### Auth Routes

* GET /register
* POST /register
* GET /login
* POST /login
* POST /logout
* GET /forgot-password
* POST /forgot-password
* GET /reset-password/{token}
* POST /reset-password
* GET /email/verify

### Member Routes

* GET /dashboard
* GET /profile
* PUT /profile
* GET /my-sponsor
* GET /my-referrals
* GET /my-companies
* POST /my-companies/{company}/join

### Admin Routes

* GET /admin
* GET /admin/users
* GET /admin/companies
* GET /admin/company-rules
* GET /admin/company-memberships
* GET /admin/placements
* GET /admin/audit-logs
* POST /admin/placements/override

## 18.11 Phase 1 Screen-by-Screen Plan

### Public Home

Must include:

* hero section
* alliance explanation
* company opportunity overview
* CTA buttons for Join and Learn More

### Join Page

Must include:

* sponsor lookup field
* sponsor confirmation display
* registration CTA
* referral code capture

### Registration Page

Must include:

* sponsor information block
* account fields
* profile fields
* legal acceptance checkboxes

### Member Dashboard

Widgets:

* sponsor card
* direct referrals count
* total joined companies count
* latest company memberships
* latest alerts placeholder

### My Referrals

Must show:

* direct referrals list
* registration date
* status

### My Companies

Must show:

* company list
* membership state
* join button if not yet joined
* status badge

### Admin Users

Must show:

* searchable table
* sponsor username
* member status
* actions

### Admin Companies

Must show:

* create/edit company
* status toggle
* enrollment URL

### Admin Placement Logs

Must show:

* member
* company
* original sponsor
* resolved sponsor
* reason
* timestamp

### Admin Override Screen

Must include:

* target member
* company
* current resolved sponsor
* new sponsor
* reason required

## 18.12 Phase 1 Detailed Workflow Specifications

### Workflow 1: Member Registration

Trigger: prospect submits registration form.

Steps:

1. Validate sponsor username exists.
2. Validate sponsor account is eligible to sponsor in the alliance.
3. Validate email and username uniqueness.
4. Create user.
5. Create member profile.
6. Read sponsor's alliance path.
7. Create alliance relationship with:

   * sponsor_id
   * depth = sponsor depth + 1
   * path = sponsor path + new user id + '/'
8. Write audit log.
9. Emit MemberRegistered.
10. Redirect user to dashboard or verification notice.

Failure cases:

* invalid sponsor
* duplicate username/email
* race condition on username uniqueness
* partial writes

Implementation note:

* registration flow must be transactional

### Workflow 2: Join Company

Trigger: member clicks Join Company.

Steps:

1. Validate company is active.
2. Ensure member is not already active in company.
3. Create company_membership as pending or active depending on business policy.
4. Emit CompanyJoinRequested or CompanyMembershipActivated.
5. Dispatch ResolveEligibilityJob if activation occurs immediately.

Failure cases:

* duplicate join request
* inactive company
* membership suspended by admin

### Workflow 3: Resolve Eligible Sponsor

Trigger: active company membership is created.

Steps:

1. Load member's direct alliance sponsor.
2. Load company rule set.
3. Traverse upward through alliance genealogy.
4. At each ancestor, check:

   * active company membership exists
   * not suspended
   * satisfies basic rule set
5. If eligible, stop traversal.
6. If no eligible sponsor found, use configured fallback.
7. Save eligibility resolution.
8. Save append-only placement log with decision path JSON.
9. Emit PlacementResolved.
10. Write audit log if required.

Failure cases:

* missing alliance relationship
* missing company rule set
* no fallback configured
* circular/invalid genealogy data

### Workflow 4: Admin Override Placement

Trigger: admin submits override form.

Steps:

1. Validate admin permission.
2. Validate target company membership exists.
3. Validate replacement sponsor exists.
4. Save new override-based resolution.
5. Save placement log marked as override.
6. Save audit log with actor, before, after, reason.

Failure cases:

* unauthorized admin
* invalid replacement sponsor
* missing reason

## 18.13 Phase 1 Validation Rules

### Registration Validation

* username: required, alpha_dash, unique
* email: required, email, unique
* password: required, min length, confirmed
* sponsor_username: required, exists in users.username
* terms_accepted: accepted
* privacy_accepted: accepted

### Company Join Validation

* company must be active
* company_membership unique per user/company
* member account not suspended

### Override Validation

* reason required
* resolved sponsor cannot equal null unless fallback policy allows

## 18.14 Phase 1 Authorization Matrix

### Member

Can:

* edit own profile
* view own sponsor
* view own referrals
* join company
* view own company memberships

Cannot:

* override placements
* edit company rules
* access admin logs

### Alliance Admin

Can:

* manage users
* manage companies
* view logs
* override placements
* suspend memberships

### Support

Can:

* view users
* view memberships
* view logs (limited)
* cannot change company rules unless explicitly granted

## 18.15 Phase 1 Technical Build Sequence

### Step 1: Project Bootstrap

* create Laravel project
* install Livewire
* install Tailwind
* install Sanctum
* install Spatie Permission
* configure MySQL, Redis, queues

### Step 2: Authentication Foundation

* implement registration and login
* email verification
* password reset
* role seeding

### Step 3: Core Data Layer

* create migrations
* create models
* define relationships
* create factories and seeders

### Step 4: Registration + Sponsor Flow

* sponsor lookup
* registration transaction
* alliance relationship creation

### Step 5: Admin Company Management

* CRUD companies
* CRUD company rule sets

### Step 6: Company Membership + Eligibility Engine

* member join flow
* resolver service
* resolution logs

### Step 7: Dashboards

* member dashboard
* admin dashboard
* lists and logs

### Step 8: Testing + Hardening

* unit tests
* feature tests
* seed test data
* validation and authorization review

## 18.16 Phase 1 Sprint Plan

### Sprint 1: Foundation

* bootstrap app
* auth
* roles and permissions
* public pages skeleton

### Sprint 2: Registration and Genealogy

* sponsor lookup
* registration transaction
* alliance tree persistence
* profile pages

### Sprint 3: Company Admin

* company CRUD
* rule set CRUD
* admin tables

### Sprint 4: Membership and Resolver

* join company
* eligibility resolver
* placement logs
* override tool

### Sprint 5: Polish and QA

* dashboards
* audit logs
* test coverage
* seed/demo data

## 18.17 Phase 1 Test Plan

### Unit Tests

* GenealogyService path generation
* EligibilityResolverService traversal logic
* CompanyMembershipService duplicate prevention

### Feature Tests

* registration with valid sponsor
* registration rejected with invalid sponsor
* join company success
* join company duplicate rejection
* admin override success
* unauthorized override rejection

### Integration Tests

* full register → join company → resolve sponsor flow
* fallback sponsor behavior when no eligible upline exists

## 18.18 Phase 1 Seed Data Plan

Need base seeders for:

* roles and permissions
* root admin user
* demo sponsor chain
* 3 to 5 demo companies
* company rule sets
* sample memberships

## 18.19 Phase 1 Risks and Controls

### Risk: invalid genealogy data

Control: transactional writes + unique constraints + service-level validation

### Risk: placement disputes

Control: append-only placement logs + audit trail + required override reason

### Risk: overcomplicated rules too early

Control: keep Phase 1 eligibility minimal and configurable

### Risk: performance degradation

Control: materialized path + indexes + lazy-loaded descendant queries

## 18.20 Phase 1 Exit Criteria

Phase 1 is complete when:

* all core migrations are in place
* registration works end-to-end
* genealogy path/depth are correct
* company membership works end-to-end
* eligibility resolution works end-to-end
* placement logs are visible in admin
* override flow is secured and audited
* test suite passes for critical workflows
* seeded demo environment is usable for walkthroughs
