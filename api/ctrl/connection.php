<?php 
/**
date_default_timezone_set('Asia/Manila');
$server_time = date('Y-m-d H:i:s');

$db_name = 'lookup_admin';
$db_host = '127.0.0.1';

//localhost
$db_user = 'root'; 
$db_pass = 'mysql';

//production
//$db_user = 'adrian';
//$db_pass = 'U5uf9nr5V3e2RT';
    

$nect = mysqli_connect($db_host, $db_user, $db_pass);
    if (!$nect) {
        die('Unable to connect to host: ' . mysqli_error($nect));
    } else {
		$selected_db = mysqli_select_db($nect, $db_name);
		if (!$selected_db) {
			die('Unable to use the selected database: '. mysqli_error($nect));
		}
	}

*/
?>