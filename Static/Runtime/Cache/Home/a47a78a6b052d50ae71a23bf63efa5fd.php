<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title><?php echo ((isset($seo['tags']) && ($seo['tags'] !== ""))?($seo['tags']):$info['tags']); ?></title>
    <meta name="renderer" content="webkit">
    <meta name="description" content="<?php echo ((isset($seo['meta_description']) && ($seo['meta_description'] !== ""))?($seo['meta_description']):$info['meta_description']); ?>">
    <meta name="keywords" content="<?php echo ((isset($seo['meta_keyword']) && ($seo['meta_keyword'] !== ""))?($seo['meta_keyword']):$info['meta_keyword']); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="/Static/Public/Wap/fms/css/base.css">
    <link rel="stylesheet" type="text/css" href="/Static/Public/Wap/fms/css/fmsStyle.css">
    
    <script type="text/javascript" src="/Static/Public/Wap/fms/js/jquery.min.js"></script>
    <script type="text/javascript">        
        /**              
         * 时间戳转换日期              
         * @param <int> unixTime    待时间戳(秒) 
         * @param <int>  type   返回的格式(0: Y-m-d 或者1: Y/m/d )                 
         * @param <bool> isFull    返回完整时间(false: Y-m-d 或者 true: Y-m-d H:i:s)              
         * @param <int>  timeZone   时区              
         */
        function UnixToDate(unixTime, type, isFull, timeZone) {
            if (typeof (timeZone) == 'number')
            {
                unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
            }
            var time = new Date(unixTime * 1000);
            var ymdhis = "";
            var month = (time.getUTCMonth()+1);
            if( month.toString().length < 2 ){
                month = "0" + month;
            }
            if( type == 0 ){
                ymdhis += time.getUTCFullYear() + "/";
                ymdhis +=  month + "/";
                ymdhis += time.getUTCDate(); 
            }else{
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();                
            }
            if (isFull === true)
            {
                ymdhis += " " + time.getUTCHours() + ":";
                ymdhis += time.getUTCMinutes() + ":";
                ymdhis += time.getUTCSeconds();
            }
            return ymdhis;
        }

        // 通用滚动方法
        function isScroll(bottomCall){
            var startX = 0, startY = 0;
            function touchSatrtFunc(evt) {
                  try
                  {

                      var touch = evt.touches[0]; //获取第一个触点  
                      var x = Number(touch.clientX); //页面触点X坐标  
                      var y = Number(touch.clientY); //页面触点Y坐标  
                      //记录触点初始位置  
                      startX = x;
                      startY = y;

                  } catch (e) {
                      alert( e.message);
                  }
            }
            //touchstart事件  
            document.body.addEventListener('touchstart', touchSatrtFunc, false);
            document.body.addEventListener('touchmove',scrlllfunction,false);
            function scrlllfunction (ev){
                var _point = ev.touches[0];
                 // window滚动
                var _top = document.body.scrollTop || document.documentElement.scrollTop;
                 // 什么时候到底部
                var bottomAdr = document.body.scrollHeight - window.innerHeight;
                  //判断是否滚到底部加载更多
                  if(_top >= bottomAdr-10 && _point.clientY < startY){                 
                      if(bottomCall){
                        bottomCall();
                      }
                  }
                  // 到达顶端
                  if (_top === 0) {
                      // 阻止向下滑动
                      if (_point.clientY > startY) {
                          ev.preventDefault();
                      } else {
                          // 阻止冒泡
                          // 正常执行
                          ev.stopPropagation();
                      }
                  } else if (_top == bottomAdr) {
                      // 到达底部
                      // 阻止向上滑动
                      if (_point.clientY < startY) {
                          ev.preventDefault();
                      } else {
                          // 阻止冒泡
                          // 正常执行
                          ev.stopPropagation();
                      }
                  } else if (_top > 0 && _top < bottomAdr) {
                      ev.stopPropagation();
                  } else {
                      ev.preventDefault();
                  }
            }
        }        
        $(function(){
           $(window).scroll(function(){
              var topScroll =$(window).scrollTop();              
              if(topScroll > 0){
                  $('.scroll-top-box').css({"display":"flex"});
              } else { 
                  $('.scroll-top-box').hide();                                  
              }                            
           }) 
           $('.scroll-top-box').click(function(){
              $(window).scrollTop(0);
           })  
        })
    </script>
