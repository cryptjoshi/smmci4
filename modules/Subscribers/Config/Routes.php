<?php 
$routes->group('subscribers',['filter' => 'auth','namespace' => 'Modules\Subscribers\Controllers'], function ($routes) {
    $routes->add('/', 'Subscribers::index');
    $routes->add('index', 'Subscribers::index');
    // $routes->post('ajax_sign_in', 'Statistics::ajax_sign_in');
    // $routes->add('signin','Statistics::signin');
    // $routes->add('login','Statistics::login');
  //  $routes->add('blog', 'Admin\Blog::index');
});