<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WAP手机版设置</title>
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
        <h1 class="pagetitle">WAP手机版设置</h1>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <form class="stdform stdform2" action="<?php echo U('Index/wapSetting');?>" method="post" id="question-form">

            <div class="line-dete">
                <label>WAP手机网站名称</label>
                <span class="field">
                    <input type="text" name="wapName" value="<?php echo C('wapName');?>" />
                </span>
            </div>

            <div class="line-dete">
                <label>WAP模板风格</label>
                <span class="field">
                    <input type="text" name="wapTemplet" value="<?php echo C('wapTemplet');?>" />
                </span>
            </div>

          <!--   <div class="line-dete">
                <label>文章模块列表页</label>
                <span class="field">
                    每页显示&nbsp;&nbsp;<input type="text" name="wapArticlePage" value="<?php echo C('wapArticlePage');?>" />&nbsp;&nbsp;条
                </span>
            </div>

            <div class="line-dete">
                <label>产品模块列表页</label>
                <span class="field">
                    每页显示&nbsp;&nbsp;<input type="text" name="wapProductPage" value="<?php echo C('wapProductPage');?>" />&nbsp;&nbsp;条
                </span>
            </div>
            
            <div class="line-dete">
                <label>图库模块列表页</label>
                <span class="field">
                    每页显示&nbsp;&nbsp;<input type="text" name="wapPhotoPage" value="<?php echo C('wapPhotoPage');?>" />&nbsp;&nbsp;条
                </span>
            </div>

            <div class="line-dete">
                <label>下载模块列表页</label>
                <span class="field">
                    每页显示&nbsp;&nbsp;<input type="text" name="wapDownloadPage" value="<?php echo C('wapDownloadPage');?>" />&nbsp;&nbsp;条
                </span>
            </div>

            <div class="line-dete">
                <label>招聘模块列表页</label>
                <span class="field">
                    每页显示&nbsp;&nbsp;<input type="text" name="wapHrPage" value="<?php echo C('wapHrPage');?>" />&nbsp;&nbsp;条
                </span>
            </div> -->


            <div class="line-dete">
                <label></label>
                <span class="field">
                    <input type="submit" class="stdbtn" id="Ksubmit" value="保存"/>
                   <!--  <input type="button" class="stdbtn" onclick="window.location.href='<?php echo U('Questionnaire/problemList', array('id'=>$_GET['questionnaire_id']));?>'" value="返回"/> -->
                </span>
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
    


</body>
</html>