<?php


namespace YiiContrib\Sms\Validators;

use Yii;
use yii\validators\Validator;

class TokenValidator extends Validator
{
    public $phoneAttribute = 'phone';
    public $phoneValue;
    
    public function init()
    {
        parent::init();
    }
    
    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);
    }
}