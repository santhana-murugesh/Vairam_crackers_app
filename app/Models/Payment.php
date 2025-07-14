<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankAccount;
use App\Models\Order;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'bank_account_id', 'payment_received_date'];

    protected $with = ['bankAccount', 'order'];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected static function boot()
    {
        parent::boot();

        
        static::creating(function ($payment) {
            if (!$payment->payment_received_date) {
                $payment->payment_received_date = now();
            }
        });
    }
}
