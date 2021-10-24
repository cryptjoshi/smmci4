<?php 
$routes->group('order',['filter' => 'auth','namespace' => 'Modules\Order\Controllers'], function ($routes) {
  //  $routes->match(['get', 'post'], '/', 'Order::index');
    $routes->add('', 'Order::add');
    $routes->add('/', 'Order::add');
    $routes->add('log', 'Order::log');
    $routes->add('add', 'Order::add');
    $routes->post('getdata','Order::getdata');
    $routes->post('get_services/(:any)','Order::get_services');
    $routes->post('get_service/(:any)','Order::get_service');
    $routes->post('service_search','Order::service_search');
    $routes->post('ajax_add_order','Order::ajax_add_order');
    $routes->post('ajax_mass_order','Order::ajax_mass_order');
    $routes->post('ajax_load_services/(:any)','Order::ajax_load_services');
    //$routes->get('empty_data', 'Blocks::empty_data');
});