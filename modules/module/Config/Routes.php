<?php 
$routes->group('module',['filter'=>'auth','namespace' => 'Modules\Module\Controllers'], function ($routes) {
$routes->add('', 'Module::index');
$routes->add('ajax_install_module/(:any)','Module::ajax_install_module/$1');
$routes->add('update/(:any)','Module::update/$1');
$routes->add('ajax_upgrade_module/(:any)','Module::ajax_upgrade_module/$1');
});