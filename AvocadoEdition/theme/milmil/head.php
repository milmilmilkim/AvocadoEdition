<?php
if (!defined('_GNUBOARD_'))
	exit; // 개별 페이지 접근 불가
include_once (G5_PATH . '/head.sub.php');
include_once (G5_LIB_PATH . '/latest.lib.php');
include_once (G5_LIB_PATH . '/outlogin.lib.php');
include_once (G5_LIB_PATH . '/poll.lib.php');
include_once (G5_LIB_PATH . '/visit.lib.php');
include_once (G5_LIB_PATH . '/connect.lib.php');
include_once (G5_LIB_PATH . '/popular.lib.php');
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/header.css">', 0);


?>


<header id="header" class="m-common-header">
	<?
	$menu_content = get_site_content('site_menu');
	if ($menu_content) {
		echo $menu_content;
	}
	?>
</header>