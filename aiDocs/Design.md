# FieldMA 2.0 SaaS — Technical & UI/UX Design System

## 1. Design System Philosophy & Architectural Duality

FieldMA 2.0 employs a dual design architecture tailored to the distinct operational needs of two user tiers:

1. **Central Landlord Console (Super Admin)**: Built with a modern, high-contrast, responsive **Tailwind CSS 3.x** design system adhering strictly to the **60-30-10 color rule**, the Inter typography system, and semantic state badges for platform management.
2. **Tenant Enterprise Web Portal**: Built on the **Metronic Layout 5** theme (Bootstrap & jQuery), enhanced with a **Dynamic CSS Variable Theming Engine** that allows every tenant company to inject their proprietary corporate brand colors and assets at runtime.
3. **Mobile Staff Interface (REST API)**: Backed by a standardized, versioned **JSON REST API design pattern** utilizing cursor pagination, structured response envelopes, and multi-tenant header detection.

---

## 2. Super Admin Design System (Tailwind CSS)

### 2.1 The 60-30-10 Color Hierarchy

The Super Admin Console implements an intentional visual hierarchy defined in `tailwind.config.js`:

```
┌──────────────────────────────────────────────────────────────┐
│  60% Dominant (Canvas & Backgrounds)                        │
│  - Slate-50 (#F8FAFC) : Main application workspace canvas    │
│  - Slate-100 (#F1F5F9): Inner table cards & subtle sections  │
├──────────────────────────────────────────────────────────────┤
│  30% Structural (Surfaces, Sidebar & Navigation)            │
│  - Pure White (#FFFFFF): Card surfaces, modal sheets, tables │
│  - Slate-200 (#E2E8F0): Structural borders & dividers        │
│  - Deep Indigo (#1E1B4B): Brand sidebar base                │
│  - Midnight Divider (#2E2A72): Subtle internal dividers     │
├──────────────────────────────────────────────────────────────┤
│  10% Accent (Focal Points & Call to Actions)                │
│  - Logo Red (#C0392B): Primary CTAs, brand emblems, badges   │
│  - Amber Gold (#F39C12): Secondary highlights, warning tags  │
└──────────────────────────────────────────────────────────────┘
```

#### Tailwind Config Tokens (`tailwind.config.js`):
```javascript
module.exports = {
  content: [
    "./resources/views/super-admin/**/*.blade.php",
    "./resources/views/super-admin/*.blade.php",
    "./resources/js/super-admin/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        admin: {
          canvas: '#F8FAFC',
          canvasDark: '#F1F5F9',
          surface: '#FFFFFF',
          border: '#E2E8F0',
          borderSubtle: '#F1F5F9',
          sidebar: {
            DEFAULT: '#1E1B4B',
            hover: 'rgba(255, 255, 255, 0.08)',
            active: 'rgba(255, 255, 255, 0.14)',
            activeBorder: '#C0392B',
            text: '#94A3B8',
            textHover: '#FFFFFF',
            textActive: '#FFFFFF',
            muted: '#64748B',
            divider: '#2E2A72',
          },
          header: {
            DEFAULT: '#FFFFFF',
            border: '#E2E8F0',
            text: '#0F172A',
            muted: '#64748B',
          },
          primary: {
            DEFAULT: '#C0392B',
            hover: '#A93226',
            active: '#922B21',
            subtle: '#FEF2F2',
            border: '#FECACA',
            focus: 'rgba(192, 57, 43, 0.25)',
          },
          accent: {
            DEFAULT: '#F39C12',
            hover: '#D68910',
            subtle: '#FFFBEB',
            border: '#FDE68A',
            focus: 'rgba(243, 156, 18, 0.25)',
          },
          title: '#0F172A',
          body: '#334155',
          muted: '#64748B',
          subtle: '#94A3B8',
          status: {
            active: '#10B981',       activeBg: '#ECFDF5', activeText: '#047857', activeBorder: '#A7F3D0',
            trial: '#F59E0B',        trialBg: '#FFFBEB',  trialText: '#B45309',  trialBorder: '#FDE68A',
            suspended: '#EF4444',    suspendedBg: '#FEF2F2', suspendedText: '#B91C1C', suspendedBorder: '#FECACA',
            inactive: '#64748B',     inactiveBg: '#F1F5F9',  inactiveText: '#475569', inactiveBorder: '#E2E8F0',
          }
        }
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      boxShadow: {
        'admin-card': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04)',
        'admin-card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05)',
        'admin-dropdown': '0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04)',
      }
    }
  }
};
```

### 2.2 Semantic Status System

Tenants and subscriptions communicate operational status through unified semantic badges:

| Status Code | Badge Background | Badge Border | Text Color | Meaning |
| :--- | :--- | :--- | :--- | :--- |
| **`active`** | `#ECFDF5` (Emerald-50) | `#A7F3D0` (Emerald-200) | `#047857` (Emerald-700) | Fully operational, paid subscription. |
| **`trial`** | `#FFFBEB` (Amber-50) | `#FDE68A` (Amber-200) | `#B45309` (Amber-700) | Active evaluation period with trial countdown. |
| **`suspended`** | `#FEF2F2` (Red-50) | `#FECACA` (Red-200) | `#B91C1C` (Red-700) | Temporarily disabled by super admin or non-payment. |
| **`inactive`** | `#F1F5F9` (Slate-100) | `#E2E8F0` (Slate-200) | `#475569` (Slate-600) | Workspace created but pending initialization/payment. |

### 2.3 Component Blueprint: Tenant List Card & Table
The Super Admin layout features clean statistics cards, quick action filter bars, and dense data tables:

