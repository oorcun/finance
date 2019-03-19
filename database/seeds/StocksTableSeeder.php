<?php

use App\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("stocks")->truncate();

        foreach ((new Stock)->getStocks() as $symbol => $name) {
        	Stock::create([
        		"symbol" => $symbol,
        		"name" => $name
        	]);
        }
    }
}
