<?php 
$routes->group('category',['filter'=>'auth','namespace' => 'Modules\Category\Controllers'], function ($routes) {
    $routes->add('/', 'Category::index');
    $routes->add('update/(:any)', 'Category::update');
    $routes->add('ajax_update/(:any)','Category::ajax_update');
    $routes->add('ajax_delete_item/(:any)', 'Category::ajax_delete_item');
    $routes->post('ajax_actions_option','Category::ajax_actions_option');
    // $routes->add('index', 'Auth::index');
    // $routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    // $routes->add('signin','Auth::signin');
    // $routes->add('login','Auth::login');
    // $routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});