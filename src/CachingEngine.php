<?php

interface CachingEngine
{
    public function set($key, $value, $timeout);
    public function get($key);
}
