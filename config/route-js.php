<?php

$config = [
    /**
     * If you only want to use the @routes directive to make your app's routes available in JavaScript, but don't need the route() helper 
     * function, set the skip-route-function config value to true
     */
    'skip-route-function' => false,

    /**
     * You have to choose one or the other. Setting both only and except will disable filtering altogether and return all named routes.
     * 
     * You can also use asterisks as wildcards in route filters. In the example below, admin.* will exclude routes named admin.login 
     * and admin.register
     */
    'only' => [
        //
    ],

    'except' => [
        '_debugbar'
    ],

    /**
     * You can also define groups of routes that you want make available in different places in your app, using a groups key in your config file
     */
    'groups' => [
        'admin' => ['admin.*'],
    ],
];