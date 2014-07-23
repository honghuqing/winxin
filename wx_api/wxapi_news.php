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
 
 
             //回复欢迎图文菜单
              $resultStr="<xml>\n
              <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
              <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
              <CreateTime>".time()."</CreateTime>\n
              <MsgType><![CDATA[news]]></MsgType>\n
              <ArticleCount>5</ArticleCount>\n
              <Articles>\n";
              
              //添加封面图文消息
              $resultStr.="<item>\n
              <Title><![CDATA[国内最专业的微信教程]]></Title> \n
              <Description><![CDATA[国内最专业的微信教程，国内最专业的微信教程]]></Description>\n
              <PicUrl><![CDATA[http://weixincourse-weixincourse.stor.sinaapp.com/Snip20130403_8.jpg]]></PicUrl>\n
              <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MjgwNjgwMA==&appmsgid=10000169&itemidx=1]]></Url>\n
              </item>\n";
              
              //添加4条列表图文消息
              $resultStr.="<item>\n
              <Title><![CDATA[教程大纲]]></Title> \n
              <Description><![CDATA[]]></Description>\n
              <PicUrl><![CDATA[http://weixincourse-weixincourse.stor.sinaapp.com/Snip20130403_14.jpg]]></PicUrl>\n
              <Url><![CDATA[http://ztalk.sinaapp.com]]></Url>\n
              </item>\n";
              
              $resultStr.="<item>\n
              <Title><![CDATA[报名培训]]></Title> \n
              <Description><![CDATA[国内最专业的微信教程，国内最专业的微信教程]]></Description>\n
              <PicUrl><![CDATA[http://weixincourse-weixincourse.stor.sinaapp.com/Snip20130504_8.png]]></PicUrl>\n
              <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MjgwNjgwMA==&appmsgid=10000169&itemidx=1]]></Url>\n
              </item>\n";
              
              $resultStr.="<item>\n
              <Title><![CDATA[关于老贼]]></Title> \n
              <Description><![CDATA[]]></Description>\n
              <PicUrl><![CDATA[http://weixincourse-weixincourse.stor.sinaapp.com/lz.jpg]]></PicUrl>\n
              <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MTcyMDAyMQ==&appmsgid=10000054&itemidx=1]]></Url>\n
              </item>\n";
              
              $resultStr.="<item>\n
              <Title><![CDATA[联系老贼]]></Title> \n
              <Description><![CDATA[国内最专业的微信教程，国内最专业的微信教程010-123123123]]></Description>\n
              <PicUrl><![CDATA[http://weixincourse-weixincourse.stor.sinaapp.com/Snip20130403_19.jpg]]></PicUrl>\n
              <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MTcyMDAyMQ==&appmsgid=10000054&itemidx=1]]></Url>\n
              </item>\n";
              
              $resultStr.="</Articles>\n
              <FuncFlag>0</FuncFlag>\n
              </xml>";
                        
              /*
              //回复欢迎文字消息
              $msgType = "text";
              $contentStr = "感谢您关注公众平台教程！[愉快]\n\n想学公众平台使用的朋友请输入“跟我学”！[玫瑰]";
              $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
              */
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