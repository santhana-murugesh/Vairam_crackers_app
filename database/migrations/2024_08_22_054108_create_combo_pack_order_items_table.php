<?php

use App\Models\ComboPack;
use App\Models\ComboPackOrder;
use App\Models\Product;
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
        Schema::create('combo_pack_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ComboPackOrder::class);
            $table->foreignIdFor(ComboPack::class);
            $table->integer('quantity');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_pack_order_items');
    }
};
