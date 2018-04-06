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
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Bbs/css/style-viewthread.css" />
    <link href="/Static/Public/Bbs/css/reset.css" rel="stylesheet">

</head>
<body class="bg">
	
    <div class="viewthread-container pdb">
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
        <div class="add-comment-container">
            <div class="add-comment">
                <form name="respone_form" id="Jform">
                    <div id="reply_form" class="form" style="position:relative">
                        <span class="show-face c-icon c-icon-face Jface"></span>
                        <span class="show-pic c-icon c-icon-pic"></span>
                        <!-- <span class="show-demo c-icon c-icon-demo Jquick"></span> -->
                        <textarea id="reply_input" class="reply-input" name="content" placeholder="我也来说一句吧" data-uid="0" data-allowreply=""></textarea>
                        <input type="hidden" name="article_id" value="<?php echo ($_GET['id']); ?>">
                        <span id="reply_btn" class="reply-btn">回复</span>
                    </div>
                    <div class="board upload-pics" id="JuploadImg" style="display: none;">
                        <div class="pics-container">
                            <div class="add-list">
                                <div id="pic_list" class="pic-list"></div>
                                <span class="add-btn">
                                    <input id="upload_pics" type="file" name="Filedata">
                                </span>
                            </div>
                        </div>
                        <div class="tips">
                            <p class="page">
                                <span id="pic_count">0</span>
                                /10
                            </p>
                            <!-- <p>图片可以拖动排序</p> -->
                        </div>
                    </div>
                </form>
            <div class="board demo-reply" id="Jquick" style="display: none;">
                <ul class="list" id="JquickList">
                    <!-- <li class="item">
                        <span class="long">前排占个座#17,万一火了呢#89</span>
                        <span class="short">抢个前排</span>
                    </li>
                    <li class="item">
                        <span class="long">楼猪说的真好#81给楼猪点赞啦#44</span>
                        <span class="short">好文要顶</span>
                    </li>
                    <li class="item">
                        <span class="long">虽然不知道楼猪在说什么#24，但是看起来好厉害的样子！</span>
                        <span class="short">不明觉厉</span>
                    </li>
                    <li class="item">
                        <span class="long">人生已经如此艰难，有些事情就不要拆穿#135</span>
                        <span class="short">人艰不拆</span>
                    </li>
                    <li class="item">
                        <span class="long">小手一抖，酱油到手，积分我有#163</span>
                        <span class="short">小手一抖</span>
                    </li> -->
                </ul>
            </div>
        </div>
        <div class="add-comment-mask" style="display: none;"></div>
    </div>
    <div class="main-scroll">
        <div class="view-content c-card Jloading" id="JarticleDetail">
            
        </div>
        <div id="thread_list" class="thread-list c-card Jloading">
            <!--<div class="nav">
                <a class="current">默认</a>
                <a>最新</a>
            </div>-->
        </div>
        <div class="loading-banner c-card" id="Jtips" style="display:none">
            <div id="loading" class="loading" style="display: none;">
                <i class="c-icon c-icon-loading"></i>
            </div>
            <div id="empty-tips" class="empty-tips" style="">没有数据了哦~</div>
        </div>

        <div id="refresh_btn" class="refresh-btn" style="display: none;">
            <i class="c-icon c-icon-refresh"></i>
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

<script src="/Static/Public/Bbs/js/jquery.qqFace.js"></script>
<script src="/Static/Public/Common/js/ajaxfileupload.js"></script>

<script id="articleDetail_tpl" type="text/html">
<p class="c-simple-title">
    <%=data['articleDetail']['article_name']%>
</p>
<div class="c-author-info">
    <div class="pic">
        <img src="<%=data['authorDetail']['headimgurl']%>"></div>
    <div class="text">
        <a class="author" href=""><%=data['authorDetail']['nickname']%></a>
        <p class="time">
            <% var date = new Date(data['articleDetail']['add_time'] * 1000); var addTime = date.pattern('yyyy-MM-dd HH:mm:ss');%>
            <%=addTime%>
        </p>
    </div>
