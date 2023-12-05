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
        Schema::create('t_approval_transaction_food_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("t_food_tabs_id");
            $table->unsignedSmallInteger('m_status_tabs_id');
            $table->integer("responded_by");
            $table->timestamp('responded_at');
            $table->foreign('t_food_tabs_id')->on('t_food_tabs')->references('id')->onDelete('cascade');
            $table->foreign('m_status_tabs_id')->on('m_status_tabs')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_approval_transaction_food_tabs');
    }
};
