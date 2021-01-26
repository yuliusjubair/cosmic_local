<?php
	$db_host = 'localhost';
	$db_port = '';
	$db_user = 'root';
	$db_pass = 'jakarta';
	$db_name = 'ptsi';
	$db_prefix = '';
	$db_type = 'MySQL';
	$db_server = $db_port !== '' ? $db_host.":".$db_port : $db_host;
	
	$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

	if($conn->connect_errno > 0){
		die('Unable to connect to database [' . $conn->connect_error . ']');
	}
?>