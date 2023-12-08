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
        Schema::create('m_user_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('email', 80)->unique();
            $table->string('password');
            $table->string('repassword');
            $table->char('fullname', 30);
            $table->tinyInteger('is_activated')->default(0)->comment('0 = tidak aktif, 1 = aktif');
            $table->tinyInteger('is_deleted')->default(0)->comment('0 = false , 1 = true');
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
        Schema::dropIfExists('m_user_tabs');
    }
};
