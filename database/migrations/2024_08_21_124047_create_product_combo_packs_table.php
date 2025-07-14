<?php

use App\Models\ComboPack;
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
        if (!Schema::hasTable('product_combo_packs')) {
            Schema::create('product_combo_packs', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Product::class);
                $table->foreignIdFor(ComboPack::class);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_combo_packs');
    }
};
