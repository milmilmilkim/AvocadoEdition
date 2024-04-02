<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>

    </style>
</head>

<?php
include_once ('./_common.php');
define('_MAIN_', true);
include_once (G5_THEME_PATH . '/head.php');
include_once (G5_PATH . "/intro.php");
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/common-layout.css">', 0);
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/main.css">', 0);
?>


<!-- swiper slide 직접 구현 -->
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
<div id="m-common-container">
    <div class="banner-wrapper">
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php foreach ($banner as $bn): ?>
                    <div class="swiper-slide">
                        <img src="<?= $bn['bn_img'] ?>" onclick="window.location='<?= $bn['bn_url'] ?>';"
                            alt="<?= $bn['bn_alt'] ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="main-content-wrapper">
        <div class="left">
            <div class="content">
                <iframe src="<?php echo G5_THEME_URL; ?>/cal.php" width="100%" height="307" frameborder="0"
                    scrolling="no"></iframe>
            </div>
        </div>



        <div class="right">
            <div class="content">
                <p>아벤티노 신전 뒷편에서 울음소리가 들려온다.</p>
                <button>퀘스트 입수</button>
            </div>
        </div>
    </div>


</div>

<script type="module">
    import Swiper11 from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs'
    const swiper = new Swiper11('.swiper', {
        direction: 'vertical',
        loop: true,
        speed: 1000,
        autoplay: {
            delay: 4000,
        },
        effect: 'fade',

    });
</script>


<?
include_once (G5_PATH . '/tail.php');
?>