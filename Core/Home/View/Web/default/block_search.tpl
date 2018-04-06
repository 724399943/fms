<div class="oe_topsearch" style="display: none;">
  <span class="oe_jian" f="search"></span>
  <form method="post" action="<!--{$wapfile}-->?c=search" id="serform" />
  <div class="search">
    <select name="type" id="type"><option value="product"<!--{if $type == 'Product'}--> selected<!--{/if}-->>&nbsp;产品&nbsp;</option><option value="photo"<!--{if $type == 'photo'}--> selected<!--{/if}-->>&nbsp;图库&nbsp;</option><option value="article"<!--{if $type == 'article'}--> selected<!--{/if}-->>&nbsp;文章&nbsp;</option><option value="download"<!--{if $type == 'download'}--> selected<!--{/if}-->>&nbsp;下载&nbsp;</option><option value="hr"<!--{if $type == 'hr'}--> selected<!--{/if}-->>&nbsp;招聘&nbsp;</option></select><input type='text' name='keyword' id="keyword" value="<!--{$keyword}-->" /><input class='searchimage' type='submit' f='btnser' />
  </div>
  </form>
</div>
<script language="javascript">
$(function(){
	
	$("[f='btnser']").bind("click", function(){
		if ($("#type").find("option:selected").val().length == 0) {
			alert("请选择搜索类型");
			return false;
		}

		if ($("#keyword").val().length == 0) {
			alert("请输入搜索关键词");
			return false;
		}
	});
});
</script>

