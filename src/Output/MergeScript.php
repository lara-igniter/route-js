<?php

namespace Elegant\RouteJs\Output;

use Elegant\RouteJs\RouteJs;
use Stringable;

class MergeScript implements Stringable
{
    protected RouteJs $routejs;

    protected string $nonce;

    public function __construct(
        RouteJs $routejs,
        string $nonce = ''
    ) {
        $this->routejs = $routejs;
        $this->nonce = $nonce;
    }

    public function __toString(): string
    {
        $routes = json_encode($this->routejs->toArray()['routes']);

        return
            <<<HTML
            <script type="text/javascript"{$this->nonce}>
                (function () {
                    const routes = {$routes};

                    Object.assign(RouteJS.routes, routes);
                })();
            </script>
            HTML;
    }
}
