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


  <div class="oe_nav">
    <span f="back">返回</span>
    <em>查看详情</em>
    <label f="search"></label>
  </div>
  <div class="oe_product_detail">
    <h1 class="title"><?php echo ($info['product_name']); ?></h1>
    <div class="oe_product_con">
      <ul>
        <!-- <li><b>编号 : </b><span><?php echo ($info['product_sn']); ?></span></li>
        <li><b>分类 : </b><span f="gourl" data-url="" style="color: blue"><?php echo getCategoryName($info['cat_id']);?></span></li>
        <li><b>日期 : </b><span><?php echo (date("Y-m-d",$info['add_time'])); ?></span></li>
        <li><b>价格 : </b><span><?php echo ($info['bprice']); ?></span></li> -->
        <!-- <?php dump($info) ?> -->
        <?php if(is_array($info['attrInfo'])): $i = 0; $__LIST__ = $info['attrInfo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <b><?php echo ($vo['attr_name']); ?> : </b>
              <span>
                <?php if(is_array($vo['attrValue'])): $i = 0; $__LIST__ = $vo['attrValue'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i; echo ($v1['attr_value']); ?>;<?php endforeach; endif; else: echo "" ;endif; ?>
              </span>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="clear"></div>
  	</ul>
    </div>
    <div class="oe_product_img">
      <ul>
        <div class="swiper-container oe_banner">
          <div class="swiper-wrapper">
          <?php $swiper = getSwiper(images, $info['id']); ?>
          <?php if(is_array($swiper)): $i = 0; $__LIST__ = $swiper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
              <a href=""><img src="<?php echo ($vo['goods_image']); ?>" style="height: 180px" /></a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <!-- <li><img src="<?php echo ($info['upload_files']); ?>" /></li> -->
        <!--{if !empty($info.gallery)}-->
        <!--{foreach $info.gallery as $val}-->
  	  <li>
  	    <h3><!--<?php echo ($val["imgname"]); ?>--></h3>
  	    <img border='0' src='<!--<?php echo ($val["imgurl"]); ?>-->'>
  	  </li>
  	  <!--{/foreach}-->
  	  <!--{/if}-->
      </ul>
    </div>
    <div class="oe_product_text">
      <?php echo ($info['content']); ?>
    </div>
    <div class="nepr">
      <?php if(!empty($previous['id'])): ?>上一条：<a href="<?php echo U('Product/productDetail',array('id' => $previous['id']));?>"><?php echo ($previous['product_name']); ?></a><br>
      <?php else: ?>
         下一条： 没有了 <br><?php endif; ?>
      <?php if(!empty($next['id'])): ?>下一条：<a href="<?php echo U('Product/productDetail',array('id' => $next['id']));?>"><?php echo ($next['product_name']); ?></a><br>
      <?php else: ?>
         下一条： 没有了 <br><?php endif; ?>
    </div>
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
          // autoplay:3000,
          autoplayDisableOnInteraction : false
        });


      });
  </script>

</html>