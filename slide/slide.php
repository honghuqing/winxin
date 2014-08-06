<?php 
	require('../wp-blog-header.php');
	require('./predis/autoload.php');
	if (!is_user_logged_in()) { 
		header('location:http://www.7hae.com/wp-admin'); 
		exit;
	} 

	$redis=new Predis\Client(); //实例化一个redis对象

	if($_POST['action'] == 'insert'){

		$img	=$_POST['image'];
		$type	=$_POST['type'];
		$top	=empty($_POST['top'])?'':$_POST['top'];
		$bottom	=empty($_POST['bottom'])?'':$_POST['bottom'];
		$left	=empty($_POST['left'])?'':$_POST['left'];
		$right	=empty($_POST['right'])?'':$_POST['right'];
		$bg_img	=$img[0]; //获取主图
		$l_img	=$img[1];//获取小图

		//组合数据
		$slide=array(
			'bg_img'=>$bg_img,
			'l_img'=>$l_img,
			'type'=>$type
		);

		if($top != ''){
			$top1['top']=$top;
			$slide=array_merge($slide,$top1);
		}
		if($bottom != ''){
			$bottom1['bottom']=$bottom;
			$slide=array_merge($slide,$bottom1);
		}
		if($left != ''){
			$left1['left']=$left;
			$slide=array_merge($slide,$left1);
		}
		if($right != ''){
			$right1['right']=$right;
			$slide=array_merge($slide,$right1);
		}
		$slide_id=$redis->incr('id');//自增长排序
		$slide['order']=$slide_id; //添加排序字段


		// $slide_json=json_encode($slide); 		//数组转换成json字符串
		$slide_name='story:'.$slide_id; 			// 生成不同的名称
		// $redis->set($slide_name,$slide_json);	 //以字符串类型存入redis数据库

		$redis->hmset($slide_name,$slide);

		echo "<script type='text/javascript'>alert('添加成功！')</script>";
		header('location:http://www.7hae.com/slide/');
	}
	
?>