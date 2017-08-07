<?php

namespace YiiContrib\Sms\Component;

use Overtrue\EasySms\Contracts\StrategyInterface;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\Messenger;
use Overtrue\EasySms\Strategies\OrderStrategy;
use yii\base\Component;
use yii\console\controllers\MessageController;
use YiiContrib\Sms\Event\AfterSendEvent;
use YiiContrib\Sms\Event\BeforeSendEvent;

/**
 * Sms is the component build on `overtrue/easy-sms`.
 *
 * This component is build a simple interface to send message.
 *
 * The configuration like this:
 * maybe `main.php`, this depends on your application template
 *
 * ```
 * 'sms' => [
 *     'class' => \YiiContrib\Sms\Component\Sms::class,
 *     'gateways' => [
 *          'errorlog' => [
 *              'file' => '/tmp/easy-sms.log',
 *          ],
 *     ],
 * ]
 * ```
 * More gateways configuration please refer the `overtrue/easy-sms`.
 *
 * @author lichunqaing<light-li@hotmail.com>
 * @since 1.0.0
 */
class Sms extends Component
{
    const EVENT_BEFORE_SEND = 'beforeSend';
    const EVENT_AFTER_SEND  = 'afterSend';
    
    /**
     * @var array
     */
    public $gateways = [];
    /**
     * @var StrategyInterface
     */
    public $strategy = OrderStrategy::class;
    /**
     * @var float
     */
    public $timeout = 5.0;
    /**
     * @var EasySms
     */
    protected $_sms;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->_sms = new EasySms([
            'timeout' => $this->timeout,
            'default' => [
                'strategy' => $this->strategy,
                'gateways' => array_keys($this->gateways),
            ],
            'gateways' => $this->gateways,
        ]);
    }
    
    /**
     * @param string $to
     * @param array|\Overtrue\EasySms\Contracts\MessageInterface $message
     * @param array $gateways
     *
     * @return bool
     */
    public function send($to, $message, $gateways = [])
    {
        $this->trigger(self::EVENT_BEFORE_SEND, new BeforeSendEvent([
            'to' => $to,
            'message' => $message,
        ]));
        
        $result = $this->_sms->send($to, $message, $gateways);
        
        $this->trigger(self::EVENT_AFTER_SEND, new AfterSendEvent([
            'result' => $result,
        ]));
        
        foreach ($result as $gateway => $_result) {
            if ($_result['status'] === Messenger::STATUS_SUCCESS) {
                return true;
            }
        }
        
        return false;
    }
}
