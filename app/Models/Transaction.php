<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TransactionSubtype;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'type', 'subtype', 'amount'];

    protected $casts = [
        'subtype' => TransactionSubtype::class,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
