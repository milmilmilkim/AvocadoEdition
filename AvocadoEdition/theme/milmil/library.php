<?php
include_once (G5_THEME_PATH . '/head.php');
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/common-layout.css">', 0);
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/library.css">', 0);

?>

<div class="container">
    <a href="<? echo G5_BBS_URL . '/content.php?co_id=noti_1' ?>" alt="notice" class="book notice"></a>
    <a href="<? echo G5_BBS_URL . '/content.php?co_id=world_1' ?>" alt="world" class="book world"></a>
    <a href="<? echo G5_BBS_URL . '/content.php?co_id=system_1' ?>" alt="system" class="book system"></a>
    <a href="<? echo G5_BBS_URL . '/content.php?co_id=character_1' ?>" alt="character" class="book character"></a>
</div>

<?php
include_once (G5_THEME_PATH . '/tail.php');
?>