<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>广告版位</title>
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
	<?php if(checkControllerAuth(array('Ad'))): ?><li <?php if(in_array(CONTROLLER_NAME, array('Ad'))): echo chr(32);?>class="current"<?php endif; ?>>
			<a class="date-tit sys-tj" href="<?php echo getAuthUrl(array('Ad-adGroupList', 'Ad-addAdGroup', 'Ad-editAdGroup'));?>" class="addons">广告管理</a>
			<ul class="Jcon-ctr">
				<?php if(checkActionAuth('Ad-adGroupList')): ?><li class="<?php if(in_array(ACTION_NAME, array('adGroupList','editAdGroup'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Ad/adGroupList');?>">广告版位</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Ad-addAdGroup')): ?><li class="<?php if(in_array(ACTION_NAME, array('addAdGroup'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Ad/addAdGroup');?>">添加广告版位</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Ad-adList')): ?><li class="<?php if(in_array(ACTION_NAME, array('adList','editAd'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Ad/adList');?>">广告列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Ad-addAd')): ?><li class="<?php if(in_array(ACTION_NAME, array('addAd'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Ad/addAd');?>">添加广告</a>
					</li><?php endif; ?>
			</ul>
		</li><?php endif; ?>
</ul>

            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            
    <div class="pageheader">
        <h1 class="pagetitle">广告版位</h1>
    </div>
    <div class="contentwrapper">
        <?php if(checkActionAuth('Ad-addAdGroup')): ?><a href="<?php echo U('Ad/addAdGroup');?>" class="btn btn_link" style="float: right;margin-right: 10px;">
                <span style="font-size:14px">添加版位</span>
            </a><?php endif; ?>
        <br>
        <br>  
            <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablequick">
                <tr>
                    <th width="">ID</th>
                    <th width="">版位名称</th>
                    <th width="">版位标识</th>
                    <th width="">排序</th>
                    <th width="">大小</th>
                    <th width="">广告数</th>
                    <th width="">添加时间</th>
                    <th width="">状态</th>
                    <th width="23%">操作</th>
                </tr>
                <?php if(empty($list)): ?><tr>
                        <td colspan="9">目前没有数据~！</td>
                    </tr>
                <?php else: ?>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo['id']); ?></td>
                            <td><?php echo ($vo["group_name"]); ?></td>
                            <td><?php echo ($vo['group_sign']); ?></td>
                            <td><?php echo ($vo['sort']); ?></td>
                            <td><?php echo ($vo['width']); ?> x <?php echo ($vo['height']); ?>(像素)</td>
                            <td><?php echo ($vo['ad_count']); ?></td>
                            <td><?php echo (time_format($vo['add_time'] )); ?></td>
                            <?php if($vo['is_delete'] == '1'): ?><td class="Jvalid" data-id="<?php echo ($vo['id']); ?>-0">
                                    <img src="/Static/Public/Admin/images/no.gif" alt="">
                                </td>
                                <?php else: ?>
                                <td class="Jvalid" data-id="<?php echo ($vo['id']); ?>-1">
                                    <img src="/Static/Public/Admin/images/yes.gif" alt="">  
                                </td><?php endif; ?>

                            <!-- <?php if($vo['flag'] == '1'): ?><td class="Jstatus" data-id="<?php echo ($vo['id']); ?>-2">
                                      <img src="/Static/Public/Admin/images/yes.gif" alt="">
                                  </td>
                                  <?php else: ?>
                                  <td class="Jstatus" data-id="<?php echo ($vo['id']); ?>-1">
                                      <img src="/Static/Public/Admin/images/no.gif" alt="">  
                                  </td><?php endif; ?> -->

                            <td>
                                <?php if(checkActionAuth('Ad-editAdGroup')): ?><a class="stdbtn btn_lime" href="<?php echo U('Ad/editAdGroup', array('id'=>$vo['id']));?>">编辑</a>
                                <?php else: ?>
                                    无权限访问<?php endif; ?>
                                <?php if(checkActionAuth('Ad-delAdGroup')): ?><a class="stdbtn btn_lime" href="<?php echo U('Ad/delAdGroup', array('id'=>$vo['id']));?>">删除</a>
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
//选择必填
$('.Jvalid').click(function(){
        var id = $(this).attr('data-id')
        var yes = "/Static/Public/Admin/images/yes.gif"
        var no = "/Static/Public/Admin/images/no.gif"
        var field = 'is_valid'
        id = id.split('-')
        console.log(id);
        if (id[1] == 1) {
            $(this).children('img').attr('src', no)
            $(this).attr('data-id', id[0] + '-0')
        } else {
           $(this).children('img').attr('src',yes)
           $(this).attr('data-id', id[0] + '-1')
        }
        $.ajax({
            url: "<?php echo U('Ad/setAdGroupStatus');?>",
            type: 'post',
            dataType: 'json',
            data: {id: id[0],status:id[1]}
        })
        .done(function(data) {
            console.log(data);
        })

   })

</script>

</body>
</html>