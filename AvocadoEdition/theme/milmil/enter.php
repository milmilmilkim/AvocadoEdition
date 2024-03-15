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
	<link media="all" type="text/css" rel="stylesheet" href="<?= G5_THEME_CSS_URL ?>/enter.css">

</head>

<body>


	<div class="wrapper" id="m-main-enter">
		<div class="inner">
			<div class="index-logo">
				<p class="txt-default">화면 클릭시 전환됨</p>
			</div>
		</div>
	</div>

	<script></script>

</body>

</html>