<?php
namespace Jinlulu\WechatSwoole\Lib;

use EasySwoole\Http\Request as BaseRequest;

class Request extends BaseRequest {

    use Singleton;

    public function getContentType() {
        return $this->getHeader('content_type');
    }

    public function getContent() {
        $content = $this->getSwooleRequest()->rawContent();
        return $content;
    }

    public function get($key, $def = NULL) {
        $get = $this->getRequestParam();
        if (isset($get[$key])) {
            return $get[$key];
        }
        return $def;
    }
}
