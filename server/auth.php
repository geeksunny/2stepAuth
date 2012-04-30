<?php
//=========================================
// -- 2stepAuth
// ---- SERVER
//=========================================

if ($_REQUEST['time_check'] == 'y')
{
	exit((string)time()."||".date_default_timezone_get());
}
elseif ($_REQUEST['user'] != '' && $_REQUEST['hash'] != '')
{
	$seconds = (date("s",time()) < 30) ? "00" : "30";

	// We will check the given hash against the real-time hash in addition to the past two historical hashes.
	$hash1 = crc32($_REQUEST['user']."-".date("Y-m-d H:i",time()).':'.$seconds);
	$hash2 = crc32($_REQUEST['user']."-".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i",time()).':'.$seconds." -30 seconds")));
	$hash3 = crc32($_REQUEST['user']."-".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i",time()).':'.$seconds." -60 seconds")));

	if (in_array($_REQUEST['hash'],array($hash1,$hash2,$hash3)))
		$result = "success";
	else
		$result = "failure";

	exit((string)$result);
}
else
{
	echo "ERROR: Missing Parameters.";
}
?>