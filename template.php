<?php /** @var Banner $banner */ ?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Promo</title>
	<style type="text/css">
		* {
			margin: 0
		}
		img {
			border: 0
		}
	</style>
</head>
<body>
<a href="<?php echo "click.php?rid={$rotatorId}&amp;bid={$banner->id()}"; ?>" target="_blank">
	<img src="<?php echo $banner->image(); ?>" width="<?php echo $banner->width(); ?>" height="<?php echo $banner->height(); ?>" alt="Promo <?php echo $banner->image(); ?>">
</a>
</body>
</html>
