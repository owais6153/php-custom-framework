<?php
namespace App\Middlewares;

interface Middleware {
    public function __invoke($request, $next);
}