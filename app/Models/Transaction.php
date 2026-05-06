<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\AuditLog;

class Transaction extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($transaction) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => optional(auth()->user())->name ?? 'system',
                'action' => 'created',
                'model_type' => 'Transaction',
                'model_id' => $transaction->id,
                'changes' => $transaction->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });

        static::updated(function ($transaction) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => optional(auth()->user())->name ?? 'system',
                'action' => 'updated',
                'model_type' => 'Transaction',
                'model_id' => $transaction->id,
                'changes' => [
                    'before' => $transaction->getOriginal(),
                    'after' => $transaction->getChanges(),
                ],
                'ip_address' => request()->ip(),
            ]);
        });

        static::deleting(function ($transaction) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => optional(auth()->user())->name ?? 'system',
                'action' => 'deleted',
                'model_type' => 'Transaction',
                'model_id' => $transaction->id,
                'changes' => $transaction->toArray(),
                'ip_address' => request()->ip(),
            ]);

            if ($transaction->receipt) {
                Storage::disk('local')->delete($transaction->receipt->file_path);
                $transaction->receipt->delete();
            }
        });
    }

    protected $fillable = [
        'serial_no',
        'transaction_id',
        'date',
        'month',
        'month_date',
        'type',
        'category_id',
        'department',
        'source_vendor',
        'description',
        'payment_method_id',
        'account_id',
        'amount',
        'receipt_id',
        'receipt_link',
        'approved_by',
        'recorded_by',
        'transaction_tag'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
