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
        Schema::create('t_food_attachment_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("t_food_tabs_id");
            $table->char("filename", 80);
            $table->char('size', 5);
            $table->foreign('t_food_tabs_id')->on('t_food_tabs')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_food_attachment_tabs');
    }
};
