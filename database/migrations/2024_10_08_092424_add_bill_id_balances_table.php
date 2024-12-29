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
        Schema::table('balances', function (Blueprint $table) {
            $table->foreignId('bill_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::table('points', function (Blueprint $table) {
            $table->foreignId('bill_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bill_id');
        });
        Schema::table('points', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bill_id');
        });
    }
};
