<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaDataToCompetitionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competition_user', function (Blueprint $table) {
            $table->string('license_number')->unique();
            $table->integer('ranking_position');
            $table->unique(['ranking_position','competition_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competition_user', function (Blueprint $table) {
            $table->dropUnique('competition_user_ranking_position_competition_id_index');
            $table->dropColumn('license_number');
            $table->dropColumn('ranking_position');
        });
    }
}
