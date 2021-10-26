<?php 
$routes->group('news',['filter'=>'auth','namespace' => 'Modules\News\Controllers'], function ($routes) {
    $routes->add('', 'News::index');
    $routes->add('update', 'News::update');
    $routes->add('update/(:any)', 'News::update/$1');
    $routes->post('ajax_notification/(:any)', 'News::ajax_notification/$1');
    $routes->post('ajax_update','News::ajax_update');
    $routes->post('ajax_update/(:any)','News::ajax_update/$1');
    $routes->post('ajax_delete_item/(:any)', 'News::ajax_delete_item/$1');
    // $routes->post('ajax_actions_option','Category::ajax_actions_option');
    // $routes->add('index', 'Auth::index');
    // $routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    // $routes->add('signin','Auth::signin');
    // $routes->add('login','Auth::login');
    // $routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});