</head>
    
        <div class="headWrap">

	   <header class="head tone">
        <a class="ico menu Jmenu"></a>
        <h1 class="y-confirm-order-h1"><img src="/Static/Public/Wap/fms/images/logo.png" class="logo"></h1>
        <a href="<?php echo U('About/aboutUs');?>" class="about"></a>
  </header>
    <div class="headCont JheadCont">
        <div class="categoryList">
          <div class="isearch">
          <form method="post" action="<?php echo U('Search/search');?>" id="serform">
              <em class="sea"></em>
              <input type="hidden" name="type" value="product" />
              <input type="search" name="keyword"  placeholder="搜索商品" id="Jinput" style="border: none">
              <em class="close" id="JclearInput"></em>
          </form>
          </div>
          <!-- 商品分类 -->
          <div class="clsfiy">
            <?php $firstCat = getGoodsCategory(0); ?>
            <?php if(is_array($firstCat)): $i = 0; $__LIST__ = $firstCat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$first): $mod = ($i % 2 );++$i;?><div class="Jclslist">
                <p class="title"><?php echo ($first['category_name']); ?><em class="ico"></em></p>
                <?php $category = getGoodsCategory($first['id']); ?>
                <ul class="clsul JclsUl">
                  <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Goods/goods',array('catId'=>$vo['id']));?>"><li><?php echo ($vo['category_name']); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
              </div><?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
        </div>
    </div>
</div> 
<script type="text/javascript">
    var openbol = true;

    // 菜单
    $(".Jmenu").click(function(){
        if( openbol == true ){
          $(this).addClass("on");
          $(".JheadCont").fadeIn(200);
          $("body").css({"overflow":"hidden"});
          openbol = false;          
        }else{
          $(this).removeClass("on");
          resetFun();
          openbol = true;
        }
    })

    $("#Jinput").bind("input",function(){
      if( $(this).val().length > 0 ){
        $("#JclearInput").show();
      }else{
        $("#JclearInput").hide();
      }
    })

    $("#JclearInput").click(function(){
      $("#Jinput").val("");
    })

    function resetFun(){
      $(".JheadCont").fadeOut(200);
      $("body").css({"overflow":"auto"});
      $("#Jinput").val("");
      $("#JclearInput").hide();
    }
    
    $('.sea').click(function(){
      $("#serform").submit();
    })

    $(".Jclslist").on("click",".title",function(){
      $(this).toggleClass('on');
      $(this).siblings(".JclsUl").toggle();
    })
</script>
    
    
    <?php $goods = getGoods(index, 0); ?>
	<div class="main pd">
        <div class="indWrap">
            <?php $swiper = getSwiper(index, 2); ?>