```html
<!-- Stat Card Pattern -->
<div class="bg-white rounded-xl border border-slate-200 p-5 shadow-admin-card hover:shadow-admin-card-hover transition-all duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Tenants</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">48</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-building-circle-check"></i>
        </div>
    </div>
</div>
```

---

## 3. Tenant Portal Design System & Dynamic Branding

### 3.1 Dynamic CSS Custom Property Theming Engine
In the Tenant Portal (`resources/views/dashboards/partials/head.blade.php`), branding is dynamically rendered based on the currently authenticated tenant workspace:

```blade
@php
    $currentTenant = function_exists('tenant') ? tenant() : null;
    $titleName   = $currentTenant ? $currentTenant->company_name : config('app.company_name') ?? 'FieldMA';
    $themeColor  = $currentTenant ? $currentTenant->getThemeColor() : '#315DA9';
    $favIcon     = $currentTenant ? $currentTenant->getFaviconUrl() : asset('assets/pages/img/logo-fieldma.png');
@endphp

<title>{{ $titleName }}</title>
<link rel="shortcut icon" href="{{ $favIcon }}" />
<link rel="icon" href="{{ $favIcon }}" />

<style>
    :root {
        --primary-theme-color: {{ $themeColor }};
        --nav-text-color: #315DA9;
    }

    .navActive {
        color: {{ $themeColor }} !important;
        border-bottom: 2px solid {{ $themeColor }} !important;
        font-weight: 600 !important;
    }

    .quickNav, #quickNav, .active {
        color: white !important;
        background: {{ $themeColor }} !important;
    }

    .btn-primary, .btn.blue {
        background-color: {{ $themeColor }} !important;
        border-color: {{ $themeColor }} !important;
    }
</style>
```

### 3.2 Tenant Self-Service Branding Form
Located at `tenant/settings/branding` (`TenantSettingController`):
- **Logo Upload**: Validated image (`jpeg,png,jpg,svg,webp`, max 2MB) saved to `/storage/branding/logos/`.
- **Favicon Upload**: Square icon/image (`jpeg,png,jpg,svg,webp,ico`, max 1MB) saved to `/storage/branding/favicons/`.
- **Theme Color Picker**: Hex code input with live color picker (`regex:/^#[a-fA-F0-9]{6}$/`).
- **Immediate Propagation**: Changes update the `data->theme_color` JSON field on the Tenant model and cascade immediately to all web portal views without cache invalidation delays.

---

## 4. API Design Standards & Mobile Architecture

### 4.1 RESTful Endpoint Conventions
FieldMA mobile endpoints are grouped under `/api/` with semantic versioning:
- `/api/` (v1 legacy): Core attendance, payment, order history, notices.
- `/api/v2/`: Site registration, enhanced payment collections, attendance selfies.
- `/api/v3/`: Advanced order booking, package permissions synchronization, team monitoring.
- `/api/v4/`: Alert reminders, enhanced attendance geofencing, complaint logging.

### 4.2 Multi-Tenant Header Resolution
To eliminate mobile app reconfiguration when users belong to different tenant companies, the API supports multi-tenancy resolution via HTTP headers:

```http
POST /api/v2/site-registrations HTTP/1.1
Host: manojshrestha239.com.np
X-Tenant: acme-corp
Authorization: Bearer <jwt-token>
Content-Type: application/json
```

The `InitializeTenancyForApi` middleware intercepts the `X-Tenant` header, resolves `Tenant::where('id', $val)->orWhere('slug', $val)->first()`, and activates the tenant context and database before routing the request.

### 4.3 Standardized JSON Response Envelope
All API controllers inherit the `ApiResponse` trait (`app/Http/Responses/API/ApiResponse.php`), enforcing strict uniform schemas:

#### Success Envelope (`200 OK` / `201 Created`):
```json
{
  "code": 200,
  "status": true,
  "type": "success",
  "message": "Team members retrieved successfully",
  "description": null,
  "data": {
    "summary": {
      "total": 12,
      "checkin": 8,
      "checkout": 2,
      "on_leave": 2
    },
    "members": [
      {
        "id": 42,
        "name": "Bikash Shrestha",
        "phone": "+977-9841000000",
        "status": "checkin",
        "checkin_time": "10:04:12",
        "last_location": {
          "lat": 27.7172,
          "lng": 85.3240,
          "address": "Baluwatar, Kathmandu"
        }
      }
    ]
  },
  "meta": {
    "next_cursor": "eyJpZCI6NDJ9",
    "prev_cursor": null
  }
}
```

#### Error Envelope (`400 Bad Request` / `403 Forbidden` / `422 Unprocessable`):
```json
{
  "code": 403,
  "status": false,
  "type": "feature_restricted",
  "message": "The module 'tada' is not included in your company's subscription plan.",
  "description": "Please contact your system administrator to upgrade the subscription plan.",
  "data": null,
  "meta": {
    "next_cursor": null,
    "prev_cursor": null
  }
}
```

### 4.4 Pagination Guidelines
- **High-Frequency Feeds (Site Registrations, Mobile Location Logs)**: Use **Cursor Pagination** (`cursorPaginate()`) with base64 cursors (`next_cursor`, `prev_cursor`) to guarantee constant-time queries on high-volume tables.
- **Administrative Tabular Views (Orders, Dealers, Tada Reports)**: Use standard **LengthAware Pagination** (`paginate(20)`) for page number navigation and bulk exports.

### 4.5 API Documentation with Scribe
The API documentation configuration is managed in `config/scribe.php`. Run:
```bash
php artisan scribe:generate
```
This generates interactive documentation, OpenAPI specs, and Postman collections hosted at `/docs`.
