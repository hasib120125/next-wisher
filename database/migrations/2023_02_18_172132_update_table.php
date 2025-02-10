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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('type')->nullable();
        });
        Schema::table('languages', function (Blueprint $table) {
            $table->text('spanish')->nullable()->after('portugues');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('slug');
            $table->boolean('is_parent')->default(false)->after('slug');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('sub_category_id')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('spanish');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('is_parent');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sub_category_id');
        });
    }
};
