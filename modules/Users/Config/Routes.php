<?php 
$routes->group('users', ['filter' => 'auth','namespace' => 'Modules\Users\Controllers'], function ($routes) {
    $routes->add('', 'Users::index');
    $routes->add('index', 'Users::index');
    $routes->add('update/(:any)', 'Users::update');
    $routes->add('view_user/(:any)', 'Users::view_user');
    $routes->add('ajax_delete_item/(:any)','Users::ajax_delete_item');
    $routes->add('mail/(:any)','Users::mail');
    $routes->add('add_funds_manual/(:any)','Users::add_funds_manual');
});