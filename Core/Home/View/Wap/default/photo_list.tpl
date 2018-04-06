<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_nav.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_search.tpl"}-->
<div class="oe_photo_list">
  <!--{if empty($photo)}-->
  <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
  <!--{else}-->
  <!--{foreach $photo as $volist}-->
  <dl f="gourl" data-url="<!--{$volist.wapurl}-->">
    <dt><img src="<!--{$volist.thumbfiles}-->" alt="<!--{$volist.title}-->"/></dt>
	<dd><h3><!--{$volist.title}--></h3></dd>
	<div class="clear"></div>
  </dl>
 <!--{/foreach}-->
 <div class="clear"></div>
 <!--{/if}-->
</div>
<script type="text/javascript">
$(function(){
	$max_width = (WIN_WIDTH-35)/3;
	$(".oe_photo_list dl").css({"width":$max_width});
	$(".oe_photo_list dl dt img").css({"width":$max_width});
	$(".oe_photo_list dl dt img").css({"height":$max_width});
});
</script>

<!--{if !empty($showpage)}-->
<div class="oe_page">
  <!--{$showpage}-->
</div>
<!--{/if}-->
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>