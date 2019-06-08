<?php
namespace Jinlulu\WechatSwoole\Lib;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class OauthRequest extends BaseRequest {

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
        $this->initialize($_GET, $_POST, $attributes, $_COOKIE, $_FILES, $_SERVER, $content);
    }
}
