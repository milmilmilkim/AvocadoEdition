<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$colspan = 5;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$category_option = get_category_option($bo_table, $sca);
?>




<div class="board-notice">
		<?=stripslashes($board['bo_content_head']);?>
	<br>
		</br>
	</div>
	
	
	

<ul class="fs-timeline">
<div class="control">
  <?	


	for ($i=0; $i<count($list); $i++) {
	   
	  
	   ?>

		 <ul class="ui-btn etc tabs" style="
       padding: 0px;
    width: 300px;text-align: left;
    line-height: 35px;
    height: 35px;background: rgb(34, 36, 37);
    font-family: 'GmarketSansMedium';">
<li rel="tab<? echo $list[$i]['wr_id'] ?>">&nbsp;&nbsp;<? echo $list[$i]['subject'] ?></li></ul>


  		<? } ?>
  						
</div>

			 <div class="tab_container">
  <?	

	for ($i=0; $i<count($list); $i++) {
	   
	  
	   ?>

        <div id="tab<? echo $list[$i]['wr_id'] ?>" class="tab_content">
	 <div class="hint1"><? echo $list[$i]['subject'] ?>
    	 <?	if($is_admin) {?>
    <a href="./write.php?w=u&bo_table=<?=$bo_table?>&wr_id=<?=$list[$i]['wr_id']?>"style="
    font-size: 15px;">수정</a>	<? } ?>

    <span class="hint2"><? echo $list[$i]['wr_4'] ?></span></div>
	 <div class="hint3">	<? echo nl2br($list[$i]['wr_content']) ?></div>
    
    
    	 <div class="hint4">	
    
   <span style="border-left: #9c4141 5px solid;padding: 0px 5px;"> HINT</span></br>
  <?	if($list[$i]['wr_1'] ) {?><? echo $list[$i]['wr_1'] ?><? }else{ ?>
     <span style="color: #707070;padding: 0px 5px;">해금되지 않은 정보입니다.</span><? }?></div>
    
				
				</div>
				
  
  		<? } ?>

				</div>
		
		
		
		
  
</ul>






<script>

 $(function () {

    $(".tab_content").hide();
    $(".tab_content:first").show();

    $("ul.tabs li").click(function () {
        $("ul.tabs li").removeClass("active").css("color", "#CDCDCD");
        $("ul.tabs li").removeClass("active").css("background", "rgb(34 36 37)");
        //$(this).addClass("active").css({"color": "rgb(255 244 208)","font-weight": "bolder"});
        $(this).addClass("active").css("color", "rgb(255 244 208)");
         $(this).addClass("active").css("background", "#5a7d8a"); 
        
        $(".tab_content").hide()
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn()
    });
});


(function() {
  function updateTimelineItems(event, firstRun) {
    var top = 0;
    var bottom = window.innerHeight;

    [].slice.call(document.querySelectorAll('.fs-timeline-item')).forEach(function(element, i) {
      var rect = element.getBoundingClientRect();
      if (rect.bottom >= top && rect.top <= bottom) {
        if (firstRun === true) {
          setTimeout(function() {
            this.classList.add('is-visible');
          }.bind(element), i * 120);
        } else {
          element.classList.add('is-visible');
        }
      } else {
        element.classList.remove('is-visible');
      }
    });
  }
  window.addEventListener('resize', updateTimelineItems);
  window.addEventListener('scroll', updateTimelineItems);
  updateTimelineItems(null, true);
})();
</script>















<div class="board-skin-basic">

	<? if ($list_href || $is_checkbox || $write_href) { ?>
	<div class="bo_fx txt-right" style="padding: 20px 0;">
		<? if ($list_href || $write_href) { ?>
		<? if ($list_href) { ?><a href="<? echo $list_href ?>" class="ui-btn">목록</a><? } ?>
		<? if ($write_href) { ?><a href="<? echo $write_href ?>" class="ui-btn point">키워드 등록하기</a><? } ?>
		<? } ?>
	</div>
	<? } ?>

	<!-- 페이지 -->
	<? echo $write_pages;  ?>

</div>


<? if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
	var f = document.fboardlist;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]")
			f.elements[i].checked = sw;
	}
}

function fboardlist_submit(f) {
	var chk_count = 0;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
			chk_count++;
	}

	if (!chk_count) {
		alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택복사") {
		select_copy("copy");
		return;
	}

	if(document.pressed == "선택이동") {
		select_copy("move");
		return;
	}

	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
			return false;

		f.removeAttribute("target");
		f.action = "./board_list_update.php";
	}

	return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
	var f = document.fboardlist;

	if (sw == "copy")
		str = "복사";
	else
		str = "이동";

	var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

	f.sw.value = sw;
	f.target = "move";
	f.action = "./move.php";
	f.submit();
}
</script>
<? } ?>
<!-- } 게시판 목록 끝 -->
