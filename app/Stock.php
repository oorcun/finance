<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
	 * Stocks to be used in this application.
	 *
	 * @var array
	 */
    protected $stocks = [
    	"AAPL" => "Apple Inc.",
    	"FB" => "Facebook Inc.",
    	"GOOGL" => "Alphabet Inc.",
    	"AMZN" => "Amazon.com Inc.",
    	"NVDA" => "NVIDIA Corporation",
    	"IBM" => "International Business Machines Corporation",
    	"MSFT" => "Microsoft Corporation",
    	"INTC" => "Intel Corporation",
    	"ORCL" => "Oracle Corporation",
    ];

    /**
     * Gets all stocks.
     *
     * @return array
     */
    public function getStocks()
    {
    	return $this->stocks;
    }
}
