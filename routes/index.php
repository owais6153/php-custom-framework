<?php
use App\Includes\Routes;
use App\Includes\HttpRequest;
use App\Controllers\Common\PagesController;

    Routes::get('/', PagesController::class, 'home');

try {
    Routes::resolve(new HttpRequest());
} catch (Error $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}