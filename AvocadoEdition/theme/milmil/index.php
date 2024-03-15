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
include_once (G5_THEME_PATH . "/enter.php");
?>


<!-- 오디오 영역 -->
<div id="site_bgm_box">
	<audio id="m-music-player" autoplay loop>
		<source src="<?php echo G5_THEME_IMG_URL . '/main.mp3'; ?>" type="audio/mpeg">
	</audio>
</div>

<!-- 콘텐츠 시작 ~ -->
<div id="wrapper">
	<iframe name="frm_main" id="main" border="0" frameborder="0" marginheight="0" marginwidth="0" topmargin="0"
		scrolling="auto" allowTransparency="true"></iframe>
</div>

<script>
	$(document.body).on("keydown", this, function (event) {
		if (event.keyCode == 116) {
			document.getElementById('main').contentDocument.location.reload(true);
			return false;
		}
	});

	// 모던 브라우저 정책을 위해 클릭 시 재생
	const wrapper = document.querySelector('#m-main-enter');
	const audio = document.querySelector('#m-music-player')
	const mainIframe = document.querySelector('#main')
	wrapper.addEventListener('click', (e) => {
		audio.play();
		mainIframe.setAttribute('src', './main.php')
		e.currentTarget.style = 'display: none;'
	})


</script>

<?php
include_once (G5_PATH . '/tail.sub.php');
?>