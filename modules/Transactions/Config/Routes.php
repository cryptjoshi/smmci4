<?php 
$routes->group('transactions',['filter' => 'auth', 'namespace' => 'Modules\Transactions\Controllers'], function ($routes) {
    $routes->add('/', 'Transactions::index');
    $routes->add('/index', 'Transactions::index');
});