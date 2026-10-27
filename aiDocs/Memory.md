# FieldMA 2.0 SaaS — Project Memory & Active Context

## 1. Project Identity & Status Summary

- **Repository**: `fieldma-2.o-SaaS-new`
- **Current Active Branch**: `feature/saas-multitenancy`
- **Phase**: Multi-Tenant SaaS Evolution & Modernization
- **Primary Architect/Maintainer**: Manoj Shrestha
- **Platform Architecture**: Database-per-tenant Multi-Tenancy (powered by `stancl/tenancy 3.10` on Laravel & PHP 8.3)
- **Timezone**: `Asia/Kathmandu` (Nepal)

FieldMA was originally designed as a single-tenant, white-label sales force automation (SFA) application cloned per client instance (as historically documented in `fieldMA Clone.md`). The project is actively undergoing a transition into a scalable, multi-tenant SaaS platform where hundreds of client organizations operate securely on isolated databases managed through a centralized Super Admin control plane.

---

## 2. Active & Completed Modules

### 2.1 Central Super-Admin Console (`/super-admin`)
- **Authentication**: Dedicated `super_admin` guard using `super_admins` table and session storage.
- **Tenant Management**:
  - Full CRUD for tenant organizations with automatic database provisioning and slug-based routing.
  - One-click tenant maintenance operations:
    - **Toggle Status**: Instant toggle between `active` and `suspended`.
    - **Run Migrations**: Run `tenants:migrate` for an individual tenant.
    - **Rollback Migrations**: Run `tenants:rollback` for an individual tenant.
    - **Storage Symlink**: Run `storage:link`.
    - **Optimize & Clear**: Clear cache and warm configuration cache (`optimize:clear`, `config:cache`).
    - **Drop Database on Delete**: Optional cascading database deletion when destroying a tenant.
- **Subscription Plans Management**:
  - Full CRUD for tiered subscription plans (`starter`, `professional`, `enterprise`).
  - Configuration of monthly/yearly pricing, max mobile users, max admin users, and granular feature toggles.
  - Dynamic pricing and feature override rules per tenant.
- **CLI Migration Command (`tenant:import`)**:
  - Custom Artisan command `app/Console/Commands/ImportExistingTenantCommand.php` to migrate legacy single-tenant databases into the SaaS platform without loss of data.

### 2.2 Multi-Tenant Core Engine
- **Tenant Isolation**: Database-per-tenant (`tenant_{slug}`).
- **Domain & Subdomain Routing**:
  - Subdomains resolved automatically against central domains (`{slug}.localhost`, `{slug}.manojshrestha239.com.np`).
  - Custom domain support (e.g., `portal.clientcompany.com`).
  - Dedicated API header resolution (`X-Tenant: {slug}` or `?tenant={slug}`) for mobile clients via `InitializeTenancyForApi`.
- **Tenant Lifecycle Middleware**:
  - `CheckTenantStatus`: Intercepts suspended, inactive, or expired (trial/subscription) tenants with customized views (`errors.tenant-suspended`, `errors.tenant-expired`) or JSON errors.
  - `EnsureFeatureEnabled`: Gates routes according to the tenant's subscription plan permissions.
- **Dynamic White-Labeling**:
  - Dynamic company logo and favicon storage in `storage/app/public/branding/`.
  - Dynamic theme color injection into Blade templates via `:root { --primary-theme-color: ... }`.
  - Dynamic FCM topic generation (`{slug}_fieldMA_allUser`).

### 2.3 Sales Force Automation (SFA) & Field Management
- **Dealer & Territory Management**:
  - Hierarchy: Areas -> Subareas -> Assigned Areas -> Dealers.
  - Unlisted Dealers: Instant creation and reuse during ad-hoc field visits without re-entering data.
  - Fast chunk-based Excel import (`DealerImport`, `ProductImport`, `Bankimport`) via Maatwebsite Excel.
- **Field Staff Tracking & Attendance**:
  - Mobile user check-in, check-out, break-in, and break-out.
  - Real-time GPS location ingestion (`LocationController`, `SessionController`).
  - Live field staff tracking map and individual historical path rendering.
  - `AutoCheckout` console command to automatically check out inactive field personnel and notify them via FCM.
- **Order Booking & Distribution**:
  - Field order creation with product catalogs, pricing tiers, and real-time carts.
  - Delivery Challan generation and fulfillment workflows.
  - Comprehensive reporting (Order reports, MIS reports, Zero-order reports).
- **Collections & Financial Reconciliation**:
  - Cash and cheque payment collections recorded directly from dealer visits.
  - Bank denomination breakdowns (`Denomination`, `Denominationpoint`).
  - Dealer aging calculations and due balance reporting.
- **TADA (Travel & Daily Allowance) System**:
  - Multi-expense tracking: Dealer visits, transportation (KM remarks), meal allowances, hotel stays, miscellaneous.
  - Receipt image uploads and management.
  - Multi-tier authorization hierarchy: `TadaAuthorizedUser` and `TadaExpenseAuthorizedUser`.
