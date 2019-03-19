<?php

use App\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table("themes")->truncate();

        foreach ((new Theme)->getThemes() as $name => $url) {
        	Theme::create([
        		"name" => $name,
        		"url" => $url
        	]);
        }
    }
}
