<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class StockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Displays all stocks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
    	$stocks = Stock::all();

        $user = auth()->user();

        $userStocks = $user->getStocksCount($stocks);

        $capital = $user->getCapital($stocks);

        $fetcherRunning = Cache::has("run") ? true : false;

    	return view("trade")->with(compact("stocks", "fetcherRunning", "userStocks", "user", "capital"));
    }

    /**
     * Gets the stock prices.
     *
     * @return array
     */
    public function getPrices()
    {
        return Cache::get("prices");
    }

    /**
     * Starts fetcher asynchronously.
     */
    public function startFetcher()
    {
        Cache::set("run", 1);

        if (env("OS") == "windows") {
            pclose(popen("cd .. && start /B php artisan stock:get", "r"));
        } else {
            exec("cd .. && php artisan stock:get > /dev/null &");
        }
    }

    /**
     * Stops fetcher.
     */
    public function stopFetcher()
    {
        Cache::forget("run");
    }

    /**
     * Buys a stock.
     *
     * @return \Illuminate\Http\Response|void
     */
    public function buy()
    {
        request()->validate([
            "id" => "required|integer|min:1",
            "price" => "required|numeric|min:0.01",
        ]);

        if (auth()->user()->cant("buy", Stock::class)) {
            return response("not enough credit", 403);
        }

        DB::transaction(function() {
            if ($stock = auth()->user()->stocks()->find(request()->id)) {
                auth()->user()->stocks()->updateExistingPivot(request()->id, ["count" => $stock->pivot->count + 1]);
            } else {
                auth()->user()->stocks()->attach(request()->id, ["count" => 1]);
            }

            auth()->user()->decreaseBalance(request()->price);
        });
    }

    /**
     * Sells a stock.
     *
     * @return \Illuminate\Http\Response|void
     */
    public function sell()
    {
        request()->validate([
            "id" => "required|integer|min:1",
            "price" => "required|numeric|min:0.01",
        ]);

        auth()->user()->load(["stocks" => function($query) {
            $query->find(request()->id);
        }]);

        if (auth()->user()->cant("sell", Stock::class)) {
            return response("no stock in user inventory", 403);
        }

        DB::transaction(function() {
            if (auth()->user()->stocks->first()->pivot->count > 1) {
                auth()->user()->stocks()->updateExistingPivot(request()->id, ["count" => auth()->user()->stocks->first()->pivot->count - 1]);
            } else {
                auth()->user()->stocks()->detach(request()->id);
            }

            auth()->user()->increaseBalance(request()->price);
        });
    }
}
