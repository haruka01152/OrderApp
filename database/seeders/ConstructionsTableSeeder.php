<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ConstructionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('constructions')->insert([
            [
                'customer_name' => '株式会社ライズ',
                'construction_name' => 'FUJITSU PC 導入費用一式',
                'alert_config' => 1,
            ],
            [
                'customer_name' => '株式会社クラップ',
                'construction_name' => 'FUJITSU Server 導入費用一式',
                'alert_config' => 2,
            ],
            [
                'customer_name' => '有限会社SK',
                'construction_name' => '無線AP設置・構築費用一式',
                'alert_config' => 3,
            ],
            ]);
    }
}
