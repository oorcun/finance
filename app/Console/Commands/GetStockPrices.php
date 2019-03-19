<?php

namespace App\Console\Commands;

use App\Stock;
use App\Iex\IEXTrading;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GetStockPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "stock:get";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Gets all recorded stock prices from IEX (The Investors Exchange) API.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $symbols = Stock::all()->pluck("symbol")->toArray();

        while (Cache::has("run")) {
            $prices = Cache::get("prices", array_fill_keys(
                Stock::all()->pluck("symbol")->toArray(), ["price" => 0, "status" => "constant"])
            );

            $oldPrices = array_combine(array_keys($prices), array_column($prices, "price"));
            $newPrices = IEXTrading::getStockPrices($symbols);

            foreach ($newPrices as $symbol => $newPrice) {
                $prices[$symbol]["price"] = $newPrice;
                if ($oldPrices[$symbol] < $newPrice) {
                    $prices[$symbol]["status"] = "increased";
                } else if ($oldPrices[$symbol] > $newPrice) {
                    $prices[$symbol]["status"] = "decreased";
                } else {
                    $prices[$symbol]["status"] = "constant";
                }
            }

            Cache::put("prices", $prices);

            sleep(1);
        }
    }
}
