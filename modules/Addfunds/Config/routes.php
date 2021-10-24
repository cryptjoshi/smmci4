<?php 
$routes->group('add_funds',['filter' => 'auth','namespace' => 'Modules\Addfunds\Controllers'], function ($routes) {
    $routes->add('/', 'Addfunds::index');
    $routes->post('process','Addfunds::process');
    $routes->add('omise','Addfunds::omise');        
    $routes->post('omise/process','Omise::process');
    $routes->post('omise/otp','Omise::otp');
    $routes->get('omise/success','Omise::success');
    $routes->get('omise/checkotp','Omise::checkotp');

    //$routes->add('docs', 'Aadd_fundspi::docs');
    //$routes->post('ajax_sign_in', 'Auth::ajax_sign_in');
    //$routes->add('signin','Auth::signin');
    //$routes->add('login','Auth::login');
    //$routes->add('logout','Auth::logout');
  //  $routes->add('blog', 'Admin\Blog::index');
});

