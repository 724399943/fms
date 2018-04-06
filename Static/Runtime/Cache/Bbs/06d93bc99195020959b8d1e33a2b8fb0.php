<?php if (!defined('THINK_PATH')) exit(); echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" style="font-size: 36px;">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="" />
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta content="telephone=no" name="format-detection" />
    <link href="/Static/Public/Bbs/css/base.css" rel="stylesheet" type="text/css"/>
    <script src="/Static/Public/Common/js/jquery-1.8.3.min.js"></script>
    <style>
    .Jloading{position: relative;min-height: 100px}
	.Jloading:after{content:'';width: 35px;height: 35px;background: url('/Static/Public/Bbs/images/loading.gif') 0 0 no-repeat;position:absolute;left:50%;margin-left:-18px;top: 10px}
    </style>
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Wap/fms/css/fmsStyle.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Bbs/css/style.css" />
    <style>
    .nophoto {margin-right:10px}
    .nophoto img {width: 2.133333rem; height: 1.6rem;}
.hot-recommend .hot-rec-content .hot-rec-item .hot-rec-sub-desc {overflow: hidden; -o-text-overflow: ellipsis; text-overflow: ellipsis; white-space: nowrap; max-width: 6rem;}
.container .hot-recommend .hot-rec-content .hot-rec-item .hot-rec-sub-title{overflow: hidden;-o-text-overflow: ellipsis;text-overflow: ellipsis;white-space: nowrap;max-width: 7rem;}
    

    </style>

</head>
<body class="bg">
	

    <div class="container pdb">
        <header class="head tone">
            <a class="ico menu Jmenu"></a>
            <h1 class="y-confirm-order-h1"><img src="/Static/Public/Wap/fms/images/logo.png" class="logo"></h1>
            <a href="/About/aboutUs" class="about"></a>
        </header>
        <div class="headCont JheadCont">
            <div class="categoryList">
              <div class="isearch">
              <form method="post" action="/Search/search" id="serform">
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
                        <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Home/Goods/goods',array('catId'=>$vo['id']));?>"><li><?php echo ($vo['category_name']); ?></li></a>
                         <!-- <a href="<?php echo U('Goods/goods',array('catId'=>$vo['id']));?>"><li><?php echo ($vo['category_name']); ?></li></a> --><?php endforeach; endif; else: echo "" ;endif; ?>
                      </ul>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
        </div>
        <!--入口-->
        <!-- <div class="entry-area">
            <?php $bbs_index_ad_group = getAdGroup("bbs_index_ad_group"); ?>
            <?php if(is_array($bbs_index_ad_group)): $i = 0; $__LIST__ = $bbs_index_ad_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bbs_main_cate): $mod = ($i % 2 );++$i;?><a class="entry-item" href="<?php echo ($bbs_main_cate['url']); ?>">
                    <span class="entry-icon">
                        <img src="<?php echo ($bbs_main_cate['image']); ?>" alt=""/>
                    </span>
                    <span><?php echo ($bbs_main_cate['ad_name']); ?></span>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div> -->
        <!--论坛版块-->
        <div class="hot-area">
            <div class="area-title">
                <p>论坛版块</p>
            </div>
            <div id="JmoduleList" class="Jloading">
                
            </div>
            <!-- <div class="area-more">
                <p>
                    <a>查看更多版块</a>
                </p>
            </div> -->
        </div>

        <div class="hot-recommend">
            <div class="area-title">
                <p>最新消息</p>
            </div>
            <div class="hot-rec-content Jloading" id="JarticleList"></div>
        </div>
        
        <div class="post-alertbox">
            <div class="post-menu" id="JpostMenu">
                <!-- <div class="pos-box">
                    <div class="pos-menu-row"><a href="/Bbs/Index/article_post/module/2"><img src=""></a></div> 
                    <p class="pt">综合讨论</p> 
                </div>
                <div class="pos-box">
                    <div class="pos-menu-row"><a href="/Bbs/Index/article_post/module/3"><img src="/Static/Public/Wap/fms/images/post-icon2.png"></a></div>  
                    <p class="pt">建议反馈</p>
                </div>
                <div class="pos-box">
                    <div class="pos-menu-row"><a href="/Bbs/Index/article_post/module/4"><img src="/Static/Public/Wap/fms/images/post-icon2.png"></a></div>
                    <p class="pt">活动专区</p>
                </div>
                <div class="pos-box">
                    <div class="pos-menu-row"><a href="javascript:;"></a></div>
                </div> -->
            </div>            
        </div>
        <div class="fix-box post Jfixpost">
            <img src="/Static/Public/Wap/fms/images/post.png">
        </div>
        <div class="fix-box top Jfixtop">
            <img src="/Static/Public/Wap/fms/images/top.png">
        </div>
    </div>

	        
        <!-- <div class="footer">
    <p>
        <a href="https://app.16163.com/">APP下载</a>
        <span>|</span>
        <a href="forum.php?mobile=no">电脑版</a>
    </p>
