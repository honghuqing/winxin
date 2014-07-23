<!DOCTYPE HTML>
<html>
<HEAD>
<meta charset="utf-8">
<TITLE>菜单添加/修改</TITLE>
</HEAD>
<body>
    <!--页面名称-->
	<h2>菜单添加/修改</h2>
    <h3>如果填写了子菜单则主菜单的关键字无效</h3>
	<h4>子菜单的类型判断：1为view，0为click</h4>
     <!--表单开始-->
    <form action=" https://api.weixin.qq.com/cgi-bin/menu/create?access_token=mCAJ7GdoTeBjUrJH9-gc3bT4JxfFiEbd0S0fp6O1ed_J3TlUqnwlXkGY8QU-cn-Y4HPI6HlSu3402XOnIMwoUQ" method="post" name="menu_add" id="menu_add" >
        <div id="menu">
               <textarea required="true" data-type="content" method="POST" name="body" rows="15" cols="70"></textarea>
                <p>
                    <input type="hidden" name="action"  value="update">
                    <input type="submit" value="生成菜单" />
                </p>
           	
            
        </div>
    </form>

