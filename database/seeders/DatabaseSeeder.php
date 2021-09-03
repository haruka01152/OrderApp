<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(Alert__ConfigsTableSeeder::class);
        $this->call(AlertsTableSeeder::class);
        $this->call(ConstructionsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
