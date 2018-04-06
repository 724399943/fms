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
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Bbs/css/style-post.css" />

</head>
<body class="bg">
	
    <div class="post-container pdb">
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

        <form name="postform" id="postform">
            <div class="post-title ">
                <input class="subject" type="text" name="article_name" size="30" placeholder="请填写标题" value="">
            </div>
            <div class="post-tools">
                <div class="form">
                    <span class="show-face c-icon c-icon-face Jface"></span>
                    <span class="show-pic c-icon c-icon-pic"></span>
                </div>
                <div class="post-tools-container">
                    <div class="board upload-pics" id="JuploadImg" style="display: none;">
                        <div class="pics-container">
                            <div class="add-list">
                                <div id="pic_list" class="pic-list attach-list"></div>
                                <!-- <form id="pic_form" action="" method="post"> -->
                                    <span class="add-btn">
                                        <input id="upload_pics" type="file" name="Filedata" accept="image/*">
                                    </span>
                                <!-- </form> -->
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
                </div>
            </div>
            <div class="post-content">
                <textarea class="content post-content-input" name="article_content" id="reply_input" placeholder="请输入内容"></textarea>
                <input type="hidden" name="module_id" value="<?php echo ($_GET['module']); ?>">
            </div>
            <div class="post-action">
                <span id="post_submit" class="submit">发布</span>
                <span id="post_save" class="save">保存</span>
            </div>
        </form>
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
<script>
    $('.Jface').qqFace({
        id : 'facebox', 
        assign:'reply_input', 
        path:'/Static/Public/Bbs/arclist/' //表情存放的路径
    });

    $('#post_submit').click(function() {
        $.ajax({
            url: "<?php echo U('Index/article_post');?>",
            type: 'POST',
            dataType: 'json',
            data: $('#postform').serialize()
        })
        .done(function(data) {
            console.log(data);
            if (data['status'] == 200000) {
                automsgbox('发布成功！');
                window.location.href = "/Bbs/Index/module/id/<?php echo ($_GET['module']); ?>";
            } else {
                automsgbox(data['message']);
            }
        });
    });

    $(function() {
        $('.c-icon-pic').click(function() {
            $('#JuploadImg').toggle();
        });

        // 上传图片
        $(document).on('change', '#upload_pics', function() {
            $.ajaxFileUpload({
                url: "<?php echo U('Index/uploadPostImg');?>",
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
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
        });

        // 删除上传图片
        $(document).on('click', '.close', function() {
            $(this).parent().remove();
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