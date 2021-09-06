<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'created_at' => Carbon::parse('2000-01-01'),
            ],
            [
                'construction_id' => 2,
                'created_at' => Carbon::parse('2000-01-01'),
            ],
            [
                'construction_id' => 3,
                'created_at' => Carbon::parse('2000-01-01'),
            ],
        ]);
    }
}
