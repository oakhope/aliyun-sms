AliyunSMS
---------

阿里云短信发送sdk封装

## 更新记录

* 2017-06-02 `Release v0.0.1` 测试版发布

## 安装

```bash
composer require oakhope/aliyun-sms
```

### 使用

```php
$aliyunSms = new \Oakhope\AliyunSMS('accessId', 'accessKey', 'endPoint', 'topicName', 'signName');

$aliyunSms->sendOne('templateCode', 'phone', ['paramA' => 'valueA', 'paramB' => 'valueB']);
```

## License
除 “版权所有（C）阿里云计算有限公司” 的代码文件外，遵循 [MIT license](http://opensource.org/licenses/MIT) 开源
