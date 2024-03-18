<?php
include_once ('./_common.php');
define('_INDEX_', true);
include_once ('./head.sub.php');
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/index.css">', 0);
?>

<?php
if (!$is_member && !$config['cf_open']) {
	goto_url(G5_BBS_URL . '/login.php');
	exit;
}
?>


<!-- 오디오 영역 -->
<div id="site_bgm_box">
	<audio id="m-music-player" autoplay loop>
		<source src="<?php echo G5_THEME_IMG_URL . '/main.mp3'; ?>" type="audio/mpeg">
	</audio>
</div>

<!-- 콘텐츠 시작 -->
<div id="wrapper">
	<iframe id="m-main-iframe" src="./enter.php" name="frm_main" border="0" frameborder="0" marginheight="0"
		marginwidth="0" topmargin="0" scrolling="auto" allowTransparency="true"></iframe>
</div>


<script>
	const audio = document.querySelector('#m-music-player')

	// 기존에 있던 코드.. F5에 새로고침 
	$(document.body).on("keydown", this, function (event) {
		if (event.keyCode == 116) {
			document.getElementById('main').contentDocument.location.reload(true);
			audio.pause();
			return false;
		}
	});

	// 모던 브라우저 정책을 위해 클릭시 음악 재생
	window.addEventListener('message', function (event) {
		if (event.data === 'playAudio') {
			audio.play();
		}
	});

	// 페이지를 나가거나 새로고침할 때 음악 멈추게 함
	window.addEventListener('beforeunload', () => {
		audio.pause();
	});

</script>

<?php
include_once (G5_PATH . '/tail.sub.php');
?>