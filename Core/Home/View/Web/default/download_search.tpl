<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_search.tpl"}-->
<div class="oe_nav">
  <span f="back">返回</span>
  <em>搜索：<!--{$keyword}--></em>
  <label f="search"></label>
</div>
<div class="oe_download_list">
  <!--{if empty($search)}-->
  <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
  <!--{else}-->
  <!--{foreach $search as $volist}-->
  <dl>
	<dt>下载</dt>
	<dd f="gourl" data-url="<!--{$volist.wapurl}-->">
	  <h3><!--{$volist.title}--></h3>
	  <span><b>大小</b>：<!--{$volist.filesize}-->K</span>
	  <span><b>浏览</b>：<!--{$volist.hits}--></span>
	  <span><b>发布</b>：<!--{$volist.addtime|date_format:'%Y/%m/%d'}--></span>
	</dd>
	<div class="clear"></div>
  </dl>
  <!--{/foreach}-->  
  <div class="clear"></div>
  <!--{/if}-->
</div>
<!--{if !empty($showpage)}-->
<div class="oe_page">
  <!--{$showpage}-->
</div>
<!--{/if}-->
<script type="text/javascript">
$(function(){
	$max_width = (WIN_WIDTH-80);
	$(".oe_download_list dl dd").css({"width":$max_width});
});
</script>
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>