<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('order_status')->insert([
            [
                'name' => '- - - - -',
            ],
            [
                'name' => '発注指示待ち',
            ],
            [
                'name' => '発注指示',
            ],
            [
                'name' => '発注完了',
            ],
        ]);
    }
}
