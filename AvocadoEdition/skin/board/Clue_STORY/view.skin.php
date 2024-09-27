<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<script src="<? echo G5_JS_URL; ?>/viewimageresize.js"></script>



	<div class="subject">
		<strong style="
    font-size: 15px;
    display: inline-block;
    position: revert;
    font-family: 'GmarketSansMedium';"><?=get_text($view['wr_subject'])?></strong>
		<?if ($view[wr_2] == '1'){?>
		<p style="
    font-size: 10px;
    background: #910505;
    display: inline-block;
    position: revert;
    padding: 5px 10px;
    line-height: 10px;
    border-radius: 10px;
    font-family: 'GmarketSansMedium';
">스토리 진행중</p> 
		<? } ?><? if ($update_href) { ?><a href="<? echo $update_href ?>" class="ui-btn">수정</a><? } ?>
	</div>

<div class="board-viewer">

		  
				  	<hr class="padding" /style="height: 100px;">

	<?
	// 코멘트 입출력
	include_once(G5_BBS_PATH.'/view_comment.php');
	?>
		  


	<!-- 링크 버튼 시작 { -->
	<div id="bo_v_bot">
		<?
		ob_start();
		 ?>
		<? if ($prev_href || $next_href) { ?>
		<div class="bo_v_nb">
			<? if ($prev_href) { ?><a href="<? echo $prev_href ?>" class="ui-btn">이전글</a><? } ?>
			<? if ($next_href) { ?><a href="<? echo $next_href ?>" class="ui-btn">다음글</a><? } ?>
		</div>
		<? } ?>

		<div class="bo_v_com">
			<? if ($update_href) { ?><a href="<? echo $update_href ?>" class="ui-btn">수정</a><? } ?>
			<? if ($delete_href) { ?><a href="<? echo $delete_href ?>" class="ui-btn" onclick="del(this.href); return false;">삭제</a><? } ?>
			<? if ($copy_href) { ?><a href="<? echo $copy_href ?>" class="ui-btn admin" onclick="board_move(this.href); return false;">복사</a><? } ?>
			<? if ($move_href) { ?><a href="<? echo $move_href ?>" class="ui-btn admin" onclick="board_move(this.href); return false;">이동</a><? } ?>
			<? if ($search_href) { ?><a href="<? echo $search_href ?>" class="ui-btn">검색</a><? } ?>
			<a href="<? echo $list_href ?>" class="ui-btn">목록</a>
			<? if ($reply_href) { ?><a href="<? echo $reply_href ?>" class="ui-btn">답변</a><? } ?>
			<? if ($write_href) { ?><a href="<? echo $write_href ?>" class="ui-btn">글쓰기</a><? } ?>
		</div>
		<?
		$link_buttons = ob_get_contents();
		ob_end_flush();
		 ?>
	</div>
	<!-- } 링크 버튼 끝 -->

</div>


	
<script>





$( document ).ready(function() {
    $("body").css({"background":"url(<?=$view[wr_4]?>) fixed"});
    $("body").css({"background-repeat":"no-repeat "});
      $("body").css({"background-size": "cover"});

});





$('.send_memo').on('click', function() {
	var target = $(this).attr('href');
	window.open(target, 'memo', "width=500, height=300");
	return false;
});


<? if ($board['bo_download_point'] < 0) { ?>
$(function() {
	$("a.view_file_download").click(function() {
		if(!g5_is_member) {
			alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
			return false;
		}

		var msg = "파일을 다운로드 하시면 포인트가 차감(<? echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

		if(confirm(msg)) {
			var href = $(this).attr("href")+"&js=on";
			$(this).attr("href", href);

			return true;
		} else {
			return false;
		}
	});
});
<? } ?>

function board_move(href)
{
	window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
	$("a.view_image").click(function() {
		window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
		return false;
	});

	// 추천, 비추천
	$("#good_button, #nogood_button").click(function() {
		var $tx;
		if(this.id == "good_button")
			$tx = $("#bo_v_act_good");
		else
			$tx = $("#bo_v_act_nogood");

		excute_good(this.href, $(this), $tx);
		return false;
	});

	// 이미지 리사이즈
	$("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
	$.post(
		href,
		{ js: "on" },
		function(data) {
			if(data.error) {
				alert(data.error);
				return false;
			}

			if(data.count) {
				$el.find("strong").text(number_format(String(data.count)));
				if($tx.attr("id").search("nogood") > -1) {
					$tx.text("이 글을 비추천하셨습니다.");
					$tx.fadeIn(200).delay(2500).fadeOut(200);
				} else {
					$tx.text("이 글을 추천하셨습니다.");
					$tx.fadeIn(200).delay(2500).fadeOut(200);
				}
			}
		}, "json"
	);
}
</script>
<!-- } 게시글 읽기 끝 -->