<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$sst = $_GET['sst'];

// 분류 사용 여부
$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
	$is_category = true;
	$category_href = G5_BBS_URL.'/board.php?bo_table='.$bo_table;

	$category_option .= '<li><a href="'.$category_href.'"';
	if ($sca=='')
		$category_option .= ' id="bo_cate_on"';
	$category_option .= '>전체</a></li>';

	$categories = explode('|', $board['bo_category_list']); // 구분자가 , 로 되어 있음
	for ($i=0; $i<count($categories); $i++) {
		$category = trim($categories[$i]);
		if ($category=='') continue;
		$category_option .= '<li><a href="'.($category_href."&amp;sca=".urlencode($category)).'"';
		$category_msg = '';
		if ($category==$sca) { // 현재 선택된 카테고리라면
			$category_option .= ' id="bo_cate_on"';
			$category_msg = '<span class="sound_only">열린 분류 </span>';
		}
		$category_option .= '>'.$category_msg.$category.'</a></li>';
	}
}

$sop = strtolower($sop);
if ($sop != 'and' && $sop != 'or')
	$sop = 'and';

// 분류 선택 또는 검색어가 있다면
$stx = trim($stx);
if ($sca || $stx) {
	$sql_search = get_sql_search($sca, $sfl, $stx, $sop);

	// 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
	$sql = " select MIN(wr_num) as min_wr_num from {$write_table} ";
	$row = sql_fetch($sql);
	$min_spt = (int)$row['min_wr_num'];

	if (!$spt) $spt = $min_spt;

	$sql_search .= " and (wr_num between {$spt} and ({$spt} + {$config['cf_search_part']})) ";

	// 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
	// 라엘님 제안 코드로 대체 http://sir.kr/g5_bug/2922
	$sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	/*
	$sql = " select distinct wr_parent from {$write_table} where {$sql_search} ";
	$result = sql_query($sql);
	$total_count = sql_num_rows($result);
	*/
} else {
	$sql_search = "";
	$total_count = $board['bo_count_write'];
}

$page_rows = $board['bo_page_rows'];
$list_page_rows = $board['bo_page_rows'];

if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)

// 년도 2자리
$today2 = G5_TIME_YMD;
$list = array();
$i = 0;
$notice_count = 0;
$notice_array = array();

// 공지 처리
if (!$sca) {
	$arr_notice = explode(',', trim($board['bo_notice']));
	$from_notice_idx = ($page - 1) * $page_rows;
	if($from_notice_idx < 0)
		$from_notice_idx = 0;
	$arr_notice = array_filter($arr_notice);
	$board_notice_count = count($arr_notice);

	if($sst == 'wr_id') {
		// 전체 목록인 경우, 게시글 개수에서 공지글 제외
		$total_count -= $board_notice_count;
	}
	else if($board_notice_count > 0) {
		$pair_str = implode("' or wr_id = '", $arr_notice);
		$pair_str = "wr_id = '".$pair_str."'";
		// 페어 목록인 경우, 페어 목록만 추출하고 게시글 개수를 공지글로 한정
		$pair_str = "({$pair_str}) and wr_type = 'pair'";
		$total_count = $board_notice_count;

		$pair_sql = " select * from {$write_table} where {$pair_str} order by wr_ing desc, wr_subject asc ";
		$notice_result = sql_query($pair_sql);

		for($k=0; $row = sql_fetch_array($notice_result); $k++) {
			if (!$row['wr_id']) continue;
			$notice_array[] = $row['wr_id'];

			if($k < $from_notice_idx) continue;
			if($sst != 'wr_id') {
				$list[$i] = get_list($row, $board, $board_skin_url, G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);
				$list[$i]['is_notice'] = true;

				$i++;
				$notice_count++;
				
				if($notice_count >= $list_page_rows)
					break;
			}
		}

	}
}

$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

// 공지글이 있으면 변수에 반영
if(!empty($notice_array)) {
	$from_record -= count($notice_array);

	if($from_record < 0)
		$from_record = 0;

	if($notice_count > 0)
		$page_rows -= $notice_count;

	if($page_rows < 0)
		$page_rows = $list_page_rows;
}

// 관리자라면 CheckBox 보임
$is_checkbox = false;
if ($is_member && ($is_admin == 'super' || $group['gr_admin'] == $member['mb_id'] || $board['bo_admin'] == $member['mb_id']))
	$is_checkbox = true;