<div class="banner">
  <div class="slideBox" id="focus">
    <div class="hd">
      <ul>
        <?php $__FOR_START_352893638__=0;$__FOR_END_352893638__=$swiper['imgCount'];for($i=$__FOR_START_352893638__;$i < $__FOR_END_352893638__;$i+=1){ ?><li></li><?php } ?>
      </ul>
    </div>
      <div class="bd">
        <ul>
    		<?php if(is_array($swiper['list'])): $i = 0; $__LIST__ = $swiper['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
    				<a href="<?php echo ($vo['url']); ?>">
		          <img src="<?php echo ($vo['image']); ?>">
		        </a>
    			</li><?php endforeach; endif; else: echo "" ;endif; ?>
    		</ul>                          	
    	</div>                                  
	</div>
</div>
            <div class="warp-box-item">
              <div class="item on"><a href="/Goods/goods/catId/2.html"><img src="/Static/Public/Wap/fms/images/p1.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/3.html"><img src="/Static/Public/Wap/fms/images/p2.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/4.html"><img src="/Static/Public/Wap/fms/images/p3.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/5.html"><img src="/Static/Public/Wap/fms/images/p4.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/6.html"><img src="/Static/Public/Wap/fms/images/p5.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/8.html"><img src="/Static/Public/Wap/fms/images/p6.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/15.html"><img src="/Static/Public/Wap/fms/images/p7.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/9.html"><img src="/Static/Public/Wap/fms/images/p8.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/10.html"><img src="/Static/Public/Wap/fms/images/p9.png"></a></div>
              <div class="item "><a href="/Goods/goods/catId/11.html"><img src="/Static/Public/Wap/fms/images/p10.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/12.html"><img src="/Static/Public/Wap/fms/images/p11.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/13.html"><img src="/Static/Public/Wap/fms/images/p12.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/14.html"><img src="/Static/Public/Wap/fms/images/p13.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/16.html"><img src="/Static/Public/Wap/fms/images/p14.png"></a></div>
              <div class="item"><a href="javascript:alert('敬请期待');"><img src="/Static/Public/Wap/fms/images/p15.png"></a></div>
              <div class="item on"><a href="javascript:;"><img src="/Static/Public/Wap/fms/images/p16.png"></a></div>
            </div>
            
            <div class="ind_cont">
                <div class="ind_goods" style="margin-bottom:20px">
                    <div class="area-title">
                        <p>精彩视频</p>
                        <a href="javascript:;">更多</a>
                    </div>
                    <?php $indexVideo = getSwiper(video, 4); ?>
                    <?php echo ($indexVideo); ?>
                </div>
                <div class="ind_goods">
                    <div class="area-title">
                        <p>最新产品</p>
                        <a href="<?php echo U('Product/product');?>">更多</a>
                    </div>
                    <ul class="indgoc" style="background: #fff;padding:0 15px">
                     <?php $goodsList = getGoods(index, 0); ?>
                     <?php if(is_array($goodsList['list'])): $i = 0; $__LIST__ = $goodsList['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a href="<?php echo U('Goods/goodsDetail',array('id' => $vo['id']));?>" class="ali">
                              <div class="imgbox">
                                  <img src="<?php echo ($vo['goods_image']); ?>">                  
                              </div>
                              <p class="name db-overflow"><?php echo ($vo['goods_name']); ?></p>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="ind_active">
                    <div class="area-title">
                        <p>精彩活动</p>
                        <a href="<?php echo U('Article/article');?>">更多</a>
                    </div>
                    <ul class="indcle" style="padding: 0 15px">
                    <?php $information = getInformation(index, 0); ?>
                    <?php if(is_array($information)): $i = 0; $__LIST__ = $information;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a href="<?php echo U('Article/articleDetail',array('id' => $vo['id']));?>" class="ali">
                                <div class="imgbox">
                                    <img src="<?php echo ($vo['image']); ?>">
                                </div>
                                <div class="artcle">
                                    <p class="title db-overflow"><strong class="db-overflow"><?php echo ($vo['title']); ?></strong></p>
                                    <!-- <p class="msg db-overflow"><?php echo ($vo['summary']); ?></p> -->
                                    <p class="time"><?php echo (date("Y/m/d",$vo['add_time'])); ?></p>
                                </div>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="copyright">版权所有 2018 索立得保留所有权利</div>
            </div>
        </div>            
    </div>


    
        <div class="scroll-top-box">
          <img src="/Static/Public/Wap/fms/images/top.png">
        </div>
        <!-- 底部固定菜单 -->
<footer class="y-ycar-foot">
  	<a href="<?php echo U('Index/index');?>">
    	<em class="icon on"><i></i>首页</em>
  	</a>
  	<a href="<?php echo U('Product/product');?>">
    	<em class="icon"><i></i>产品介绍</em>
  	</a>
  	<a href="<?php echo U('Article/article');?>">
    	<em class="icon"><i></i>精彩活动</em>
  	</a>
    <!-- <a href="<?php echo U('About/aboutUs');?>"> -->
  	<a href="<?php echo U('Bbs/Index/index');?>">
    	<em class="icon"><i></i>社区</em>
  	</a>      	
</footer>
    
    
    
	<script type="text/javascript" src="/Static/Public/Wap/fms/js/TouchSlide.1.0.js"></script>
	<script type="text/javascript">
		TouchSlide({ 
          slideCell:"#focus",
          titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
          mainCell:".bd ul", 
          effect:"left", 
          autoPlay:true,//自动播放
          autoPage:"<li></li>", //自动分页
          switchLoad:"_src" //切换加载，真实图片路径为"_src" 
        });	
	</script>

    <script type="text/javascript">
      var lastY;
      $("body").on('touchstart', function(event) {
          lastY = event.originalEvent.changedTouches[0].clientY;
      });
      $("body").on('touchmove', function(event) {
          var y = event.originalEvent.changedTouches[0].clientY;
          var st = $(this).scrollTop();
          if (y >= lastY && st <= 10) {
              lastY = y;
              if( event.preventDefault ){
                event.preventDefault();
              }else{
                event.returnValue = false;
              }
          }
          lastY = y;   
      });  
    </script>
</html>