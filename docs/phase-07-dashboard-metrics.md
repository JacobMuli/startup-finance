# Phase 7: Dashboard & Analytics (Metrical Deep-Dive)

## 🎯 Objective

Deploy the **Founder Intelligence View**. This phase transforms the raw ledger into a diagnostic control center, providing immediate visibility into the financial health of the startup through high-fidelity metrics.

## 7.1 Financial Formulae

The dashboard calculates metrics in real-time based on the transaction dataset:

- **Total Revenue**: `Sum(Amount) WHERE type = IN`.
- **Total Expenses**: `Sum(Amount) WHERE type = OUT`.
- **Net Position**: `Total Revenue - Total Expenses`.
- **Burn Rate (MoM)**: Average of total expenses over the last 3 persistent months.
- **Survival Runway**: `Current Cash Balance / Burn Rate`.

## 7.2 High-Velocity Analytics

Implemented in `App\Http\Controllers\DashboardController`:

- **MoM Trend Widgets**: Visual indicators that compare current month performance to the previous month using Carbon-based snapshots.
- **Performance Grids**: Pinned table view of the current month's latest 10 transactions for immediate operational orientation.

## 7.3 Data Architecture

Metrics are calculated using optimized Eloquent queries with date-grouping to ensure dashbaord performance even as the ledger grows to thousands of records.

---

*Status: **Phase 7 - Dashboard Intelligence Active.***
