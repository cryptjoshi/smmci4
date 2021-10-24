<?php 
$routes->group('home',['filter' => 'auth','namespace' => 'Modules\Home\Controllers'], function ($routes) {
    $routes->add('/', 'Home::index');
    
    //$routes->get('empty_data', 'Blocks::empty_data');
});