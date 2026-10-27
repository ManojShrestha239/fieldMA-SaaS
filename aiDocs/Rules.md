# FieldMA 2.0 SaaS — Development Rules & Engineering Standards

## 1. Overview & Core Philosophy

FieldMA 2.0 is a B2B multi-tenant Sales Force Automation (SFA) and Field Operations Management platform built on Laravel and PHP 8.3. The system supports multi-organization tenant isolation with dedicated databases per tenant, dynamic domain routing, real-time GPS tracking, mobile workforce management, and enterprise billing/subscription plans.

These rules govern all code contributions, architectural implementations, and operational procedures within this repository. All engineers must adhere strictly to these standards to maintain codebase hygiene, multi-tenant isolation, security, and backward compatibility.

---

## 2. Environment & Tooling Requirements

### 2.1 Runtime & Tool Versions
- **PHP**: `^8.2` (production target: `8.3.0` via Alpine container `php:8.3-fpm-alpine`)
- **Composer**: Composer 2.x
- **Web Server**: Nginx `stable-alpine` (reverse proxy configuring FastCGI to `app:9000` with `client_max_body_size 100M`)
- **Database**: MySQL 8.0 (platform `linux/amd64`, storage engine InnoDB, default collation `utf8mb4_unicode_ci`, SQL mode note: `strict: false`)
- **Node.js**: Node 18+ LTS / NPM 9+
- **Frontend Assets**: Laravel Mix 6 / Sass / Vite (Tailwind CSS 3.x CDN for Super Admin; Metronic Layout 5 + Bootstrap for Tenant web)
- **Containerization**: Docker Compose (`app`, `nginx`, `mysql`, `phpmyadmin`, `cloudflared`, `scheduler`)

### 2.2 Docker Local Setup Standards
- Application containers run on network `fieldma_saas_net` and link to external network `ms_connect_to_bihani_subscription_system` where applicable.
- Port allocations:
  - Nginx: `80:80`
  - MySQL Host Port: `3307` mapped to internal `3306` (prevents collision with local MySQL)
  - phpMyAdmin: `8082:80`
- The `scheduler` container executes `while true; do php artisan schedule:run; sleep 60; done`.
- Always run Artisan and Composer commands through docker when running in containerized mode:
  ```bash
  docker compose exec app php artisan <command>
  docker compose exec app composer <command>
  ```

---

## 3. Architectural Boundaries & Multi-Tenancy Rules

### 3.1 Landlord (Central) vs Tenant Separation
FieldMA operates two strictly separated execution contexts powered by `stancl/tenancy`:

```
┌────────────────────────────────────────────────────────┐
│                   Central / Landlord                   │
│  - DB: fieldma_saas_db                                 │
│  - Guard: super_admin                                  │
│  - Models: SuperAdmin, Tenant, Domain, SubscriptionPlan│
│  - Routes: routes/web.php                              │
│  - Views: resources/views/super-admin/**               │
└────────────────────────────────────────────────────────┘
                           │
             Domains: localhost, 127.0.0.1,
                      manojshrestha239.com.np
                           │
                           ▼
┌────────────────────────────────────────────────────────┐
│                   Tenant Workspaces                    │
│  - DB: tenant_{slug} (Dedicated DB per tenant)         │
│  - Guard: web (Admins/Staff), api (JWT Mobile Users)   │
│  - Models: User, Mobileuser, Dealer, Order, Tada...   │
│  - Routes: routes/tenant.php -> routes/tenant_web.php  │
│  - Views: resources/views/dashboards/**                │
└────────────────────────────────────────────────────────┘
```

#### Mandatory Rules:
1. **Never query tenant models from Central context without initializing tenancy**:
   ```php
   // FORBIDDEN
   $dealers = Dealer::all(); // Will execute against central DB and fail!

   // REQUIRED
   $tenant->run(function () {
       $dealers = Dealer::all();
   });
   ```
2. **Never access Central models inside Tenant requests**:
   Tenant controllers and services must not query `SuperAdmin` or directly manipulate the central `tenants` table unless explicitly utilizing landlord repository services.
3. **Always use helper functions for tenant metadata**:
   - `tenant()`: Current tenant instance.
   - `tenant_has_feature('tada')`: Feature flag verification.
   - `tenant_brand_color()`: Dynamic primary brand hex color.
   - `tenant_logo_url()`: Tenant-specific logo.
   - `tenant_favicon_url()`: Tenant-specific favicon.
