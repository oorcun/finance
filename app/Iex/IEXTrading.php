<?php

namespace App\Iex;

use DPRMC\IEXTrading\IEXTrading as IEXT;

class IEXTrading extends IEXT
{
	/**
	 * Gets the stock prices of given symbols.
	 *
	 * @param  array $symbols
	 * @return array
	 */
	public static function getStockPrices($symbols)
	{
        $response = \GuzzleHttp\json_decode(
        	(string)static::makeRequest(
	        	"GET",
	        	"stock/market/batch?symbols=" . implode(",", $symbols) . "&types=quote"
	        )->getBody(),
	        true
	    );

        foreach ($symbols as $symbol) {
        	$prices[$symbol] = $response[$symbol]["quote"]["latestPrice"];
        }

        return $prices;
    }
}
