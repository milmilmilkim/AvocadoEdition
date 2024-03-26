<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

$validPrefixes = ['noti', 'character', 'system', 'world'];
$prefix = strstr($co_id, '_', true);

$contents = array();

if (in_array($prefix, $validPrefixes)) {
    $sql = "SELECT * FROM {$g5['content_table']} WHERE co_id LIKE '{$prefix}_%'";
    $result = sql_query($sql);
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $contents[$i] = $row;
    }
}

add_stylesheet('<link rel="stylesheet" href="' . $content_skin_url . '/layout.css">', 0);
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/jquery.fullpage.min.js"
    integrity="sha512-bxzECOBohzcTcWocMAlNDE2kYs0QgwGs4eD8TlAN2vfovq13kfDfp95sJSZrNpt0VMkpP93ZxLC/+WN/7Trw2g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/jquery.fullpage.min.css"
    integrity="sha512-q54FvbV+gGBn+NvgaD4gpJ7w4wrO00DgW7Rx503PIhrf0CuMwLOwbS+bXgOBFSob+6GVy1HDPfaRLJ8w2jiS4g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<div id="m-common-container" style="height: 100%;">

    <div class="m-content-container">

        <a href="/library" class="back"> ◀︎ </a>

        <nav class="content-category">
            <? echo '<ul>';
            foreach ($contents as $content) {
                echo '<li>';
                echo '<a href="' . G5_BBS_URL . '/content.php?co_id=' . $content['co_id'] . '">' . $content['co_subject'] . '</a>';
                // echo '<a class="m-link " href="#" data-id="' . $content['co_id'] . '">' . $content['co_subject'] . '</a>';
                echo '</li>';
            }
            echo '</ul>';
            ?>
        </nav>

        <div id="fullpage">
            <?php echo $str; ?>
        </div>

    </div>

</div>



<script type="text/javascript">

    const addVideo = (el, videoName) => {
        const video = document.createElement('video');
        video.className = 'bg-video';
        video.loop = true;
        video.muted = true;
        video.autoplay = true;

        const source = document.createElement('source');
        source.src = `/skin/content/world/assets/${videoName}.mp4`;
        source.type = 'video/mp4';

        video.appendChild(source);
        el.appendChild(video)
    }

    const section1 = document.querySelectorAll('.section-1');
    const section3 = document.querySelectorAll('.section-3');
    const section4 = document.querySelectorAll('.section-4');
    const section5 = document.querySelectorAll('.section-5');
    const section6 = document.querySelectorAll('.section-6');



    window.addEventListener('load', () => {
        $('#fullpage').fullpage({
            verticalCentered: false,
        });

        section1.forEach((el) => addVideo(el, '1'))
        section3.forEach((el) => addVideo(el, '3'))
        section4.forEach((el) => addVideo(el, '4'))
        section5.forEach((el) => addVideo(el, '5'))
        section6.forEach((el) => addVideo(el, '6'))
    })

</script>