<?php
include_once ('./_common.php');
define('_MAIN_', true);
include_once (G5_THEME_PATH . '/head.php');
include_once (G5_PATH . "/intro.php");
?>

<?= display_banner('basic', true, 'slide', '5000', 'default', true, '700') ?>
<div>스와이퍼 버전이 5인듯..?</div>


<?
include_once (G5_PATH . '/tail.php');
?>