<?php
use Jinlulu\WechatSwoole\OfficialAccount;

if (!function_exists('officialAccount')) {
    function officialAccount(array $conf = []) {
        if (empty($conf)) {
            $instance = \EasySwoole\EasySwoole\Config::getInstance();
            $wechat = $instance->getConf('wechat');
        }
        return OfficialAccount::getInstance($conf);
    }
}
