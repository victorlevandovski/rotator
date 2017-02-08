<?php

abstract class RotatorRepository
{
    protected $cachePrefix = '';
    const CACHE_TIMEOUT = 67;

    /**
     * @var CachingEngine
     */
    protected $cachingEngine;

    public function __construct(CachingEngine $cachingEngine)
    {
        $this->cachingEngine = $cachingEngine;
    }

    /**
     * @param $rotatorId
     * @return Rotator
     * @throws Exception
     */
    public function rotator($rotatorId)
    {
        $key = $this->cachePrefix . $rotatorId;

        $rotator = $this->cachingEngine->get($key);

        if ($rotator === null) {echo 'reloaded from db';
            $rotator = $this->rotatorOfId($rotatorId);

            if ($rotator === null) {
                $rotator = ['id' => 0];
            }

            $this->cachingEngine->set($key, $rotator, self::CACHE_TIMEOUT);
        }

        if (!$rotator['id']) {
            throw new Exception('Invalid rotator ID');
        }

        if (!$rotator['promo']) {
            throw new Exception('No promo for specified rotator');
        }

        return $this->assembleFromArray($rotator);
    }

    abstract protected function rotatorOfId($rotatorId);
    abstract protected function assembleFromArray($rotator);
}
