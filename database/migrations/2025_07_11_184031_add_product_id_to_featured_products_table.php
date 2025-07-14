<?php

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
        Schema::table('featured_products', function (Blueprint $table) {
            // Add product_id field
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Make title and description nullable since they can come from product
            $table->string('title')->nullable()->change();
            $table->text('description')->nullable()->change();
            
            // Add fields for featured product specific content
            $table->string('highlight_text')->nullable()->after('description');
            $table->text('featured_description')->nullable()->after('highlight_text');
            $table->boolean('show_price')->default(true)->after('featured_description');
            $table->boolean('show_discount')->default(true)->after('show_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('featured_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn([
                'product_id',
                'highlight_text',
                'featured_description',
                'show_price',
                'show_discount'
            ]);
            
            // Revert title and description to be required
            $table->string('title')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