// 정렬에 사용하는 QUERY_STRING
$qstr2 = 'bo_table='.$bo_table.'&amp;sop='.$sop;

// 0 으로 나눌시 오류를 방지하기 위하여 값이 없으면 1 로 설정
$bo_gallery_cols = $board['bo_gallery_cols'] ? $board['bo_gallery_cols'] : 1;
$td_width = (int)(100 / $bo_gallery_cols);

// 정렬
$order = "wr_ing desc";
if ($order) {
	$sql_order = " order by {$order} ";
}

if ($sca || $stx) {
	$sql = " select distinct wr_parent from {$write_table} where {$sql_search} ";
	if(!empty($notice_array))
		$sql .= " and wr_parent not in (".implode(', ', $notice_array).") ";
	$sql .= "{$sql_order} limit {$from_record}, $page_rows ";
} else {
	$sql = " select * from {$write_table} where wr_is_comment = 0 ";
	if(!empty($notice_array))
		$sql .= " and wr_id not in (".implode(', ', $notice_array).") ";
	// 전체 목록인 경우, 캐릭터 게시글만 추출. 페어 목록인 경우, 페어 게시글만 추출
	if($sst=='wr_id' || $board_notice_count < 1)
		$sql .= " and wr_type != 'pair'";
	else
		$sql .= " and wr_type = 'pair'";
	$sql .= " {$sql_order} limit {$from_record}, $page_rows ";
}

// 페이지의 공지개수가 목록수 보다 작을 때만 실행
if($page_rows > 0) {
	$result = sql_query($sql);

	$k = 0;

	while ($row = sql_fetch_array($result))
	{
		// 검색일 경우 wr_id만 얻었으므로 다시 한행을 얻는다
		if ($sca || $stx)
			$row = sql_fetch(" select * from {$write_table} where wr_id = '{$row['wr_parent']}' ");

		$temp_list = get_list($row, $board, $board_skin_url, G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);

		$list[$i] = $temp_list;
		if (strstr($sfl, 'subject')) {
			$list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
		}
		
		$list_num = $total_count - ($page - 1) * $list_page_rows - $notice_count;
		$list[$i]['num'] = $list_num - $k;

		$i++;
		$k++;
	}
}

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './board.php?bo_table='.$bo_table.$qstr.'&amp;page=');

$list_href = '';
$prev_part_href = '';
$next_part_href = '';
if ($sca || $stx) {
	$list_href = './board.php?bo_table='.$bo_table;

	$patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');

	//if ($prev_spt >= $min_spt)
	$prev_spt = $spt - $config['cf_search_part'];
	if (isset($min_spt) && $prev_spt >= $min_spt) {
		$qstr1 = preg_replace($patterns, '', $qstr);
		$prev_part_href = './board.php?bo_table='.$bo_table.$qstr1.'&amp;spt='.$prev_spt.'&amp;page=1';
		$write_pages = page_insertbefore($write_pages, '<a href="'.$prev_part_href.'" class="pg_page pg_prev">이전검색</a>');
	}

	$next_spt = $spt + $config['cf_search_part'];
	if ($next_spt < 0) {
		$qstr1 = preg_replace($patterns, '', $qstr);
		$next_part_href = './board.php?bo_table='.$bo_table.$qstr1.'&amp;spt='.$next_spt.'&amp;page=1';
		$write_pages = page_insertafter($write_pages, '<a href="'.$next_part_href.'" class="pg_page pg_end">다음검색</a>');
	}
}


$write_href = '';
if ($member['mb_level'] >= $board['bo_write_level']) {
	$write_href = './write.php?bo_table='.$bo_table;
}

$nobr_begin = $nobr_end = "";
if (preg_match("/gecko|firefox/i", $_SERVER['HTTP_USER_AGENT'])) {
	$nobr_begin = '<nobr>';
	$nobr_end   = '</nobr>';
}

// RSS 보기 사용에 체크가 되어 있어야 RSS 보기 가능 061106
$rss_href = '';
if ($board['bo_use_rss_view']) {
	$rss_href = './rss.php?bo_table='.$bo_table;
}

$stx = get_text(stripslashes($stx));


?>

