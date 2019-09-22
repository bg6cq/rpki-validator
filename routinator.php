<?php
	$url = @$_REQUEST["url"];
	$addpre = @$_REQUEST["addpre"];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9556".$url);
	if($addpre == 1)
		echo "<pre>";
	curl_exec ($ch);
	curl_close ($ch);
	if($addpre == 1)
		echo "</pre>";
?>
