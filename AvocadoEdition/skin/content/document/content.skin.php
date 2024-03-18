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

<div id="m-common-container">
    <div class="m-content-container">
        <div>
            <? echo '<ul>';
            foreach ($contents as $content) {
                echo '<li>';
                echo '<a href="' . G5_BBS_URL . '/content.php?co_id=' . $content['co_id'] . '">' . $content['co_subject'] . '</a>';
                echo '</li>';
            }
            echo '</ul>';

            ?>
        </div>
        <div class="page">
            <div class="m-content-title">
                <?php echo $co['co_subject'] ?>
            </div>
            <div>
                <?php echo $str; ?>
            </div>
        </div>

    </div>
</div>


<script>
    // const co = <?php echo json_encode($co); ?>;
    const co = <?php echo json_encode($co_id); ?>;

    const contents = <?php echo json_encode($contents); ?>;
    console.log(contents)
</script>