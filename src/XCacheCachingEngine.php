<?php

require_once('CachingEngine.php');

class XCacheCachingEngine implements CachingEngine
{
    public function set($key, $value, $timeout)
    {
        xcache_set($key, $value, $timeout);
    }

    public function get($key)
    {
        return xcache_get($key);
    }
}
