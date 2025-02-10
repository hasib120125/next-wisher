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
        Schema::create('mail', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->string('attachment')->nullable();
            $table->string('from')->nullable();
            $table->string('for')->nullable();
            $table->string('occasion')->nullable();
            $table->date('expirationDate')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('seen')->default(false);
            $table->boolean('isSelected')->default(false);
            $table->string('genders')->nullable();
            $table->text('instructions')->nullable();
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
        Schema::dropIfExists('mail');
    }
};
