<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
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
     * Updates theme.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        request()->validate([
            "theme" => "required|integer|min:1",
        ]);

    	auth()->user()->update([
    		"theme_id" => request()->theme
    	]);

    	return back()->with("status", "Theme successfully applied.");
    }
}
