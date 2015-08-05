<?php

require_once('rotator.class.php');

class BannerRotator extends Rotator {

	function rotate()
	{
		$banner = parent::rotate();

		$this->log($this->storage->rotatorId, $banner['id'], self::ACTION_VIEW);

		$url = "/click.php?rid={$this->storage->rid}&amp;bid={$banner['id']}";

		return [
			'href'		=> $url,
			'src'		=> $banner['i'],
			'width'		=> floor($banner['s'] / 1000),
			'height'	=> $banner['s'] % 1000
		];
	}

	function click($bannerId)
	{
		$this->log($this->storage->rotatorId, $bannerId, self::ACTION_CLICK);

		$url = $this->rotator['url'];
		$url .= strstr($url, '?') ? '&' : '?';
		$url .= "partner={$this->rotator['uid']}&sub_id={$this->rotator['sub']}";

		return $url;
	}
}