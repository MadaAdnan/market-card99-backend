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
        Schema::table('settings', function (Blueprint $table) {
            $table->text('widget1')->nullable();
            $table->text('widget2')->nullable();
            $table->text('widget3')->nullable();
            $table->text('widget4')->nullable();
            $table->text('about')->nullable();
        });
    }

    /**
     * Reverse the, migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'widget1','widget2','widget3','widget4','about'
            ]);
        });
    }
};
