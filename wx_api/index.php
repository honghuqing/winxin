<?php
	header("Content-type: text/html;charset=utf-8");
$res=file_get_contents('http://localhost/newslist/?json=get_search_results&search=%E5%87%8F%E8%82%A5&count=20&page=1');

	echo '<pre>';
	var_dump($res);exit;

?>