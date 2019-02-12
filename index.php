<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

$loader = new Loader();

$loder->registerDirs(
    array(
        __DIR__.'/app/models'    
    )
)->register();

$di = new FactoryDefault();

$di->set('db', funciton() {
    return new PdoMysql(
        array(
            'host'     => 'localhost',
            'username' => 'garstvic',
            'password' => '',
            'dbname'   => 'c9'
        )  
    );
});

$app = new Micro($di);

// Retrieves all cars
$app->get('/api/cars', function() {
     
});

// Searches for cars with $license_plate_no in their name
$app->get('/api/cars/{$license_plate_no}', function($license_plate_no) {
    
});

// Retrivies cars based on primary key ($id)
$app->get('/api/cars/search/{id: [0-9]+}', function($id) {
    
});

// Adds a new cars
$app->post('/api/cars', function() {
    
});

// Updates car based on primary key ($id)
$app->put('/api/cars/{id: [0-9]+}', function($id) {
    
});

// Deletes car based on primary key ($id)
$app->delete('/api/cars/{id: [0-9]+}', function($id) {
    
});

$app->notFound(function() use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "This is crazy, but this page was not found!";
});

$app->handle();
