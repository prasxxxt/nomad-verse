<?php

namespace Database\Seeders;

use App\Models\Country;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c = new Country;
        $c->name = "Hardcoded Country";
        $c->iso_code = "HCC";
        $c->flag = null;
        $c->save();
        
        Country::factory(100)->create();
    }
}
