<?php

use Illuminate\Database\Seeder;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->insert(['name' => "rookie", 'description' => "rookie desc"]);
        DB::table('levels')->insert(['name' => "amateur", 'description' => "amateur desc"]);
        DB::table('levels')->insert(['name' => "pro", 'description' => "pro desc"]);
    }
}
