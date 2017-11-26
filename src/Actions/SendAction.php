<?php

namespace YiiContrib\Sms\Actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\validators\Validator;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use YiiContrib\Sms\Component\Sms;
use YiiContrib\Sms\Component\Token;
use YiiContrib\Sms\Contracts\Message;
use YiiContrib\Sms\Validators\PhoneValidator;

class SendAction extends Action
{
    /**
     * @var string The $_GET or $_POST params of the `phone` params
     */
    public $phoneParam = 'phone';
    /**
     * @var \Closure
     */
    public $phoneValue;
    /**
     * @var string
     */
    public $method = 'get';
    /**
     * @var string|array|Sms The component id or configuration array or component object of the sms component.
     */
    public $sms = 'sms';
    /**
     * @var string|Validator
     */
    public $validator = PhoneValidator::class;
    /**
     * @var Message
     */
    public $message;
    /**
     * @var string
     */
    public $messageDataTokenKey = 'token';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException(Yii::t('contrib-sms', 'This only can access via ajax.'));
        }
        if (!$this->message) {
            throw new InvalidConfigException('Please configrate the "message" attribute!');
        }
        
        //json response
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        
        $this->validator = Instance::ensure($this->validator, Validator::class);
        
        $this->sms = Instance::ensure($this->sms, Sms::class);
        $this->message = Instance::ensure($this->message, Message::class);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $method = strtolower($this->method);
        if (is_callable($this->phoneValue)) {
            $phone = call_user_func($this->phoneValue);
        } else {
            $phone = Yii::$app->getRequest()->{$method}($this->phoneParam);
        }
        
        if (empty($phone)) {
            return [
                'errcode' => 1,
                'errmsg' => Yii::t('contrib-sms', 'The phone value can not empty.'),
            ];
        }
        
        $error = null;
        $this->validator->validate($phone, $error);
        
        if ($error) {
            return [
                'errcode' => 1,
                'errmsg' => $error,
            ];
        }
        
        /** @var Token $token */
        $token = Yii::createObject([
            'class' => Token::class,
            'seed' => $phone,
        ]);
        
        if ($token->exists()) {
            return [
                'errcode' => 2,
                'errmsg' => Yii::t('contrib-sms', 'The token had been sent.'),
                'ttl' => $token->ttl(),
            ];
        }
        
        $token_str = $token->generate();
        
        $this->message->data = [$this->messageDataTokenKey => $token_str];
        
        $result = $this->sms->send($phone, $this->message);
        
        if (false === $result) {
            $token->remove();
            
            return [
                'errcode' => 1,
                'errmsg' => Yii::t('contrib-sms', 'Send token error, please retry.'),
            ];
        }
        
        return [
            'errcode' => 0,
            'errmsg' => Yii::t('contrib-sms', 'OK'),
            'data' => [
                'expiry' => $token->expiry,
            ],
        ];
        
    }
}
