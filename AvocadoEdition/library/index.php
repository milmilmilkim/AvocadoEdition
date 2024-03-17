<?php
include_once ('./_common.php');
include_once ('./_head.php');

if (defined('G5_THEME_PATH') && is_file(G5_THEME_PATH . '/library.php')) {
    require_once (G5_THEME_PATH . '/library.php');
    return;
}


?>


<?php
include_once ('./_tail.php');
?>