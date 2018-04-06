<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_search.tpl"}-->
<div class="oe_nav">
  <span f="back">返回</span>
  <em>搜索：<!--{$keyword}--></em>
  <label f="search"></label>
</div>
<div class="oe_hr_list">
  <!--{if empty($search)}-->
  <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
  <!--{else}-->
  <!--{foreach $search as $volist}-->
  <dl f="gourl" data-url="<!--{$volist.wapurl}-->">
  	<dt><!--{$volist.title}--></dt>
  	<dd>
	  招聘：<!--{$volist.number}-->人  
	  浏览：<!--{$volist.hits}-->  
	  发布时间：<!--{$volist.addtime|date_format:'%Y/%m/%d'}-->
	</dd>
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
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>