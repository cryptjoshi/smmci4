<?php 
$routes->group('blocks',['filter' => 'auth','namespace' => 'Modules\Blocks\Controllers'], function ($routes) {
    $routes->get('index', 'Blocks::index');
    $routes->get('empty_data', 'Blocks::empty_data');
});