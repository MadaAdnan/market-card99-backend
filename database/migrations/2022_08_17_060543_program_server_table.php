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
        Schema::create('program_server', function (Blueprint $table) {
            $table->foreignId('server_id')->constrained();
            $table->foreignId('program_id')->constrained();
            $table->string('code');
            $table->decimal('price')->default(0);
            $table->decimal('cost')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_server');
    }
};
