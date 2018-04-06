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
	<link href="/Static/Public/Web/default/style/swiper.css" rel="stylesheet" type="text/css"/>
	<link href="/Static/Public/Web/default/style/main.css" rel="stylesheet" type="text/css"/>
	<link href="/Static/Public/Web/default/style/flickerplate.css" rel="stylesheet" type="text/css"/>
	
		<style>
			/*.page-m{padding:20px 0;text-align:center;background:#fff;}
		    .page-m a {height: 34px;line-height: 34px;margin: 0 5px;padding: 0 10px;display: inline-block;zoom: 1;vertical-align:middle;}
		    .page-m a:hover{color:#128cee;text-decoration:underline;}
		    .page-m a.cur {color:#128cee;text-decoration:underline;}
		    .page-m a.pagePrev{background:url(../images/arrow-right-ico.png) center center no-repeat;transform:rotateZ(180deg);}
		    .page-m a.pageNext{background:url(../images/arrow-right-ico.png) center center no-repeat;}
		    .page-m .txt .text{height: 32px;padding: 0 5px;border: 1px solid #ddd;border-radius: 3px;width: 40px;}
		    .page-m .txt .btn{display: inline-block;zoom: 1;width: 50px;height: 34px;border: 1px solid #ddd;border-radius: 3px;line-height: 34px;text-align: center;font-size: 14px;color: #999;cursor: pointer;}
		    .page-m .txt .btn:hover{background:#128cee;color:#fff;border-radius:3px;}
		    .page-m .txt .btn:active{background:#1382db;color:#fff;border-radius:3px;}
		    .page-m span{display: none;}*/
		</style>
	
</head>

<div class="oe_top">
  <div class="oe_topcn">
    <div class="oe_top_tel"> 
      <!--{assign var="contact" value="<!--{label name="contact"}-->"}--> 
      <!--{if empty($contact)}--> 
      <!-- 请在后台添加自定义HTML标签：contact  -->
      <!--{else}--> 
      <!--<?php echo ($contact); ?>--> 
      <!--{/if}--> 
    </div>
    <div class="oe_top_tips">
      <!--{assign var="toptips" value="<!--{label name="toptips"}-->"}--> 
      <!--{if empty($toptips)}--> 
      <!-- 请在后台添加自定义HTML标签：toptips -->
      <!--{else}--> 
      <!--<?php echo ($toptips); ?>--> 
      <!--{/if}--> 
    </div>
    <div class="oe_topsearch">
      <form method="post" action="" name="myform" id="myform" onsubmit="return checksearch();" >
      <span class="select">
          <select name="type" id="type">
              <option value="product" <?php if(product): ?>selected<?php endif; ?>>&nbsp;产品&nbsp;</option>
              <option value="photo" <?php if(photo): ?>selected<?php endif; ?>>&nbsp;图库&nbsp;</option>
              <option value="article" <?php if(article): ?>selected<?php endif; ?>>&nbsp;文章&nbsp;</option>
              <option value="download" <?php if(download): ?>selected<?php endif; ?>>&nbsp;下载&nbsp;</option>
              <option value="hr" <?php if(hr): ?>selected<?php endif; ?>>&nbsp;招聘&nbsp;</option>
          </select>
      </span>
      <span class="input">
          <input type='text' name='keyword' id="keyword" value="" />
      </span>
          <input class='searchimage' type='submit' />
      </form>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="oe_big_logo">
  <div class="oe_logo">
    <div class="oe_logocn"><a href="<!--<?php echo ($config["siteurl"]); ?>-->"><img src="<!--<?php echo ($config["logo"]); ?>-->"> </a></div>
    <div class="oe_menu">
      <ul>
        <!--{assign var='mymenu' value=vo_category("type={sedmenu}")}--> 
        <!--{foreach $mymenu as $parent}-->
        <li f="show"> 
          <!--{if $parent.url==''}--> 
          <a><!--<?php echo ($parent["catname"]); ?>--></a> 
          <!--{else}--> 
          <a href="<!--<?php echo ($parent["url"]); ?>-->"><!--<?php echo ($parent["catname"]); ?>--></a> 
          <!--{/if}--> 
          <!--{if !empty($parent.childmenu)}-->
          <div id="show" style="display:none;">
            <img src="<!--<?php echo ($skinpath); ?>-->images/ico_menu_s.png" />
            <!--{foreach $parent.childmenu as $child}--> 
            <a href="<!--<?php echo ($child["url"]); ?>-->"><!--<?php echo ($child["catname"]); ?>--></a> 
            <!--{/foreach}--> 
          </div>
          <!--{/if}--> 
        </li>
        <!--{/foreach}-->
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>

