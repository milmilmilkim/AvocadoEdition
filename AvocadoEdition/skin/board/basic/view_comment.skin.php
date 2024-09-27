<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$comment_action_url = https_url(G5_BBS_DIR)."/write_comment_update.php";
?>

<script>
// 글자수 제한
var char_min = parseInt(<?php echo $comment_min ?>); // 최소
var char_max = parseInt(<?php echo $comment_max ?>); // 최대
</script>

<!-- 댓글 시작 { -->


<!-- } 댓글 끝 -->



<!-- 댓글 쓰기 시작 { -->



<!-- } 댓글 쓰기 끝 -->