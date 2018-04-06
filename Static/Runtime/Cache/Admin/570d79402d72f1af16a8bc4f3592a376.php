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
	<?php if(checkActionAuth('System-setting')): ?><li <?php if(checkActionAuth(array('System-setting', 'System-createQrCode'))): echo chr(32);?>class="current"<?php endif; ?>>
			<a class="date-tit sys-tj" href="<?php echo getAuthUrl(array('System-setting', 'System-createQrCode'));?>" class="addons">系统设置</a>
			<ul class="Jcon-ctr">
				<?php if(checkActionAuth('System-setting')): ?><li class="<?php if(in_array(ACTION_NAME, array('setting'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('System/setting');?>">站点设置</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('System-seoSetting')): ?><li class="<?php if(in_array(ACTION_NAME, array('seoSetting','editSeo'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('System/seoSetting');?>">seo设置</a>
					</li><?php endif; ?>
			</ul>
		</li><?php endif; ?>
</ul>

            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            
	<div class="pageheader">
	    <h1 class="pagetitle">站点设置</h1>
	</div>

	<div id="contentwrapper" class="contentwrapper">

        <ul class="hornav">
            <li class="current" data-index="1"><a href="javascript:;">站点信息设置</a></li>
        </ul>

        <form class="stdform stdform2" action="<?php echo U(System/setting);?>" method="post">
	        <div id="contentwrapper" class="contentwrapper">
	            <div class="line-dete">
					<label>网站链接</label>
	                <span class="field">
	                	<input type="text" name="webSite" class="smallinput" value="<?php echo C('webSite');?>">
	                </span>
	            </div>
	            <div class="line-dete">
					<label>系统名称</label>
	                <span class="field">
	                	<input type="text" name="systemName" class="smallinput" value="<?php echo C('systemName');?>">
	                </span>
	            </div>
	            <div class="line-dete">
					<label>备案号码</label>
	                <span class="field">
	                	<input type="text" name="beian" class="smallinput" value="<?php echo C('beian');?>"><span style="color: #999">（网站备案信息将显示在页面底部）</span>
	                </span>
	            </div>
	            <div class="line-dete">
					<label>系统LOGO</label>
	                <span class="field">
	                	<input type="hidden" id="Jcoverlogo" name="logo" value="<?php echo C('logo');?>">
						<div id="JlogoPic" class="m-photo-list">
							<div class="pic-wrap">
							<img style="width:160px; height:160px;" src="<?php echo C('logo');?>" /></div>
						</div>
						<div class="upload-wrap">
			        		<input type="file" id="JlogoToUpload" name="JlogoToUpload" data-sign="logo" class="f-upload" />
			        	</div>
	                </span>
	            </div>
	            <div class="line-dete">
					<label>主题模板</label>
	                <span class="field">
	                	<input type="text" name="templet" value="<?php echo C('templet');?>" />
	                	<!-- <input type="hidden" id="Jcoverlogo" name="logo" value="<?php echo C('logo');?>">
						<div id="JlogoPic" class="m-photo-list">
							<div class="pic-wrap">
							<img style="width:160px; height:160px;" src="<?php echo C('logo');?>" /></div>
						</div>
						<div class="upload-wrap">
			        		<input type="file" id="JlogoToUpload" name="JlogoToUpload" data-sign="logo" class="f-upload" />
			        	</div> -->
	                </span>
	            </div>
	            <div class="line-dete">
					<label>是否开启手机版开启版本</label>
	                <span class="field">
	                	<input name="openWap" type="radio" value="1" <?php if(C('openWap') == '1'): ?>checked<?php endif; ?>/>是&nbsp;&nbsp;
	                	<input name="openWap" type="radio" value="0" <?php if(C('openWap') == '0'): ?>checked<?php endif; ?>/>否&nbsp;&nbsp;
	                </span>
	            </div>
	            <div class="line-dete">
					<label>是否开启PC版</label>
	                <span class="field">
	                	<input name="openWeb" type="radio" value="1" <?php if(C('openWeb') == '1'): ?>checked<?php endif; ?>/>是&nbsp;&nbsp;
	                	<input name="openWeb" type="radio" value="0" <?php if(C('openWeb') == '0'): ?>checked<?php endif; ?>/>否&nbsp;&nbsp;
	                </span>
	            </div>
		        <input type="submit" class="big-btn stdbtn" value="更新">
	        </div>
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