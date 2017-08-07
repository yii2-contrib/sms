<?php

namespace YiiContrib\Sms\Validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;
use YiiContrib\Sms\Component\Token;

class TokenValidator extends Validator
{
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
        
        if (!$this->phone) {
            throw new InvalidConfigException(Yii::t('contrib-sms', '{attribute} can not be empty', ['attribute' => __CLASS__ . '::$phone']));
        }
        
        $default = [
            'class' => Token::class,
            'seed' => $this->phone,
        ];
        $this->token = Yii::createObject(array_merge($default, $this->tokenConfig));
    }
    
    public function validateValue($value)
    {
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
