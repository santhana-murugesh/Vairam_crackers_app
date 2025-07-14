<?php

use App\Models\Address;
use App\Models\Customer;
use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class);
            $table->foreignIdFor(Address::class);
            $table->foreignIdFor(State::class)->nullable();
            $table->string('place_of_supply')->nullable();
            $table->integer('sub_total')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('net_total')->nullable();
            $table->integer('discount_total')->nullable();
            $table->boolean('gst_enabled')->nullable();
            $table->integer('sgst')->nullable();
            $table->integer('cgst')->nullable();
            $table->integer('igst')->nullable();
            $table->integer('gst_total')->nullable();
            $table->integer('total')->nullable();
            $table->enum('status', ['placed', 'pending', 'payment_received', 'dispatched', 'delivered', 'cancelled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_orders');
    }
};
