<!DOCTYPE html>
<html lang="en">
<head>
	<title><block name="title">{$seo['tags']|default=$info['tags']}</block><block name="suffix"></block></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Keywords" content="{$seo['meta_keyword']|default=$info['meta_keyword']}" />
	<meta name="Description" content="{$seo['meta_description']|default=$info['meta_description']}" />
	<meta name="viewport" content="width = 320,initial-scale=1,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta content="telephone=no" name="format-detection" />
	<!-- css -->
	<link href="__PUBLIC__/Wap/default/style/swiper.css" rel="stylesheet" type="text/css"/>
	<link href="__PUBLIC__/Wap/default/style/css.css" rel="stylesheet" type="text/css"/>
	<block name="style">
		<style>
			.page-m{padding:20px 0;text-align:center;background:#fff;}
		    .page-m a {height: 34px;line-height: 34px;margin: 0 5px;padding: 0 10px;display: inline-block;zoom: 1;vertical-align:middle;}
		    .page-m a:hover{color:#128cee;text-decoration:underline;}
		    .page-m a.cur {color:#128cee;text-decoration:underline;}
		    .page-m a.pagePrev{background:url(../images/arrow-right-ico.png) center center no-repeat;transform:rotateZ(180deg);}
		    .page-m a.pageNext{background:url(../images/arrow-right-ico.png) center center no-repeat;}
		    .page-m .txt .text{height: 32px;padding: 0 5px;border: 1px solid #ddd;border-radius: 3px;width: 40px;}
		    .page-m .txt .btn{display: inline-block;zoom: 1;width: 50px;height: 34px;border: 1px solid #ddd;border-radius: 3px;line-height: 34px;text-align: center;font-size: 14px;color: #999;cursor: pointer;}
		    .page-m .txt .btn:hover{background:#128cee;color:#fff;border-radius:3px;}
		    .page-m .txt .btn:active{background:#1382db;color:#fff;border-radius:3px;}
		    .page-m span{display: none;}
		</style>
	</block>
</head>
<block name="main">
</block>

<block name="footer">
	<include file="Wap/default/Common:footer" />
</block>

<script type="text/javascript" src="__PUBLIC__/Common/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wap/default/js/jquery.SuperSlide.2.1.2.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wap/default/js/swiper.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wap/default/js/oecms.wap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Common/js/jquery.jslides.js"></script>
<script type="text/javascript">
     
</script>
<block name="script">

    <script language="javascript">
		$(function(){
			
			$("[f='btnser']").bind("click", function(){
				if ($("#type").find("option:selected").val().length == 0) {
					alert("请选择搜索类型");
					return false;
				}

				if ($("#keyword").val().length == 0) {
					alert("请输入搜索关键词");
					return false;
				}
			});
		});
</script>
</block>
</html>
