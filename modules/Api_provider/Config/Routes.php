<?php 
$routes->group('api_provider',['namespace' => 'Modules\Api_provider\Controllers'], function ($routes) {
    $routes->add('/', 'Api_provider::index');
    //$routes->add('docs', 'Api_provider::docs');
    //$routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    //$routes->add('signin','Auth::signin');
    //$routes->add('login','Auth::login');
    //$routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});