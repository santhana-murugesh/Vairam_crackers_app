<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToComboPacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->softDeletes(); // This will add the 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->dropSoftDeletes(); // This will remove the 'deleted_at' column
        });
    }
}
