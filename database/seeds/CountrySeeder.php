<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=['country1','country2','country3','country4','country5'];
        foreach ($data as $key => $value) {
            DB::table('countries')->insert(['name'=>$value]);
        }
    }
}
