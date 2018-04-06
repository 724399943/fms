<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo C('systemName');?>管理后台</title>
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/style.default.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/uniform.tp.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/pop.css" />
    <link rel="shortcut icon" href="/Static/Public/xcrozz.ico" type="image/x-icon" />
    
		<style type="text/css">
		.module_num span{color: #f60;margin-left: 10px}
		</style>
	
</head>

<body class="withvernav">
    <div class="bodywrapper">
        <div class="topheader">
            <div class="left" style="color:#fff">
                <ul>
                    <li>欢迎你 <?php echo (session('adminAccount')); ?></li>
                </ul>
            </div>
        </div>
        
        <div class="header">
        	<ul class="headermenu">
	<?php if(checkControllerAuth('Index')): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('Index'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('Index-systemSetting'));?>">
				<span class="icon icon-console"></span>
				<span class="tet">控制台</span>
			</a>
			<em></em>
		</li><?php endif; ?>
	
	<?php if(checkControllerAuth(array('Module'))): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('Module','Article','Field'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('Module-setModule'));?>">
				<span class="icon icon-class"></span>
				<span class="tet">模块管理</span>
			</a>
			<em></em>
		</li><?php endif; ?>

	<?php if(checkControllerAuth('Auth', 'Admin')): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('Auth', 'Admin'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('Admin-adminList', 'Admin-addAdmin', 'Auth-roleList', 'Auth-addRole'));?>">
				<span class="icon icon-admin"></span>
				<span class="tet">管理员中心</span>
			</a>
			<em></em>
		</li><?php endif; ?>

	<?php if(checkControllerAuth('Ad')): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('Ad'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('Ad-adGroupList', 'Ad-createQrCode'));?>">
				<span class="icon icon-content"></span>
				<span class="tet">广告管理</span>
			</a>
			<em></em>
		</li><?php endif; ?>
	
	<?php if(checkControllerAuth('Message')): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('Message'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('Message-messageList', 'Message-addMessage','Message-replyMessage'));?>">
				<span class="icon icon-shop"></span>
				<span class="tet">插件应用</span>
			</a>
			<em></em>
		</li><?php endif; ?>

	<?php if(checkControllerAuth('System')): ?><li class="<?php if(in_array(CONTROLLER_NAME, array('System'))): ?>current<?php endif; ?>">
			<a href="<?php echo getAuthUrl(array('System-setting'));?>">
				<span class="icon icon-system"></span>
				<span class="tet">系统设置</span>
			</a>
			<em></em>
		</li><?php endif; ?>

	<li>
		<a href="<?php echo U('Login/logout');?>">
			<span class="icon icon-exit"></span>
			<span class="tet">退出登录</span>
		</a>
		<em></em>
	</li>
</ul>
        </div>
        <div class="main-date-lr">
          <div class="vernav2 iconmenu">
            
	<ul>
	<?php if(checkControllerAuth(array('Index'))): ?><li <?php if(in_array(CONTROLLER_NAME, array('Index'))): echo chr(32);?>class="current"<?php endif; ?>>
			<a class="date-tit sys-tj" href="<?php echo getAuthUrl(array('Index-systemSetting', 'Index-statistics', 'Index-userAnalyze'));?>" class="addons">控制台</a>
			<ul class="Jcon-ctr">
				<?php if(checkActionAuth('Index-systemSetting')): ?><li class="<?php if(in_array(ACTION_NAME, array('systemSetting'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Index/systemSetting');?>">系统信息</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Index-wapSetting')): ?><li class="<?php if(in_array(ACTION_NAME, array('wapSetting'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Index/wapSetting');?>">WAP手机版设置</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Index-userAnalyze')): ?><li class="<?php if(in_array(ACTION_NAME, array('userAnalyze'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Index/userAnalyze');?>">用户分析</a>
					</li><?php endif; ?>
			</ul>
		</li><?php endif; ?>
</ul>

            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            
	<div class="pageheader">
	    <h1 class="pagetitle">系统信息</h1>
	</div>

	<div id="contentwrapper" class="contentwrapper">
        <form class="stdform stdform2" action="<?php echo U(System/setting);?>" method="post">
	         <ul class="hornav">
	            <li class="current" data-index="1"><a href="javascript:;">网站基本数据</a></li>
	         </ul>
	        <table>
	        	<tr class="module_num">
	        		<td>文章模型：<span><?php if(empty($return['articleMod'])): ?>0<?php else: echo ($return['articleMod']); endif; ?></span></td>
	        		<td>产品模型：<span><?php if(empty($return['productMod'])): ?>0<?php else: echo ($return['productMod']); endif; ?></span></td>
	        		<td>图库模型：<span><?php if(empty($return['photoMod'])): ?>0<?php else: echo ($return['photoMod']); endif; ?></span></td>
	        	</tr>
				<tr class="module_num">
	        		<td>下载模型：<span><?php if(empty($return['downloadMod'])): ?>0<?php else: echo ($return['downloadMod']); endif; ?></span></td>
	        		<td>招聘模型：<span><?php if(empty($return['hrMod'])): ?>0<?php else: echo ($return['hrMod']); endif; ?></span></td>
	        		<td>单页模型：<span><?php if(empty($return['aboutMod'])): ?>0<?php else: echo ($return['aboutMod']); endif; ?></span></td>
	        	</tr>
	        </table>
	         <ul class="hornav">
	            <li class="current" data-index="1"><a href="javascript:;">服务器信息</a></li>
	        </ul>
	        <table>
	        	<tr>
	        		<td>服务器IP：&nbsp;<span><?php echo $_SERVER['SERVER_ADDR'];?></span></td>
	        		<td>客户端IP：&nbsp;<span><?php echo $_SERVER['REMOTE_ADDR'];?></span></td>
	        		<td>操作系统：&nbsp;<span><?php echo PHP_OS;?></span></td>
	        	</tr>
	        	<tr>
	        		<td>web引擎：&nbsp;<span><?php echo $_SERVER['SERVER_SOFTWARE'];?></span></td>
	        		<td>PHP版本：&nbsp;<span><?php echo PHP_VERSION;?></span></td>
	        		<td>GD版本：&nbsp;<span><?php echo ($return['gd_info']); ?></span></td>
	        	</tr>
	        	<tr>
	        		<!-- <td>curl支持：&nbsp;<span></span></td> -->
	        		<!-- <td>iconv支持：&nbsp;<span>2</span></td> -->
	        		<!-- <td>url fopen：&nbsp;<span>2</span></td> -->
	        	</tr>
	        </table>
        </form>
		

        
	</div>

        </div>
        </div>
    </div>
    
    <script type="text/javascript" src="/Static/Public/Admin/js/plugins/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/Static/Public/Admin/js/pop.js"></script>
    <script type="text/javascript">
        function msgBox(title, content, time) {
            var _title = title ? title : '提示';
            var _time = time ? time : 1500;

            popwin(_title, content);
            setTimeout(function() {
                window.location.href = window.location.href;
            }, _time);
        }
        $(function(){
            // 页码点击
            $('.page-box .btn').click(function(){
                jump_page = $('input[name="p"]').val();
                length = $('.page').length;
                for (var i = 0; i < length; i++) {
                    var that = $('.page').eq(i);
                    if ( that.attr('href') ) {
                        href = that.attr('href');
                        href_page = that.text();
                        break;
                    }
                }
                jump = href.replace('/p/'+href_page, '/p/'+jump_page);
                window.location.href = jump;
            });
        })
        $('.close').click(function(){
            $('.maskWrap').hide()
        })
    </script>
    
	<script type="text/javascript" src="/Static/Public/Admin/js/plugins/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/Static/Public/Admin/js/ajaxfileupload.js"></script>
	<script type="text/javascript">

		$(document).on('change', '.f-upload', function() {
			var sign = $(this).attr('data-sign');
			$.ajaxFileUpload({
				url: "<?php echo U('System/photoSave');?>",
				secureuri: false,
				fileElementId: 'J' + sign + 'ToUpload',
				dataType: 'json',
				success: function (data, status) {
					if(typeof(data.error) != 'undefined') {
						if(data.error != '') {
							alert(data.error);
						} else {
							if (sign == 'logo') {
								$('#J' + sign + 'Pic').html('<div class="pic-wrap"><img style="width:160px; height:160px" src="' + data.src + '" /></div>');
							} else {
								$('#J' + sign + 'Pic').html('<div class="pic-wrap"><img style="width:auto; height:100% " src="' + data.src + '" /></div>');
							}
							$('#Jcover' + sign).val(data.src);
						}
					}
				},
				error: function (data, status, e) {
					alert(e);
				}
			});
		});

		$('.line-dete').on('click', '.del-pic', function() {
			$(this).parent().remove();
		});
	</script>

</body>
</html>