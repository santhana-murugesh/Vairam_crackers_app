<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboPackPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'combo_pack_order_id',
        'bank_account_id',
        'payment_received_date',
       
    ];


    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
    public function combopackorder()
    {
        return $this->belongsTo(ComboPackOrder::class, 'order_id');
    }

}