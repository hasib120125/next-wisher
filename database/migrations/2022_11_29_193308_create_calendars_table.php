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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('year', 10)->nullable();
            $table->tinyInteger('selectedPageIndex')->nullable();
            $table->string('language', 50)->nullable();
            $table->tinyInteger('weekStartDay')->nullable();
            $table->string('theme', 100)->nullable();
            $table->boolean('is_salable')->nullable()->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
};
