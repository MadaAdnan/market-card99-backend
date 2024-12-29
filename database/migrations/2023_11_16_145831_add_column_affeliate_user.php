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
        Schema::table('users',function(Blueprint $table){
            $table->string('affiliate')->nullable();
            $table->boolean('is_affiliate')->default(false)->nullable();
            $table->double('win_by_affiliate')->default(0)->nullable();
            $table->foreignId('affiliate_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users',function(Blueprint $table){
            $table->dropColumn('affiliate');
            $table->dropColumn('is_affiliate');
            $table->dropColumn('win_by_affiliate');
            $table->dropColumn('affiliate_id');
        });
    }
};
