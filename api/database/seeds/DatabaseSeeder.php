<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('urls')->count() <= 0) {
            DB::table('urls')->insert([
                'url' => "https://www.google.com.br/search?q=audima&safe=off&cad=h"
            ]);
        }
        if (DB::table('users')->count() <= 0) {
            DB::table('users')->insert([
                'name' => 'Audima',
                'email' => 'audima@audima.com',
                'password' => bcrypt('audima'),
            ]);
        }
    }
}
