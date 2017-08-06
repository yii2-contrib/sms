<?php


namespace YiiContrib\Sms\Validators;

use Yii;
use yii\validators\Validator;

class PhoneValidator extends Validator
{
    public $regexp = '/^1\d{10}/';
    
    public function init()
    {
        parent::init();
        
        if (null === $this->message) {
            $this->message = Yii::t('validator', '"{value}" is not an valid phone number');
        }
    }
    
    /**
     * @param mixed $value
     *
     * @return array|null
     */
    protected function validateValue($value)
    {
        if (preg_match($this->regexp, $value)) {
            return null;
        }
        
        return [
            $this->message,
            ['value' => $value]
        ];
    }
}