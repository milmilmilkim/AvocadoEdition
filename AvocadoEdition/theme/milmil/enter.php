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
		<a href="./main.php">
			<video autoplay muted id="m-main-video" playsinline>
				<source src="<?= G5_THEME_IMG_URL ?>/intro.mp4" type="video/mp4">
				이 브라우저는 video 태그를 지원하지 않습니다.
			</video>
		</a>
	</div>

	<script>
		/**
		 * 동영상 일시 정지
		 */
		const video = document.querySelector('#m-main-video');
		const pauseAtTime = (targetTime) => {
			if (video.currentTime >= targetTime) {
				console.log(video.currentTime)
				video.pause();
				video.removeEventListener('timeupdate', timeUpdateHandler);

			}
		};

		const timeUpdateHandler = () => {
			pauseAtTime(3.6);
		}

		video.addEventListener('timeupdate', timeUpdateHandler);

		video.addEventListener('ended', () => {
			video.pause();
		})

		/**
		 * 클릭시 무모로 post message
		 */
		document.addEventListener('click', () => {
			window.parent.postMessage('playAudio', '*'); // 'playAudio'는 식별 가능한 메시지, '*'는 보안상의 이유로 실제 사용 시 도메인으로 교체해야 할 수 있습니다.
		});

	</script>


</body>

</html>