<?php 
 $routes->group('setting',['filter' => 'auth','namespace' => 'Modules\Setting\Controllers'], function ($routes) {
     $routes->add('', 'Setting::index');
//     $routes->add('website_setting', 'Setting::index');
//     $routes->add('website_logo', 'Setting::index');
//     $routes->add('cookie_policy', 'Setting::cookie_policy');
//     $routes->add('terms_policy', 'Setting::terms_policy');
//     $routes->add('default', 'Setting::default');
//     $routes->add('currency', 'Setting::currency');
//     $routes->add('affiliate', 'Setting::affiliate');
//     $routes->add('childpanel', 'Setting::childpanel');
//     $routes->add('other', 'Setting::other');
//     $routes->add('smtp', 'Setting::smtp');
//     $routes->add('template', 'Setting::template');
//     $routes->add('payment', 'Setting::payment');

 });

$routes->get('setting/(:any)', 'Setting::index',['namespace' => 'Modules\Setting\Controllers']);
$routes->post('setting/ajax_general_settings','Setting::ajax_general_settings',['namespace' => 'Modules\Setting\Controllers']);