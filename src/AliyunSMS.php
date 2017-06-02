<?php
/**
 * Created by wyq
 * Date: 17-6-2
 * Time: 下午3:37
 */

namespace Oakhope;

require_once __DIR__.'/aliyun-mns-php-sdk-1.3.4/mns-autoloader.php';

use Exception;
use AliyunMNS\Client;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class AliyunSMS
{
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;
    private $topicName;
    private $signName;

    public function __construct(
        $accessId,
        $accessKey,
        $endPoint,
        $topicName,
        $signName
    ) {
        /**
         * Step 1. 初始化Client
         */
        $this->accessId = $accessId;
        $this->accessKey = $accessKey;
        $this->endPoint = $endPoint;  // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com
        $this->topicName = $topicName;
        $this->signName = $signName;

        $this->client = new Client(
            $this->endPoint,
            $this->accessId,
            $this->accessKey
        );

    }

    public function sendOne($templateCode, $phone, array $templateParam = ['' => ''], $messageBody = 'empty') {

        /**
         * Step 2. 获取主题引用
         */
        $topic = $this->client->getTopicRef($this->topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes($this->signName, $templateCode);
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值

        $batchSmsAttributes->addReceiver($phone, $templateParam);

        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
//        $messageBody = "smsmessage";

        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $topic->publishMessage($request);
            return $res->isSucceed();
        }
        catch (MnsException $e)
        {
            throw new Exception($e->getMessage().' [code:'.$e->getMnsErrorCode().']');
        }
    }


}