- **Site Registration & Construction Tracking (V2)**:
  - Lead pipeline tracking for construction sites, paint/raw material estimations, contractors, and architectural stakeholders.
  - Follow-up scheduling with auto-reminders.
  - Modern implementation using strictly typed immutable DTOs, Enums, and dedicated Domain Services.
- **"My-Teams" Hierarchy**:
  - Senior-Junior reporting structure with closure tables (`MobileuserTeam`).
  - Real-time team monitoring dashboard for senior managers to observe junior field activities.
  - Automated FCM push notifications triggered when juniors check in or check out.
- **Nepal-Specific Localization**:
  - Built-in Gregorian (AD) to Bikram Sambat (BS) date conversion utilities (`CustomNepaliCalendarConverter`, `Nepali_Calendar`).

---

## 3. Known Technical Debt & Pending Refactors

### 3.1 Architectural Inconsistencies
1. **Legacy God Controllers vs Modern V2 Services**:
   - `app/Http/Controllers/OrderController.php` (1,000+ lines) and `app/Http/Controllers/API/TadaReportController.php` (1,300+ lines) contain business logic, raw DB queries, and response formatting mixed together.
   - *Target Refactor*: Migrate legacy controllers to the modern pattern established in `app/Services/v2/siteRegistration/` (`FormRequest` -> `Readonly DTO` -> `Service` -> `ApiResponse`).
2. **Leftover Artifacts & Duplicate Files**:
   The following legacy files exist in the tree and should be cleaned up:
   - `app/Http/Controllers/SubproductController copy.php`
   - `app/Http/Controllers/CartControllerold.php`
   - `app/Http/Controllers/TokenControllerold.php`
   - `app/Http/Controllers/ChallanControllereuro.php`
   - `resources/views/dashboards/dealer.blade.php` (0 bytes empty file)
   - `resources/views/welcomeno.blade.php` (superseded view)
3. **Frontend Architecture Duality**:
   - Central Super Admin: Modern Tailwind CSS 3.x, Inter font, custom Slate/Indigo/Red palette.
   - Tenant Portal: Legacy Metronic Layout 5 with Bootstrap 3/4 and jQuery plugins.
   - *Target Refactor*: Incrementally modernize tenant dashboard layouts toward clean Tailwind components while preserving dynamic CSS color variable hooks.

### 3.2 Database & Environment Caveats
1. **MySQL `strict: false`**:
   `config/database.php` has `strict => false` enabled on MySQL. Several legacy queries rely on non-aggregated `GROUP BY` columns. Moving toward strict mode requires refactoring historical attendance and report queries.
2. **Synchronous Tenant Database Provisioning**:
   In `app/Providers/TenancyServiceProvider.php`, `Jobs\CreateDatabase`, `Jobs\MigrateDatabase`, and `Jobs\SeedDatabase` have `shouldBeQueued(false)`. Under production traffic, tenant workspace creation must be pushed to a background Redis/database queue.
3. **Test Suite Coverage**:
   The `/tests` folder was previously gitignored and lacks automated test coverage. A dedicated feature test suite verifying tenant isolation, cross-tenant leak prevention, and API contracts is needed.

---

## 4. Important Configuration Notes & Gotchas

### 4.1 Central Domain Configuration
- Central domains must be accurately configured in `.env` under `CENTRAL_DOMAINS` (comma-separated):
  ```env
  CENTRAL_DOMAINS=manojshrestha239.com.np,localhost,127.0.0.1
  ```
- Subdomain routing strictly checks whether incoming hostnames end with one of these configured domains.
- When adding a tenant with slug `abc`, the generated primary domain is `abc.localhost` (local) or `abc.manojshrestha239.com.np` (remote).

### 4.2 Storage Symlinks in Multi-Tenant Context
- `config/tenancy.php` has `asset_helper_tenancy` set to `false`. Tenant assets and global assets are served via `/storage/`.
- After deploying or switching environments, always ensure storage is linked:
  ```bash
  php artisan storage:link
  ```
- Tenant logos and favicons are stored in the central `public` disk under `branding/logos/` and `branding/favicons/` to allow the central web server to serve them without switching database contexts.

### 4.3 Firebase Cloud Messaging (FCM) Credentials
- Production and development Firebase service account JSON files must reside in:
  ```text
  storage/app/fcm_files/fcm-prod.json
  storage/app/fcm_files/fcm-dev.json
  ```
- These files are gitignored for security. Ensure they are mounted or deployed securely in container environments.

### 4.4 Docker Networking with External Bihani Subscription System
- In `docker-compose.yml`, the application connects to an external network:
  ```yaml
  networks:
    shared:
      external: true
      name: ms_connect_to_bihani_subscription_system
  ```
- If running on a clean host where this Docker network has not yet been created, Docker Compose will fail to start. Create it with:
  ```bash
  docker network create ms_connect_to_bihani_subscription_system
  ```
