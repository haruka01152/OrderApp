<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('alerts')->insert([
            [
                'construction_id' => 1,
            ],
            [
                'construction_id' => 2,
            ],
            [
                'construction_id' => 3,
            ],
        ]);
    }
}
