<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<div class="oe_nav">
  <span f="back">返回</span>
  <em>查看详情</em>
  <label f="search"></label>
</div>
<div class="oe_hr_detail">
  <h1 class="title"><!--{$hr.title}--> </h1>
  <div class="oe_hr_con">
	<ul>
	  <li><b>职位编号 ：</b> #<!--{$hr.hrid}--></li>
	  <li><b>招聘人数 ： </b> <!--{$hr.number}--> 人</li>
	  <li><b>工作地点 ：</b> <!--{$hr.workarea}--></li>
	  <li><b>发布日期 ：</b> <!--{$hr.addtime|date_format:'%Y-%m-%d'}--></li>
	  <li><b>浏览次数 ：</b> <!--{$hr.hits}--></li>
	</ul>
	<div class="clear"></div>
  </div>
  <div class="oe_hr_text"><!--{$hr.content}--></div>
</div>
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>