4. **Tenant Database Migrations**:
   - Central migrations live strictly in `database/migrations/`.
   - Tenant-specific migrations live strictly in `database/migrations/tenant/`.
   - Never run `php artisan migrate` expecting tenant schemas to update. Always run:
     ```bash
     php artisan tenants:migrate
     ```

### 3.2 Layered Architecture Pattern
Code written for modern features (such as `v2` modules) must follow clean separation of concerns:

```
HTTP Request
     │
     ▼
[FormRequest]  ───────> Validates input & casts data types
     │
     ▼
[Controller]   ───────> Passes FormRequest into DTO factory; calls Service
     │
     ▼
[DTO]          ───────> Immutable readonly payload (Strictly typed)
     │
     ▼
[Service]      ───────> Encapsulates business logic, transactions, external calls
     │
     ▼
[Eloquent Model / QueryBuilder]
     │
     ▼
[API Resource / View] ──> Uniform JSON Response via ApiResponse trait or Blade View
```

1. **Controllers must remain thin**:
   Controllers only validate input, instantiate DTOs, invoke domain services, and return responses. No database queries (`DB::table(...)`, complex `where()` chains) or business decisions belong inside controllers.
2. **Data Transfer Objects (DTOs)**:
   - Must be `final readonly class`.
   - Must use constructor property promotion.
   - Must enforce strict types.
   - Example template:
     ```php
     declare(strict_types=1);

     namespace App\DTO\v2\siteRegistration;

     final readonly class StoreSiteRegistrationDTO
     {
         public function __construct(
             public string $siteName,
             public int $siteTypeId,
             public float $lat,
             public float $lng,
             public ?string $preferredBrand = null,
         ) {}

         public static function fromRequest(\Illuminate\Foundation\Http\FormRequest $request): self
         {
             $data = $request->validated();
             return new self(
                 siteName: $data['site_name'],
                 siteTypeId: (int) $data['site_type_id'],
                 lat: (float) $data['lat'],
                 lng: (float) $data['lng'],
                 preferredBrand: $data['preferred_brand'] ?? null,
             );
         }
     }
     ```
3. **Services**:
   - Business operations modifying multiple models must be wrapped in `DB::transaction()`.
   - Services must throw descriptive domain exceptions or standard HTTP exceptions (`HttpException`, `ValidationException`).
4. **Form Requests**:
   All POST, PUT, and PATCH routes must use dedicated `FormRequest` classes instead of `$request->validate()` in controllers.

---

## 4. Coding Standards & Conventions

### 4.1 Strict Typing & PHP 8.3 Features
- Every new PHP file must start with `declare(strict_types=1);`.
- All method parameters and return types must be explicitly typed.
- Use PHP 8.3 match expressions, typed class constants, enums, null-safe operators (`?->`), and readonly classes where applicable.

### 4.2 Naming Conventions
- **Classes & Traits**: `PascalCase` (e.g., `SiteRegistrationService`, `CustomNepaliCalendarConverter`).
- **Methods**: `camelCase` (e.g., `getTeamMembers`, `resolveRouteBinding`).
- **Database Tables**: `snake_case`, pluralized (e.g., `dealers`, `tada_entries`, `subscription_plans`). Note: Preserve legacy table names where established (`orderitems`, `assignareas`, `producimages`).
- **Database Columns**: `snake_case` (e.g., `company_name`, `subscription_plan_id`, `is_physical_visit`).
- **Blade Files**: `kebab-case` or `snake_case` matching directory standards (e.g., `tenant-suspended.blade.php`, `side-menu.blade.php`).
- **Routes**:
  - Web: `kebab-case` URLs, dot notation route names (`super-admin.tenants.toggle-status`).
  - Mobile API: Keep consistency with existing endpoints (`/dealerWithboundry`, `/countAmountandVisit`, `/site-registrations`).

### 4.3 Nepali Date & Calendar Conversion Rules
The application serves businesses operating in Nepal. Date operations frequently toggle between Gregorian (AD) and Bikram Sambat (BS).
- When handling BS dates in requests, always convert to AD using `CustomNepaliCalendarConverter` or `Nepali_Calendar` before persisting to MySQL `date` or `datetime` columns.
- Never write ad-hoc date string splitting (`explode('-', $date)`) without validating against standard Nepali calendar year ranges (2000 BS to 2090 BS).

