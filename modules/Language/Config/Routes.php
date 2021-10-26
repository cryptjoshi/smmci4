<?php 
$routes->group('language',['filter'=>'auth','namespace' => 'Modules\Language\Controllers'], function ($routes) {
    $routes->add('', 'Language::index');
    $routes->post('set_language/(:any)', 'Language::set_language/$1');
    $routes->add('update/(:any)', 'Language::update/$1');
    $routes->add('ajax_update/(:any)','Language::ajax_update/$1');
    $routes->add('ajax_delete_item/(:any)', 'Language::ajax_delete_item/$1');
    // $routes->post('ajax_actions_option','Category::ajax_actions_option');
    // $routes->add('index', 'Auth::index');
    // $routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    // $routes->add('signin','Auth::signin');
    // $routes->add('login','Auth::login');
    // $routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});