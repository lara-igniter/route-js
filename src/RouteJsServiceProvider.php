<?php

namespace Elegant\RouteJs;

use Elegant\Contracts\Hook\PostControllerConstructor;
use Elegant\Contracts\Hook\PreSystem;
use Elegant\Support\Facades\Blade;

class RouteJsServiceProvider implements PreSystem, PostControllerConstructor
{
    public function preSystem()
    {
        if (!file_exists(APPPATH . '/config/route-js.php')) {
            copy(__DIR__ . './../config/route-js.php', APPPATH . '/config/route-js.php');
        }
    }

    public function postControllerConstructor(&$params)
    {
        Blade::directive('routes', function ($group) {
            return (new BladeRoute())->generate($group);
        });
    }
}