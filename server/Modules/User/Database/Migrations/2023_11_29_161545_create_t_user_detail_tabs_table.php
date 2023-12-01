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
        Schema::create('t_user_detail_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_user_tab_id'); // column ini akan berisikan nilai id dari table m_user_tab
            $table->tinyInteger('age')->nullable();
            $table->unsignedTinyInteger('m_gender_tab_id');
            $table->char('city', 50)->nullable();
            $table->foreign('m_user_tab_id')->on('m_user_tabs')->references('id')->onDelete('cascade');
            $table->foreign('m_gender_tab_id')->on('m_gender_tabs')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_user_detail_tabs');
    }
};
