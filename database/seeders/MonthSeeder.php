<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Month::create(["month" => "January"]);
        Month::create(["month" => "February"]);
        Month::create(["month" => "March"]);
        Month::create(["month" => "April"]);
        Month::create(["month" => "May"]);
        Month::create(["month" => "June"]);
        Month::create(["month" => "July"]);
        Month::create(["month" => "August"]);
        Month::create(["month" => "September"]);
        Month::create(["month" => "October"]);
        Month::create(["month" => "November"]);
        Month::create(["month" => "December"]);
    }
}