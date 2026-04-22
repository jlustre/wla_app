# Wealth Legacy Alliance (WLA)

> Centralized recruitment, genealogy, and enrollment-routing platform for multi-company network marketing participation.

---

## Stack

- Laravel 12
- Livewire
- Tailwind CSS
- MySQL 8
- Redis
- Sanctum
- Spatie Permission

---

## Overview

WLA enables members to join once and maintain a universal alliance tree, then join companies later with company-specific sponsor resolution via configurable eligibility rules. The platform is designed for auditability, compliance, and modular growth.

### Key Features
- Immutable alliance genealogy (materialized path)
- Company-specific routing and eligibility
- Configurable rules per company
- Full audit logging and append-only placement logs
- Modular Laravel monolith architecture
- Role-based access (SuperAdmin, AllianceAdmin, Support, Member)

---

## Core Modules

- Public: Marketing pages, sponsor lookup, registration
- Auth: Register, login, verify, password reset
- Member: Dashboard, profile, genealogy, company participation
- Admin: User/company management, rule builder, placement logs, overrides
- Genealogy: Tree view, ancestry path, logs
- Routing Engine: Eligibility resolution, logs, simulation

---

## Database (Core Tables)

- users
- member_profiles
- alliance_relationships
- companies
- company_rule_sets
- company_memberships
- eligibility_resolutions
- placement_logs
- audit_logs
- notifications

---

## Engineering Standards

- Thin controllers, domain services for logic
- Event-driven side effects, queues for heavy ops
- Append-only logs for placement decisions
- Policies for authorization
- Enums for status/type fields
- Form requests or Livewire validation rules
- One responsibility per service

---

## API (Initial)

- POST /api/register
- POST /api/login
- GET /api/me
- GET /api/tree
- GET /api/companies
- POST /api/companies/join
- POST /api/admin/override-placement

---

## Phase 1 MVP

**Core Deliverables:**
- Registration with sponsor
- Alliance tree
- Company membership
- Eligibility resolver
- Admin overrides

**Core Screens:**
- Register
- Dashboard
- Tree view
- Company join
- Admin users

---

## Phase 1 Authentication Features

**Required:**
- Register
- Login
- Logout
- Email verification
- Forgot password
- Reset password
- Remember me
- Auth redirects (after login/registration)
- Middleware protection for routes
- Role-based dashboard routing (member/admin)

**Optional for later:**
- Two-factor authentication (2FA)
- Social login (Google, Facebook, etc.)
- Device/session management UI
- Admin impersonation

---

## Authentication Approach

WLA uses a single users table and a unified authentication system for all user types (members, admins, support, etc.).

- No separate admin or member guards
- No multiple login systems
- All users authenticate through the same mechanism
- Role-based authorization (via Spatie Permission) determines access to admin/member features

**Why?**
- All users are part of one platform and may change roles over time
- Simpler session, testing, and maintenance
- Clean, scalable, and secure

**Model:**
- Authenticate once
- Authorize by role and permission

---

## Development & Contribution

See [WLA-Tech-Spec.md](WLA-Tech-Spec.md) for full technical specification, workflows, and standards.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
