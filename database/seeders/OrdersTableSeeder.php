<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('orders')->insert([
            [
                'image' => 'image_sample01',
                'construction_id' => 1,
                'memo' => '一番上の物品がまだです',
                'arrive_status' => 0,
            ],
            [
                'image' => 'image_sample01',
                'construction_id' => 2,
                'memo' => '',
                'arrive_status' => 1,
            ],
            [
                'image' => 'image_sample01',
                'construction_id' => 3,
                'memo' => '納期未定',
                'arrive_status' => 0,
            ],
        ]);
    }
}
