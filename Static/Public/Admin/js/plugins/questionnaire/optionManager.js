optionManager = {
	// score_test：分值型测试问卷 / jump_test：跳转型测试问卷 /survey：调研问卷
	questionnaireType: '', 
	option_type : '',
	isLoad : false,
	isDelete : false,

	/* 选项管理器应用启动 */
	start: function(){
		this.questionnaireType = $('#questionnaire_type').val();
		this.delegateEvents();
	},

	/* 处理一些事件委托事务 */
	delegateEvents: function(){
		var self = this;

		/* 点击选项下拉菜单 */
		$(document).delegate('#question-form .list a', 'click', function(){
			self.option_type = $(this).attr('type');
			self.loadOptionsEvent();
		});

		/* 提交问题 */
		$(document).delegate('#Ksubmit', 'click', function(){
			var state1 = true;
			state1 = self.collectOptions();
			//选项和标准答案都收集成功则提交表单
			if(state1) {
				uri = $('#question-form').attr('action');
				$.ajax({
					url: uri,
					type: 'POST',
					dataType: 'json',
					data: $('#question-form').serialize()
				})
				.done(function(returnData) {
					if ( returnData['status'] == '200000' ) {
						location.href = returnData['data']['referer'];
					} else {
						alert(returnData['message']);
					}
				});
			}
		});

		/* 删除选项 */
		$(document).delegate('.option-del', 'click', function(){
			var $this = $(this);

			/* 先复位全部选项可用 */
			$('#question-form .list > li > a').removeClass('disabled');
			if ( self.isDelete === false ) self.isLoad = false;
			self.isDelete = true;

			/* 点击选项下拉菜单 */
			$(document).delegate('#question-form .list .disabled', 'click', function(){
				self.option_type = $(this).attr('type');
				self.loadOptionsEvent();
			});

			/* 删除选项 */ 
			$this.parents('.option-item').remove();

			/* 重置选项DOM */
			if ( $('.option-list .option-del').length <= 0 ) {
				$('.checkbox-tags').hide();
				self.isDelete = false;
				self.clearOption();
				$('#question-form .option-list').html( '<br><br>' );
			}

			/* 更新可用选项 */
			self.updateAlterOption();
		});
	},

	/* 点击选项下拉菜单事件 */
	loadOptionsEvent: function(){
		var self = this;
		if ( self.option_type == 'radio_othertext' ) {
			$('#option_type').val('radio');
		} else if ( self.option_type == 'checkbox_othertext' || self.option_type == 'checkbox' ) {
			$('.checkbox-tags').show();
			$('#option_type').val('checkbox');
		} else {
			$('#option_type').val(self.option_type);
		}
		if ( self.isLoad === false ) self.loadSomeDocument();
		self.addOption();
		self.updateAlterOption();
	},

	loadSomeDocument: function(){
		var self = this;
		$('#question-form .option-list').html('');
		switch ( self.option_type ) {
			case 'image' : 
				$('#question-form .option-list').append('<div id="photoList" class="m-photo-list"></div>');
				break;
			case 'star' :
				$('#question-form .option-list').append('<span style="color:#fb9337;">*评分数（即选项数目）</span>');
				break;
			case 'checkbox' :
			case 'checkbox_othertext' :
				$('.checkbox-tags').show();
				break;
		}
		self.isLoad = true;
	},

	/* 页面添加指定类型的选项 */
	addOption: function(){
		$('#question-form .option-list').append( OptionTemplate.get(this.option_type) );
	},

	/* 更新可添加的选项 */
	updateAlterOption: function(){
		var self = this;
		var types = [];

		$('.option-list .option-item').each(function(){
			var type = $(this).attr('class').replace('option-item ', '').replace('_option', '');

			if( types.indexOf(type) == -1 )
				types.push(type);
		});

		types.forEach(function(type){
			self.disableOptAgainestCase(type);
		});
	},

	/* 禁用与type类型选项冲突的选项 */
	disableOptAgainestCase: function(type){
		$options = $('#question-form .list > li > a');
		
		switch(type){
			case 'score_test' : 
				$options.not('[type=score_test]').addClass('disabled').unbind('click');			
				break;
			case 'jump_test' : 
				$options.not('[type=jump_test]').addClass('disabled').unbind('click');
				break;
			case 'image' :
				$options.addClass('disabled').unbind('click');
				break;
			case 'star' :
				$options.not('[type=star]').addClass('disabled').unbind('click');
				break;
			case 'radio':
				$options.not('[type=radio]').not('[type=radio_othertext]').addClass('disabled').unbind('click');
				break;
			case 'checkbox':
				$options.not('[type=checkbox]').not('[type=checkbox_othertext]').addClass('disabled').unbind('click');
				break;
			case 'text':
				$options.addClass('disabled').unbind('click');
				break;
			case 'radio_othertext':
				$options.not('[type=radio]').addClass('disabled').unbind('click');
				break;
			case 'checkbox_othertext':
				$options.not('[type=checkbox]').addClass('disabled').unbind('click');
				break;
		}
	},

	/* 从配置好的选项上收集相应的json结构的选项信息 */
	collectOptions: function(){
		var self = this;
		if( !self.validateOptions() ) //验证是否输入好选项文本
			return false;

		var options = [];
		/* 循环选项，收集选项信息 */
		$('#question-form .option-list .option-item').each(function(i){
			$this = $(this);
			if ( $this.find('#fileToUpload').length > 0 ) return true;
			type = $this.attr('class').replace('option-item ', '').replace('_option', '');
			switch ( type ) {
				case 'score_test' : 
					var text = $this.find("input[name='text[]']").val(),
						score = $this.find("input[name='score[]']").val();
					options[i] = {"type":"score_test","text":text,"score":score};
					break;
				case 'jump_test' : 
					var text = $this.find("input[name='text[]']").val(),
						jump_type = $this.find("select[name='jump_type[]']").val(),
						jump_id = $this.find("select[name='jump_id[]']").val();
					options[i] = {"type":"jump_test","text":text,"jump_type":jump_type,"jump_id":jump_id};
					break;
				case 'image' : 
					var image = $this.find('img').attr('src'),
						description = $this.find('.Janswers').val();
					options[i] = {"type":"image","text":image,"description":description};
					break;
				case 'star' :
					options[i] = {"type":"star","text":i+1};
					break;
				case 'radio':
					var text = $this.find("input[name='text[]']").val();
					options[i] = {"type":"radio","text":text};
					break;
				case 'checkbox':
					var text = $this.find("input[name='text[]']").val();
					options[i] = {"type":"checkbox","text":text};
					break;
				case 'text':
					options[i] = {"type":"text"};
					break;
				case 'radio_othertext':
					options[i] = {"type":"radio_othertext"};
					break;
				case 'checkbox_othertext':
					options[i] = {"type":"checkbox_othertext"};
					break;
			}
		});

		//序列化选项信息，写入到隐藏域#options中
		var optionsVal = JSON.stringify(options);
		$('#options').val( optionsVal );

		return true;
	},

	/* 检查选项是否配置了相应选项文本 */
	validateOptions: function(){
		var self =  this;
		var state = true;
		var $optionItem = $('#question-form .option-list .option-item');

		$optionItem.each(function(i){
			$this = $(this);
			if ( $this.find('#fileToUpload').length > 0 ) return true;
			type = $this.attr('class').replace('option-item ', '').replace('_option', '');
			switch ( type ) {
				case 'score_test' : 
					break;
				case 'jump_test' : 
					break;
				case 'image' : 
					if ( !$this.find('.Janswers').val() ) {
						self.validateOptionTip('请填写描述');
						state = state && false;
						return false;
					}
					break;
				case 'star' :
					break;
				case 'radio':
					if ( !$this.find('input[name="text[]"]').val() ) {
						self.validateOptionTip('请输入选项文本');
						state = state && false;
						return false;
					}
					break;
				case 'checkbox':
					if ( !$this.find('input[name="text[]"]').val() ) {
						self.validateOptionTip('请输入选项文本');
						state = state && false;
						return false;
					}
					break;
				case 'text':
					break;
				case 'radio_othertext':
					break;
				case 'checkbox_othertext':
					break;
			}
		});

		if ( $optionItem.length <= 0 ) {
			self.validateOptionTip('请添加选项');
			state = state && false;
		}

		return state;
	},

	/* 选项验证提示 */
	validateOptionTip: function(tips){
		// var $tip = $('<span class="tip">请填写选项文本</span>');
		// $target.append($tip);

		// setTimeout(function(){
		// 	$tip.remove();
		// }, 1500);
		alert(tips);
	},

	/* 清除所有选项 */
	clearOption: function(){
		$('#question-form .option-list').html( '' );
	}
};


