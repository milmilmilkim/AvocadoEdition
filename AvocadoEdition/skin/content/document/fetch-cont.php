<?php
include_once ('../../../common.php');

header('Content-Type: application/json');

$contents = array();

$sql = "SELECT * FROM {$g5['content_table']} WHERE co_id LIKE 'cont_%'";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    $contents[] = $row;
}

echo json_encode($contents);
?>