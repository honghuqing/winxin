<?php 
	require('../wp-blog-header.php');
	require('./predis/autoload.php');
	if (!is_user_logged_in()) { 
		header('location:http://www.7hae.com/wp-admin'); 
		exit;
	} 

	$redis=new Predis\Client(); //实例化一个redis对象

	$slide_name=$redis->keys('story:*');
	

  $slides=array();
  foreach($slide_name as $k=>$v){
    $story=$redis->hgetall($v);
    $slides[$story['order']]=$story;
	$slides[$story['order']]['name']=$v;
  }

  ksort($slides);
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/bootstrap.css">
	<link rel="stylesheet" href="./css/flat-ui.css">
	<script src="js/jquery-1.8.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script>
	<script src="js/application.js"></script>
</head>
 <body>
	<div class="container" style="padding-bottom:100px">

		<div class="col-lg-3" style="margin-top:50px">
			<div class="affix list-group" style="box-shadow: 0px 0px 5px 3px #CCC;">
			  <a href="/slide/" class="list-group-item">模块列表</a>
			  <a href="/slide/insert.php" class="list-group-item">添加模块</a>
			</div>
		</div>

		<div class="col-lg-9" style="border-left:1px solid #dddddd;margin-top:50px;">
		<table class="table table-hover">
			<tr style="background-color:#eeeeee">
				<th style="text-align:center">背景图</th>
				<th style="text-align:center">小图片</th>
				<th style="text-align:center">移动模式</th>
				<th style="text-align:center">小图位置</th>
				<th style="text-align:center">排序</th>
				<th style="text-align:center">操作</th>
			</tr>
			<?php 
				foreach($slides as $Key=>$value){
					$keys=array_keys($value);
					$values=array_values($value);
			?>
			<tr>
				<td style="text-align:center"><img src="<?php echo $values[0];?>" height="100" /></td>
				<td style="text-align:center"><img src="<?php echo $values[1];?>" height="100" style="background:#dddfff"/></td>
				<td style="text-align:center">
					<?php
							if($value['type'] == '1'){
								echo '从上到下';
							}elseif($value['type'] == '2'){
								echo '从下到上';
							}elseif($value['type'] == '3'){
								echo '从左到右的';
							}elseif($value['type'] == '4'){
								echo '从右到左';
							}
					?>
				</td>
				<td style="text-align:center">
					<?php echo $keys[3].':';?>
					<input type="text" value="<?php echo $values[3];?>" onblur="edit(this,'<?php echo $value['name'];?>','<?php echo $keys[3];?>')" class="form-control" style="width:25px;padding:0px"/>px<br />
					
					<?php echo $keys[4].':';?>
					<input type="text" value="<?php echo $values[4];?>" onblur="edit(this,'<?php echo $value['name'];?>','<?php echo $keys[4];?>')" class="form-control" style="width:25px;padding:0px"/>px<br />
					
				</td>
				<td style="text-align:center">
					<input type="text" value="<?php echo $value['order'];?>" onblur="edit(this,'<?php echo $value['name'];?>','order')" class="form-control" style="width:30px;padding:0px"/>
				</td>
				<td style="text-align:center"><a href="./del_slide.php?key=<?php echo $value['name'];?>" onclick="return confirm('删除？')">删除</td>
			</tr>
			<?php }?>
		</table>
		</div>

	</div>
 </body>
</html>

<script type="text/javascript">
	function edit(msg, key, field){

		var value=msg.value;
		var data={
				key:key,
				field:field,
				value:value
			}
		$.ajax({
			url: './edit_story.php',
			type:"POST",
			data:data,
			success:function(response)
			{
				if(response == 1){
					alert('修改成功！')
					location.href="http://www.7hae.com/slide";
				}else if(response == 0){
					alert('修改失败！');
				}
			}
		})

	}
</script>