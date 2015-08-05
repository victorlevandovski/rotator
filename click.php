<?php

/**
 * First let's check if rotator ID is provided
 */

$rid = isset($_GET['rid']) ? $_GET['rid'] : '';
$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

if (!preg_match('/^[0-9a-f]{16}$/', $rid)) {
	exit('Please set a correct rotator ID');
}


/**
 * Then include some files
 */

require('config.php');
require('include/banner_rotator.class.php');
require('include/banner_rotator_storage.class.php');


/**
 * Now we can process click and redirect user somewhere
 */

$storage = new BannerRotatorStorage($rid);

$rotator = new BannerRotator($storage);

$url = $rotator->click($bid);

header('Location: ' . $url);