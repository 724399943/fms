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

  <div class="oe_top">
    <span f="nav">导航</span>
    <em><?php echo ($wapName); ?></em>
    <label f="search">
  </div>
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


	<div class="swiper-container oe_banner">
		<div class="swiper-wrapper">
		<?php $swiper = getSwiper(index, 2); ?>
		<?php if(is_array($swiper)): $i = 0; $__LIST__ = $swiper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
				<a href="<?php echo ($vo['url']); ?>"><img src="<?php echo ($vo['image']); ?>" style="height: 180px" /></a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>

    <div class="oe_index_product">
      <h2>最新产品<span f="gourl" style="display:none;">更多&gt;&gt;</span></h2>
        <div class="oe_index_productcn">
        <?php $goodsList = getProduct(index, 0); ?>
          <?php if(is_array($goodsList['list'])): $i = 0; $__LIST__ = $goodsList['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl f="gourl" data-url="<?php echo U('Product/productDetail',array('id' => $vo['id']));?>">
              <dt><img src="<?php echo ($vo["upload_files"]); ?>" style="width: 113.333px; height: 113.333px;" /></dt>
              <!-- <dt><img src="<?php echo ($vo["thumb_files"]); ?>" style="width: 113.333px; height: 113.333px;" /></dt> -->
              <dd>
                <h3><?php echo ($vo['product_name']); ?></h3>
              <p>型号：<?php echo ($vo['product_sn']); ?></p>
              <p>价格：<span><?php echo ($vo['bprice']); ?></span></p>
              </dd>
            </dl><?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="clear"></div>
      </div>
   </div>

   <div class="oe_index_article">
      <h2>精彩活动<span f="gourl" style="display:none;">MORE</span></h2>
      <?php $information = getInformation(index, 0); ?>
      <?php if(is_array($information)): $i = 0; $__LIST__ = $information;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl f="gourl" data-url="<?php echo U('Article/articleDetail',array('id' => $vo['id']));?>">
          <dt>
          <h3><?php echo ($vo['title']); ?></h3>
          <p><?php echo ($vo['summary']); ?></p>
        </dt>
        <dd>
            <span>分类：<?php echo getCategoryName($vo['cat_id']);?></span>
            <label>浏览：<?php echo ($vo['hits']); ?>  &#12288;</label>
        </dd>
        </dl><?php endforeach; endif; else: echo "" ;endif; ?>
      <div class="clear"></div>
      <div class="oe_index_article_more" f="gourl" style="display:none;">更多资讯</div>
    </div>



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