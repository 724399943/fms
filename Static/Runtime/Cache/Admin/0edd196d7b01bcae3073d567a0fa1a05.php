<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>留言管理</title>
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
        <h1 class="pagetitle">留言管理</h1>
    </div>
    <div class="contentwrapper">
        <form class="order-list" method="GET" action="<?php echo U('Message/messageList');?>">
            <p>
                <input type="button" class="Kdelete" value="删除" />&nbsp;&nbsp;
                留言标题&nbsp;&nbsp;<input type="text" name="title" value="<?php echo ($return['title']); ?>" />
               <!--  <a href="<?php echo U('Message/addMessage',array('firstCatId'=>$firstCatId));?>" class="btn btn_link" style="float: right;margin-right: 10px;">
                    <span style="font-size:14px">添加留言</span>
                </a> -->
                <!-- <input type="hidden" name="firstCatId" value="<?php echo ($firstCatId); ?>" /> -->
                <input type="submit" value="搜索" />
            </p>
        </form>    
            <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablequick">
                <tr>
                    <th width="">选择</th>
                    <th width="">姓名</th>
                    <th width="">标题</th>
                    <th width="">留言时间</th>
                    <th width="">IP</th>
                    <!-- <th width="">审核</th> -->
                    <th width="">回复</th>
                    <th width="23%">操作</th>
                </tr>
                <?php if(empty($list)): ?><tr>
                        <td colspan="9">目前没有数据~！</td>
                    </tr>
                <?php else: ?>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox" class="ids" value="<?php echo ($vo['id']); ?>" /></td>
                            <td><?php echo ($vo["username"]); ?></td>
                            <td><?php echo ($vo['title']); ?></td>
                            <td><?php echo (time_format($vo['add_time'])); ?></td>
                            <td><?php echo ($vo['ip']); ?></td>
                            <td>
                                <?php if($vo['reply_flag'] == '0'): ?>未回复
                                <?php else: ?>
                                <span style="color: green;">已回复</span>
                                <br />
                                <span><?php echo (time_format($vo[reply_time])); ?></span><?php endif; ?>
                            </td>

                            <td>
                                <?php if(checkActionAuth('Message-replyMessage')): ?><a class="stdbtn btn_lime" href="<?php echo U('Message/replyMessage', array('id'=>$vo['id']));?>">回复</a>
                                <?php else: ?>
                                    无权限访问<?php endif; ?>
                                <?php if(checkActionAuth('Message-delMessage')): ?><a class="stdbtn btn_lime" href="<?php echo U('Message/delMessage', array('ids'=>$vo['id']));?>">删除</a>
                                <?php else: ?>
                                    无权限访问<?php endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <?php if($counting >= 25): ?><td colspan="8">
                                <div class="page-box"><?php echo ($show); ?></div>
                            </td><?php endif; ?>
                            <!-- <input type="text" class="input-text" style="width:250px" placeholder="标识" name="tags" value="<?php echo ($return['tags']); ?>" > -->
                    </tr><?php endif; ?>
            </table>
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
    
<script type="text/javascript">
//删除
 $(".Kdelete").click(function () {

        var objs = $('.ids');
        var ids = '';
        for(var j=0;j<objs.length;j++)
        {   
            if ($(objs[j]).is(':checked'))
            {
                ids += $(objs[j]).val()+',';
            }
        }
        if ( !ids )  {
            alert('请选择要删除的数据');
            return;
        }
        var jumpUrl = "<?php echo U('Message/delMessage');?>?ids=" + ids;
        window.location.href = jumpUrl;
    });









































// //选择删除
//  $(".Kdelete").click(function () {

//         var objs = $('.ids');
//         var ids = '';
//         for(var j=0;j<objs.length;j++)
//         {   
//             if ($(objs[j]).is(':checked'))
//             {
//                 ids += $(objs[j]).val()+',';
//             }
//         }
//         if ( !ids )  {
//             alert('请选择要删除的数据');
//             return;
//         }
//         var jumpUrl = "<?php echo U('Article/delArticle');?>?ids=" + ids;
//         window.location.href = jumpUrl;
//     });

</script>

</body>
</html>