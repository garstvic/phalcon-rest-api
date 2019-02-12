<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;

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
        $validator = new Validation();

        $validator->add(
            'license_plate_no',
            new PresenceOfValidator([
                'model' => $this,
                'message' => 'The license plate number is required!'
            ])
        );
        
        $validator->add(
            'engine_no',
            new PresenceOfValidator([
                'model' => $this,
                'message' => 'The engine number is required!'
            ])
        ); 
        
        $validator->add(
            'owner_name',
            new PresenceOfValidator([
                'model' => $this,
                'message' => 'The owner name is required!'
            ])
        );        

        $validator->add(
            'license_plate_no',
            new UniquenessValidator([
                'model' => $this,
                'message' => 'The license plate number should be unique!'
            ])
        );   

        $validator->add(
            'engine_no',
            new UniquenessValidator([
                'model' => $this,
                'message' => 'The engine number should be unique!'
            ])
        ); 
        
        $validator->add(
            'license_plate_no',
            new RegexValidator([
                'model' => $this,
                'pattern' => '/^[A-Z]{3}-[0-9]{3}$/',
                'message' => 'Invalid license plate number!'
            ])
        );        

        $this->validate($validator);
        
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
