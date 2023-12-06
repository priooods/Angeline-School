<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_food_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_user_tabs_id');
            $table->text("description");
            $table->char('price', 8);
            $table->char('shop', 100);
            $table->char('latitude', 20)->nullable();
            $table->char('longitude', 20)->nullable();
            $table->json('video')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
            $table->foreign('m_user_tabs_id')->on('m_user_tabs')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_food_tabs');
    }
};
