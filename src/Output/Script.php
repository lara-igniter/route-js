<?php

namespace Elegant\RouteJs\Output;

use Elegant\RouteJs\RouteJs;
use Stringable;

class Script implements Stringable
{
    protected RouteJs $routejs;

    protected string $function;

    protected string $nonce;

    public function __construct(
        RouteJs $routejs,
        string $function,
        string $nonce = ''
    ) {
        $this->routejs = $routejs;
        $this->function = $function;
        $this->nonce = $nonce;
    }

    public function __toString(): string
    {
        return
            <<<HTML
            <script type="text/javascript"{$this->nonce}>
                const RouteJS = {$this->routejs->toJson()};
                {$this->function}
            </script>
            HTML;
    }
}
