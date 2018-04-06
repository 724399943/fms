<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_search.tpl"}-->
<div class="oe_nav">
  <span f="back">返回</span>
  <em>查看详情</em>
  <label f="search"></label>
</div>
<div class="oe_down_detail">
  <h1 class="title"><!--{$download.title}--> </h1>
  <div class="oe_down_con">
    <ul>
	  <li><b>文件大小 ：</b> <!--{$download.filesize}--> K</li>
	  <li><b>下载分类 ：</b> <!--{$catinfo.catname}--></li>
	  <li><b>下载次数 ： </b> <!--{$download.downs}--> 次</li>
	  <div class="clear"></div>
	</ul>
	<div class="oe_down_url">
	  <span> <a href="<!--{$download.fileurl}-->" target="_blank"><b><font color="orange">点击下载</font></b></a></span>
    </div>
  </div>
  <div class="oe_down_text">
   <!--{$download.content}-->
  </div>
  <div class="nepr">
    上一条：<!--{$previous_item}--><br>
	下一条：<!--{$next_item}-->
  </div>
</div>
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>