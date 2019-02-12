<?php

use Phalcon\Mvc\Micro;

$app = new Micro();

$app->get('/api/cars', function() {
     
});

$app->get('/api/cars/{id: [0-9]+}', function() {
    
});

$app->get('/api/cars/search/{reg_no}', function() {
    
});

$app->post('/api/cars', function() {
    
});

$app->put('/api/cars/{id: [0-9]+}', function() {
    
});

$app->delete('/api/cars/{id: [0-9]+}', function() {
    
});

$app->notFound(function() use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "This is crazy, but this page was not found!";
});

$app->handle();
