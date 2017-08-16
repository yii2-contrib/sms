<?php

namespace YiiContrib\Sms\Validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\validators\Validator;
use YiiContrib\Sms\Component\Token;

class TokenValidator extends Validator
{
    /**
     * @var string
     */
    public $phoneAttribute = 'phone';
    /**
     * @var string
     */
    public $phone;
    /**
     * @var array
     */
    public $tokenConfig = [];
    /**
     * @var Token
     */
    protected $token;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (null === $this->message) {
            $this->message = Yii::t('contrib-sms', 'Token "{token}" is invalid.');
        }
        
        $default = [
            'class' => Token::class,
        ];
        $this->token = Yii::createObject(array_merge($default, $this->tokenConfig));
    }
    
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if (!$this->phone) {
            $this->phone = $model->{$this->phoneAttribute};
        }
        
        parent::validateAttribute($model, $attribute);
    }
    
    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!$this->phone) {
            throw new InvalidConfigException(Yii::t('contrib-sms', '{attribute} can not be empty',
                ['attribute' => __CLASS__ . '::$phone']));
        }
        $this->token->seed = $this->phone;
        
        if ($value !== $this->token->get()) {
            return [$this->message, ['token' => $value]];
        }
        
        return null;
    }
    
    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }
}
