# FieldMA 2.0 SaaS — Product Requirements Document (PRD)

## 1. Executive Summary & Vision

### 1.1 Vision Statement
FieldMA 2.0 is an enterprise-grade Sales Force Automation (SFA) and Field Operations SaaS platform tailored for manufacturing, distribution, and wholesale enterprises operating in dynamic field environments (with specialized support for Nepal and emerging South Asian markets). The platform empowers organizations to eliminate manual field paperwork, gain real-time visibility over mobile sales forces, automate order and payment workflows, track travel allowances, and manage multi-tier dealer networks with precision.

### 1.2 Core Value Proposition
- **Multi-Tenant SaaS Economy**: Enables FieldMA to transition from individual single-client codebase deployments to a unified multi-tenant SaaS architecture where thousands of organizations run on secure, completely isolated databases.
- **Complete SFA Suite**: Unifies field attendance, live GPS breadcrumb tracking, order booking, invoice challans, cash/cheque collection, dealer aging, and site construction tracking in a single pane of glass.
- **Granular Financial Control**: Solves field cash reconciliation challenges through bank denomination point logging, receipt uploads, and multi-tier travel allowance (TADA) authorization workflows.
- **White-Label Customization**: Every tenant receives custom subdomains, branded login screens, bespoke logo/favicon injection, and dynamic CSS primary color theming without code changes.

---

## 2. Target Users & Personas

| User Persona | Role | Primary Goals & Workflows | Access Mode |
| :--- | :--- | :--- | :--- |
| **Platform Super Admin** | Central Landlord Operator | Manages SaaS subscriptions, creates client tenant workspaces, monitors tenant health, manages database migrations, adjusts pricing plans and feature flags. | Central Web Console (`/super-admin`) |
| **Tenant Business Owner / Director** | Enterprise Client Executive | Monitors high-level company sales KPIs, team attendance summaries, daily collections, outstanding dealer dues, and overall field productivity. | Tenant Web Portal (`{slug}.fieldma.com`) |
| **Sales / Area Manager (Senior)** | Middle Management | Plans territory visit schedules, monitors assigned junior representatives, approves orders, verifies TADA expense submissions, tracks live location breadcrumbs. | Tenant Web Portal & Mobile App ("My-Teams") |
| **Field Sales Representative (Junior)** | Frontline Field Staff | Records daily GPS check-in/out, conducts physical/virtual dealer visits, takes orders in carts, collects payments, logs TADA travel expenses, registers construction sites. | Mobile App (Android/iOS via REST API) |
| **Dispatcher / Account Manager** | Back-office Operations | Verifies pending orders, generates delivery Challans, reconciles bank collections with physical denomination breakdowns, manages product pricing. | Tenant Web Portal |

---

## 3. Product Features & Functional Requirements

### 3.1 Central SaaS & Tenant Management (Landlord Control Plane)
- **FR-1.1: Automated Tenant Provisioning**: Super Admins can create a new tenant workspace by specifying company name, unique slug, admin credentials, subscription plan, and optional custom domains. The system automatically creates a dedicated database (`tenant_{slug}`), runs migrations, seeds initial data, and assigns a subdomain.
- **FR-1.2: One-Click Tenant Maintenance**:
  - Run or rollback migrations per tenant.
  - Re-link storage directories.
  - Clear application cache and warm configuration cache.
  - Instant workspace suspension / activation toggle.
- **FR-1.3: Subscription Plan Catalog**:
  - Super Admins can configure plans (Starter, Professional, Enterprise) with monthly/yearly pricing, maximum mobile user limits, and feature toggles (`attendance`, `order_booking`, `dealers_management`, `notices`, `payments`, `tada`, `site_registration`, `raw_inventory`, `stock_management`, `live_tracking`).
- **FR-1.4: Legacy Database Onboarding**: CLI tool (`php artisan tenant:import`) allowing existing single-tenant FieldMA MySQL databases to be imported into the SaaS infrastructure without downtime.

### 3.2 Tenant Dynamic White-Labeling & Self-Service
- **FR-2.1: Dynamic Visual Branding**: Tenants can configure their display company name, VAT/PAN number, address, contact email, primary brand hex color (e.g. `#C0392B`), corporate logo, and favicon.
- **FR-2.2: Dynamic Theming Engine**: Tenant web views dynamically bind the tenant's chosen brand color via CSS variables (`--primary-theme-color`), updating buttons, navigation highlights, and badges automatically.
- **FR-2.3: Isolated Push Notification Topics**: The system dynamically assigns FCM topics based on the tenant slug (`{slug}_fieldMA_allUser`) to ensure notifications never leak across organizations.

### 3.3 Sales Force Automation & Dealer Management
- **FR-3.1: Hierarchical Territories**: Multi-level organizational hierarchy composed of Areas, Subareas, and Assigned Areas with designated sales representatives.
- **FR-3.2: Dealer Directory & Profiling**: Comprehensive dealer records tracking contact details, geo-coordinates, credit limits, PAN/VAT, and financial aging.
- **FR-3.3: Unlisted Dealer Fast-Path**: Field staff visiting newly discovered shops can create "Unlisted Dealers" on the fly and reuse them on subsequent visits without administrative pre-approval.
- **FR-3.4: Bulk Data Ingestion**: High-throughput Excel import/export for thousands of dealers, products, and bank accounts in chunks of 500 rows with validation and duplicate prevention.

