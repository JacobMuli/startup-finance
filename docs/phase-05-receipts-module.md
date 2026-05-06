# Phase 5: Receipts Module (Evidence Deep-Dive)

## 🎯 Objective

Architect a secure, professional vault for financial evidence. This phase ensures that every financial event in the ledger can be backed by digital proof-of-payment (PDF or Image) without compromising privacy.

## 5.1 Evidence Schema (Table: `receipts`)

Migration: `2026_04_02_171740_create_receipts_table.php`

| Column | Type | Purpose |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key. |
| `transaction_id` | FK (Nullable) | Immediate link to ledger parent. |
| `file_path` | STRING | Internal path on the secure disk. |
| `original_name` | STRING | Preserve filename for founder recall. |
| `mime_type` | STRING | Secure file-handling (image/jpeg, application/pdf). |

## 5.2 Secure Private Storage (Vault)

Configured in `config/filesystems.php`:

- **Disk Name**: `local` (Non-publicly accessible).
- **Security Logic**: Receipts are never exposed via a public URL (`/storage/...`).
- **Secure Streaming Proxy**: Implemented in `ReceiptController@show`. The system streams the file contents after verifying the user's role and permission to view financial evidence.

## 5.3 Integrated Viewer

- **Modal-Based Preview**: High-fidelity AlpineJS modal that allows founders to view receipts inline on the ledger without downloading.

---

*Status: **Phase 5 - Receipts Vault Active.***
