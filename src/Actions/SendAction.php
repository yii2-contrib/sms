<?php

namespace YiiContrib\Sms\Actions;

use Overtrue\EasySms\Message;
use Yii;
use yii\base\Action;
use yii\di\Instance;
use yii\validators\Validator;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use YiiContrib\Sms\Component\Sms;
use YiiContrib\Sms\Helpers\TokenHelper;
use YiiContrib\Sms\Persistence\PersistenceInterface;
use YiiContrib\Sms\Persistence\Session;
use YiiContrib\Sms\Validators\PhoneValidator;

class SendAction extends Action
{
    /**
     * @var string The $_GET or $_POST params of the `phone` params
     */
    public $phoneParams = 'phone';
    /**
     * @var string
     */
    public $method = 'get';
    /**
     * @var int The token length.
     */
    public $tokenLength = 4;
    /**
     * @var int The seconds of the token is expired.
     */
    public $tokenExpiry = 90;
    /**
     * @var string|array|Sms The component id or configuration array or component object of the sms component.
     */
    public $sms = 'sms1';
    /**
     * @var string|PersistenceInterface
     */
    public $persistence = Session::class;
    /**
     * @var string|Validator
     */
    public $validator = PhoneValidator::class;
    /**
     * @var \Overtrue\EasySms\Contracts\MessageInterface
     */
    public $message;
    /**
     * @var null|\Closure
     */
    public $tokenGenerator;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('This only can access via ajax.');
        }
        //json response
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        
        $this->persistence = Instance::ensure($this->persistence, PersistenceInterface::class);
        $this->validator = Instance::ensure($this->validator, Validator::class);
        
        $this->sms = Instance::ensure($this->sms, Sms::class);
        $this->message = Instance::ensure($this->message, Message::class);
    }
    
    public function run()
    {
        $method = strtolower($this->method);
        $phone = Yii::$app->getRequest()->{$method}($this->phoneParams);
        
        if (empty($phone)) {
            return 'test';
        }
        
        $error = null;
        
        $this->validator->validate($phone, $error);
        
        if ($error) {
            return $error;
        }
        
        $cacheKey = $this->persistence->buildKey($phone);
        
        if ($this->persistence->exists($cacheKey)) {
            return 'send later';
        }
        
        $token = TokenHelper::random($this->tokenLength);
        
        $this->persistence->set($cacheKey, $token, $this->tokenExpiry);
        
        $this->sms->send($phone, $this->message);
        
        return [
            'errcode' => 0,
            'errmsg' => 'OK',
            'data' => [
                'token' => $token,
                'expiry' => $this->tokenExpiry,
            ],
        ];
        
    }
}