<?
include_once ('./_common.php');

?>
<!doctype html>
<html lang="ko">

<head>
	<meta charset="utf-8">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta http-equiv="imagetoolbar" content="no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="title" content="<?= $g5['title'] ?>">
	<meta name="keywords" content="<?= $config['cf_site_descript'] ?>">
	<meta name="description" content="<?= $config['cf_site_descript'] ?>">

	<meta property="og:title" content="<?= $g5['title'] ?>">
	<meta property="og:description" content="<?= $config['cf_site_descript'] ?>">
	<meta property="og:url" content="<?= G5_URL ?>">

	<title>
		<?= $g5['title'] ?>
	</title>

	<link rel="shortcut icon" href="<?= $config['cf_favicon'] ?>">
	<link rel="icon" href="<?= $config['cf_favicon'] ?>">
	<?
	if (!$config['cf_7']) {
		echo '<link rel="stylesheet" href="' . G5_DATA_URL . '/css/_design.config.css" type="text/css" />';
	}
	?>
	<link media="all" type="text/css" rel="stylesheet" href="<?= G5_THEME_CSS_URL ?>/enter.css">

</head>

<body>

	<div class="wrapper" id="m-main-enter">
		<video autoplay muted id="m-main-video" playsinline>
			<source src="<?= G5_THEME_IMG_URL ?>/intro.mp4" type="video/mp4">
			이 브라우저는 video 태그를 지원하지 않습니다.
		</video>
	</div>

	<script>


	</script>

</body>

</html>