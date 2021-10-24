<?php 
$routes->group('api',['namespace' => 'Modules\Api\Controllers'], function ($routes) {
    $routes->add('/', 'Api::index');
    $routes->add('docs', 'Api::docs');
    //$routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    //$routes->add('signin','Auth::signin');
    //$routes->add('login','Auth::login');
    //$routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});