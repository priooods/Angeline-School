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
        Schema::create('m_status_tabs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->char('title',20);
            $table->tinyInteger('status')->default(0)->comment('0 = tidak aktif, 1 = aktif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_status_tabs');
    }
};
