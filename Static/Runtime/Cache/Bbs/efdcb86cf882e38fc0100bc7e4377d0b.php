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
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Bbs/css/style-fl.css" />

</head>
<body class="bg">
	
    <div class="lists-container pdb">
        <!--头部-->
<!-- <div class="header">
    <a class="back c-icon c-icon-back" id="Jback" href="javascript:;"></a>
    <span class="title">论坛</span>
    <span class="tools">
       
    </span>
</div> -->
<header class="head tone">
    <a class="back c-icon c-icon-back" id="Jback" href="javascript:;"></a>
    <h1 class="y-confirm-order-h1"><img src="/Static/Public/Wap/fms/images/logo.png" class="logo"></h1>
    <a href="/About/aboutUs" class="about"></a>
</header>
        <div class="control-banner c-card Jloading" id="JmoduleDetail"></div>
        <div class="subject c-card">
            <div class="subject-menu">
                <ul id="subject_menu_types" class="types">
                    <!-- <li class="default active" data-type="top">
                        <a href="javascript:;">默认</a>
                    </li> -->
                    <li class="lastpost active" data-type="new">
                        <a href="javascript:;">最新</a>
                    </li>
                    <li class="heat" data-type="hot">
                        <a href="javascript:;">热门</a>
                    </li>
                    <li class="digest" data-type="reco">
                        <a href="javascript:;">精华</a>
                    </li>
                </ul>
            </div>
            <div class="subject-tops">
                <!-- <div class="recommendPanel" id="Jtop" data-page="1" data-load="1"></div> -->
                <div class="recommendPanel" id="Jnew" data-page="1" data-load="0"></div>
                <div class="recommendPanel" style="display: none" id="Jhot" data-page="0" data-load="0"></div>
                <div class="recommendPanel" style="display: none" id="Jreco" data-page="0" data-load="0"></div>
            </div>
            <div id="more_subject_tops" class="c-area-more">
                <p>
                    <a>查看更多</a>
                </p>
            </div>
        </div>
        <div id="JpostList" class="notice-list c-card Jloading" data-page="1" data-load="0">
           
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

<script id="moduleDetail_tpl" type="text/html">
    <div class="pic">
        <img id="forum_icon" src="<%=moduleDetail['icon']%>" width="45px"></div>
    <div class="text">
        <a href="javascript:;" style="display:inline-block;">
            <p class="hot-sub-title"><%=moduleDetail['module_name']%></p>
            <p class="hot-sub-desc"><%=moduleDetail['introduction']%></p>
        </a>
    </div>
    <div class="btns">
        <a href="javascript:;" id="JmoduleCollect" class="c-btn-4 c-bgc-red"> <i class="c-icon c-icon-plus"></i>
            收藏本版
        </a>
        <%if( moduleDetail['is_post'] == '1'){%>
        <a href="/Bbs/Index/article_post/module/{$_GET['id']}" class="c-btn-4 c-bgc-blue"> <i class="c-icon c-icon-write"></i>
            我要发帖
        </a>
        <%}%>
    </div>
</script>
<script id="postList_tpl" type="text/html">
    <% for (var i = 0; i < articleList.length; i ++) {%>
    <div class="hot-warp-box">
        <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>">
            <div class="box">
                <img src="http://bt.hpingtai.com/Static/Public/Bbs/images/article_default.jpg" alt="">
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
            <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>" class="ping"><em></em> <%=articleList[i]['comment_number']%></a>
            <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>" class="liu"><em></em><%=articleList[i]['view_number']%></a>
        </div>
    </div>
    <%}%>
</script>
<script id="reco_tpl" type="text/html">
    <%for (var i = 0; i < articleList.length; i ++) {%>
        <a href="/Bbs/Index/article_detail/id/<%=articleList[i]['id']%>">
            <span class="tags c-tags hong">置顶</span>
            <span class="title" style="font-weight: bold;color: #f8a411;"><%=articleList[i]['article_name']%></span>
        </a>
    <%}%>
</script>
<script>
    var bt = baidu.template;
    var cur_type = 'new';

    // 模块详情
    $.ajax({
        url: "<?php echo U('Index/module');?>",
        type: 'POST',
        dataType: 'json',
        data: {id: <?php echo ($_GET['id']); ?>}
    })
    .done(function(data) {
        // console.log(data);
        var moduleDetail = bt('moduleDetail_tpl', data['data']);
        $('#JmoduleDetail').removeClass('Jloading');
        document.getElementById('JmoduleDetail').innerHTML = moduleDetail;
    });

    
    // 点赞
    $('#JpostList').on('click', '.Jlike', function() {
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

    // 获取文章
    function getArticle(page, module, type, callback) {
        $.ajax({
            url: "<?php echo U('Index/article_list');?>",
            type: 'POST',
            dataType: 'json',
            data: {
                page: page,
                module_id: module,
                type: type
            }
        })
        .done(function(data) {
            var html = bt('reco_tpl', data['data']);
            $('#J' + type).append(html);
            if (callback) {
                callback(data);
            }
        });
    }

    getArticle(0, <?php echo ($_GET['id']); ?>, 'new');
    $("#Jnew").attr("data-load",1);

    // 帖子列表
    $.ajax({
        url: "<?php echo U('Index/article_list');?>",
        type: 'POST',
        dataType: 'json',
        data: {
            module_id: <?php echo ($_GET['id']); ?>
        }
    })
    .done(function(data) {
        // console.log(data);
        var postList = bt('postList_tpl', data['data']);
        $('#JpostList').removeClass('Jloading');
        document.getElementById('JpostList').innerHTML = postList;
    });
    
    // 收藏版块
    $(function() {
        isScroll(function() {
            var page = $('#JpostList').attr('data-page');
            var load = $('#JpostList').attr('data-load');
            if (load == 0) {
                $('#JpostList').attr('data-load', 1);
                getArticle(page, <?php echo ($_GET['id']); ?>, '', function(data) {
                    if( data["data"]["articleList"].length > 0 ){
                        var postList = bt('postList_tpl', data['data']);
                        $('#JpostList').append(postList);
                        $('#JpostList').attr('data-page', ++ page);
                        $('#JpostList').attr('data-load', 0);
                    }else{
                        $('#JpostList').attr('data-load', 1);
                    }
                });
            }
        });

        $('#JmoduleDetail').on('click', '#JmoduleCollect', function() {
            $.ajax({
                url: "<?php echo U('Index/module_collect');?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: <?php echo ($_GET['id']); ?>
                }
            })
            .done(function(data) {
                console.log(data);
                if (data['status'] == 200000) {
                    automsgbox('收藏成功！');
                } else {
                    automsgbox(data['message']);
                }
            });
        });

        $('#subject_menu_types li').click(function() {
            var type = $(this).attr('data-type');
            var load = $('#J' + type).attr('data-load');
            // var page = $(this).data('page');
            var _that = $(this);
            if (load == 0) {
                getArticle(0, <?php echo ($_GET['id']); ?>, type, function() {
                    _that.addClass('active').siblings().removeClass('active');
                    $('#J' + type).attr('data-load', 1);
                    $('.recommendPanel').hide();
                    $('#J' + type).show();
                });
            } else {
                _that.addClass('active').siblings().removeClass('active');
                $('.recommendPanel').hide();
                $('#J' + type).show();
            }
            cur_type = type;
        });

        $('#more_subject_tops').click(function() {
            var page = $('#J' + cur_type).attr('data-page');
            getArticle(page, cur_type, function() {

            });
        });
    });
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