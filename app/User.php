<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', "theme_id", "balance"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Gets the user's theme.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Gets the user's stocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stocks()
    {
        return $this->belongsToMany(Stock::class)->withPivot("count");
    }

    /**
     * Gets the path of user's theme.
     *
     * @return string
     */
    public function getThemePath()
    {
        if ($this->theme->name == "Default") {
            return asset("css/app.css");
        }

        return $this->theme->url;
    }

    /**
     * Gets the user's stocks count.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $stocks
     * @return array
     */
    public function getStocksCount($stocks)
    {
        if ($this->stocks->isEmpty()) {
            foreach ($stocks as $stock) {
                $userStocks[$stock->id] = 0;
            }

            return $userStocks;
        }

        foreach ($this->stocks as $stock) {
            $userStocks[$stock->id] = $stock->pivot->count;
        }

        foreach ($stocks as $stock) {
            if( ! isset($userStocks[$stock->id])) {
                $userStocks[$stock->id] = 0;
            }
        }

        return $userStocks;
    }

    /**
     * Decreases user's balance by given amount.
     *
     * @param double $credits
     */
    public function decreaseBalance($credits)
    {
        $this->update([
            "balance" => $this->balance - $credits
        ]);
    }

    /**
     * Increases user's balance by given amount.
     *
     * @param double $credits
     */
    public function increaseBalance($credits)
    {
        $this->update([
            "balance" => $this->balance + $credits
        ]);
    }

    /**
     * Gets the user's capital which consists of user's balance plus all bought stocks.
     *
     * @return double
     */
    public function getCapital()
    {
        $prices = Cache::get("prices", array_fill_keys(
            Stock::all()->pluck("symbol")->toArray(), ["price" => 0, "status" => "constant"])
        );

        $capital = $this->balance;
        foreach ($this->stocks as $stock) {
            $capital += $stock->pivot->count * $prices[$stock->symbol]["price"];
        }

        return $capital;
    }
}
