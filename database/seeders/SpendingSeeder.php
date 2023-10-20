<?php

namespace Database\Seeders;

use App\Models\Spending;
use Illuminate\Database\Seeder;

class SpendingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Spending::create([
            "user_id" => 1,
            "month" => 1,
            "year" => 1,
            "amount" => 30
        ]);

        Spending::create([
            "user_id" => 1,
            "month" => 2,
            "year" => 1,
            "amount" => 50
        ]);
    }
}