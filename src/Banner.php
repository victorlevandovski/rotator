<?php

require_once('Promo.php');

class Banner implements Promo
{
    protected $id;
    protected $weight;
    protected $quality;
    protected $size;
    protected $image;

    public function __construct($id, $weight, $quality, $size, $image)
    {
        $this->id = $id;
        $this->weight = $weight;
        $this->quality = $quality;
        $this->size = $size;
        $this->image = $image;
    }

    public function id()
    {
        return $this->id;
    }

    public function weight($weight = null)
    {
        if ($weight !== null) {
            $this->weight = $weight;
        }

        return $this->weight;
    }

    public function quality($quality = null)
    {
        if ($quality !== null) {
            $this->quality = $quality;
        }

        return $this->quality;
    }

    public function size()
    {
        return $this->size;
    }

    public function image()
    {
        return $this->image;
    }

    public function width()
    {
        return intval(floor($this->size / 1000));
    }

    public function height()
    {
        return intval($this->size % 1000);
    }
}
