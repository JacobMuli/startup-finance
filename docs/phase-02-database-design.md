# Phase 2: Database Design (Structural Deep-Dive)

## 🎯 Objective

Architect a robust, normalized database schema that ensures zero-loss data integrity and professional financial traceability. This phase defines the granular column-level details of the **startup-finance** ecosystem.

## 2.1 Core Schema Migrations

The following migrations establish the foundational financial taxonomy:

- `2026_04_02_171739_create_categories_table.php`: Revenue and Expense classification.
- `2026_04_02_171739_create_accounts_table.php`: Bank, Wallet, and Gateway mapping.
- `2026_04_02_171739_create_payment_methods_table.php`: Payment instrument mapping.
- `2026_04_02_171740_create_transactions_table.php`: Primary high-fidelity ledger.

## 2.2 Ledger Core (Table: `transactions`)

The master ledger is designed for high-fidelity reconciliation:

| Column Name | Type | Description / Logic |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key. |
| `transaction_id` | STRING | Unique traceability ID (e.g., TXN-2026-0001). |
| `date` | DATE | Physical date of financial event. |
| `type` | ENUM | Routing: `IN` (Revenue) or `OUT` (Expense). |
| `amount` | DECIMAL | Precision amount (15,2 for significant scale). |
| `category_id` | FK | Links to classification taxonomy. |
| `account_id` | FK | Links to bank/wallet repository. |
| `source_vendor` | STRING | Entity/Vendor recognition (e.g., AWS, Uber). |
| `receipt_id` | FK | Link to secure private storage vault. |

## 2.3 Financial Taxonomy (Sub-Tables)

- **`categories`**: `id`, `name`, `type` (IN/OUT).
- **`accounts`**: `id`, `name`.
- **`payment_methods`**: `id`, `name`.

---

*Status: **Phase 2 - Schema Definition Ready.***
