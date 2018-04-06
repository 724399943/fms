<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<!--{include file="<!--{$waptplpath}-->block_search.tpl"}-->
<div class="oe_nav">
  <span f="back">返回</span>
  <em>查看详情</em>
  <label f="search"></label>
</div>
<div class="oe_product_detail">
  <h1 class="title"><!--{$photo.title}--></h1>
  <div class="oe_product_con">
    <ul>
      <li><b>点击 : </b><span><!--{$photo.hits}--></span></li>
      <li><b>日期 : </b><span><!--{$photo.addtime|date_format:'%Y-%m-%d'}--></span></li>
      <div class="clear"></div>
	</ul>
  </div>
  <div class="oe_product_img">
    <ul>
      <li><img src="<!--{$photo.uploadfiles}-->" /></li>
      <!--{if !empty($photo.gallery)}-->
      <!--{foreach $photo.gallery as $val}-->
	  <li>
	    <h3><!--{$val.imgname}--></h3>
	    <img border='0' src='<!--{$val.imgurl}-->'>
	  </li>
	  <!--{/foreach}-->
	  <!--{/if}-->
    </ul>
  </div>
  <div class="oe_product_text">
    <!--{$photo.content}-->
  </div>
  <div class="nepr">
    上一条：<!--{$previous_item}--><br>
	下一条：<!--{$next_item}-->
  </div>
</div>
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>