<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>回复留言</title>
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/style.default.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/uniform.tp.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/pop.css" />
    <link rel="shortcut icon" href="/Static/Public/xcrozz.ico" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/ui-frame/ui.css" />
    <style type="text/css">
    .line-dete em{color: #e18383}
    .read_only input,.read_only textarea{background-color: #ccc;}
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
	<?php if(checkControllerAuth(array('Message','Log'))): ?><li <?php if(in_array(CONTROLLER_NAME, array('Message'))): echo chr(32);?>class="current"<?php endif; ?>>
			<a class="date-tit sys-tj" href="<?php echo getAuthUrl(array('Message-messageList', 'Message-addMessage'));?>" class="addons">扩展应用</a>
			<ul class="Jcon-ctr">
				<?php if(checkActionAuth('Message-messageList')): ?><li class="<?php if(in_array(ACTION_NAME, array('messageList', 'addMessage'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Message/messageList');?>">留言管理</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Log-logList')): ?><li class="<?php if(in_array(ACTION_NAME, array('logList'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Log/logList');?>">日志管理</a>
					</li><?php endif; ?>
				<!-- <?php if(checkActionAuth('Admin-addAdmin')): ?><li class="<?php if(in_array(ACTION_NAME, array('addAdmin'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Admin/addAdmin');?>">添加管理员</a>
					</li><?php endif; ?> -->
			</ul>
		</li><?php endif; ?>

</ul>

            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            
    <div class="pageheader">
        <h1 class="pagetitle">回复留言</h1>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <form class="stdform stdform2" action="<?php echo U('Message/replyMessage');?>" method="post" id="question-form">
            <div class="contenttitle2">
                <h3>留言信息</h3>
            </div>
            <div class="line-dete">
                <label>姓名<em>*</em></label>
                <span class="field read_only">
                    <input type="text" name="username" value="<?php echo ($data['username']); ?>" readonly />&nbsp;&nbsp;
                    姓名长度不能大于50个任意字符
                </span>
            </div>

            <div class="line-dete">
                <label>标题<em>*</em></label>
                <span class="field read_only">
                    <input type="text" name="title" value="<?php echo ($data['title']); ?>" readonly  />&nbsp;&nbsp;
                </span>
            </div>

            <div class="line-dete">
                <label>邮箱<em>*</em></label>
                <span class="field read_only">
                    <input type="text" name="email" value="<?php echo ($data['email']); ?>" readonly  />&nbsp;&nbsp;
                </span>
            </div>

            <div class="line-dete">
                <label>留言内容<em>*</em></label>
                <span class="field read_only">
                <textarea name="content" read_only><?php echo ($data['content']); ?></textarea>
                </span>
            </div>
            
            <div class="line-dete">
                <label>留言时间<em>*</em></label>
                <span class="field read_only">
                    <input type="text" name="" readonly value="<?php echo (time_format($data['add_time'] )); ?>"  />&nbsp;&nbsp;
                </span>
            </div>
            
            <div class="line-dete">
                <label>IP<em>*</em></label>
                <span class="field read_only">
                    <input type="text" readonly name="ip" value="<?php echo ($data['ip']); ?>"  />&nbsp;&nbsp;
                </span>
            </div>
            
            <div class="line-dete">
                <label>QQ</label>
                <span class="field read_only">
                    <input type="text" name="qq" value="<?php echo ($data['qq']); ?>" readonly  />&nbsp;&nbsp;
                </span>
            </div>


            <div class="line-dete">
                <label>联系电话</label>
                <span class="field read_only">
                    <input type="text" name="mobile" value="<?php echo ($data['mobile']); ?>" readonly  />&nbsp;&nbsp;
                </span>
            </div>
            
           

            <div class="contenttitle2">
                <h3>回复留言</h3>
            </div>
            
            <div class="line-dete">
                <label>回复人<em>*</em></label>
                <span class="field <?php if($data['reply_flag']): ?>read_only<?php endif; ?>">
                <input type="text" name="reply_user" value="<?php echo ($data['reply_user']); ?>" <?php if($data['reply_flag'] == 1): ?>readonly<?php endif; ?>  />&nbsp;&nbsp;
                </span>
            </div>
            
            <div class="line-dete">
                <label>回复内容<em>*</em></label>
                <span class="field <?php if($data['reply_flag']): ?>read_only<?php endif; ?>">
                    <input type="text" name="reply_content" value="<?php echo ($data['reply_content']); ?>" <?php if($data['reply_flag'] == 1): ?>readonly<?php endif; ?>  />&nbsp;&nbsp;
                </span>
            </div>
            

            <div class="line-dete">
                <label></label>
                <span class="field">
                    <input type="hidden" name="id" value="<?php echo ($data['id']); ?>">
                    <?php if($data['reply_flag'] == 0): ?><input type="submit" class="stdbtn" id="Ksubmit" value="回复"/><?php endif; ?>
                    <input type="button" class="stdbtn" onclick="window.location.href='<?php echo U('Message/messageList');?>'" value="返回"/>
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
    
<script type="text/javascript" src="/Static/Public/Admin/js/ajaxfileupload.js"></script>
<!-- <script type="text/javascript" src="/Static/Public/Admin/js/plugins/questionnaire/optionManager.js"></script> -->
<script charset="utf-8" src="/Static/Public/Admin/js/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/Static/Public/Admin/js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
</script>

</body>
</html>