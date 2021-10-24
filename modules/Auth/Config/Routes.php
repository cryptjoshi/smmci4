<?php 
$routes->group('auth',['filter'=>'noauth','namespace' => 'Modules\Auth\Controllers'], function ($routes) {
    $routes->add('/', 'Auth::index');
    $routes->add('index', 'Auth::index');
    $routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    $routes->add('signin','Auth::signin');
    $routes->add('login','Auth::login');
    $routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});