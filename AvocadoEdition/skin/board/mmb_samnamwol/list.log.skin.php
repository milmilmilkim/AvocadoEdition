<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$update_href = $delete_href = '';

// 로그인중이고 자신의 글이라면 또는 관리자라면 비밀번호를 묻지 않고 바로 수정, 삭제 가능
if (($member['mb_id'] && ($member['mb_id'] == $list_item['mb_id'])) || $is_admin) {

	$update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
	if(!$list_item['wr_log'] || $is_admin) { 
		set_session('ss_delete_token', $token = uniqid(time()));
		$delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
	}
}
else if (!$list_item['mb_id']) { // 회원이 쓴 글이 아니라면
	$update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
	$delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
}

// 즐겨찾기 (스크랩) 여부 체크
//$is_favorite = sql_fetch("select count(*) as cnt from {$g5['scrap_table']} where mb_id = '{$member[mb_id]}' and wr_id = '{$list_item['wr_id']}' and bo_table = '{$bo_table}'");
//$is_favorite = $is_favorite[cnt] > 0 ? true : false;

if($list_item['wr_type'] == 'UPLOAD') { 
	// Upload 형태로 로그를 등록 하였을 때
	$thumb = get_mmb_image($bo_table, $list_item['wr_id']);
	$image_url = '<a class="lightbox_trigger" href="'.$thumb['src'].'"><img src="'.$thumb['src'].'"/></a>';
	$image_width = $thumb['width'];
	$image_height = $thumb['height'];
} else if($list_item['wr_type'] == 'URL') {
	// URL 형태로 로그를 등록 하였을 때
	$image_url = '<img src="'.$list_item['wr_url'].'" onclick="window.open(this.src)" />';
	$image_width = $list_item['wr_width'];
	$image_height = $list_item['wr_height'];
}else if($list_item['wr_type'] == 'VIDEO') {
	// VIDEO 형태로 로그를 등록 하였을 때
	$image_url = $list_item['wr_video'];
	$image_width = $list_item['wr_width'];
	$image_height = $list_item['wr_height'];
}else if($list_item['wr_type'] == 'TEXT') {
	// VIDEO 형태로 로그를 등록 하였을 때
	if ($list_item['wr_height']>0) $height=$list_item['wr_height']."px";
	else $height="100%";
	$image_url = "<div style='height:".$height."'>".conv_content($list_item['wr_text'],0)."</div>";
	$image_width = $list_item['wr_width'] ? $list_item['wr_width'] : 500;
	$image_height = $list_item['wr_height'];
}

$log_class = '';
$blind_class ='';
$h_class = '';
$msg="";

// 멤버공개 데이터일 시
$is_viewer = true;
$data_width = 300;
$no_member_class = '';    
if(strstr($list_item['wr_option'],"secret") ) {
	if( !$list_item['mb_id'] && get_session("ss_secret_{$bo_table}_{$list_item['wr_num']}") ||  $list_item['mb_id'] && $list_item['mb_id']==$member['mb_id'] || $is_admin )
		$is_viewer = true;
	else {
	$is_viewer = false;
	$no_member_class= 'empty secret'; 
	}
} else if ($list_item['wr_protect']!=''){
	if( get_session("ss_secret_{$bo_table}_{$list_item['wr_num']}") ||  $list_item['mb_id'] && $list_item['mb_id']==$member['mb_id'] || $is_admin )
		$is_viewer = true;
	else {
	$is_viewer = false;
	$no_member_class= 'empty protect'; 
	}
}else if($list_item['wr_secret'] == '1') {
	if($board['bo_read_level'] < $member['mb_level'] && $is_member)
		$is_viewer = true; 
	else {
	$is_viewer = false;
	$no_member_class = ' empty ';
	}
}  else {
	$is_viewer=true;
	$tb_width=1000;
	if($board['bo_table_width']>100)
		$tb_width=$board['bo_table_width'];
	if($board['bo_image_width']>0 && $image_width>$board['bo_image_width'] || $image_width>$tb_width) {
		if($list_item['wr_type']!='VIDEO' && $list_item['wr_type']!='TEXT' )
			$msg=""; 
		} //@200602
	if($board['bo_image_width']>0 && $image_width>$board['bo_image_width'] && $list_item['wr_wide']!='1'){
		$data_width=$board['bo_image_width'];
		}
	else if($image_width<300)
		$data_width=300;
	else $data_width = $image_width;
}

if($is_viewer) { 

	// 접기 여부 설정
	if(($board['bo_gallery_height'] && $image_height > $board['bo_gallery_height']) || $list_item['wr_plip'] == '1') {  
			$log_class .= "ui-slide"; 
	}
	// 블라인드 여부 설정
	if($list_item['wr_adult'] == '1') { 
		$blind_class = "ui-blind";
	}
	// 리플 아래로 내리기 여부 설정
	if($list_item['wr_wide'] == '1') { 
		$h_class = "ui-wrap";
	}
} 

// 알람 내역이 있을 경우, 확인으로체크
//sql_query("update {$g5['call_table']} set bc_check = 1 where re_mb_id = '{$member[mb_id]}' and bo_table ='{$bo_table}' and wr_id = '{$list_item['wr_id']}'");
?>