<script language="javascript">
  function checksearch(){
    if($("#type").val()==""){
      alert("请选择搜索频道.");
      return false;
    }
    if($("#keyword").val()==""){
      alert("关键字不能为空.");
      return false;
    }	
  }
  $(function(){
    $("[f='show']").bind("mouseover", function(){ //鼠标经过
      $(this).addClass("current");
      $(this).children("#show").show();
    });
    $("[f='show']").bind("mouseout", function(){ //鼠标离开
      $(this).removeClass("current");
      $(this).children("#show").hide();
    });
  });
</script>
<div class="oe_left" style="display: none;">
  <div class="oe_left_top">
    <span f="nav">导航</span>
  </div>
  <?php $category = getCategory(0, 0); ?>
  <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl id="nav_<?php echo ($vo["catid"]); ?>" f="gourl" data-url="<?php echo U($vo['url']);?>"> 
        <dt style="background:url(<?php echo ($vo['images']); ?>) 0px 15px no-repeat; background-size:18px;"></dt> 
      <dd><?php echo ($vo["cat_name"]); ?></dd> 
      </dl><?php endforeach; endif; else: echo "" ;endif; ?>

</div>

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


<div class="fullSlide">
  <?php $swiper = getSwiper(index, 2); ?>
  <div id="full-screen-slider">
    <?php if(empty($swiper)): ?><p style="padding:10px;text-align:center;">在后台添加广告位“index_slide_banner“幻灯片和广告图1920x288px</p>
      <?php else: ?>
      <ul id="slides">
          <?php if(is_array($swiper)): $i = 0; $__LIST__ = $swiper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="background:url('<?php echo ($vo['image']); ?>') no-repeat center top"> <a href="<?php echo ($vo['url']); ?>" target="<?php echo ($vo['target']); ?>"></a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul><?php endif; ?>
  
  </div>
</div>

<div class="oe_index_product">
  <div class="title">
    <h2>产品展示</h2>
    <p><img src="/Static/Public/Web/default/images/ico_index_product.png" /></p>
  </div>
  <div class="oe_in_prodct_list">
    <div class="hd"> <a class="next"></a>
      <ul>
      </ul>
      <a class="prev"></a> </div>
    <div class="bd">
      <ul class="picList">
      <?php $goodsList = getProduct(index, 0); ?>
        <?php if(is_array($goodsList['list'])): $i = 0; $__LIST__ = $goodsList['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="pic">
              <a href="<?php echo U('Product/productDetail',array('id' => $vo['id']));?>" target="_blank">
                <img src="<?php echo ($vo["thumb_files"]); ?>" alt="<?php echo ($vo["product_name"]); ?>" onload="javascript:oecmsDrawImage(this, 163, 163);" />
              </a>
            </div>
            <div class="title">
              <a href="<?php echo U('Product/productDetail',array('id' => $vo['id']));?>" target="_blank"><?php echo ($vo["product_name"]); ?></a>
            </div>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
  </div>
  <script type='text/javascript' src='<!--<?php echo ($skinpath); ?>-->js/jquery.SuperSlide.2.1.1.js'></script> 
  <script type="text/javascript">
      jQuery(".oe_in_prodct_list").slide({titCell:".hd ul",mainCell:".bd ul",scroll:2,autoPage:true,effect:"left",autoPlay:true,vis:6,delayTime:900});
  </script> 
</div>






	<div class="kong"></div>
<div class="oe_footer">
  <dl class="oe_bar_1  <?php if(in_array(ACTION_NAME,array('index'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Index/index');?>">
  	<dt></dt>
  	<dd>首页</dd>
  </dl>

  <dl class="oe_bar_2 <?php if(in_array(ACTION_NAME,array('product','productDetail'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Product/product');?>">
  	<dt></dt>
  	<dd>互动游戏</dd>
  </dl>


  <dl class="oe_bar_3 <?php if(in_array(ACTION_NAME,array('article','articleDetail'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('Article/article');?>">
  	<dt></dt>
  	<dd>最新资讯</dd>
  </dl>
 
  <dl class="oe_bar_4 <?php if(in_array(ACTION_NAME,array('guestBookMessage'))): ?>current<?php endif; ?> " f="gourl" data-url="<?php echo U('GuestBook/guestBookMessage');?>">
    <dt></dt>
    <dd>留言</dd>
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

  <script type="text/javascript">
      $(function(){
        //滑动效果
        var mySwiper = new Swiper ('.swiper-container', {
          loop : true,
          pagination: '.swiper-pagination',
          autoplay:3000,
          autoplayDisableOnInteraction : false
        });


        $max_width = ($(window).width()-35)/3;
          $(".oe_index_productcn dl").css({"width":$max_width});
          $(".oe_index_productcn dl dt img").css({"width":$max_width});
          $(".oe_index_productcn dl dt img").css({"height":$max_width});
      });
  </script>

</html>