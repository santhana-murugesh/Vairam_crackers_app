<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Dispatch;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['customer_id', 'net_total', 'discount_total', 'sub_total', 'address_id', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function bank_account(){
        return $this->belongsTo(BankAccount::class,  'bank_account_id');
    }

    public function dispatch()
    {
        return $this->hasOne(Dispatch::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

}
