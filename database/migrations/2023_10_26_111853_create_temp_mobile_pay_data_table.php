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
        Schema::create('temp_mobile_pay_data', function (Blueprint $table) {
            $table->id();
            $table->json('all_request')->nullable();
            $table->json('payment_request')->nullable();
            $table->double('user_amount');
            $table->json('talent_earnings_data')->nullable();
            $table->string('deposit_id')->nullable();
            $table->json('mail_data')->nullable();
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
        Schema::dropIfExists('temp_mobile_pay_data');
    }
};
