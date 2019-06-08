<?php
namespace Jinlulu\WechatSwoole;

use EasyWeChat\Factory;
use EasySwoole\EasySwoole\Config;
use EasyWeChat\OfficialAccount\Application as OfficialAccountApplication;
use Jinlulu\WechatSwoole\Lib\Singleton;
use Jinlulu\WechatSwoole\Lib\OauthRequest;

// 微信
class OfficialAccount {

    use Singleton;

    private $app;
    private $conf = [];

    public function __construct(array $conf = []) {
        $this->setConf($conf);
        $this->setApp();
    }

    public function setConf(array $conf = []) : void {
        if (empty($conf)) {
            $instance = Config::getInstance();
            $conf = $instance->getConf('wechat');
        }
        if (empty($conf)) {
            throw new WechatException('缺少微信相关配置');
        }
        $this->conf = $conf;
    }

    public function getConf() : array {
        return $this->conf;
    }

    public function setApp() : void {
        $this->app = Factory::officialAccount($this->conf);
        $cacheDriver = $this->conf['cache'];
        $this->app->rebind('cache', $cacheDriver);
    }

    public function getApp() : OfficialAccountApplication {
        return $this->app;
    }

    public function getUserInfo() {
        return $this->app->oauth->setRequest($this->getRequest())->user();
    }

    public function getOauthUrl() {
        return $this->app->oauth->setRequest($this->getRequest())->redirect()->getTargetUrl();
    }

    private function getRequest() {
        return new OauthRequest();
    }

    /**
     * [getJsSign description]
     * @param  [array] $config ['onMenuShareQQ', 'onMenuShareWeibo']
     * @param  boolean $debug
     * @param  boolean $beta
     * @param  boolean $json
     * @return [type]
     */
    public function getJsSign($url, $config=[], $debug=false, $beta=false, $json=false) {
        $js = $this->app->jssdk;
        $js->setUrl($url);
        return $js->buildConfig($config, $debug, $beta, $json);
    }
}
