<?php 
$routes->group('profile', ['namespace' => 'Modules\Profile\Controllers','filter'=>'auth'], function ($routes) {
    $routes->add('', 'Profile::index');
    $routes->post('ajax_update','Profile::ajax_update');
    $routes->post('ajax_update_more_infors','Profile::ajax_update_more_infors');
    $routes->post('ajax_update_api','Profile::ajax_update_api');
    //$routes->add('', 'Services::index');
});