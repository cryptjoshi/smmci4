<?php 
$routes->group('payments_bonuses',['filter' => 'auth','namespace' => 'Modules\Payments_bonuses\Controllers'], function ($routes) {
    $routes->add('/', 'Payments_bonuses::index');
    $routes->add('update/(:any)',"Payments_bonuses::update");
    $routes->post('ajax_update/(:any)',"Payments_bonuses::ajax_update");
    $routes->post('ajax_toggle_item_status/(:any)',"Payments_bonuses::ajax_toggle_item_status");
    $routes->post('ajax_delete_item/(:any)',"Payments_bonuses::ajax_delete_item");
    //$routes->add('log', 'Payments::log');
    //$routes->add('add', 'Payments::add');
    //$routes->get('empty_data', 'Blocks::empty_data');
});