### 3.4 Attendance, Live Tracking & Geofencing
- **FR-4.1: Mobile GPS Attendance**: Field staff register check-in, check-out, break-in, and break-out with mandatory GPS coordinates and device model logging.
- **FR-4.2: Automated End-of-Day Checkout**: Scheduled daemon (`AutoCheckout`) automatically checks out users who remain checked-in after office hours, records the event, and pushes warning notices via FCM.
- **FR-4.3: Real-Time Map & Route Playback**: Live Google Maps dashboard displaying current positions of all active field representatives and historical chronological travel paths for any selected date.

### 3.5 Order Management, Carts & Delivery Challans
- **FR-5.1: Mobile Order Booking**: Field reps select products by category and size, configure quantities and custom discounts, and submit orders directly to the back office.
- **FR-5.2: In-App Shopping Cart**: Persistent cart functionality allows drafting and updating multi-line orders before submission.
- **FR-5.3: Delivery Challan Processing**: Back-office dispatchers generate delivery Challans, track fulfillment stages, and issue customer delivery confirmations.
- **FR-5.4: Sales & MIS Reporting**: Detailed Excel and tabular reports covering order collections, staff summaries, area visit metrics, and zero-order analytics.

### 3.6 Collections & Cash Reconciliation
- **FR-6.1: Field Payment Ingestion**: Record cash, cheque, and bank transfer collections against dealer accounts during field visits.
- **FR-6.2: Physical Denomination Breakdown**: Cash collections support denomination point counting (number of 1000s, 500s, 100s, etc.) to eliminate accounting discrepancies.
- **FR-6.3: Outstanding Balance & Aging**: Real-time dealer balance and aging reports (`/api/due/{area}/{dealer}`, `/api/ageing/{area}/{dealer}`).

### 3.7 TADA (Travel Allowance & Daily Allowance) System
- **FR-7.1: Multi-Category Expense Claims**: Field representatives submit daily travel expenses including kilometers traveled (KM remarks), fuel, hotel accommodations, meals, and miscellaneous expenses.
- **FR-7.2: Digital Receipt Uploads**: Support for capturing and attaching photo receipts to individual expense claims.
- **FR-7.3: Two-Tier Authorization Workflow**: Configurable approval flow via `TadaAuthorizedUser` and `TadaExpenseAuthorizedUser` with administrative remarks and approval/rejection audits.

### 3.8 Site Registration & Construction Pipeline (V2)
- **FR-8.1: Construction Lead Tracking**: Track individual building and infrastructure projects from foundation to finishing.
- **FR-8.2: Multi-Stakeholder Directory**: Associate multiple stakeholders (contractors, architects, painters, site owners) with each registered site.
- **FR-8.3: Product Estimation & Competitor Intelligence**: Log paint/material volume estimations, preferred brands, and competitor product presence.
- **FR-8.4: Scheduled Follow-ups & Reminders**: Automated follow-up date scheduling with stage progression logs.

### 3.9 "My-Teams" Hierarchy & Monitoring
- **FR-9.1: Senior-Junior Organizational Hierarchy**: Configure multi-level sales reporting trees where senior managers supervise assigned juniors.
- **FR-9.2: Senior Activity Feed**: Senior managers have real-time visibility into junior attendance status (check-in/out/leave), today's total orders, today's total collection, and visit types (physical vs virtual).
- **FR-9.3: Push Event Triggers**: Automated FCM push notifications sent directly to senior managers whenever an assigned junior checks in or checks out.

### 3.10 Localization & Calendar Integration
- **FR-10.1: Bikram Sambat (BS) Dual Calendar**: Full bi-directional conversion between Nepali Bikram Sambat (BS) and Gregorian (AD) calendars, enabling localized filtering and reporting in accordance with Nepali fiscal periods.

---

## 4. Non-Functional Requirements (NFRs)

### 4.1 Data Isolation & Security
- **NFR-1.1: Database-per-Tenant Isolation**: Hard security boundary preventing cross-tenant data leaks. Database connections switch dynamically upon domain/header resolution.
- **NFR-1.2: Authentication & Authorization**:
  - Central Super Admin: Session authentication via `super_admin` guard.
  - Tenant Web Portal: Session authentication via `web` guard with Spatie RBAC (`Role`, `Permission`).
  - Mobile REST API: Stateless JSON Web Tokens (JWT via `tymon/jwt-auth`) with API token validation (`AuthenticateToken`).
- **NFR-1.3: Tenant Status Enforcement**: Requests to suspended, inactive, or expired workspaces are blocked at the middleware layer before executing business code.

### 4.2 Performance & Scalability
- **NFR-2.1: High-Volume Data Ingestion**: Bulk imports process in chunks of 500 rows to ensure low memory consumption.
- **NFR-2.2: Scoped Caching**: All application caching is automatically tagged with `tenant_{id}`, preventing cache pollution across organizations.
- **NFR-2.3: Cursor Pagination**: Modern endpoints (e.g. Site Registration V2) implement cursor-based pagination for fast queries on large datasets.

### 4.3 Reliability & Availability
- **NFR-3.1: Zero-Trust Public Ingress**: Cloudflare Tunnel integration enables secure public access without exposing MySQL or PHP-FPM ports to the public internet.
- **NFR-3.2: Automated Health & Scheduler Daemon**: Background scheduler container guarantees execution of auto-checkout jobs and maintenance routines every 60 seconds.
