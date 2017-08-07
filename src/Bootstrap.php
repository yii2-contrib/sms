<?php

namespace YiiContrib\Sms;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if (!isset($app->getI18n()->translations['contrib-sms*'])) {
            $app->getI18n()->translations['contrib-sms*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/resources/i18n',
                'sourceLanguage' => 'en-US',
            ];
        }
    }
}
