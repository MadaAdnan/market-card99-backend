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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_fixed_group')->nullable()->default(false);
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->double('min_value')->default(2000)->nullable();
            $table->double('ratio_delegate')->default(0.2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_fixed_group');
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('min_value');
            $table->dropColumn('ratio_delegate');
        });
    }
};
