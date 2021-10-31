<?php 
$routes->group('auth',['filter'=>'noauth','namespace' => 'Modules\Auth\Controllers'], function ($routes) {
    $routes->add('/', 'Auth::index');
    $routes->add('index', 'Auth::index');
    $routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    $routes->add('signin','Auth::signin');
    $routes->add('signup', 'Auth::signup');
    $routes->add('login','Auth::login');
 
  //  $routes->add('blog', 'Admin\Blog::index');
});
$routes->get('auth/logout','Auth::logout',['namespace' => 'Modules\Auth\Controllers']);