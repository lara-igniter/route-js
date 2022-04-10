<?php

namespace Elegant\RouteJs;

use Elegant\RouteJs\Output\MergeScript;
use Elegant\RouteJs\Output\Script;

class BladeRoute
{
    public static $generated;

    public function generate($group = null, $nonce = null)
    {
        $routejs = new RouteJs($group);

        $nonce = $nonce ? ' nonce="' . $nonce . '"' : '';

        if (static::$generated) {
            return (string) $this->generateMergeJavascript($routejs, $nonce);
        }

        $function = $this->getRouteFunction();

        static::$generated = true;

//        app()->config->load('routejs', TRUE);
//
//        app()->config->item('compiled', 'view');

//        $output = config('ziggy.output.script', Script::class);

        $output = Script::class;

        return (string) new $output($routejs, $function, $nonce);
    }

    private function generateMergeJavascript(RouteJs $routejs, $nonce)
    {
//        $output = config('ziggy.output.merge_script', MergeScript::class);
        $output = MergeScript::class;

        return new $output($routejs, $nonce);
    }

    private function getRouteFunction()
    {
       return config('ziggy.skip-route-function') ? '' : file_get_contents(__DIR__ . '/../dist/index.js');
        return file_get_contents(__DIR__ . '/js/index.js');
    }
}
