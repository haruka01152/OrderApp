<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Alert__ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('alert__configs')->insert([
            [
                'name' => '2日前',
                'period' => 2,
            ],
            [
                'name' => '5日前',
                'period' => 5,
            ],
            [
                'name' => '1週間前',
                'period' => 7,
            ],
            [
                'name' => '2週間前',
                'period' => 14,
            ],
            ]);
    }
}
