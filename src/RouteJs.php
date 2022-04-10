<?php

namespace Elegant\RouteJs;

use Elegant\Routing\RouteBuilder;
use Elegant\Support\Arr;
use Elegant\Support\Collection;
use Elegant\Support\Str;
use JsonSerializable;

class RouteJs implements JsonSerializable
{

    protected static $cache;

    protected $url;

    protected $group;

    protected $routes;

    public function __construct($group = null, string $url = null)
    {
        $this->group = $group;

        $this->url = rtrim($url ?? base_url('/'), '/');

        if (!static::$cache) {
            static::$cache = $this->nameKeyedRoutes();
        }

        $this->routes = static::$cache;
    }

    public static function clearRoutes()
    {
        static::$cache = null;
    }

    /**
     * Filter routes by name using the given patterns.
     */
    public function filter($filters = [], $include = true): self
    {
        $this->routes = $this->routes->filter(
            function ($route, $name) use ($filters, $include) {
                return Str::is(
                    Arr::wrap($filters),
                    $name
                ) ? $include : !$include;
            }
        );

        return $this;
    }


    /**
     * Convert this RouteJS instance to an array.
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'port' => parse_url($this->url)['port'] ?? null,
            'defaults' => [],
            'routes' => $this->applyFilters($this->group)->toArray(),
        ];
    }

    /**
     * Convert this RouteJS instance into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return array_merge($routes = $this->toArray(), [
            'defaults' => (object)$routes['defaults'],
        ]);
    }

    /**
     * Convert this RouteJS instance to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    private function applyFilters($group)
    {
        if ($group) {
            return $this->group($group);
        }

        //        // return unfiltered routes if user set both config options.
        //        if (config()->has('ziggy.except') && config()->has('ziggy.only')) {
        //            return $this->routes;
        //        }
        //
        //        if (config()->has('ziggy.except')) {
        //            return $this->filter(config('ziggy.except'), false)->routes;
        //        }
        //
        //        if (config()->has('ziggy.only')) {
        //            return $this->filter(config('ziggy.only'))->routes;
        //        }

        return $this->routes;
    }

    /**
     * Filter routes by group.
     */
    //    private function group($group)
    //    {
    //        if (is_array($group)) {
    //            $filters = [];
    //
    //            foreach ($group as $groupName) {
    //                $filters = array_merge(
    //                    $filters,
    //                    config("ziggy.groups.{$groupName}")
    //                );
    //            }
    //
    //            return $this->filter($filters, true)->routes;
    //        }
    //
    //        if (config()->has("ziggy.groups.{$group}")) {
    //            return $this->filter(config("ziggy.groups.{$group}"), true)->routes;
    //        }
    //
    //        return $this->routes;
    //    }

    /**
     * Get a list of the application's named routes, keyed by their names.
     */
    private function nameKeyedRoutes(): Collection
    {
        $routes = collect(RouteBuilder::$compiled['names'])
            ->reject(function ($route) {
                //                dd($route);
                return Str::startsWith($route->getName(), 'phpdebugbar');
            })->reject(function ($route) {
                return Str::is('default_controller', $route->getName());
            })->reject(function ($route) {
                return Str::is('translate_uri_dashes', $route->getName());
            })->reject(function ($route) {
                return Str::is('404_override', $route->getName());
            });

        return $routes->map(function ($route) {
            return collect($route)->only(['params'])
                ->put('uri', $route->getPath())
                ->put('methods', $route->getMethods())
                //                    ->when($middleware = config('ziggy.middleware'), function ($collection) use ($middleware, $route) {
                //                        if (is_array($middleware)) {
                //                            return $collection->put('middleware', collect($route->middleware())->intersect($middleware)->values()->all());
                //                        }
                //
                //                        return $collection->put('middleware', $route->middleware());
                //                    })
                ->filter();
        });
    }

}
