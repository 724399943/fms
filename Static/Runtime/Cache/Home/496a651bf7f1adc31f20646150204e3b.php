<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo ((isset($seo['tags']) && ($seo['tags'] !== ""))?($seo['tags']):$info['tags']); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Keywords" content="<?php echo ((isset($seo['meta_keyword']) && ($seo['meta_keyword'] !== ""))?($seo['meta_keyword']):$info['meta_keyword']); ?>" />
	<meta name="Description" content="<?php echo ((isset($seo['meta_description']) && ($seo['meta_description'] !== ""))?($seo['meta_description']):$info['meta_description']); ?>" />
	<meta name="viewport" content="width = 320,initial-scale=1,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta content="telephone=no" name="format-detection" />
	<!-- css -->
	<link href="/Static/Public/Wap/default/style/swiper.css" rel="stylesheet" type="text/css"/>
	<link href="/Static/Public/Wap/default/style/css.css" rel="stylesheet" type="text/css"/>
	
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
	
</head>

  <div class="oe_nav">
  <span f="back">返回</span>
  <?php $categorys = getCategory(2, 0); ?>
  <?php $url = CONTROLLER_NAME . '/' . ACTION_NAME; ?>
  <div class="swiper-container oe_navcn">
    <div class="swiper-wrapper oe_nav_con">
      <?php if(is_array($categorys)): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><em class="swiper-slide <?php if($vo['id'] == $_GET['cat_id']): ?>current<?php endif; ?> " f="gourl"  data-url="<?php echo U($url,array('cat_id'=>$vo['id']));?>"><?php echo ($vo['cat_name']); ?></em><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
  </div>
  <label f="search"></label>
</div>


  <?php $articleList = getArticle('list', 0); ?>
  <div class="oe_topsearch" style="display: none;">
  <span class="oe_jian" f="search"></span>
  <form method="post" action="<?php echo U('Search/search');?>" id="serform" />
  <div class="search">
    <select name="type" id="type">
    	<option value="product" <?php if(product): ?>selected<?php endif; ?>>&nbsp;产品&nbsp;</option>
    	<option value="photo" <?php if(photo): ?>selected<?php endif; ?>>&nbsp;图库&nbsp;</option>
    	<option value="article" <?php if(article): ?>selected<?php endif; ?>>&nbsp;文章&nbsp;</option>
    	<option value="download" <?php if(download): ?>selected<?php endif; ?>>&nbsp;下载&nbsp;</option>
    	<option value="hr" <?php if(hr): ?>selected<?php endif; ?>>&nbsp;招聘&nbsp;</option>
    </select><input type='text' name='keyword' id="keyword" value="" /><input class='searchimage' type='submit' f='btnser' />
  </div>
  </form>
</div>


  <div class="oe_article_list">
    <?php if(empty($articleList['list'])): ?><p style="padding:10px;text-align:center;">没有符合条件的信息</p>
    <?php else: ?>
    <?php if(is_array($articleList['list'])): $i = 0; $__LIST__ = $articleList['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl f="gourl" data-url="<?php echo U('Article/articleDetail',array('id' => $vo['id']));?>">
        <img src="<?php echo ($vo['images']); ?>" width="100px" height="100px" style="float:left">
        <dt style="padding-left:110px">
    	  <h3><?php echo ($vo['title']); ?></h3>
          <p><?php echo strCut($vo['content'],200);?></p>
        </dt>
      	<dd>
          <span style="left: 110px;">分类：<?php echo getCategoryName($vo['cat_id']);?></span>
          <label>浏览：<?php echo ($vo['hits']); ?>  &#12288;<?php echo (date("Y/m/d",$vo['add_time'])); ?></label>
        </dd>
      </dl><?php endforeach; endif; else: echo "" ;endif; ?>
    <div class="clear"></div><?php endif; ?>
  </div>
  <?php if(!empty($articleList['page'])): ?><div class="page-m">
      <div class="page-box"><?php echo ($articleList['page']); ?></div>
    </div><?php endif; ?>



	<div class="kong"></div>
<div class="oe_footer">
  <dl class="oe_bar_1  <?php if(in_array(ACTION_NAME,array('index'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Index/index');?>">
  	<dt></dt>
  	<dd>首页</dd>
  </dl>

  <dl class="oe_bar_2 <?php if(in_array(ACTION_NAME,array('product','productDetail'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Product/product');?>">
  	<dt></dt>
  	<dd>产品介绍</dd>
  </dl>


  <dl class="oe_bar_3 <?php if(in_array(ACTION_NAME,array('article','articleDetail'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Article/article');?>">
  	<dt></dt>
  	<dd>精彩活动</dd>
  </dl>
 
 <!--  <dl class="oe_bar_4 <?php if(in_array(ACTION_NAME,array('guestBookMessage'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('GuestBook/guestBookMessage');?>">
    <dt></dt>
    <dd>关于我们</dd>
  </dl> -->
   <dl class="oe_bar_4 <?php if(in_array(ACTION_NAME,array('guestBookMessage'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Bbs/Index/index');?>">
    <dt></dt>
    <dd>关于我们</dd>
  </dl>
  <div class="clear"></div>
</div>


<script type="text/javascript" src="/Static/Public/Common/js/jquery.min.js"></script>
<script type="text/javascript" src="/Static/Public/Wap/default/js/jquery.SuperSlide.2.1.2.js"></script>
<script type="text/javascript" src="/Static/Public/Wap/default/js/swiper.min.js"></script>
<script type="text/javascript" src="/Static/Public/Wap/default/js/oecms.wap.js"></script>
<script type="text/javascript" src="/Static/Public/Common/js/jquery.jslides.js"></script>
<script type="text/javascript">
     
</script>


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

</html>