<?php

//装载模板文件
include_once("wx_tpl.php");

//获取微信发送数据
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//返回回复数据
if (!empty($postStr)){
	//解析数据
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	//发送消息方ID
	$fromUsername = $postObj->FromUserName;
	//接收消息方ID
	$toUsername = $postObj->ToUserName;
	//消息类型
	$form_MsgType = $postObj->MsgType;

	//事件消息
	if($form_MsgType=="event")
	{
		//获取事件类型
		$form_Event = $postObj->Event;
		//订阅事件
		if($form_Event=="subscribe")
		{
			//回复欢迎文字消息
			$msgType = "text";
			$contentStr = "感谢您关注爱承知！[玫瑰]";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
			echo $resultStr;
			exit;
		}          
	}

	//文字消息
	if($form_MsgType=="text")
	{
		$form_content = trim($postObj->Content);
		
		if(!empty($form_content))
		{
			//回复消息
			$msgType = "text";
			$kw=urlencode($form_content);
			
			//引入redis操作类
			require './predis/autoload.php';

			$redis=new Predis\Client(); //实例化一个redis对象

			$data=$redis->exists($kw);	//判断用户请求信息在数据库中是否存在
			
			if(!$data){ // not exists
				
				//获取页面信息开始
				$curl = curl_init();

				// 设置你需要抓取的URL
				curl_setopt($curl, CURLOPT_URL, 'http://localhost/newslist/?json=get_search_results&search='.$kw.'&count=9&page=1');

				// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

				// 运行cURL，请求网页
				$data = curl_exec($curl);

				// 关闭URL请求
				curl_close($curl);

				$res=json_decode($data,true); //json转码
				$json=array(); //定义一个数组用来存储数据
				foreach($res['posts'] as $k=>$row){
					$json[$k]['Title']=$row['title'];
					$json[$k]['Url']=$row['url'];
					if($k == 0){
						$json[$k]['PicUrl']=$row['attachments'][0]['images']["medium"]['url'];
					}else{
						$json[$k]['PicUrl']=$row['attachments'][0]['images']["thumbnail"]['url'];
					}
				}
				//如果有数据，将数据存入redis
				if(count($json) > 0){
				  $redis->set($kw,json_encode($json)); //将数据的json字符串存储在键为“用户发来的关键字”的值里面
				  $redis->expire($kw,1800);//定义过期时间为半小时
				}

			}else{// exists
				$info=$redis->get($kw); // 从redis里获取信息
				$json=json_decode($info,true); //转换数据类型
			}
				if(count($json) > 0){ //如果有数据则返回给用户


						//拼接字符串
					  $resultStr="<xml>\n
					  <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
					  <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
					  <CreateTime>".time()."</CreateTime>\n
					  <MsgType><![CDATA[news]]></MsgType>\n
					  <ArticleCount>".count($json)."</ArticleCount>\n
					  <Articles>\n";

					  foreach($json as $v){
					  $resultStr.="<item>\n
					  <Title><![CDATA[".$v['Title']."]]></Title> \n
					  <Description><![CDATA[]]></Description>\n
					  <PicUrl><![CDATA[".$v['PicUrl']."]]></PicUrl>\n
					  <Url><![CDATA[".$v['Url']."]]></Url>\n
					  </item>\n";
						}

					  $resultStr.="</Articles>\n
					  <FuncFlag>0</FuncFlag>\n
					  </xml>";

				}else{
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType,'没有相关内容');
				}

				
				
				echo $resultStr;
				exit;
			
		} else {
			//回复消息
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, '请输入要 关键字...');
			echo $resultStr;
			exit;
		}         
	}

}
else 
{
echo "";
exit;
}

?>