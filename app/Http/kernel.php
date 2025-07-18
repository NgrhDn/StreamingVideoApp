<?php
// app/Http/Kernel.php

protected array $routeMiddleware = [
    // ...
    'auth' => \App\Http\Middleware\Authenticate::class,
    // ...
];