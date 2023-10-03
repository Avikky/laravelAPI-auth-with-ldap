<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mainDemand')->create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_id');
            $table->unsignedInteger('user_id');
            $table->text('token');
            $table->integer('refreshed')->default(0);
            $table->integer('expired')->default(0);
            $table->integer('revoked')->default(0);
            $table->dateTime('expires_at');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_tokens');
    }
}
