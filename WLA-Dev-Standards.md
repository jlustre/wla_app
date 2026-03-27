# WLA Development Standards

## Core Principles

- **Service Classes for Business Logic**
  - All business logic must reside in dedicated service classes (app/Services or app/Domain/*/).
  - Controllers and Livewire components should only coordinate requests and responses.

- **Transactions for Multi-Write Workflows**
  - Any workflow that writes to multiple tables (e.g., registration, placement) must use database transactions.

- **Append-Only Logs for Placement Decisions**
  - All placement decisions and overrides must be recorded in append-only log tables (e.g., placement_logs, audit_logs).

- **Policies for Authorization**
  - Use Laravel policies for all resource authorization. Do not check permissions in controllers or views directly.

- **Enums Instead of Magic Strings**
  - Use PHP enums for all status, type, and mode fields. Never use raw strings for these values in code.

- **Form Requests or Livewire Validation Rules**
  - All input validation must use Form Request classes or Livewire component rules. Do not validate in controllers or services.

- **One Responsibility per Service**
  - Each service class should have a single, clear responsibility. Split logic into multiple services if needed.

## Explicitly Prohibited

- **No Sponsor-Resolution Logic in Controllers or Livewire Components**
  - All sponsor and eligibility resolution logic must be in dedicated service classes only.

---

## How to Apply
- Reference this file in code reviews and onboarding.
- Update only with team consensus.
- Violations should be refactored before merging.

---

## References
- See WLA-Tech-Spec.md for architecture and workflow details.
- See app/Enums for all enum definitions.
