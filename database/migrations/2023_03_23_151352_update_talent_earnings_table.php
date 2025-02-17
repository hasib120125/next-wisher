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
            $table->boolean('is_expire')->nullable();
            $table->dateTime('expire_date')->nullable();
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
            $table->dropColumn('is_expire');
            $table->dropColumn('expire_date');
        });
    }
};
