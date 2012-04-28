<?php
//=========================================
// -- 2stepAuth
// ---- SERVER
//=========================================

//=== Configuration ===//
$server = "http://your.domain/2step/auth.php";	// Full URL of the server's auth.php file.

//===== Functions =====//
function curl_post($url, $post_string, $method = 'post')
{
	$ch = curl_init();
	if($method == 'get')
	{
		curl_setopt($ch, CURLOPT_URL, $url. '?' . $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(preg_match("/https/", $url)){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
	}
	else
	{	//default to 'post'
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		if(preg_match("/https/", $url)){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
	}
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

//======= Code ========//
$server_time = curl_post($server,"time_check=y");
$client_time = time();
$time_diff = $client_time-$server_time;

$uname = $_REQUEST['user'];

$seconds = (date("s",$client_time+$time_diff) < 30) ? "00" : "30";

$hash = crc32($uname."-".date("Y-m-d H:i",$client_time+$time_diff).$seconds);

// Return the hash!
exit((string)$hash);
?>