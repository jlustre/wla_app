# WLA Phase 1 – Project Bootstrap & Core Foundation Checklist

## Step 1: Project Bootstrap

### Goal

By the end of this step, you should have:
- [x] App boots locally
- [x] Database connection works
- [x] Vite builds assets
- [x] Livewire is installed
- [x] Sanctum is installed
- [x] Spatie Permission is installed
- [x] sessions/cache/queue tables exist
- [x] Route files are separated
- [x] public/member/admin layouts exist
- [x] Base domain folders exist
- [x] Seeders for roles and admin user are created

---

## Step 1 – Detailed Tasks

- [x] Install Laravel via Composer
- [x] Install Livewire via Composer
- [x] Install Tailwind CSS (npm & config)
- [x] Configure Vite for asset bundling
- [x] Set up .env for MySQL (local)
- [x] Set up .env for Redis (local)
- [x] Install and configure Sanctum
- [x] Install Spatie/laravel-permission
- [x] Publish vendor configs as needed
- [x] Create domain-oriented folder structure in app/ (Domain, Services, Actions, etc.)
- [x] Test Livewire component renders
- [x] Test Tailwind classes render
- [x] Test database connection (migrate:fresh)
- [x] Test Redis/queue connection (queue:work)
- [ ] Commit initial baseline to version control

---

## Notes
- Use Laravel’s official Livewire starter kit as the base.
- Follow the folder structure and conventions in WLA-Tech-Spec.md.
- Mark each item as complete as you progress.

---

## References
- [WLA-Tech-Spec.md](WLA-Tech-Spec.md)
- [Laravel Livewire Docs](https://laravel-livewire.com/docs/)
- [Laravel Tailwind Install](https://laravel.com/docs/10.x/frontend#using-vite)
- [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
- [Spatie Permissions](https://spatie.be/docs/laravel-permission/v5/introduction)
