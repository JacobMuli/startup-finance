# Founder Financial Interface (FFI)

A professional, diagnostic-grade financial operating system designed for startup founders. FFI transforms raw transaction data into actionable strategic insights, focusing on runway survival, growth diagnostics, and elite operational velocity.

---

## 🏛️ System Architecture: A Functional Deep-Dive

FFI is built as a multi-layered financial environment that bridges the gap between simple ledger tracking and advanced diagnostic advisory.

### 1. The Financial Intelligence Engine (FIE)

The core of FFI’s advisory capability is the `InsightService`, a diagnostic engine that monitors the ledger across a **Hybrid Scope**.

- **Hybrid Scope Resolution**:
  - **Global Safety (🚨 Critical)**: Critical alerts like **Survival Runway** (< 3 months) and **Revenue Plummets** (> 20% MoM) are calculated against the entire dataset, ignoring dashboard filters to ensure the founder never misses a survival-critical signal.
  - **Contextual Analysis (⚠️ Warning / ℹ️ Info)**: Spending spikes and category dominance are filter-aware, allowing for deep-dive analysis of specific departments or periods.

- **Diagnostic Rules**:
  - **Spike Detection**: Automated flagging of any category with a >30% MoM increase.
  - **Dominance Detection**: Signals if a single category accounts for >50% of total burn.

- **Predictive V1 Engine**: Uses a 3-month rolling average for expenses and historical growth-rate mapping for revenue to project the next 30 days of performance.

---

## 🧭 System Roadmap & Detailed Phase Documentation

The evolution of FFI is documented across 14 distinct engineering phases. For technical deep-dives into each module, refer to the following:

- **Phase 0**: [Product Definition](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-00-product-definition.md)
- **Phase 1**: [Project Setup](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-01-project-setup.md)
- **Phase 2**: [Database Design](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-02-database-design.md)
- **Phase 3**: [Domain Logic Layer](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-03-domain-logic.md)
- **Phase 4**: [Transactions Module](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-04-transactions-module.md)
- **Phase 5**: [Receipts Module](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-05-receipts-module.md)
- **Phase 6**: [Master Data (Taxonomy)](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-06-master-data-modules.md)
- **Phase 7**: [Dashboard & Analytics](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-07-dashboard-metrics.md)
- **Phase 8**: [Security & Audit Log](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-08-security-audit.md)
- **Phase 9**: [Elite UX & Interactions](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-09-elite-ux.md)
- **Phase 10**: [UI/UX Refinement](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-10-ui-ux-refinement.md)
- **Phase 11**: [Testing & QA](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-11-testing.md)
- **Phase 12**: [Deployment & Optimization](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-12-deployment.md)
- **Phase 13**: [Financial Intelligence Layer](file:///home/andy/Desktop/Projects/professional/startup-finance/docs/phase-13-financial-intelligence.md)

---

## 🏗️ System Architecture: A Functional Deep-Dive

A hardened security layer ensures financial data is traceable, private, and tamper-evident.

- **Role-Based Access Control (RBAC)**: Powered by `Spatie\Permission`, the system enforces three distinct tiers:
  - **Admin**: Full diagnostic and configuration authority.
  - **Recorder**: High-velocity entry specialized (Add-only access to critical financial records).
  - **Viewer**: Read-only access to dashboards and reports.

- **Traceable Audit Ledger**: Every mutation (Created, Updated, Deleted) on a `Transaction` is captured via Eloquent Observers. The audit log records the user, IP address, and a `before/after` JSON snapshot of the data.

- **Secure File Vault**: Financial receipts (PDF/JPG) are stored on a non-public `local` disk. Images are never exposed via a public URL; instead, they are streamed via an auth-checked proxy controller.

### 3. Elite UI/UX & Velocity Engine

Designed for power-users to record and analyze transactions in seconds, not minutes.

- **Global Shortcut Engine**: High-fidelity keyboard listeners that are context-aware (disabled when typing in inputs).
  - `N`: Record New Transaction.
  - `/`: Focus Ledger Search (automatically selects existing text for instant overtyping).

- **AI Bridge (Rule-Based)**: Real-time keyword matching on the "Vendor" field. The system recognizes patterns (e.g., `AWS` -> `Hosting`, `Uber` -> `Transport`) to suggest categories instantly.

- **Tactile Feedback**: A comprehensive micro-interaction layer using a `0.2s` hover scale (`1.02x`) and global button-press feedback (`0.97x`).

### 4. Data Lifecycle & Ledger Logic

- **Unique Traceability**: Every record is assigned a human-readable `transaction_id` (e.g., `TXN-2026-0001`).
- **Auto-Month Generation**: The system automatically derives financial months and dates from transaction timestamps to ensure zero-configuration reporting.
- **Validation Guards**: Programmatic hardening prevents the saving of inconsistent data (e.g., negative amounts or mis-typed categories).

---

## 🛠️ Technical Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Tailwind CSS (Premium Utilities) & AlpineJS
- **Security**: Laravel Fortify & Spatie Permissions
- **Architecture**: Service-Oriented (Diagnostic logic encapsulated in `InsightService`)
- **Testing**: PHPUnit (100% logic coverage for diagnostic rules)

---

## 🚀 Setup & Installation Guide

### 1. Environment Requirements

Ensure your environment meets the high-performance requirements:

- PHP 8.1 or higher
- Composer
- Node.js & NPM (LTS)
- MySQL 8.0+ / PostgreSQL

### 2. Manual Deployment

```bash
# Install dependencies
composer install
npm install

# Build premium assets
npm run build

# Generate unique project key
cp .env.example .env
php artisan key:generate
```

### 3. Database & Mastery Data

FFI ships with a pre-configured financial taxonomy to get you operational instantly:

```bash
# Deploy schema and default categories/accounts
php artisan migrate --seed
```

*Seeded categories include Marketing, Software Tools, Hosting, Consulting, and SaaS Subscriptions.*

### 4. Storage & Security

```bash
# Link the secure proxies
php artisan storage:link
```

### 5. Launch

```bash
php artisan serve
```

---

## 🚀 Default Access

- **Admin Account**: `jacobmwalughs@gmail.com`
- **Default Testing User**: `test@example.com`

---

*System Status: **V1 - Intelligence & Security Locked.** Optimized for founder decision velocity.*
