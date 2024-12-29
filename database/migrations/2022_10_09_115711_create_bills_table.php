<?php

use App\Enums\BillStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->decimal('cost')->default(0)->nullable();
            $table->decimal('price')->default(0)->nullable();
            $table->enum('status', [BillStatusEnum::CANCEL->value, BillStatusEnum::COMPLETE->value, BillStatusEnum::PENDING->value, BillStatusEnum::REQUEST_CANCEL->value])->default(BillStatusEnum::PENDING->value);
            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_note')->nullable();
            $table->string('customer_username')->nullable();
            $table->string('customer_password')->nullable();
            $table->string('data_name')->nullable();
            $table->string('data_username')->nullable();
            $table->string('data_password')->nullable();
            $table->string('data_id')->nullable();
            $table->string('cancel_note')->nullable();
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
        Schema::dropIfExists('bills');
    }
};