<div class="item <?=$h_class?>" id="log_<?=$list_item['wr_id']?>">
	<div class="item-inner">
	<!--  로그 이미지 출력 부분 -->
		<div class="ui-pic <?=$no_member_class?>" data-width="<?=$data_width?>" data-image="<?=$image_width?>" >

			<!-- 로그 상단 영역 -->
			<div class="pic-header">
				<p class="no">
					
					<? // 로그 넘버링 출력 ?>
					<?=($list_item['wr_num'] * -1)?>

					<? if($list_item['ca_name']){ 
						// 카테고리 출력
					?>
					<span data-category="<?=$list_item['ca_name']?>" class="ico-category">
						<?=$list_item['ca_name']?>
					</span>
					<? } ?>
					<? if($list_item['wr_adult']) {
						// 19금 필터링 마크
					?>
					<span style="color:#d3393d;">■</span>
					<? } ?>
				</p>
					<?if(strstr($list_item['wr_option'],"secret")){?>
					&nbsp;&nbsp;<span class="co-secret highlight">#secret</span>
					<?}else if ($list_item['wr_protect']!=''){?>
					&nbsp;&nbsp;<span class="co-secret highlight">#protect</span>
					<?}else if($list_item['wr_secret'] == '1' ){?>
					&nbsp;&nbsp;<span class="co-member highlight">#member only</span>
					<?}//@200602 ?>

				<? if($is_viewer) { 
					// 보기 권한이 존재 할 경우 (멤버의 경우)
					// -- 버튼 영역 출력
					if ($delete_href)		{ ?><a href="<?php echo $delete_href ?>" class="del" onclick="del(this.href); return false;">삭제</a><?	} ?>
												<!--<a href="?bo_table=<?=$bo_table?>&log=<?=$list_item['wr_num'] * -1?>&single=Y" target="_blank" class="new">로그링크</a> -->
					<? if ($update_href)	{ ?><a href="<?php echo $update_href ?>" class="mod">수정</a><? } ?>
				<? } ?>
			</div>
			<!-- // 로그 상단 영역 -->

			<!-- 로그 이미지 -->
			<div class="pic-data <?=$log_class?>"  style="max-width:<?=$data_width?>px;">
			<? if(!$is_viewer) { 
				// 비공개 이미지
			?>
				<div>
					<?if($no_member_class=='empty secret' && $list_item['mb_id']==''){?>
					<a href="./password.php?w=s&amp;bo_table=<?=$bo_table?>&amp;wr_id=<?=$list_item['wr_id'].$qstr?>" class="s_cmt">
					<img src="<?=$board_skin_url?>/img/img_lock.png" />
					</a>
					<?}else if ($no_member_class=='empty protect'){?> 
					<a href="./password.php?w=p&amp;bo_table=<?=$bo_table?>&amp;wr_id=<?=$list_item['wr_id'].$qstr?>" class="s_cmt">
					<img src="<?=$board_skin_url?>/img/img_lock.png" />
					</a>
					<?}else{?>
					<img src="<?=$board_skin_url?>/img/img_lock.png" style="cursor:default;" /> 
					<? } ?>
				</div>	
			<?} else { ?>

				<? if($image_url) { ?>
					<div class="img-data <?=!$member['mb_adult'] ? $blind_class : ""?> <?if($list_item['wr_type']=='TEXT') { if($list_item['wr_height']=='0') echo "theme-box"; else echo "theme-box scroll";}?>" data-height="<?=$image_height?>">
						<?=$image_url?>
						<?=$msg ? help($msg) : "";?>
					</div>
					<?	if($log_class) { 
						// 접기 기능 (펼치기)
					?>
					<a href="#" class="ui-open-log ui-btn etc">OPEN</a>
					<? } ?>
					<?	if($blind_class) { 
						// 블라인드 (19금 필터링)
					?>
					<a href="#" class="ui-remove-blind"><span>해당 로그는 필터 된 로그 입니다.<br />확인을 원하실 경우 클릭해주세요.</span></a>
					<? } ?>
				<? } ?>

			<? } ?>
			</div>
			<!-- // 로그 상단 영역 -->

		</div>
	<!--  // 로그 이미지 출력 부분 -->

	<!--  로그 코멘트 출력 부분 -->

		<div class="ui-comment"> 
			<div class="item-comment-box">
				<? include($board_skin_path."/view_comment.php");?>
			</div>
			<!--코멘트 접기 부분-->
			<a onclick="this.nextSibling.style.display=(this.nextSibling.style.display=='none')?'block':'none';" href="javascript:void(0)">
				 <p class="comment_open"> ▼ </p> 
			</a><div style="DISPLAY: none">
				<div class="item-comment-form-box">
					<? include($board_skin_path."/write_comment.php");?>
				</div> 
			</div> 
	<!-- // 로그 코멘트 출력 부분 -->
	</div>
</div>
</div>

<!--// 그림 원본 박스용 스크립트 //-->
<script>
jQuery(document).ready(function($) {
	$('.lightbox_trigger').click(function(e) {
		e.preventDefault();
		var image_href = $(this).attr("href");
		var scrolltop = $(window).scrollTop();
		if ($('#lightbox').length > 0) { 
			$('#lightbox').css('top',scrolltop);
			$('#content').html('<a href="javascript:close();"><img src="' + image_href +'" /></a>');
			$('#lightbox').show();
		}
		else { 
			var lightbox = 
			'<div id="lightbox">' +
				'<div id="content">' + 
					'<a href="javascript:close();"><img src="' + image_href +'" /></a>' +
				'</div>' +	
			'</div>';
			$('body').append(lightbox);
			$('#lightbox').css('top',scrolltop);
		}
	});
});
function close() { 
		$('#lightbox').hide();
	}
</script>
<!--// 그림 원본 박스용 스크립트 //-->