<?php 
$routes->add('tickets/(:num)', 'Tickets::view/$1',['filter'=>'auth','namespace' => 'Modules\Tickets\Controllers']);
$routes->group('tickets',['filter'=>'auth','namespace' => 'Modules\Tickets\Controllers'], function ($routes) {
    $routes->add('', 'Tickets::index');
    $routes->get('add', 'Tickets::add');
    $routes->get('search', 'Tickets::search');
    $routes->post('ajax_add/(:any)', 'Tickets::ajax_add/$1');
    $routes->post('ajax_update/(:any)','Tickets::ajax_update/$1');
    $routes->post('ajax_change_status/(:any)','News::ajax_change_status/$1');
    $routes->post('ajax_search/(:any)','Tickets::ajax_search/$1');
    $routes->post('ajax_order_by/(:any)','Tickets::ajax_order_by/$1');
    $routes->post('ajax_delete_item/(:any)', 'Tickets::ajax_delete_item/$1');
    // $routes->add('update', 'News::update');
    // $routes->add('update/(:any)', 'News::update/$1');
    // $routes->post('ajax_notification/(:any)', 'News::ajax_notification/$1');
    // $routes->post('ajax_update','News::ajax_update');
    // $routes->post('ajax_order_by/(:any)','Tickets::ajax_order_by/$1');
    // $routes->post('ajax_delete_item/(:any)', 'Tickets::ajax_delete_item/$1');
   
});
 
 
 