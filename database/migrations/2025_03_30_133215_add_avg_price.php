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
        Schema::table('bills', function (Blueprint $table) {
            $table->double('wins')->virtualAs('price - cost')->after('price');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->double('wins')->virtualAs('price - cost')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('wins');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('wins');
        });
    }
};