</div>
<div class="content">
    <div class="photo-list">
        <%if (data['articleDetail']['photos'].length) {%>
            <%for (var i = 0; i < data['articleDetail']['photos'].length; i ++) {%>
            <img src="<%=data['articleDetail']['photos'][i]['url']%>" alt="" width="100%">
            <%}%>
        <%}%>
    </div>
    <div class="inner"><%:=data['articleDetail']['article_content']%></div>

    <div class="control">
        <a id="Jshare" class="item share">
            <i class="c-icon c-icon-share"></i>
            分享
        </a>
        <a class="item collect" id="Jcollect" href="javascript:;">
            <i class="c-icon c-icon-collect"></i>
            收藏
        </a>
        <a id="Jlike" class="item like">
            <i class="c-icon c-icon-like"></i>
            点赞
            <span class="count"><%=data['articleDetail']['like_number']%></span>
        </a>
    </div>
</div>
<div class="view-more" style="display:none">
    <span class="view-btn">加载全文</span>
</div>
</script>
<script id="respone_tpl" type="text/html">
    <% for (var i = 0; i < commentList.length; i ++) {%>
    <div id="pid50907560" class="thread-item">
        <div class="c-author-info">
            <div class="pic">
                <img src="<%=commentList[i]['headimgurl']%>"></div>
            <div class="text">
                <p class="author"><%=commentList[i]['nickname']%></p>
                <p class="time">
                    <% var date = new Date(commentList[i]['add_time'] * 1000); var addTime = date.pattern('yyyy-MM-dd HH:mm:ss')%>
                    <span><%=addTime%></span>
                    <!-- <a class="only-author" href="forum.php?mod=viewthread&amp;tid=2977449&amp;page=1&amp;authorid=2059709">只看该作者</a> -->
                </p>
            </div>
            <div class="like-comment">
                <!-- <p class="pos">沙发</p> -->
                <p class="num">
                    <a class="cmt cmt-reply" data-pid="50907560" data-page="1">
                        <i class="c-icon c-icon-cmt-s"></i>
                    </a>
                </p>
            </div>
        </div>
        <div class="item-content">
            <div class="photo-list">
                <%if (commentList[i]['photos'].length) {%>
                    <%for (var j = 0; j < commentList[i]['photos'].length; j ++) {%>
                    <img src="<%=commentList[i]['photos'][j]['url']%>" alt="" width="200">
                    <%}%>
                <%}%>
            </div>
            <div class="reply">
                <%=commentList[i]['content']%>
            </div>
        </div>
    </div>
    <%}%>
