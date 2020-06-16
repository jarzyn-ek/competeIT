<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhaseCompetitionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phase_competition_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_user_id')->constrainded()->nullable();
            $table->foreign('competition_user_id')->references('id')->on('competition_user');
            $table->foreignId('phase_id')->constrained();
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
        Schema::dropIfExists('phase_competition_user');
    }
}
