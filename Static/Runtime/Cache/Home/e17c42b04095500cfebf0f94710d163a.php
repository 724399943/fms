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
            <?php if(is_array($firstCat)): $i = 0; $__LIST__ = $firstCat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$first): $mod = ($i % 2 );++$i;?><p class="title"><?php echo ($first['category_name']); ?></p>
              <?php $category = getGoodsCategory($first['id']); ?>
              <ul class="clsul">
                <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Goods/goods',array('catId'=>$vo['id']));?>"><li><?php echo ($vo['category_name']); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
              </ul><?php endforeach; endif; else: echo "" ;endif; ?>
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
</script>
    
    
	<div class="main pd">
          <!-- <p style="padding:10px;text-align:center;">没有符合条件的信息</p> -->
            <ul class="article-cate" id="JcateTab">
                <li data-id="2" data-page="1" data-none="0" class="cate-active">FMS巡回游</li>
                <li data-id="74" data-page="0" data-none="0">公益活动</li>
                <li data-id="75" data-page="0" data-none="0">抽奖福利</li>
            </ul>
            <div class="artlist">
                <ul class="indcle Jartlist" style="display:none;">
                    
                </ul>
                <ul class="indcle Jartlist" style="display:none;">
                     
                </ul>
                <ul class="indcle Jartlist" style="display:none;">
                       
                </ul>    
                <p class="nomore Jnomore">我也是有底线的~</p>             
                <div class="pull-up JpullUp">
                    <img src="/Static/Public/Wap/fms/images/icon2.png">
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
    
    
    
    <script type="text/javascript">
    $(function(){
        getData();
        $(".Jartlist").eq(0).show();
    })
        var page = 1,catId = 2,is_load = true,listIndex = 0;

        function getData(){          
          $.ajax({
            url: '<?php echo U('Article/loadArticleList');?>',
            type: 'post',
            dataType: 'json',
            data: { catId:catId, page: page },
          })
          .done(function(data) {
            var url = "<?php echo U('Article/articleDetail');?>";
            var list ='';
            if(data['data']['list'].length > 0){
              for (var i = 0; i < data['data']['list'].length; i++) {
                var dlist = data['data']['list'][i];
                var time = UnixToDate(dlist['add_time'],0,false);
                list += '<li><a href="'+url+'?id='+dlist['id']+'" class="ali"><div class="imgbox"><img src="'+dlist['image']+'"></div><div class="artcle"><p class="title db-overflow"><strong>'+dlist['title']+'</strong></p><p class="time">'+time+'</p></div></a></li>';
              }
              is_load = true;
            }else{
                $("#JcateTab li").eq(listIndex).attr("data-none",1);
                $(".Jnomore").show();                
            }
            $('.Jartlist').eq(listIndex).append(list);            
            $(".JpullUp").hide();
          })       
        }        

        function loadMore(){
            if( is_load == true ){
                $(".JpullUp").show();
                page = $("#JcateTab li").eq(listIndex).attr("data-page");
                page++;
                getData();
                $("#JcateTab li").eq(listIndex).attr("data-page",page);
                is_load = false;
            }
        }
        isScroll(loadMore);
        // document.addEventListener('scroll',function(){
        //   var _top = document.documentElement.scrollTop || document.body.scrollTop;
        //   if(_top + window.innerHeight >= document.body.clientHeight){
        //     $(".JpullUp").show();
        //     page++;
        //     getData();
        //   }
        // })
         
        $("#JcateTab li").click(function(){
            listIndex = $(this).index();
            $("#JcateTab li").removeClass("cate-active");
            $(this).addClass("cate-active");
            catId = $(this).attr("data-id");
            page = $(this).attr("data-page");
            if( page == 0 ){
                page++;
                getData();                
                $(this).attr("data-page",page);
            }         
            if( $(this).attr("data-none") == 1 ){
                $(".Jnomore").show();    
            }else{
                $(".Jnomore").hide();
            }        
            $(".Jartlist").hide(); 
            $(".Jartlist").eq(listIndex).show();                  
        })
    </script>


</html>