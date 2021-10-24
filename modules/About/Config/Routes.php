<?php 
$routes->group('about',['namespace' => 'Modules\About\Controllers'], function ($routes) {
    $routes->add('/', 'About::index',);
    $routes->add('me','About::me');
  //  $routes->add('blog', 'Admin\Blog::index');
});