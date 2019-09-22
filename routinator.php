<?php
	$url = $_REQUEST["url"];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9556".$url);
	curl_exec ($ch);
	curl_close ($ch);
?>
