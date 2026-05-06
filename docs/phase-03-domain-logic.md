# Phase 3: Domain Logic Layer (Service Deep-Dive)

## 🎯 Objective

Implement the core financial engine and domain rules that ensure system integrity and professional traceability. This phase focuses on the underlying logic that drives ledger consistency and automated metadata generation.

## 3.1 Transaction Service (Core Engine)

The system uses a dedicated service layer to handle the lifecycle of every financial record, ensuring all business rules are applied atomically.

- **`App\Services\TransactionService`**: Orchestrates creation and validation.
- **ID Generation (TXN-YYYY-0001)**: Implements automated, human-readable traceability IDs to replace spreadsheet-style incrementing.

## 3.2 Key Logic & Business Rules

Hardened financial constraints are enforced at the service level:

- **Category-to-Type Routing**: Enforced rule where Type `IN` (Revenue) must only map to an income category, and Type `OUT` (Expense) only to an expenditure category.
- **Automated Period Mapping**: The system automatically derives the `month` and `month_date` (1st of the month) from the transaction timestamp to ensure alignment across MoM reporting widgets.

## 3.3 Validation Layer (Rules)

Every transaction is subject to the following technical guards:

- `amount`: `required | numeric | minimum:0.01`
- `date`: `required | date | before_or_equal:today`
- `category_id`: `required | exists:categories,id`
- `account_id`: `required | exists:accounts,id`

---

*Status: **Phase 3 - Domain Logic Ready.***
