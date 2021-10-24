<?php 
$routes->group('payments',['filter' => 'auth','namespace' => 'Modules\Payments\Controllers'], function ($routes) {
    $routes->add('/', 'Payments::index');
    $routes->add('update/(:any)',"Payments::update");
    $routes->post('ajax_update/(:any)',"Payments::ajax_update");
    //$routes->add('log', 'Payments::log');
    //$routes->add('add', 'Payments::add');
    //$routes->get('empty_data', 'Blocks::empty_data');
});