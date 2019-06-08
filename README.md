# 安装
`composer require jinlulu/wechat_swoole`
由于依赖包的问题还需要咱们手动下载另外一个cache包
`composer require psr/simple-cache`

# 配置文件
`wechat.php` 配置都是用的easywechat
日志文件没有做分隔，可以利用easyswoole的功能进行分割
```php
<?php
use Jinlulu\WechatSwoole\Lib\Cache;

return [
    'app_id' => 'xxx',
    'secret' => 'xxx',
    // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
    'response_type' => 'array',
    'cache' => new Cache('easy_wechat', 10, EASYSWOOLE_ROOT . '/Temp/cache'),
    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],
        // 'callback' => '/examples/oauth_callback.php',
    ],
    // 'http' => [
    //     'max_retries' => 1,
    //     'retry_delay' => 500,
    //     'timeout' => 5.0,
    //     // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
    // ],
    'log' => [
        'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => EASYSWOOLE_ROOT . '/Log/easy_wechat/easywechat.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path' => EASYSWOOLE_ROOT . '/Log/easy_wechat/easywechat.log',
                'level' => 'debug',
            ],
        ],
    ],

];
```

# 使用
```php
# 登录
public function login() {
    $instance = \EasySwoole\EasySwoole\Config::getInstance();
    $wechat = $instance->getConf('wechat');
    $queryParams = $this->request()->getQueryParams();
    $wechat['oauth']['callback'] = 'http://127.0.0.1:9501/login?' . http_build_query($queryParams);
    $wechatApp = officialAccount($wechat);
    if ($this->request()->getQueryParam('code')) {
        var_dump($wechatApp->getUserInfo());
        return;
    }
    return $this->response()->redirect($wechatApp->getOauthUrl());
}

# 获取jssign
public function jssign() {
    try {
        // $url 是你的加密url
        $url = $this->request()->getHeader('referer');
        if (empty($url)) {
            $url = $this->request()->getHeader('host')[0];
        }
        $wechatApp = officialAccount();
        $jsSign = $wechatApp->getJsSign($url);
        return $this->writeJson(200, $jsSign, 'ok');
    } catch (\Exception $e) {
        return $this->writeJson($e->getCode(), [], $e->getMessage());
    }
}

public function server() {
    $app = officialAccount()->getApp();
    $req = $this->request()->getSwooleRequest();
    $app->rebind('request', new \Jinlulu\WechatSwoole\Lib\Request($req));
    $app->server->push(function ($message) {
       return "您好！欢迎使用 WeChat!";
    });
    $response = $app->server->serve();
    $this->response()->write($response->getContent());
}
```
