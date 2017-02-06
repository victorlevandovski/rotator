<?php

$rotatorId = isset($_GET['rid']) ? $_GET['rid'] : '';
$bannerId = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

if (!preg_match('/^[0-9a-f]{16}$/', $rotatorId)) {
	exit('Please set a correct rotator ID');
}

require('config.php');
require('src/require.php');


$repository = new MySqlBannerRotatorRepository(new XCacheCachingEngine());

try {
    $rotator = $repository->rotator($rotatorId);
} catch (Exception $e) {
    die($e->getMessage());
}

$url = $rotator->click($bannerId);

header('Location: ' . $url);
