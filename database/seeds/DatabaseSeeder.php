<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(CurrenciesSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
