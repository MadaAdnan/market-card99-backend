<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('server_id')->nullable()->constrained();
            $table->foreignId('program_id')->nullable()->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->boolean('is_Sim90')->nullable()->default(0);
            $table->string('country_name')->nullable();
            $table->string('program_name')->nullable();
            $table->decimal('price')->default(0);
            $table->decimal('cost')->default(0);
            $table->string('phone');
            $table->string('code')->nullable();
            $table->enum('status',[OrderStatusEnum::CANCEL->value, OrderStatusEnum::COMPLETE->value, OrderStatusEnum::WAITE->value])
                ->nullable()->default(OrderStatusEnum::WAITE->value);
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
        Schema::dropIfExists('orders');
    }
};
