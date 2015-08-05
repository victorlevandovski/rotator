<?php

interface RotatorStorageInterface {

	function rotator();
	function promo();
}

abstract class RotatorStorage implements RotatorStorageInterface {

	public $rid;
	public $rotatorId = 0;

	public $dbc = null;

	public $xcachePrefix = '';
	public $table = 'promo_rotator';

	const CACHE_TIMEOUT = 67;

	function __construct($rid)
	{
		$this->rid = $rid;
	}

	function rotator()
	{
		$key = $this->xcachePrefix . $this->rid;

		$rotator = xcache_get($key);

		if (!is_array($rotator)) {
			$this->dbConnect();

			if ($rotator = $this->loadRotatorFromDb()) {
				xcache_set($key, $rotator, self::CACHE_TIMEOUT);
			}
		}

		$this->rotatorId = isset($rotator['id']) ? intval($rotator['id']) : 0;

		return $rotator;
	}

	function promo()
	{
		$key = $this->xcachePrefix . 'p' . $this->rid;

		$promo = xcache_get($key);

		if (!is_array($promo)) {
			$this->dbConnect();

			if ($promo = $this->loadPromoFromDb()) {
				xcache_set($key, $promo, self::CACHE_TIMEOUT);
			}
		}

		return $promo;
	}

	function loadRotatorFromDb()
	{
		return [];
	}

	function loadPromoFromDb()
	{
		return [];
	}

	function dbConnect()
	{
		if (is_resource($this->dbc)) {
			return;
		}

		$this->dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if (!is_resource($this->dbc)) {
			die();
		}
		mysql_set_charset(DB_CHARSET, $this->dbc);
		mysql_select_db(DB_NAME, $this->dbc);
	}
}