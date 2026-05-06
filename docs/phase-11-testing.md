# Phase 11: Testing & QA (Quality Deep-Dive)

## 🎯 Objective

Zero-defect financial reporting. This phase validates the core domain logic and diagnostic engine through automated and manual verification cycles, ensuring the founder can trust every insight.

## 11.1 Logic Validation (Unit Tests)

Located in `tests/Unit/InsightServiceTest.php`:

- **`detectRunwayRisk()`**: Verifies that runway < 3 months triggers a `critical` alert.
- **`detectSpendingSpike()`**: Validates the >30% MoM surge threshold.
- **`detectRevenueDrop()`**: Confirms the >20% MoM drop detection logic.
- **`detectCategoryDominance()`**: Validates the >50% total-expense threshold for individual categories.
- **`prioritySorting()`**: Ensures that `critical` alerts always float to the top of the dashboard.

## 11.2 Lifecycle Validation (Feature Tests)

- **Ledger CRUD**: Feature tests for creating transactions and ensuring the correct `transaction_id` is generated.
- **Auth Hardening**: Verification that `Viewer` roles cannot modify financial records or access delete endpoints.
- **Data Persistence**: Ensuring all DECIMAL values are correctly saved with (15,2) precision.

## 11.3 Performance Verification

Running continuous tests ensures the system maintains operational velocity:

```bash
php artisan test
```

---

*Status: **Phase 11 - Logic Verified.***
