<?php
include_once ('./_common.php');
define('_MAIN_', true);
include_once (G5_THEME_PATH . '/head.php');
include_once (G5_PATH . "/intro.php");
?>

<?php
$banner = array();
$sql = " select * from {$g5['banner_table']} where '" . G5_TIME_YMDHIS . "' between bn_begin_time and bn_end_time order by bn_order, bn_id desc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $banner[$i] = $row;
}

// {
//     "bn_id": "1",
//     "bn_img": "http://localhost/data/banner/visual_1710515996.png",
//     "bn_m_img": "http://localhost/data/banner/visual_1710515996_m.png",
//     "bn_alt": "",
//     "bn_url": "http://",
//     "bn_new_win": "0",
//     "bn_begin_time": "2024-03-16 00:00:00",
//     "bn_end_time": "2024-04-16 00:00:00",
//     "bn_order": "50"
// }
?>
<div class="banner-wrapper">
    <?php foreach ($banner as $bn): ?>
        <div class="banner-item">
            <img src="<?= $bn['bn_img'] ?>" alt="<?= $bn['bn_alt'] ?>">
        </div>
    <?php endforeach; ?>
</div>



<?
include_once (G5_PATH . '/tail.php');
?>