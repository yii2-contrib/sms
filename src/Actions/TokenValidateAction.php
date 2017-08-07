<?php

namespace YiiContrib\Sms\Actions;

use Yii;
use yii\base\Action;
use yii\di\Instance;
use yii\validators\Validator;
use yii\web\Response;
use YiiContrib\Sms\Validators\TokenValidator;

class TokenValidateAction extends Action
{
    /**
     * @var string
     */
    public $method = 'post';
    /**
     * @var string
     */
    public $phoneParam = 'phone';
    /**
     * @var string
     */
    public $tokenParams = 'token';
    /**
     * @var string
     */
    public $validator = TokenValidator::class;
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $method = strtolower($this->method);
        $phone = Yii::$app->getRequest()->{$method}($this->phoneParam);
        $token = Yii::$app->getRequest()->{$method}($this->tokenParams);
        
        if (empty($phone) || empty($token)) {
            return ['errcode' => 1, 'errmsg' => Yii::t('contrib-sms', 'Invalid request parameters')];
        }
        
        /** @var TokenValidator $validator */
        $validator = Yii::createObject([
            'class' => $this->validator,
            'phone' => $phone,
        ]);
        
        $error = null;
        $validator->validate($token, $error);
        if ($error) {
            return [
                'errcode' => 1,
                'errmsg' => $error,
            ];
        }
        
        $validator->getToken()->remove();
        
        return ['errcode' => 0, 'errmsg' => Yii::t('contrib-sms', 'OK')];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return parent::beforeRun();
    }
}
