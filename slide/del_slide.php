<?php 
	require('../wp-blog-header.php');
	require('./predis/autoload.php');
	if (!is_user_logged_in()) { 		
		header('location:http://www.7hae.com/wp-admin'); 
		exit;
	} 

	$redis=new Predis\Client(); //实例化一个redis对象

	
	$key	=$_GET['key'];
	$slide=$redis->hgetall($key);

	if($slide['bg_img']){
		unlink('../'.$slide['bg_img']);

	}
	if($slide['l_img']){
		unlink('../'.$slide['l_img']);
	}

	$redis->del($key);

	header('location:http://www.7hae.com/slide/');
	
	
?>