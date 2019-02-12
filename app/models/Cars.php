<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Validator\PresenceOf;
use Phalcon\Mvc\Validator\Uniqueness;
use Phalcon\Mvc\Validator\Regex;

class Cars extends Model
{
    public $owner_name;             // MR JOHN DOE
    public $reg_date;               // 1997-09-08
    public $license_plate_no;       // ABC-007
    public $engine_no;              // 3488057
    public $tax_payment;            // 1998-06-30
    public $car_model;              // MOYOTA MOROLLA
    public $car_model_year;         // 1997
    public $seating_capacity;       // 4
    public $horse_power;            // 200
    
    // Validations
    public function validation()
    {
        $this->validate(
            new PresenceOf(
                array(
                    'field'   => 'license_plate_no',
                    'message' => 'The license plate number is required!'
                )   
            )  
        );
        
        $this->validate(
            new PresenceOf(
                array(
                    'field'   => 'engine_no',
                    'message' => 'The engine number is required!'
                )   
            )  
        );
        
        $this->validate(
            new PresenceOf(
                array(
                    'field'   => 'owner_name',
                    'message' => 'The owner name is required!'
                )    
            )  
        );
        
        $this->validate(
            new Uniqueness(
                array(
                    'field'   => 'license_plate_no',
                    'message' => 'The license plate number should be unique!'
                )    
            )  
        );
        
        $this->validate(
            new Uniqueness(
                array(
                    'field'   => 'engine_no',
                    'message' => 'The engine number should be unique!'
                )    
            )  
        );
        
        $this->validate(
            new Regex(
                array(
                    'field'   => 'license_plate_no',
                    'pattern' => '/^[A-Z]{3}-[0-9]{3}$/',
                    'message' => 'Invalid license plate number!'
                )    
            )  
        );
        
        if ($this->car_model_year < 0)
        {
            $this->appendMessage(new Message('Car\'s model year can not be zero!'));
        }
        
        if ($this->validationHasFailed() == true)
        {
            return false;
        }
    }
}
