<?php

use App\Enums\ProductTypeEnum;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('img')->nullable();
            $table->string('info')->nullable();
            $table->decimal('cost')->default(0);
            $table->enum('type',[ProductTypeEnum::NEED_ACCOUNT->value, ProductTypeEnum::NEED_ID->value, ProductTypeEnum::DEFAULT->value,ProductTypeEnum::ITEMS->value])->nullable()->default(ProductTypeEnum::DEFAULT->value);
            $table->foreignId('category_id')->constrained();
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
        Schema::dropIfExists('products');
    }
};
