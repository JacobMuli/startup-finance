# Phase 8: Security & Audit (Privacy Deep-Dive)

## 🎯 Objective

Harden the financial environment for production-grade usage. This phase establishes professional **Role-Based Access Control (RBAC)** and a comprehensive **Audit Trail** for every financial event.

## 8.1 Security Migrations

Deployed for infrastructure hardening:

- `2026_04_02_181808_create_permission_tables.php`: Spatie RBAC tables.
- `2026_04_02_181818_create_audit_logs_table.php`: Tamper-evident ledger history.

## 8.2 Role-Based Provisioning (RBAC)

FFI implements a refined permission system:

- **Admin**: Has all permissions (Full Ledger CRUD, Taxonomy Management, Security Setup).
- **Recorder**: Can only add and view transactions. No deletion or taxonomy modifications allowed.
- **Viewer**: Read-only access to dashboards and ledger history.

## 8.3 Global Audit Observation

Implemented via Eloquent Observers in `App\Models\Transaction::booted()`:

- **Change Tracking**: Captures a JSON object containing the `before` and `after` states of the model.
- **Accountability**: Links every modification to a `user_id` and provides the **IP Address** for security context.
- **Permanence**: Once logged, audit records are immutable within the application layer.

---

*Status: **Phase 8 - Security & Audit Locked.***
