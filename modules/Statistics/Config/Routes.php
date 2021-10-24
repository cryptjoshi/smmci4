<?php 
$routes->group('statistics',['namespace' => 'Modules\Statistics\Controllers','filter' => 'auth'], function ($routes) {
    $routes->add('/', 'Statistics::index');
    $routes->add('index', 'Statistics::index');
    // $routes->post('ajax_sign_in', 'Statistics::ajax_sign_in');
    // $routes->add('signin','Statistics::signin');
    // $routes->add('login','Statistics::login');
  //  $routes->add('blog', 'Admin\Blog::index');
});