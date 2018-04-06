<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>编辑</title>
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/style.default.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/uniform.tp.css" />
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/pop.css" />
    <link rel="shortcut icon" href="/Static/Public/xcrozz.ico" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="/Static/Public/Admin/css/plugins/ui-frame/ui.css" />
    <style type="text/css">
    form input{width: 40%}
    .maskWrap{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display: none;}
    .maskMain{width:800px;height:450px;position:fixed;top:50%;left:50%;margin-top:-225px;margin-left:-400px;background-color:#ffffff;overflow:hidden;}
    .mtitle{line-height:35px;padding:0 15px;background-color:#f4f4f4;overflow:hidden;}
    .mtitle .close{float:right;cursor:pointer;}
    .mcont{margin:5px;padding:10px;border:1px solid #ddd;overflow-y:scroll;}
    .mcont .ht{line-height:35px;padding:0 10px;margin-bottom:15px;background-color:#e2eff9;}
    .mcont table,.mcont table tbody{width:100%;text-align:center;}
    .mcont .tit{background-color:#f4f4f4;}
    .mcont table .tlf{text-align:left;}
    .mcont table tbody>tr{width:100%;}
    .mcont table tbody>tr:hover{background-color:#f4f4f4;}
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
        <h1 class="pagetitle">编辑</h1>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <form class="stdform stdform2" action="<?php echo U('SinglePage/editSingle');?>" method="post" id="question-form">
        <div class="contenttitle2">
            <h3>基本信息</h3>
        </div>
            <div class="line-dete">
                <label>单页标题</label>
                <span class="field">
                    <input type="text" name="title" value="<?php echo ($singleData['title']); ?>"  />
                </span>
            </div>

            <div class="line-dete">
                <label>单页简介</label>
                <span class="field">
                    <input type="text" name="intro" value="<?php echo ($singleData['intro']); ?>"  />
                </span>
            </div>
            

            <div class="line-dete">
                <label>单页图片</label>
                <div class="field">
                    <input type="text" name="upload_files" id="imgs" class="smallinput" readonly="true" value="<?php echo ($singleData['upload']); ?>" />
                    <input type="file" name="up-pic" id="up-pic">
                </div>
            </div>

            <div class="line-dete">
                <label>单页内容</label>
                <span class="field">
                    <textarea name="content" style="width:800px;height:500px;"><?php echo ($singleData['content']); ?></textarea>
                </span>
            </div>
            <?php if(empty($extendFieldList)): else: ?>
                <div class="contenttitle2">
                    <h3>扩展字段</h3>
                </div>

                <?php if(is_array($extendFieldList)): $i = 0; $__LIST__ = $extendFieldList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-- <input type="hidden" name="module_attr_id[]" value="<?php echo ($vo['id']); ?>" /> -->
                    <input type="hidden" name="attr_id[]" value="<?php echo ($vo['attr_id']); ?>" />
                    <div class="line-dete">
                    <label><?php echo ($vo['type_name']); if($vo['is_valid'] == 1): ?><em>*</em><?php endif; ?></label>
                        <span class="field">
                            <?php switch($vo['input_type']): case "text": ?><input type="text" name="field[attr_<?php echo ($vo['id']); ?>]" value="<?php echo ($vo['ext_value']); ?>" /><?php break;?>
                                <?php case "textarea": ?><textarea name="field[attr_<?php echo ($vo['id']); ?>]"><?php echo ($vo['ext_value']); ?></textarea><?php break;?>
                                <?php case "checkbox": if(is_array($vo['attr_value'])): $i = 0; $__LIST__ = $vo['attr_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><input type="checkbox" name="field[attr_<?php echo ($vo['id']); ?>][]" value="<?php echo ($item); ?>"
                                     
                                     <?php if(in_array($item,$vo['ext_value'])): ?>checked<?php endif; ?> /><?php echo ($item); ?>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; break;?>
                                <?php case "radio": if(is_array($vo['attr_value'])): $k = 0; $__LIST__ = $vo['attr_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><input type="radio" name="field[attr_<?php echo ($vo['id']); ?>]" value="<?php echo ($item); ?>" <?php if($vo['ext_value'] == $item): ?>checked<?php endif; ?> /><?php echo ($item); ?>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; break;?>
                                <?php case "select": ?><select name="field[attr_<?php echo ($vo['id']); ?>]">
                                        <?php if(is_array($vo['attr_value'])): $i = 0; $__LIST__ = $vo['attr_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item); ?>" <?php if($vo['ext_value'] == $item): ?>selected<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select><?php break;?>
                                <?php case "img": ?><input type="text" name="field[attr_<?php echo ($vo['id']); ?>]" id="imgs2" class="smallinput" readonly="true" value="<?php echo ($vo['ext_value']); ?>" />
                                    <input type="file" name="up-pic" id="up-pic2"><?php break;?>
                                <?php case "attachment": ?><input type="text" name="field[attr_<?php echo ($vo['id']); ?>]" id="imgs3" class="smallinput" readonly="true" value="<?php echo ($vo['ext_value']); ?>" />
                                    <input type="file" name="up-pic" id="up-pic3"><?php break; endswitch;?>
                        </span>
                    </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>


            <div class="contenttitle2">
                <h3>SEO优化相关设置</h3>
            </div>
            <div class="line-dete">
                <label>TAG标签：</label>
                <span class="field">
                    <input type="text" class="smallinput" placeholder="<?php echo ($tags); ?>" value="<?php echo ($singleData['tags']); ?>" name="tags">
                     &nbsp;&nbsp;(多个标签请用","隔开，会自动关联链接)
                </span>
            </div>

            <div class="line-dete">
                <label>Meta关键字：</label>
                <span class="field">
                    <textarea name="meta_keyword" id="" cols="30" rows="10" style="margin: 0px; height: 62px; width: 629px;"><?php echo ($singleData['meta_keyword']); ?></textarea>
                </span>
            </div>

            <div class="line-dete">
                <label>Meta描述：</label>
                <span class="field">
                    <textarea name="meta_description" id="" cols="30" rows="10" style="margin: 0px; height: 68px; width: 678px;"><?php echo ($singleData['meta_description']); ?></textarea>
                </span>
            </div>


            <div class="contenttitle2">
                <h3>其他附加设置</h3>
            </div>

            <div class="line-dete">
                <label>指定模板文件</label>
                <span class="field">
                    <input type="text" name="tplname" />.tpl
                    &nbsp;&nbsp;&nbsp;
                     <a href="javascript:;" class="tpl" data-id="tplname" style="color: #0072c1">选择模板</a>
                </span>
            </div>

            <div class="line-dete">
                <label></label>
                <span class="field">
                    <input type="hidden" name="id" value="<?php echo ($singleData['id']); ?>">
                    <!-- <input type="hidden" name="referer" value="<?php echo ($_SERVER['HTTP_REFERER']); ?>"> -->
                    <input type="submit" class="stdbtn" id="Ksubmit" value="保存"/>
                   <!--  <input type="button" class="stdbtn" onclick="window.location.href='<?php echo U('Questionnaire/problemList', array('id'=>$_GET['questionnaire_id']));?>'" value="返回"/> -->
                </span>
            </div>

            <div class="maskWrap">
            <div class="maskMain">
                <div class="mtitle">选择模板文件<span class="close">关闭</span></div>
                <div class="mcont">
                    <p class="ht">正在使用的主题[默认模板] 模板目录：<?php echo ($dir[0]); ?></p>
                    <table>
                        <tbody>
                            <tr class="tit">
                                <th>序号</th>
                                <th>模板文件名</th>
                                <th>大小</th>
                                <th>最后修改时间</th>
                                <th>选择</th>
                            </tr>
                            <?php if(is_array($dir)): $i = 0; $__LIST__ = array_slice($dir,1,null,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td>1</td>
                                    <td class="tlf"><?php echo basename($vo);?></td>
                                    <td><?php echo getFileSize($vo);?></td>
                                    <td><?php echo date('Y-m-d H:i:s', filemtime($vo)) ?></td>
                                    <td style="color: #0072c1;cursor:pointer" class="JtplName" data="<?php echo basename($vo);?>">选择</td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
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
   // 上传图片
        $(document).on('change', '#up-pic', function() {
            $.ajaxFileUpload({
                url: "<?php echo U('SinglePage/photoUpload');?>",
                secureuri: false,
                fileElementId: 'up-pic',
                dataType: 'json',
                success: function (data, status) {
                    if(data.error != '') {
                        alert(data.error);
                    } else {
                        // $('.Jpic').attr('src', data.src).show();
                        $('#imgs').val(data.src);
                    }
                },error: function (data, status, e) {
                    alert(e);
                }
            });
        });
    //扩展字段图片附件
    $(document).on('change', '#up-pic2', function() {
            $.ajaxFileUpload({
                url: "<?php echo U('Article/photoUpload');?>",
                secureuri: false,
                fileElementId: 'up-pic2',
                dataType: 'json',
                success: function (data, status) {
                    if(data.error != '') {
                        alert(data.error);
                    } else {
                        // $('.Jpic').attr('src', data.src).show();
                        $('#imgs2').val(data.src);
                    }
                },error: function (data, status, e) {
                    alert(e);
                }
            });
        });
    //扩展字段文件附件
    $(document).on('change', '#up-pic3', function() {
            $.ajaxFileUpload({
                url: "<?php echo U('Article/photoUpload');?>",
                secureuri: false,
                fileElementId: 'up-pic3',
                dataType: 'json',
                success: function (data, status) {
                    if(data.error != '') {
                        alert(data.error);
                    } else {
                        // $('.Jpic').attr('src', data.src).show();
                        $('#imgs3').val(data.src);
                    }
                },error: function (data, status, e) {
                    alert(e);
                }
            });
        });
    //编辑器
    $(function(){
        var editor;
        KindEditor.ready(function(K) {
            editor = K.create('textarea[name="content"]', {
                resizeType : 1,
                allowPreviewEmoticons : false,
                allowImageUpload : true,
                uploadJson : '<?php echo U('Article/photoUpload');?>',
                allowFileManager : false,
                filterMode: false,
                items : [
                    'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link']
            });
        });
    })
    var isTplName = '';
    $('.tpl').click(function(){
        $('.maskWrap').show()
        isTplName = $(this).data('id')
    })
    
    $('.JtplName').click(function(){
        var name = $(this).attr('data');
        $("input[name="+isTplName+"]").val(name)
        $('.maskWrap').hide()
    })
</script>

</body>
</html>