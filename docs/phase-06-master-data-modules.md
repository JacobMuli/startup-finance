# Phase 6: Master Data Modules (Deep-Dive)

## 🎯 Objective

Provide the founder with complete control over the operational taxonomy of their financial ecosystem. This phase enables the dynamic creation and management of Categories, Accounts, and Payment Methods, ensuring the system can scale with the startup's growth.

## 6.1 Category Management

- **Type Routing Logic**: Enforced at the model level (`Category::type`).
- **Revenue Categories (IN)**: SaaS Subscription, Consulting, Implementation, Training, Grants, Investment.
- **Expense Categories (OUT)**: Hosting, Marketing, Software Tools, Transport, Equipment, Legal, Operations.
- **Controllers**: `CategoryController` (CRUD with type-filtering).

## 6.2 Account Management (Physical Repositories)

Mapping of actual bank accounts and digital wallets:

- **`App\Models\Account`**: Represents a financial repository.
- **Initial Seeds**: Business Bank Account, M-Pesa Till, Cash account, Stripe Gateway.
- **Utility**: Every transaction is mapped to a parent account to enable future reconciliation and audit-readiness.

## 6.3 Payment Method Management

- **`App\Models\PaymentMethod`**: Represents the instrument of payment (Bank Transfer, M-Pesa, Card, Cash).
- **Audit Value**: Essential for tracking exactly how funds were moved for every transaction.

---

*Status: **Phase 6 - Master Data Ready.***
