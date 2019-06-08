<?php
namespace Jinlulu\WechatSwoole\Lib;

use Symfony\Component\Cache\Simple\FilesystemCache as BaseCache;

class Cache extends BaseCache {
    use Singleton;
}
