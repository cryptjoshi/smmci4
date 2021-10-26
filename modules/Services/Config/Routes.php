<?php 
$routes->group('services', ['filter' => 'auth','namespace' => 'Modules\Services\Controllers'], function ($routes) {
    $routes->add('', 'Services::index');
    $routes->add('/', 'Services::index');
});