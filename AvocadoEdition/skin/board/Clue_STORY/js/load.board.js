
// 즐겨찾기 추가 - Ajax
$('a[data-function="favorite"]').on('click', function() {
	var formData = new FormData();
	var idx = $(this).data('idx');
	var obj = $(this);
	formData.append("wr_id", idx);
	formData.append("bo_table", g5_bo_table);
	formData.append("mb_id", avo_mb_id);

	$.ajax({
		url:avo_board_skin_url + '/ajax/add_favorite.php'
		, data: formData
		, processData: false
		, contentType: false
		, type: 'POST'
		, success: function(data){
			if(data == 'on') { 
				obj.removeClass('on');
				obj.addClass(data);
			}else if(data == 'off') { 
				obj.removeClass('on');
			}
		}
	});

	return false;
});

// 즐겨찾기 추가 - Ajax
$('a[data-function="like"]').on('click', function() {
	var formData = new FormData();
	var idx = $(this).data('idx');
	var obj = $(this);
	formData.append("wr_id", idx);
	formData.append("bo_table", g5_bo_table);
	formData.append("mb_id", avo_mb_id);

	$.ajax({
		url:avo_board_skin_url + '/ajax/add_like.php'
		, data: formData
		, processData: false
		, contentType: false
		, type: 'POST'
		, success: function(data){
			if(data == 'on') { 
				obj.removeClass('on');
				obj.addClass(data);
			}else if(data == 'off') { 
				obj.removeClass('on');
			}
		}
	});

	return false;
});