<?php

class Rotator
{
    protected $rotatorId;
    protected $userId;
    protected $subId;
    protected $url;
    protected $promo;

    const MINIMUM_CTR = 0.00001;
    const ACTION_VIEW = 'v';
    const ACTION_CLICK = 'c';

    public function __construct($rotatorId, $userId, $subId, $url, $promo)
    {
        $this->rotatorId = $rotatorId;
        $this->userId = $userId;
        $this->subId = $subId;
        $this->url = $url;
        $this->promo = $promo;
    }

    /**
     * @return Promo
     * @throws Exception
     */
    public function rotate()
    {
        $sum = 0;
        $maxWeight = (1 / self::MINIMUM_CTR);

        foreach ($this->promo as $promo) {
            /**
             * @var Promo $promo
             */
            if ($promo->quality() < 0.1 || $promo->quality() > 10) {
                $promo->quality(1);
            }

            $weight = intval(round($promo->weight() * $promo->quality()));

            if ($weight < 1) {
                $weight = 1;
            } else if ($weight > $maxWeight) {
                $weight = $maxWeight;
            }

            $promo->weight($weight);
            $sum += $weight;
        }

        srand();
        $weight = rand(1, $sum);
        $sum = 0;

        foreach ($this->promo as $promo) {
            $sum += $promo->weight();
            if ($sum >= $weight) {
                $this->log($this->rotatorId, $promo->id(), self::ACTION_VIEW);
                return $promo;
            }
        }

        throw new Exception('Rotation error');
    }

    public function click($promoId)
    {
        $this->log($promoId, self::ACTION_CLICK);

        $url = $this->url;
        $url .= strstr($url, '?') ? '&' : '?';
        $url .= "partner={$this->userId}&sub_id={$this->subId}";

        return $url;
    }

    protected function log($promoId, $action)
    {
        return file_put_contents($this->logFile(), "{$this->rotatorId},{$promoId},{$action}\n", FILE_APPEND | LOCK_EX);
    }

    protected function logFile()
    {
        return STATISTICS_DIR . '/' . floor(time() / 60) . '.log';
    }
}
