<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

$loader = new Loader();

$loader->registerDirs(
    array(
        __DIR__.'/app/models'    
    )
)->register();

$di = new FactoryDefault();

$di->set('db', function() {
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
$app->get(
    '/api/cars', 
    function() use ($app) 
    {
        $phql = 'SELECT * FROM Cars ORDER BY id DESC';
        $cars = $app->modelsManager->executeQuery($phql);
        
        $data = array();
        foreach ($cars as $car)
        {
            $data[] = array(
                'id'                => $car->id,
                'owner_name'        => $car->owner_name,
                'reg_date'          => $car->reg_date,
                'license_plate_no'  => $car->license_plate_no,
                'engine_no'         => $car->engine_no,
                'tax_payment'       => $car->tax_payment,
                'car_model'         => $car->car_model,
                'car_model_year'    => $car->car_model_year,
                'seating_capacity'  => $car->seating_capacity,
                'horse_power'       => $car->horse_power
            );
        }
        
        echo json_encode($data);
    }
);

// Searches for cars with $license_plate_no in their name
$app->get(
    '/api/cars/search/{license_plate_no}', 
    function($license_plate_no) use ($app) 
    {
        $phql = 'SELECT * FROM Cars WHERE license_plate_no = :license_plate_no:';
        $values = array('license_plate_no' => $license_plate_no);
        
        $car = $app->modelsManager->executeQuery($phql, $values)->getFirst();
        
        $data = array(
            'id'                => $car->id,
            'owner_name'        => $car->owner_name,
            'reg_date'          => $car->reg_date,
            'license_plate_no'  => $car->license_plate_no,
            'engine_no'         => $car->engine_no,
            'tax_payment'       => $car->tax_payment,
            'car_model'         => $car->car_model,
            'car_model_year'    => $car->car_model_year,
            'seating_capacity'  => $car->seating_capacity,
            'horse_power'       => $car->horse_power
        );
        
        echo json_encode($data);
    }
);

// Retrivies cars based on primary key ($id)
$app->get(
    '/api/cars/{id:[0-9]+}', 
    function($id) use ($app) 
    {
        $phql = 'SELECT * FROM Cars WHERE id = :id:';
        $values = array('id' => $id);
        
        $car = $app->modelsManager->executeQuery($phql, $values)->getFirst();
        
        $response = new Response();
        
        if ($car == FALSE)
        {
            $response->setJsonContent(
                array(
                    'status'    =>  'NOT-FOUND'    
                )  
            );
        }
        else
        {
            $response->setJsonContent(
                array(
                    'status'    => 'FOUND',
                    'data'      => array(
                        'id'                => $car->id,
                        'owner_name'        => $car->owner_name,
                        'reg_date'          => $car->reg_date,
                        'license_plate_no'  => $car->license_plate_no,
                        'engine_no'         => $car->engine_no,
                        'tax_payment'       => $car->tax_payment,
                        'car_model'         => $car->car_model,
                        'car_model_year'    => $car->car_model_year,
                        'seating_capacity'  => $car->seating_capacity,
                        'horse_power'       => $car->horse_power
                    )
                )  
            );
        }
        
        return $response;
    }
);

// Adds a new cars
$app->post(
    '/api/cars', 
    function() use ($app) 
    {
        $phql = '
            INSERT INTO Cars (
                owner_name, 
                reg_date, 
                license_plate_no, 
                engine_no, 
                tax_payment, 
                car_model, 
                car_model_year, 
                seating_capacity, 
                horse_power) 
            VALUES (
                :owner_name:, 
                :reg_date:, 
                :license_plate_no:, 
                :engine_no:, 
                :tax_payment:, 
                :car_model:, 
                :car_model_year:, 
                :seating_capacity:,
                :horse_power:
            )';
            
        $car = $app->request->getJsonRawBody();
        $values = array(
            'owner_name'        => $car->owner_name,
            'reg_date'          => $car->reg_date,
            'license_plate_no'  => $car->license_plate_no,
            'engine_no'         => $car->engine_no,
            'tax_payment'       => $car->tax_payment,
            'car_model'         => $car->car_model,
            'car_model_year'    => $car->car_model_year,
            'seating_capacity'  => $car->seating_capacity,
            'horse_power'       => $car->horse_power            
        );
        
        $result = $app->modelsManager->executeQuery($phql, $values);
        
        $response = new Response();
        
        if ($result->success() == TRUE)
        {
            $response->setStatusCode(201, 'Created');
            
            $car->id = $result->getModel()->id;
            
            $response->setJsonContent(
                array(
                    'status'    =>  'OK',
                    'data'      =>  $car
                )  
            );
        }
        else
        {
            $response->setStatusCode(409, 'Conflict');
            
            $errors = array();
            
            foreach($result->getMessages() as $message)
            {
                $errors[] = $message->getMessage();
            }
            
            $response->setJsonContent(
                array(
                    'status'    =>  'ERROR',
                    'message'   =>  $errors
                )  
            );
        }
        
        return $response;
    }
);

// Updates car based on primary key ($id)
$app->put(
    '/api/cars/{id:[0-9]+}', 
    function($id) use ($app)
    {
        $phql = 'UPDATE Cars SET
                    owner_name = :owner_name:,
                    reg_date = :reg_date:,
                    license_plate_no = :license_plate_no:,
                    engine_no = :engine_no:,
                    tax_payment = :tax_payment:,
                    car_model = :car_model:,
                    car_model_year = :car_model_year:,
                    seating_capacity = :seating_capacity:,
                    horse_power = :horse_power:
                WHERE id = :id:';
        
        $updatedCarValues = $app->request->getJsonRawBody();
        
        $values = array(
            'id'                => $id,
            'owner_name'        => $updatedCarValues->owner_name,
            'reg_date'          => $updatedCarValues->reg_date,
            'license_plate_no'  => $updatedCarValues->license_plate_no,
            'engine_no'         => $updatedCarValues->engine_no,
            'tax_payment'       => $updatedCarValues->tax_payment,
            'car_model'         => $updatedCarValues->car_model,
            'car_model_year'    => $updatedCarValues->car_model_year,
            'seating_capacity'  => $updatedCarValues->seating_capacity,
            'horse_power'       => $updatedCarValues->horse_power           
        );
        
        $result = $app->modelsManager->executeQuery($phql, $values);
        
        $response = new Response();
        
        if ($result->success() == TRUE)
        {
            $response->setStatusCode(200, 'OK');
            
            $car = $result->getModel()->id;
            
            $response->setJsonContent(
                array(
                    'status'    =>  'OK'    
                )  
            );
        }
        else
        {
            $response->setStatusCode(409, 'Conflict');
            
            $errors = array();
            
            foreach($result->getMessages() as $message)
            {
                $errors[] = $message->getMessage();
            }
            
            $response->setJsonContent(
                array(
                    'status'    =>  'ERROR',
                    'message'   =>  $errors
                )  
            );
        }
        
        return $response;    
    }
);

// Deletes car based on primary key ($id)
$app->delete(
    '/api/cars/{id:[0-9]+}', 
    function($id) use ($app)
    {
        $phql = 'DELETE FROM Cars WHERE id = :id:';
        
        $values = array(
            'id'    =>  $id  
        );
        
        $result = $app->modelsManager->executeQuery($phql, $values);
        
        $response = new Response();
        
        if ($result->success() == TRUE)
        {
            $response->setStatusCode(200, 'OK');
            
            $response->setJsonContent(
                array(
                    'status'    =>  'OK'    
                )  
            );
        }
        else
        {
            $response->setStatusCode(409, 'Conflict');
            
            $errors = array();
            
            foreach($result->getMessage() as $message)
            {
                $errors[] = $message->getMessage();
            }
            
            $response->setJsonContent(
                array(
                    'status'    =>  'ERROR',
                    'message'   =>  $errors
                )  
            );
        }
        
        return $response;
    }
);

$app->notFound(function() use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "This is crazy, but this page was not found!";
});

$app->handle();
