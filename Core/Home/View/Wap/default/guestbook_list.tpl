<!--{include file="<!--{$waptplpath}-->block_header.tpl"}-->
<div class="oe_nv">
  <span f="back">返回</span>  
  <b f="gourl" data-url="<!--{$wapfile}-->?c=guestbook&a=message">我要留言</b>
</div>
<div class="oe_guestlist">
  <!--{if empty($guestbook)}-->
  <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
  <!--{else}-->
  <!--{foreach $guestbook as $volist}-->
  <dl>
    <dt><!--{$volist.title}--> <span>用户: <!--{$volist.username}--></span></dt>
    <dd>留言：<!--{$volist.content}--> <span>-- <!--{$volist.addtime|date_format:"%Y/%m/%d %H:%M:%S"}--></span></dd>
  </dl>
  <!--{/foreach}--> 
  <div class="clear"></div>
  <!--{/if}--> 
</div>
<!--{if !empty($showpage)}-->
<div class="oe_page"><!--{$showpage}--></div>
<!--{/if}-->
<!--{include file="<!--{$waptplpath}-->block_footer.tpl"}-->
</body>
</html>