<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>栏目设置</title>
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/style.default.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/uniform.tp.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/pop.css" />
    <link rel="shortcut icon" href="/Static/Public/xcrozz.ico" type="image/x-icon" />
    
    <style type="text/css">
        tbody  tr td {padding: 0px 10px;  color: #666; }
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
	<?php if(checkActionAuth('Module-setModule')): ?><li <?php if(checkActionAuth(array('Module-setModule'))): echo chr(32);?>class="current"<?php endif; ?>>
			<a class="date-tit sys-tj" href="<?php echo getAuthUrl(array('Module-setModule'));?>" class="addons">模块管理</a>
			<ul class="Jcon-ctr">
				<?php if(checkActionAuth('Module-setModule')): ?><li class="<?php if(in_array(ACTION_NAME, array('setModule','addCategory','addSingleModule','editModule','addListColumn'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Module/setModule');?>">栏目设置</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Module-setModuleType')): ?><li class="<?php if(in_array(ACTION_NAME, array('setModuleType'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Module/setModuleType');?>">栏目类型</a>
					</li><?php endif; ?>
			</ul>
		</li><?php endif; ?>
</ul>
<?php if(is_array($firstCat)): $i = 0; $__LIST__ = $firstCat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
<?php if($vo['module_id'] != '7'): ?><li>
		<a class="date-tit sys-tj"><?php echo ($vo['cat_name']); ?></a>
		<ul class="Jcon-ctr">
			<!-- 文章模型 -->
			<?php if($vo['module_id'] == 1): if(checkActionAuth('Field-fieldList')): ?><li class="<?php if((in_array(ACTION_NAME, array('fieldList','delField','editField'))) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Field/fieldList',array('firstCatId'=>$vo['id']));?>">模块字段</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(( in_array(ACTION_NAME, array('addField')) ) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Field/addField',array('firstCatId'=>$vo['id']));?>">添加字段</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(( in_array(ACTION_NAME, array('articleList','delArticle','editArticle'))) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/articleList',array('firstCatId'=>$vo['id']));?>">内容列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-addArticle')): ?><li class="<?php if(( in_array(ACTION_NAME, array('addArticle')) ) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/addArticle',array('firstCatId'=>$vo['id']));?>">添加内容</a>
					</li><?php endif; endif; ?>
			<!-- 产品模型 -->
			<?php if($vo['module_id'] == 2): if(checkActionAuth('Field-fieldList')): ?><li class="<?php if((in_array(ACTION_NAME, array('fieldList','delField','editField'))) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Field/fieldList',array('firstCatId'=>$vo['id']));?>">模块字段</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(( in_array(ACTION_NAME, array('addField')) ) AND ($vo['id'] == $firstCatId)): ?>on<?php endif; ?>">
						<a href="<?php echo U('Field/addField',array('firstCatId'=>$vo['id']));?>">添加字段</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Product-productList')): ?><li class="<?php if(in_array(ACTION_NAME, array('productList','delProduct','editProduct'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Product/productList',array('firstCatId'=>$vo['id']));?>">内容列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Product-addProduct')): ?><li class="<?php if(in_array(ACTION_NAME, array('addProduct'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Product/addProduct',array('firstCatId'=>$vo['id']));?>">添加内容</a>
					</li><?php endif; endif; ?>
			<!-- 图库模型 -->
			<?php if($vo['module_id'] == 3): ?><!-- <?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(in_array(ACTION_NAME, array('articleList','delArticle','editArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/articleList',array('id'=>$vo['id']));?>">内容列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-addArticle')): ?><li class="<?php if(in_array(ACTION_NAME, array('addArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/addArticle',array('id'=>$vo['id']));?>">添加内容</a>
					</li><?php endif; ?> --><?php endif; ?>
			<!-- 下载模型 -->
			<?php if($vo['module_id'] == 4): ?><!-- <?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(in_array(ACTION_NAME, array('articleList','delArticle','editArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/articleList',array('id'=>$vo['id']));?>">内容列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-addArticle')): ?><li class="<?php if(in_array(ACTION_NAME, array('addArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/addArticle',array('id'=>$vo['id']));?>">添加内容</a>
					</li><?php endif; ?> --><?php endif; ?>
			<!-- 招聘模型 -->
			<?php if($vo['module_id'] == 5): ?><!-- <?php if(checkActionAuth('Article-articleList')): ?><li class="<?php if(in_array(ACTION_NAME, array('articleList','delArticle','editArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/articleList',array('id'=>$vo['id']));?>">内容列表</a>
					</li><?php endif; ?>
				<?php if(checkActionAuth('Article-addArticle')): ?><li class="<?php if(in_array(ACTION_NAME, array('addArticle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('Article/addArticle',array('id'=>$vo['id']));?>">添加内容</a>
					</li><?php endif; ?> --><?php endif; ?>
			<!-- 单页模型 -->
			<?php if($vo['module_id'] == 6): ?><!-- <?php if(checkActionAuth('SinglePage-editSingle')): ?>-->
					<!-- <?php $result = getSingles();dump($result); ?> -->
				<?php $_result=getSingles($vo['id'],$vo['module_id']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><li class="<?php if(in_array(ACTION_NAME, array('editSingle'))): ?>on<?php endif; ?>">
						<a href="<?php echo U('SinglePage/editSingle',array('id'=>$data['id']));?>"><?php echo ($data['cat_name']); ?></a>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
				<!--<?php endif; ?> --><?php endif; ?>
		</ul>
	</li><?php endif; ?>
</ul><?php endforeach; endif; else: echo "" ;endif; ?>

            <a class="togglemenu"></a>
            <br /><br />
        </div>
        <div class="centercontent">
            
    <div class="pageheader">
        <h1 class="pagetitle">栏目设置</h1>
            
        <span class="pagedesc"></span>
    </div>

    <div class="contentwrapper">
        <form class="order-list" method="GET" action="<?php echo U('Qrcode/qrcodeList');?>">
           <!--  <p>
                <input type="text" class="input-text" style="width:250px" placeholder="标识" name="tags" value="<?php echo ($return['tags']); ?>" >
                <input type="submit" value="搜索"></input>
            </p> -->
            <a href="<?php echo U('Module/addExternalModule');?>" class="btn btn_link" style="float: right;margin-right: 10px;">
                <span style="font-size:14px">添加一级外部栏目</span>
            </a>
            <a href="<?php echo U('Module/addSingleModule');?>" class="btn btn_link" style="float: right;margin-right: 10px;">
                <span style="font-size:14px">添加一级单页栏目</span>
            </a>
            <a href="<?php echo U('Module/addListColumn');?>" class="btn btn_link" style="float: right;margin-right: 10px;">
                <span style="font-size:14px">添加一级列表栏目</span>
            </a>
        </form>
        <br>
        <br>

        <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablequick" >
            <tr>
                <th width="">ID</th>
                <th width="">排序</th>
                <th width="">模型</th>
                <th width="">栏目标识</th>
                <th width="">栏目名称</th>
                <th width="">状态</th>
                <th width="23%">操作</th>
            </tr>
            <?php if(empty($list)): ?><tr>
                    <td colspan="7">目前没有数据~！</td>
                </tr>
            <?php else: ?>
                <?php function showTree($data) { foreach ($data as $key => $val){ echo '<tr>'; echo '<td>'.$val['id'].'</td>'; echo '<td>'.$val['orders'].'</td>'; echo '<td style="color: '.$val['color'].'">'.$val['mod_name'].'</td>'; echo '<td>'.$val['dir_name'].'</td>'; if ($val['depth'] >= 1) { echo '<td>'.str_repeat('│ ',$val['depth'] - 1).'├ '.$val['cat_name'].'</td>'; } else { echo '<td><b style="color: #222">'.$val['cat_name'].'</b></td>'; } if (!in_array($val['module_id'],['7','6'])) { $add = '<a class="stdbtn btn_del" href="/Admin/Module/addCategory?pid='.$val['id'].'&mod_id='.$val['module_id'].'">添加'.($val['depth'] + 2).'级栏目</a>'; } else { if ($val['tree_id'] == 0 AND $val['module_id'] != 7) { $add = '<a class="stdbtn btn_del" href="/Admin/Module/addCategory?pid='.$val['id'].'&mod_id='.$val['module_id'].'">添加'.($val['depth'] + 2).'级栏目</a>'; } else { $add = ''; } } if ($val['status'] == 1) { echo '<td align="center" class="Jstatus" data-id="'.$val['id'].'-2"><img src="/Static/Public/Admin/images/yes.gif" alt=""></td>'; } else { echo '<td align="center" class="Jstatus" data-id="'.$val['id'].'-1"><img src="/Static/Public/Admin/images/no.gif" alt=""></td>'; } if ($val['tree_id'] == 0) { $edit = '<a href="/Admin/Module/editModule?id='.$val['id'].'" class="stdbtn btn_del">设置</a>'; } else { $edit = '<a href="/Admin/Module/editModule?id='.$val['id'].'" class="stdbtn btn_del">修改</a>'; } echo '<td align="center" >
                                    '.$add.'
                                    '.$edit.'
                                    <a href="/Admin/Module/delCategory?id='.$val['id'].'" class="stdbtn btn_del">删除</a>
                                  </td>'; echo '</tr>'; if (!empty($val['childCategory'])) { showTree($val['childCategory']); } } } showTree($list); ?>
                <tr>
                    <?php if($counting >= 25): ?><td colspan="6">
                            <div class="page-box"><?php echo ($show); ?></div>
                        </td><?php endif; ?>
                </tr><?php endif; ?>
        </table>
    </div>
    <br>
    <br>
    <br>
    <br>

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
           $('.Jstatus').click(function(){
                var id = $(this).attr('data-id')
                var yes = "/Static/Public/Admin/images/yes.gif"
                var no = "/Static/Public/Admin/images/no.gif"
                id = id.split('-')
                if (id[1] == 1) {
                    $(this).children('img').attr('src', yes)
                    $(this).attr('data-id', id[0] + '-2')
                } else {
                   $(this).children('img').attr('src',no)
                   $(this).attr('data-id', id[0] + '-1')
                }

                $.ajax({
                    url: "<?php echo U('Module/changeModuleStatus');?>",
                    type: 'post',
                    dataType: 'json',
                    data: {id: id[0],status:id[1]}
                })
                .done(function(data) {
                    if (data.success == 1) {
                       
                    } else {
                        
                    }
                })

           })
       </script>

</body>
</html>