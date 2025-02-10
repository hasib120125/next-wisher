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
        Schema::create('talent_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_id')->constrained('users', 'id');
            $table->foreignId('user_id')->constrained();
            $table->string('type');
            $table->string('full_name')->nullable();
            $table->string('dedicated_to')->nullable();
            $table->string('from')->nullable();
            $table->string('for')->nullable();
            $table->string('gender')->nullable();
            $table->text('instruction')->nullable();
            $table->foreignId('occasion_id')->nullable()->constrained('occasions', 'id');
            $table->double('amount')->default(0);
            $table->double('commission')->default(0)->comment('commission in percent');
            $table->double('commission_amount')->default(0)->comment('commission amount in dolor');
            $table->double('maintenance_charge')->default(0)->comment('charge in percentage');
            $table->double('maintenance_charge_amount')->default(0)->comment('charge in amount in dolor');
            $table->json('transaction_info')->nullable();
            $table->boolean('status')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }
    // talent_id, user_id, amount, commission_amount, commission, type, gender, instructio
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talent_earnings');
    }
};
