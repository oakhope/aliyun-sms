<?php

namespace Oakhope;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

class AliyunSMS
{
    private $accessId;
    private $accessKey;
    private $signName;
    private static $acsClient = null;

    /**
     * AliyunSMS constructor.
     * @param $accessId
     * @param $accessKey
     * @param string $endPoint @deprecated v2.0.0
     * @param string $topicName @deprecated v2.0.0
     * @param $signName
     */
    public function __construct(
        $accessId,
        $accessKey,
        $endPoint = 'deprecated',
        $topicName = 'deprecated',
        $signName
    ) {
        $this->accessId = $accessId;
        $this->accessKey = $accessKey;
        $this->signName = $signName;
    }

    /**
     * @param $templateCode
     * @param $phoneNumbers
     * @param array $templateParam
     * @param string $messageBody @deprecated v2.0.0
     * @param string $outId 可选，设置流水号
     * @param string $smsUpExtendCode 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
     * @return mixed
        class stdClass#1245 (4) {
            public $Message =>
            string(2) "OK"
            public $RequestId =>
            string(36) "B1572C16-8C32-44EB-A497-B60E42EB1FCA"
            public $BizId =>
            string(20) "376905715726518670^0"
            public $Code =>
            string(2) "OK"
        }
     */
    public function sendOne($templateCode, $phoneNumbers, array $templateParam = ['' => ''], $messageBody = null,  $outId = null, $smsUpExtendCode = null) {

        // 参数转为string避免报错
        $templateParam = array_map(
            function ($value) {
                return (string)$value;
            },
            $templateParam
        );

        return $this->sendSms($phoneNumbers, $this->signName, $templateCode, $templateParam['code'], $templateParam['product'], $outId, $smsUpExtendCode);
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    private function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        $accessKeyId = $this->accessId; // AccessKeyId
        $accessKeySecret = $this->accessKey; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        if(static::$acsClient === null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }

        return static::$acsClient;
    }

    /**
     * 发送短信
     * @param $phoneNumbers array|string
     * @param $signName string
     * @param $templateCode string
     * @param $code string
     * @param $product string
     * @param $outId string
     * @param $smsUpExtendCode string
     * @return mixed|\SimpleXMLElement
        class stdClass#1245 (4) {
           public $Message =>
           string(2) "OK"
           public $RequestId =>
           string(36) "B1572C16-8C32-44EB-A497-B60E42EB1FCA"
           public $BizId =>
           string(20) "376905715726518670^0"
           public $Code =>
           string(2) "OK"
        }
     */
    private function sendSms($phoneNumbers, $signName, $templateCode, $code, $product, $outId = null, $smsUpExtendCode = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            'code' => $code,
            'product' => $product
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        if ($outId) {
            $request->setOutId($outId);
        }

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        if ($smsUpExtendCode) {
            $request->setSmsUpExtendCode($smsUpExtendCode);
        }

        // 发起访问请求 return $acsResponse
        return $this->getAcsClient()->getAcsResponse($request);
    }

    /**
     * 短信发送记录查询
     * @param $phoneNumber
     * @param $sendDate
     * @param $pageSize
     * @param $currentPage
     * @param string $yourBizId
     * @return mixed|\SimpleXMLElement
     */
    public function querySendDetails($phoneNumber, $sendDate, $pageSize, $currentPage, $yourBizId = "yourBizId") {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
//        $request->setPhoneNumber('1234567');
        $request->setPhoneNumber($phoneNumber);

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
//        $request->setSendDate("20170718");
        $request->setSendDate($sendDate);

        // 必填，分页大小
//        $request->setPageSize(10);
        $request->setPageSize($pageSize);

        // 必填，当前页码
//        $request->setCurrentPage(1);
        $request->setCurrentPage($currentPage);

        // 选填，短信发送流水号
        $request->setBizId($yourBizId);

        // 发起访问请求 return $acsResponse
        return $this->getAcsClient()->getAcsResponse($request);
    }
}