/* 选项模板 */
OptionTemplate = {
	problemData : {},
	resultData : {},
	/* 取得指定选项类型的选项模板 */
	get: function(type){
		/* 针对 “其他选项” 做的一些配置 */
		// if(optionManager.questionnaireType == 'exam'){
		// 	var disabled = '';
		// 	var opacity = 1;
		// }else{
			var disabled = 'disabled';
			var opacity = 0.3
		// }

		switch ( type ) {
			case 'score_test' : 
				return '<div class="option-item score_test_option"> <input type="text" name="text[]" class="Janswer" placeholder="选项"> <input type="text" name="score[]" class="Janswer" placeholder="分值"> <a class="stdbtn option-del">删除</a> </div>';

			case 'jump_test' : 
				var jumpTemp = '<select name="jump[]">';
				jumpTemp += '<option value="">选择跳转类型</option>';
				jumpTemp += '<option value="problem">问题</option>';
				jumpTemp += '<option value="result">答案</option>';
				jumpTemp += '</select>';
				return '<div class="option-item jump_test_option"> <input type="text" name="text[]" class="Janswer" placeholder="选项"> '+ jumpTemp +' <input type="hidden" name="jump_type[]"> <a class="stdbtn option-del">删除</a> </div>';

			case 'image' :
				return '<div class="option-item image_option"> <div class="upload-wrap"> <input type="file" id="fileToUpload" name="fileToUpload" class="f-upload" /> </div> </div>';
				
			case 'star' :
				return '<div class="option-item star_option"> <input type="text" name="text[]" class="Janswer" placeholder="选项"> <a class="stdbtn option-del del-star">删除</a> </div>';

			case 'radio': //单选项
				return '<div class="option-item radio_option"> <input type="text" class="smallinput" name="text[]" placeholder="请输入选项文本" /> <a class="stdbtn option-del">删除</a> </div> ';

			case 'checkbox': //多选项
				return '<div class="option-item checkbox_option"> <input type="text" class="smallinput" name="text[]" placeholder="请输入选项文本" /> <a class="stdbtn option-del">删除</a> </div> ';

			case 'text': //简答项
				return '<div class="option-item text_option"> <textarea name="text[]"> </textarea> <a class="stdbtn option-del">删除</a> </div>';

			case 'radio_othertext': //其他-单选项
				return '<div class="option-item radio_othertext_option"> <strong style="position: absolute;line-height: 25px;">其他：</strong> <input style="margin-left:40px;opacity:0.3;" type="text" class="smallinput" disabled /> <a class="stdbtn option-del">删除</a> </div> ';

			case 'checkbox_othertext': //其他-多选项
				return '<div class="option-item checkbox_othertext_option"> <strong style="position: absolute;line-height: 25px;">其他：</strong> <input style="margin-left:40px;opacity:0.3;" type="text" class="smallinput" disabled /> <a class="stdbtn option-del">删除</a> </div> ';
		}
	},

};


/* 选项管理器启动 */
optionManager.start();