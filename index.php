<?php

/**
 * First let's check if rotator ID is provided
 */

$rid = isset($_GET['rid']) ? $_GET['rid'] : '';

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
 * And now we are ready to load and "rotate" some banners
 */

$storage = new BannerRotatorStorage($rid);

$rotator = new BannerRotator($storage);

$banner = $rotator->rotate();

/**
 * Finally, we can render the template with the selected banner
 */

include 'template.php';