</div> -->
<!-- 底部固定菜单 -->
<footer class="y-ycar-foot">
  	<a href="/Index/index">
    	<em class="icon on"><i></i>首页</em>
  	</a>
  	<a href="/Product/product">
    	<em class="icon"><i></i>产品介绍</em>
  	</a>
  	<a href="/Article/article">
    	<em class="icon"><i></i>精彩活动</em>
  	</a>
    <!-- <a href="<?php echo U('About/aboutUs');?>"> -->
  	<a href="/Bbs/Index/index">
    	<em class="icon"><i></i>社区</em>
  	</a>      	
</footer>
    
</body>
<script src="/Static/Public/Common/js/baiduTemplate.js"></script>
<script src="/Static/Public/Bbs/js/common.js"></script>

<script id="articleList_tpl" type="text/html">
<% for (var i = 0; i < articleList.length; i ++) {%>
<div class="hot-warp-box">
    <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>">
        <div class="box">
            <img src="<%=articleList[i]['headimgurl']%>" alt="">
            <div class="txt"><span class="n"><%=articleList[i]['nickname']%></span>
             <% var date = new Date(articleList[i]['add_time'] * 1000); var addTime = date.pattern('yyyy-MM-dd HH:mm:ss')%>
            <p><%=addTime%></p></div>
        </div>
        <div class="tit"><%=articleList[i]['article_name']%></div>
        <div class="dec"><%=articleList[i]['article_content']%></div>
        <!-- <div class="pic">
            <img src="http://bt.hpingtai.com/Static/Public/Bbs/images/article_default.jpg" alt="">
            <img src="http://bt.hpingtai.com/Static/Public/Bbs/images/article_default.jpg" alt="">
            <img src="http://bt.hpingtai.com/Static/Public/Bbs/images/article_default.jpg" alt="">
        </div> -->
    </a>
    <div class="ctr-box">
        <span class="zan Jlike" data-id="<%=articleList[i]['id']%>"><em></em><%=articleList[i]['like_number']%></span>
        <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>" class="ping"><em></em><%=articleList[i]['comment_number']%></a>
        <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>" class="liu"><em></em><%=articleList[i]['view_number']%></a>
    </div>
</div>
<%}%>
</script>
<script id="moduleList_tpl" type="text/html">
    <% for (var i = 0; i < moduleList.length; i ++) {%>
    <% if (i % 2 == 0) {%>
    <div class="hot-content">
    <%}%>                  
        <a class="hot-content-item " href="/Bbs/Index/module/id/<%=moduleList[i]['id']%>">
            <div class="pic">
                <img src="<%=moduleList[i]['icon']%>">
            </div>
            <div class="text">
                <p class="hot-sub-title"><%=moduleList[i]['module_name']%></p>
                <p class="hot-sub-desc"><%=moduleList[i]['introduction']%></p>
            </div>
        </a>
    <% if (i % 2 == 1) {%>
    </div>
    <%}%>
    <%}%>
</script>
<script id="moduleAlert_tpl" type="text/html">
    <% for (var i = 1; i < moduleList.length; i ++) {%>
    <div class="pos-box">
        <div class="pos-menu-row"><a href="/Bbs/Index/article_post/module/<%=moduleList[i]['id']%>"><img src="<%=moduleList[i]['icon']%>"></a></div>
        <p class="pt"><%=moduleList[i]['module_name']%></p>
    </div>
    <%}%>
    <div class="pos-box">
        <div class="pos-menu-row"><a href="javascript:;"></a></div>
    </div>
