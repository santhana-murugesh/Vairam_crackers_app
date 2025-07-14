<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ComboPackOrder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('combo_pack_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ComboPackOrder::class);
            $table->integer('bank_account_id');
            $table->date('payment_received_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_pack_payments');
    }
};