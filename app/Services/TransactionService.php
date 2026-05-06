<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    /**
     * Generate a unique transaction ID in the format TXN-YYYY-NNNN.
     *
     * @return string
     */
    public static function generateTransactionId()
    {
        $year = now()->year;

        $last = Transaction::whereYear('date', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->transaction_id, -4)) + 1 : 1;

        return 'TXN-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
