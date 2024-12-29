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
        Schema::create('ask_presint', function (Blueprint $table) {
            $table->foreignId('presint_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ask_id')->constrained()->cascadeOnDelete();
            $table->string('answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ask_presint', function (Blueprint $table) {
            //
        });
    }
};
