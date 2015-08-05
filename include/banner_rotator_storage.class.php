<?php

require_once('rotator_storage.class.php');

class BannerRotatorStorage extends RotatorStorage {

	public $xcachePrefix = 'b_';

	function loadRotatorFromDb()
	{
		$res = mysql_query("SELECT id, url, subId, userId FROM banner_rotator WHERE hash='{$this->rid}'", $this->dbc);

		if ($row = mysql_fetch_assoc($res)) {
			return [
				'id'	=> $row['id'],
				'url'	=> $row['url'],
				'sub'	=> $row['subId'],
				'uid'	=> $row['userId'],
			];
		}

		return false;
	}

	function loadPromoFromDb()
	{
		$res = mysql_query('SELECT brs.weight AS w, brp.id, brp.image AS i, brp.size AS s, brp.language AS l, brp.quality AS q
							FROM banner_rotator_stat brs
							JOIN banner_rotator_promo brp ON brp.id=brs.banner_id
							WHERE brs.weight>0 AND brs.rotator_id=' . $this->rotatorId, $this->dbc);

		$banners = [];

		while ($row = mysql_fetch_assoc($res)) {
			$banners[] = $row;
		}

		return $banners;
	}
}