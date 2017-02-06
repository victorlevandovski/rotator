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
        if (!preg_match('/^[0-9a-f]{16}$/', $rotatorId)) {
            throw new Exception('Invalid rotator ID');
        }

        $key = $this->cachePrefix . 'r' . $rotatorId;

        $rotator = $this->cachingEngine->get($key);

        if ($rotator === null) {
            $rotator = $this->rotatorOfId($rotatorId);
        }

        if ($rotator === null) {
            $rotator = [
                'id' => $rotatorId,
                'url' => null,
                'sub_id' => 0,
                'user_id' => 0,
            ];
        }

        $this->cachingEngine->set($key, $rotator, self::CACHE_TIMEOUT);

        if ($rotator['user_id'] == 0) {
            throw new Exception('Invalid rotator ID');
        }

        $rotator['promo'] = $this->promo($rotatorId);

        return $this->assembleFromArray($rotator);
    }

    protected function promo($rotatorId)
    {
        $key = $this->cachePrefix . 'p' . $rotatorId;

        $promo = $this->cachingEngine->get($key);

        if ($promo !== null) {
            return $promo;
        }

        $promo = $this->promoOfRotatorId($rotatorId);

        if ($promo === null) {
            throw new Exception('No promo for specified rotator');
        }

        $this->cachingEngine->set($key, $promo, self::CACHE_TIMEOUT);

        return $promo;
    }

    abstract protected function assembleFromArray($rotator);

    abstract protected function rotatorOfId($rotatorId);

    abstract protected function promoOfRotatorId($rotatorId);
}
