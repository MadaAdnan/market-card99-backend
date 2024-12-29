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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->string('img')->nullable();
            $table->text('info')->nullable();
            $table->enum('status',[\App\Enums\BillStatusEnum::PENDING->value,\App\Enums\BillStatusEnum::CANCEL->value,\App\Enums\BillStatusEnum::COMPLETE->value])->nullable()->default(\App\Enums\BillStatusEnum::PENDING->value);
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
        Schema::dropIfExists('charges');
    }
};
