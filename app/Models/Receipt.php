<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['receipt_id', 'file_path'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