---

## 5. Git Workflow & Branching Strategy

### 5.1 Branch Topology
- **`main`**: Production-ready, deployable code. Direct pushes are forbidden.
- **`develop`**: Integration branch for upcoming releases.
- **`feature/<feature-name>`**: Branch created for all new features (e.g., `feature/saas-multitenancy`, `feature/tada-v2`).
- **`bugfix/<issue-description>`**: Non-emergency fixes branching from `develop`.
- **`hotfix/<issue-description>`**: Critical production fixes branching directly from `main` and merged into both `main` and `develop`.

### 5.2 Commit Message Standard
Follow the Conventional Commits specification:
```text
<type>(<scope>): <short description in imperative mood>

[optional body explaining 'why' and context]

[optional footer(s), e.g. Closes #123]
```
Valid types:
- `feat`: New feature or major enhancement.
- `fix`: Bug fix.
- `refactor`: Code change that neither fixes a bug nor adds a feature.
- `docs`: Documentation updates.
- `style`: Formatting, missing semi-colons, Tailwind styling.
- `chore`: Dependency updates, build configs, docker setup.
- `test`: Adding or refactoring tests.

*Example:*
```text
feat(tenant): implement automated tenant database rollback and cache optimization
```

### 5.3 Pull Request Protocol
1. Rebase feature branch on the latest `origin/develop` before opening PR.
2. Ensure code passes `composer pint` formatting.
3. Verify tenant migrations execute both forwards (`migrate`) and backwards (`rollback`).
4. At least one code review approval is required before merging.

---

## 6. Testing & Quality Assurance

### 6.1 Testing Standards
- Test suite is powered by PHPUnit 11 (`phpunit.xml`).
- Unit tests live in `tests/Unit/`; Feature tests live in `tests/Feature/`.
- Fast in-memory testing configuration is maintained in `phpunit.xml` (`CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `BCRYPT_ROUNDS=4`).

### 6.2 Multi-Tenant Testing Rules
1. Any test touching tenant models must initialize tenant context using:
   ```php
   $tenant = Tenant::factory()->create();
   tenancy()->initialize($tenant);
   ```
2. Always end tenancy in `tearDown()`:
   ```php
   tenancy()->end();
   ```
3. Mock external calls (FCM notifications, Google Maps Geocoding API, Cloudflare API, SMS gateways) using Mockery or Http Facade fakes:
   ```php
   Http::fake([
       'https://fcm.googleapis.com/*' => Http::response(['success' => 1], 200),
   ]);
   ```

---

## 7. Error Handling, Responses & Logging

### 7.1 Unified API Response Standard
All API controllers (`app/Http/Controllers/API/**`) must utilize the `ApiResponse` trait (`App\Http\Responses\API\ApiResponse`):

#### Successful Response:
```json
{
  "code": 200,
  "status": true,
  "type": "success",
  "message": "Site registration created successfully",
  "description": null,
  "data": { ... },
  "meta": {
    "next_cursor": "eyJpZCI6MTB9",
    "prev_cursor": null
  }
}
```

#### Error Response:
```json
{
  "code": 422,
  "status": false,
  "type": "validation_error",
  "message": "The given data was invalid.",
  "description": "Validation failed on dealer_code field.",
  "data": {
    "dealer_code": ["The dealer code does not exist."]
  },
  "meta": {
    "next_cursor": null,
    "prev_cursor": null
  }
}
```

### 7.2 Tenant Status Guarding
The `CheckTenantStatus` middleware intercepts tenant requests and must strictly return:
- **HTTP 403 Forbidden** with `errors.tenant-suspended` view or JSON error if `status === 'suspended'`.
- **HTTP 403 Forbidden** with `errors.tenant-inactive` view or JSON error if `status === 'inactive'`.
- **HTTP 403 Forbidden** with `errors.tenant-expired` view or JSON error if `isTrialExpired()` or `isPlanExpired()`.

### 7.3 Logging Rules
- Use `Log::channel('stack')` for general logging.
- When catching exceptions during tenant lifecycle events (creation, migration, seeding), always record context:
  ```php
  Log::error("Failed to provision tenant database", [
      'tenant_id' => $tenant->id,
      'error'     => $e->getMessage(),
      'trace'     => $e->getTraceAsString()
  ]);
  ```
- Never log raw passwords, authorization bearer tokens, FCM private server keys, or database passwords.