</script>
<!-- 导航代码 -->
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
<!-- 导航代码 -->
<script>
    var bt = baidu.template;

    $.ajax({
        url: "<?php echo U('Index/article_list');?>",
        type: 'POST',
        dataType: 'json',
        data: {
            page: 0,
            type: 'new'
        }
    })
    .done(function(data) {
        // console.log(data);
        var articlesHtml = bt('articleList_tpl', data['data']);
        $('#JarticleList').removeClass('Jloading');
        document.getElementById('JarticleList').innerHTML = articlesHtml;
    });

    $.ajax({
        url: "<?php echo U('Index/module_list');?>",
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data) {
        console.log(data);
        var moduleHtml = bt('moduleList_tpl', data['data']);
        $('#JmoduleList').removeClass('Jloading');
        document.getElementById('JmoduleList').innerHTML = moduleHtml;
        var moduleMenu = bt("moduleAlert_tpl",data['data']);
        document.getElementById('JpostMenu').innerHTML = moduleMenu;
    });
        // 点赞
    $('#JarticleList').on('click', '.Jlike', function() {
        var id = $(this).data('id')
        var that =$(this);
        $.ajax({
            url: "<?php echo U('Index/article_like');?>",
            type: 'POST',
            dataType: 'json',
            data: {id: id}
        })
        .done(function(data) {
            if (data['status'] == 200000) {
                automsgbox('点赞成功！');
                var num = parseInt(that.text())+1;
                that.html('<em></em>'+num);
            } else {
                automsgbox(data['message']);
            }
        });
    });

    $(function() {
        // $('#Jback').hide();
        $(window).scroll(function(){
          var topScroll =$(window).scrollTop();
          if(topScroll > 0){
              $('.Jfixtop').css({"display":"flex"});
          } else { 
              $('.Jfixtop').hide();
          }

        }) 
        $('.Jfixtop').click(function(){
          $(window).scrollTop(0);
        })
        // 发帖
        $(".Jfixpost").click(function(event){
            event.stopPropagation();
            event.preventDefault();
            $(".post-alertbox").fadeIn(200);
        })
        $(document).on('click',function(){
            $('.post-alertbox').hide();
        });        
    });    
    $(".Jclslist").on("click",".title",function(){
        $(this).toggleClass('on');
        $(this).siblings(".JclsUl").toggle();
    })
</script>


	<!-- 提示信息 -->
	<div class="mengban">
		<!-- 判断提示只有确定 -->
		<div class="msg-main-box2 JmsgBox-confirm">
			<div class="detail-wrap">
				<p class="detail-txt"></p>
			</div>
			<div class="btn">
				<a href="javascript:;" class="tips-btn1 JsureBtn">确定</a>
			</div>

		</div>

		<!--    判断提示 -->
		<div class="msg-main-box2 JmsgBox2">
			<div class="detail-wrap">
				<p class="detail-txt"></p>
			</div>
			<div class="btn">
				<a href="javascript:;" class="tips-btn1 JsureBtn">确定</a>
				<a href="javascript:;" class="tips-btn1 JcancelBtn">取消</a>
			</div>

		</div>

		<!-- 自动消失 -->
		<div class="automsg-main-box JmsgBox1" style="display: none;">
			<div class="tit">提示</div>
			<p class="detail-txt">加入购物车成功</p>
		</div>
	</div>

<script type="text/javascript">
		/*有确认按钮*/
		function msgbox(txt, callback) {
			var mengban = $('.mengban');
			var tipBox2 = $('.JmsgBox-confirm');
			$('.JmsgBox-confirm .detail-txt').html(txt);
			mengban.show();
			tipBox2.show();
			$('.JmsgBox-confirm .JsureBtn').click(function() {
				mengban.hide();
				tipBox2.hide();
				if (callback) {
					callback();
				}
			});
		}

		/*有取消和确认按钮*/
		function msgbox2(txt, callback) {
			var mengban = $('.mengban');
			var tipBox2 = $('.JmsgBox2');
			$('.JmsgBox2 .detail-txt').html(txt);
			mengban.show();
			tipBox2.show();
			var ctr = 1;
			$('.JmsgBox2 .JsureBtn').click(function() {
				mengban.hide();
				tipBox2.hide();
				if (callback && ctr == 1) {
					ctr = 0;
					callback();
				}
			});
			$('.JcancelBtn').click(function() {
				mengban.hide();
				tipBox2.hide();
				ctr = 0;
			});
		}

		/*自动消失*/
		function automsgbox(txt, callback) {
			var mengban = $('.mengban');
			var tipBox1 = $('.automsg-main-box');
			$('.automsg-main-box .detail-txt').html(txt);
			mengban.show();
			tipBox1.show();
			var t = setTimeout(function(){
				mengban.hide();
				tipBox1.hide();
				if (callback) {
					callback();
				}
			}, 2000);
		}

	$('#Jback').click(function() {
		window.history.back();
	});

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
</script>
</html>