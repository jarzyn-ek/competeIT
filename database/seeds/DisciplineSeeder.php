<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Discipline::class, 5)->create();
        $disciplines = array_map(function ($discipline) {
            return [
                'name' => $discipline['name'],
                'competitors_per_game' => $discipline['competitors_per_game'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }, config('games.disciplines'));

        App\Discipline::insert($disciplines);
    }
}
