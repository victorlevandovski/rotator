<?php

interface RotatorInterface {

	function rotate();
	function click($promoId);
}

abstract class Rotator implements RotatorInterface {

	const MINIMUM_CTR = 0.00001;

	const ACTION_VIEW = 'v';
	const ACTION_CLICK = 'c';

	public $dbc;

	public $rotator = [];
	public $promo = [];

	function __construct(RotatorStorage $storage)
	{
		$this->storage = $storage;

		if (!$this->rotator = $this->storage->rotator()) {
			die('Invalid rotator ID');
		}

		if (!$this->promo = $this->storage->promo()) {
			die('Rotator promo not found');
		}
	}

	function log($rotator, $promo, $action)
	{
		if (!intval($rotator) || !intval($promo) || !in_array($action, array(self::ACTION_VIEW, self::ACTION_CLICK))) {
			return false;
		}

		return file_put_contents($this->logFile(), "{$rotator},{$promo},{$action}\n", FILE_APPEND | LOCK_EX);
	}

	function logFile()
	{
		return STATISTICS_DIR . '/' . floor(time() / 60) . '.log';
	}

	function rotate()
	{
		$sum = 0;
		$maxWeight = (1 / self::MINIMUM_CTR);

		foreach ($this->promo as $i => $promo) {
			if ($promo['q'] < 0.1 || $promo['q'] > 10) {
				$promo['q'] = 1;
			}

			$weight = intval(round($promo['w'] * $promo['q']));

			if ($weight < 1) {
				$weight = 1;
			} else if ($weight > $maxWeight) {
				$weight = $maxWeight;
			}

			$this->promo[$i]['w'] = $weight;
			$sum += $weight;
		}

		srand();
		$weight = rand(1, $sum);
		$sum = 0;
		$result = [];

		foreach ($this->promo as $promo) {
			$sum += $promo['w'];
			if ($sum >= $weight) {
				$result = $promo;
				break;
			}
		}

		return $result;
	}

	function click($promoId)
	{
		return '';
	}
}