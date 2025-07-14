<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->string('product_name')->nullable();
            $table->decimal('product_price', 8, 2)->nullable(); // Adjust precision and scale as necessary
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('product_price');
        });
    }
    
};