</script>
<script>
    var bt = baidu.template;

    function replace_em(str){
        // str = str.replace(/\</g,'&lt;');
        // str = str.replace(/\>/g,'&gt;');
        // str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,'<img src="/Static/Public/Bbs/arclist/$1.gif" border="0" />');
        return str;
    }

    $('.Jface').qqFace({
        id : 'facebox', 
        assign:'reply_input', 
        path:'/Static/Public/Bbs/arclist/' //表情存放的路径

    });
    
    /*获取文章详情*/
    $.ajax({
        url: "<?php echo U('Index/article_detail');?>",
        type: 'POST',
        dataType: 'json',
        data: {id: <?php echo ($_GET['id']); ?>}
    })
    .done(function(data) {
        console.log(data);
        var articlesHtml = bt('articleDetail_tpl', data['data']);
        $('#JarticleDetail').removeClass('Jloading');
        articlesHtml = replace_em(articlesHtml);

        document.getElementById('JarticleDetail').innerHTML = articlesHtml;
        if (data['data']['data']['quickrespone'].length) {
            var quickrespone = data['data']['data']['quickrespone'];
            var quickStr = [];
            for (var i in quickrespone) {
                quickStr.push('<li class="item"> <span class="long JquickItem">' + quickrespone[i]['content'] + '</span> <span class="short">好文要顶</span> </li>');
            }
            $('#JquickList').html(quickStr.join(''));
        }
    });

    /*获取评论数据*/
    $.ajax({
        url: "<?php echo U('Index/article_comment');?>",
        type: 'POST',
        dataType: 'json',
        data: {id: <?php echo ($_GET['id']); ?>}
    })
    .done(function(data) {
        console.log(data);
        if (data['data']['commentList'].length) {
            var commentHtml = bt('respone_tpl', data['data']);
            $('#thread_list').removeClass('Jloading');
            document.getElementById('thread_list').innerHTML = commentHtml;
        } else {
            $('#thread_list').removeClass('Jloading');
            $('#Jtips').show();
        }

        //显示的地方替换
        $('.reply').each(function(index, el) {
            var str = $(this).html();
           $(this).html(replace_em(str));
        });
    });

    /*阅读量*/
    $.ajax({
        url: "<?php echo U('Index/article_view');?>",
        type: 'POST',
        dataType: 'json',
        data: {
            id: <?php echo ($_GET['id']); ?>
        }
    })
    .done(function(data) {
        console.log(data);
    });

    $(function() {
        // 收藏
        $('#JarticleDetail').on('click', '#Jcollect', function() {
            $.ajax({
                url: "<?php echo U('Index/article_collect');?>",
                type: 'POST',
                dataType: 'json',
                data: {id: <?php echo ($_GET['id']); ?>}
            })
            .done(function(data) {
                if (data['status'] == 200000) {
                    automsgbox('收藏成功！');
                } else {
                    automsgbox(data['message']);
                }
            });
        });

        // 点赞
        $('#JarticleDetail').on('click', '#Jlike', function() {
            $.ajax({
                url: "<?php echo U('Index/article_like');?>",
                type: 'POST',
                dataType: 'json',
                data: {id: <?php echo ($_GET['id']); ?>}
            })
            .done(function(data) {
                if (data['status'] == 200000) {
                    automsgbox('点赞成功！');
                } else {
                    automsgbox(data['message']);
                }
            });
        });

        // 快捷回复
        $('.Jquick').click(function() {
            $('#Jquick').toggle();
        });

        // 提交回复
        $('#reply_btn').click(function() {
            $.ajax({
                url: "<?php echo U('Index/article_response');?>",
                type: 'POST',
                dataType: 'json',
                data: $('#Jform').serialize()
            })
            .done(function(data) {
                // console.log(data);
                if (data['status'] == 200000) {
                    automsgbox('评论成功！');
                } else {
                    automsgbox(data['message']);
                }
            });
        });

        $('.c-icon-pic').click(function() {
            $('#JuploadImg').toggle();
        });

        // 上传图片
        $(document).on('change', '#upload_pics', function() {
            var uploadCount = parseInt($('#pic_count').text());
            if (uploadCount >= 10) return;

            $.ajaxFileUpload({
                url: "<?php echo U('Index/uploadImg');?>",
                secureuri: false,
                fileElementId: 'upload_pics',
                dataType: 'json',
                success: function (data, status) {
                    if(typeof(data.error) != 'undefined') {
                        if(data.error != '') {
                            alert(data.error);
                        } else {
                            $('#pic_list').append('<div class="pic-item"><img src="' + data.url + '"><span class="close"></span><input type="hidden" name="photos[]" value="' + data.url + '"></div>');
                        }

                        $('#pic_count').text(++ uploadCount);
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
        });

        // 删除上传图片
        $("body").on('click', '.close', function() {
            $(this).parent().remove();
            var uploadCount = parseInt($('#pic_count').text());
            $('#pic_count').text(-- uploadCount);
        });

        $("#JquickList").on('click', '.JquickItem', function() {
            $('#reply_input').val($(this).text());
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