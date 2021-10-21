<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('statuses')->insert([
            [
                'name' => '物品未着',
            ],
            [
                'name' => '物品到着済',
            ],
            [
                'name' => '削除済み',
            ],
        ]);
    }
}
