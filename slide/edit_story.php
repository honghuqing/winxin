<?php 
	require('./predis/autoload.php');
	$redis=new Predis\Client(); //实例化一个redis对象

	
	$key	=$_POST['key'];
	$field	=$_POST['field'];
	$value	=$_POST['value'];

	$redis->hset($key,$field,$value);

	$story=$redis->hgetall($key);

	if($story[$field] == $value){

		echo '1';
	}else{
		echo '0';
	}
	
	
	
?>