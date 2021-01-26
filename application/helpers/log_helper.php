<?php
if ( !function_exists('log_aktivitas_crud') )
{
function log_aktivitas_crud($modul, $modul_id, $description,
	    $action, $username) {
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
    	    CURLOPT_URL => SERVER_API."log_activity?modul=$modul&modul_id=$modul_id&description=$description&action=$action&username=$username",
    	    CURLOPT_RETURNTRANSFER => true,
    	    CURLOPT_ENCODING => "",
    	    CURLOPT_MAXREDIRS => 10,
    	    CURLOPT_TIMEOUT => 0,
    	    CURLOPT_FOLLOWLOCATION => true,
    	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	    CURLOPT_CUSTOMREQUEST => "GET",
    	));

    	$response = curl_exec($curl);

    	curl_close($curl);
	}
}