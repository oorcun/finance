<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
	/**
	 * Bootstrap themes from https://www.bootstrapcdn.com/bootswatch.
	 *
	 * @var array
	 */
    protected $themes = [
    	"Default" => "",
    	"Cerulean" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cerulean/bootstrap.min.css",
    	"Cosmo" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cosmo/bootstrap.min.css",
    	"Cyborg" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cyborg/bootstrap.min.css",
    	"Darkly" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/darkly/bootstrap.min.css",
    	"Flatly" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/flatly/bootstrap.min.css",
    	"Journal" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/journal/bootstrap.min.css",
    	"Litera" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css",
    	"Lumen" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lumen/bootstrap.min.css",
    	"LUX" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css",
    	"Materia" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/materia/bootstrap.min.css",
    	"Minty" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/minty/bootstrap.min.css",
    	"Pulse" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/pulse/bootstrap.min.css",
    	"Sandstone" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/sandstone/bootstrap.min.css",
    	"Simplex" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/simplex/bootstrap.min.css",
    	"Sketchy" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/sketchy/bootstrap.min.css",
    	"Slate" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/slate/bootstrap.min.css",
    	"Solar" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/solar/bootstrap.min.css",
    	"Spacelab" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/spacelab/bootstrap.min.css",
    	"Superhero" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/superhero/bootstrap.min.css",
    	"United" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/united/bootstrap.min.css",
    	"Yeti" => "https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/yeti/bootstrap.min.css",
    ];

    /**
     * Gets all themes.
     *
     * @return array
     */
    public function getThemes()
    {
    	return $this->themes;
    }
}
