AliyunSMS
---------

阿里云短信发送sdk封装

## 更新记录


* `Release v2.0.0 (待发布)` 采用阿里新版SDK，不兼容v1.0.0以前版本的代码调用
* 2018-01-12 `Release v1.0.0` 兼容测试版；采用阿里新版SDK以应对阿里于18年1月22日停止对旧版短信服务的支持
* 2017-07-06 `Release v0.0.8` 测试版;目前仅支持单条短信发送;一点优化及改bug
* 2017-06-02 `Release v0.0.1` 测试版发布

## 安装

```bash
composer require oakhope/aliyun-sms
```

### 使用


#### v2.0.0 (待发布)
```php
$aliyunSms = new \Oakhope\AliyunSMS('accessId', 'accessKey', 'signName');

$aliyunSms->sendOne('templateCode', 'phone', 'code', 'product');
```

#### v1.0.0及以前版本
```php
$aliyunSms = new \Oakhope\AliyunSMS('accessId', 'accessKey', 'endPoint', 'topicName', 'signName');

$aliyunSms->sendOne('templateCode', 'phone', ['paramA' => 'valueA', 'paramB' => 'valueB']);
```

## License
除 “版权所有（C）阿里云计算有限公司” 的代码文件外，遵循 [MIT license](http://opensource.org/licenses/MIT) 开源
