<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>添加字段</title>
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/style.default.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/uniform.tp.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/pop.css" />
    <link rel="shortcut icon" href="/Static/Public/xcrozz.ico" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/ui-frame/ui.css" />
    <style type="text/css">
    .line-dete em{color: #e18383}
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
        <h1 class="pagetitle">添加字段</h1>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <form class="stdform stdform2" action="<?php echo U('Field/addField');?>" method="post" id="question-form">
            <div class="line-dete">
                <label>所属栏目<em>*</em></label>
                <span class="field">
                    <select name="tree_id">
                        <option value="0">所有栏目</option>
                        <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>" <?php if($vo['id'] == $firstCatId): ?>selected<?php endif; ?>><?php echo ($vo['cat_name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </span>
            </div>

            <div class="line-dete">
                <label>简述文字<em>*</em></label>
                <span class="field">
                    <input type="text" name="type_name"  />&nbsp;&nbsp;
                    (发布内容时显示的字段提示，请输入长度不能大于50的任意字符段)
                </span>
            </div>
            
            <div class="line-dete">
                <label>字段类型<em>*</em></label>
                <span class="field">
                    <select name="input_type">
                        <option value="">请选择</option>
                        <option value="text">单行文本</option>
                        <option value="textarea">多行文本</option>
                        <option value="checkbox">多选框</option>
                        <option value="radio">单选框</option>
                        <option value="select">下拉框</option>
                        <option value="img">图片附件</option>
                        <option value="attachment">文件附件</option>
                    </select>&nbsp;&nbsp;
                    （注意一旦选择不能修改）
                </span>
            </div>

            <div class="line-dete">
                <label>字段名称<em>*</em></label>
                <span class="field">
                    <input type="text" name="attr_name"  />&nbsp;&nbsp;(同一模型名称必须唯一)
                    （请输入长度不能小于2和大于40的英文字符，注意一旦填写不能修改）
                </span>
            </div>
            
            <div class="line-dete">
                <label>提示文字</label>
                <span class="field">
                    <input type="text" name="type_remark"  />&nbsp;&nbsp;
                    （发布内容时显示的备注内容，请输入长度不能大于200的任意字符段）
                </span>
            </div>

            <div class="line-dete">
                <label>默认值</label>
                <div class="field">
                    <textarea name="attr_value" style="width: 50%;height: 80px;overflow: auto;"></textarea><br/>
                    （如果字段为下拉、复合选项关联输入框、单选或多选，每个默认值回车输入）
                </div>
            </div>

            <div class="line-dete">
                <label>字段排序<em>*</em></label>
                <span class="field">
                    <input type="text" name="orders" />&nbsp;&nbsp;(数字越小越靠前)
                </span>
            </div>
            

            <div class="line-dete">
                <label>必填字段</label>
                <span class="field">
                    <input type="radio" name="is_valid" value="1" />启用
                    <input type="radio" name="is_valid" value="0" checked   />关闭
                </span>
            </div>

            <div class="line-dete">
                <label>显示状态</label>
                <span class="field">
                    <input type="radio" name="flag" value="1" checked  />启用
                    <input type="radio" name="flag" value="0"   />关闭
                </span>
            </div>
            
            <div class="line-dete">
                <label></label>
                <span class="field">
                    <input type="hidden" name="firstCatId" value="<?php echo ($firstCatId); ?>">
                    <input type="hidden" name="module_id" value="<?php echo ($module_id); ?>">
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
    
<script type="text/javascript" src="/Static/Public/Admin/js/ajaxfileupload.js"></script>
<!-- <script type="text/javascript" src="/Static/Public/Admin/js/plugins/questionnaire/optionManager.js"></script> -->
<script charset="utf-8" src="/Static/Public/Admin/js/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/Static/Public/Admin/js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
</script>

</body>
</html>