<?
include_once('./_common.php');

if($is_member) { 

	$is_like = sql_fetch("select wr_10 from avo_write_{$bo_table} where wr_id = '{$wr_id}' ");
	
	if(strpos($is_like['wr_10'], $mb_id) !== false) {  
	
		$new_wr10 = str_replace(",".$mb_id, "" , $is_like['wr_10']);
		sql_query("update avo_write_{$bo_table} set wr_10 = '{$new_wr10}' where wr_id = '{$wr_id}'");
		
		echo "off"; 
		
	} else {  
		$new_wr10 = $is_like['wr_10'].",".$mb_id ;
		sql_query("update avo_write_{$bo_table} set wr_10 = '{$new_wr10}' where wr_id = '{$wr_id}'");
		
	
		echo "on";
	}  
	
	
	
}
?>