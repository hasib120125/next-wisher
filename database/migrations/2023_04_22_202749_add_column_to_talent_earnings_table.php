<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_earnings', function (Blueprint $table) {
            $table->boolean('balance_status')->default(false);
            $table->boolean('download_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talent_earnings', function (Blueprint $table) {
            $table->dropColumn('balance_status');
            $table->dropColumn('download_status');
        });
    }
};
