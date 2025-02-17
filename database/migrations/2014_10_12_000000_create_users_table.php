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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('country')->nullable();
            $table->longText('bio')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('link')->nullable();
            $table->string('video_path')->nullable();
            $table->tinyInteger('is_agree')->default(1)->nullable();
            $table->rememberToken();
            $table->string('role')->default('admin')->nullable();
            $table->integer('suspend')->default(0)->nullable();
            $table->text('note_email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
