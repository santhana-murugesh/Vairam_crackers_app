<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ComboPackOrderItem;
use App\Models\Address;
use App\Models\Customer;
use App\Models\BankAccount;
use App\Models\ComboPackDispatch;
use App\Models\ComboPackPayment;

class ComboPackOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'combo_pack_orders';

    protected $fillable = ['net_total', 'customer_id', 'delivery_location_id', 'status', 'address'];


    public function items()

        {
            return $this->hasMany(ComboPackOrderItem::class, 'combo_pack_order_id');
        }
   
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function combopackdispatches()
    {
        return $this->hasMany(ComboPackDispatch::class);
    }

    public function combopackpayment()
    {
        return $this->belongsTo(ComboPackPayment::class);
    }
}

