<?php

namespace App\Providers;

use Elegant\RouteJS\BladeRoute;
use Elegant\Contracts\Hook\PostControllerConstructor;
use Elegant\Support\Facades\Blade;

class RouteJsServiceProvider implements PostControllerConstructor
{
    public function postControllerConstructor(&$params)
    {
        Blade::directive('routes', function ($group) {
            return (new BladeRoute())->generate($group);
        });
    }
}