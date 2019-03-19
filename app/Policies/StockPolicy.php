<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determines if the stock can be bought by the user.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function buy(User $user)
    {
        return $user->balance >= request()->price;
    }

    /**
     * Determines if the stock can be sold by the user.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function sell(User $user)
    {
        return $user->stocks->isNotEmpty();
    }
}
