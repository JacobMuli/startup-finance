# Phase 13: Financial Intelligence Layer (Intelligence Deep-Dive)

## 🎯 Objective

Transform the system from a passive ledger into a proactive financial advisor. This phase implements the **Automated Insight Engine** that detects risks and opportunities in real-time, providing founders with strategic decision-making power.

## 13.1 Hybrid Diagnostic Scope

A production-grade design implemented in `DashboardController` that ensures safety signals are never masked by filters:

| Diagnostic Type | Scope | Technical Logic |
| :--- | :--- | :--- |
| **🚨 Critical (Runway)** | **Global** | Always calculated on the entire dataset to ensure financial survival. |
| **🚨 Critical (Revenue Drop)** | **Global** | Monitors for >20% MoM drop across all departments. |
| **⚠️ Warning (Spikes)** | **Filtered** | Detects local anomalies based on current dashboard filters. |
| **ℹ️ Info (Growth Cycle)** | **Filtered** | Highlights growth specific to the current context. |

## 13.2 Insight Engine (Logic Thresholds)

Implemented in `App\Services\InsightService`:

- **`detectRunwayRisk()`**: Triggered if `Runway < 3` months.
- **`detectSpendingSpike()`**: Triggered if `Expenses > PreviousMonth * 1.3 (+30%)`.
- **`detectRevenueDrop()`**: Triggered if `Revenue < PreviousMonth * 0.8 (-20%)`.
- **`detectCategoryDominance()`**: Triggered if `TopCategory > TotalExpenses * 0.5 (50%)`.

## 13.3 Predictive Forecasting (V1 Logic)

- **`getForecasts()`**:
  - **Expenses**: Calculated using a 3-month rolling average for expenditure stability.
  - **Revenue**: Calculated by applying the average historical growth rate to the latest month's revenue.

## 13.4 Intelligence UI

Features a prioritized, pulse-animated (`.critical-alert`) Insights Panel in the dashboard header to ensure immediate founder attention.

---

*Status: **Phase 13 - Intelligence Complete.** Optimized for founder decision velocity.*
