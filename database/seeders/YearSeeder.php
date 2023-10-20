<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Year::create(["year" => 2023]);
        Year::create(["year" => 2024]);
        Year::create(["year" => 2025]);
        Year::create(["year" => 2026]);
        Year::create(["year" => 2027]);
        Year::create(["year" => 2028]);
        Year::create(["year" => 2029]);
        Year::create(["year" => 2030]);
    }
}