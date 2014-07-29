<?php

  require('../predis/autoload.php');
  $redis=new Predis\Client(); //实例化一个redis对象

  $slide_name=$redis->keys('slide?');
  sort($slide_name);
  $slides=array();
  foreach($slide_name as $k=>$v){
    $slides[]=(array)json_decode($redis->get($v));
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0" />
<title>微故事</title>
<link rel="stylesheet" type="text/css"  href="stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css"  href="stylesheet/bootstrap.css" />
<script type="text/javascript" src="script/jquery-1.3.2.min.js"></script>
<style>

@media only screen and (min-width: 768px) and (max-width: 959px){img{ max-width:100%}}@media only screen and (max-width: 767px){img{ max-width:100%}}

/* Swipe 2 required styles */
.swipe {
  overflow: hidden;
  visibility: hidden;
  position: relative;
}
.swipe-wrap {
  overflow: hidden;
  position: relative;
}
.swipe-wrap > .wrap {
  float:left;
  width:100%;
  position: relative;
}
.player{
	margin-top:20px;
	position:absolute;
	right:10px;
	z-index:20;
}
/* END required styles */
.one-box{
	text-align:center;
	padding-top:20px;
}
.one-box p{padding:0px 10px}
</style>

</head>

<body>
<div class="player">
	<audio src="./1.mp3" id="music" poster="poster.jpg" autoplay ></audio>
	<a href="javascript:playPause();" ><img src="music.jpg" width="30" height="27" id="music_btn" border="0"></a>
</div>
<div id="slider" class="swipe container">
<div  class="swipe-wrap col-lg-12">

  <?php 
    $index=0;
    foreach($slides as $K=>$v){
      $keys=array_keys($v);
      $values=array_values($v);
  ?>
	<div class="wrap" style="background:url(<?php echo $v['bg_img'];?>) no-repeat;height:520px">
    <img class="img-responsive" src="<?php echo $v['l_img'];?>" width="155" style="position:absolute;<?php echo $keys[3].':'.$values[3].'px;'; echo $keys[4].':'.$values[4].'px;';?>z-index:31;opacity:0" id="img<?php echo $index;$index++;?>" thisalt="<?php echo $v['type'];?>"/>
  </div>
  <?php }?>
</div>
</div>
<script type="text/javascript">
<!--
		$("#img0").animate({top:'80px',opacity:'1'},"slow");
//-->
</script>
<script src="script/swipe.js"></script>
<script>
var slider =
  Swipe(document.getElementById('slider'), {
    auto: 0, //0为不自动播放
    continuous: true,
    callback: function(pos) {	
	$(".current_count").html(pos+1); //获取当前wrap的索引，所以要加1
    }
  });
$(function(){
	$(".total_count").html($(".swipe-wrap .wrap").length);
	 $(".pre").click(function(){
		 slider.prev(); 
   });
    $(".next").click(function(){
	      slider.next();
   });
	
});
	 



</script>
<script type="text/javascript">
       function playPause() {
 
       var myVideo = document.getElementById('music');
           var   music_btn = document.getElementById('music_btn');
 
       if (myVideo.paused){
           myVideo.play();
                   music_btn.src='music.jpg';
           }
 
       else{
           myVideo.pause();
                   music_btn.src='music.png'; 
       }
         }
         
</script>
</body></html>