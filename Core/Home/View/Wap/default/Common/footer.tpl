<div class="kong"></div>
<div class="oe_footer">
  <dl class="oe_bar_1  <if condition="in_array(ACTION_NAME,array('index'))">current</if> " f="gourl" data-url="{:U('Index/index')}">
  	<dt></dt>
  	<dd>首页</dd>
  </dl>

  <dl class="oe_bar_2 <if condition="in_array(ACTION_NAME,array('product','productDetail'))">current</if> " f="gourl" data-url="{:U('Product/product')}">
  	<dt></dt>
  	<dd>产品介绍</dd>
  </dl>


  <dl class="oe_bar_3 <if condition="in_array(ACTION_NAME,array('article','articleDetail'))">current</if> " f="gourl" data-url="{:U('Article/article')}">
  	<dt></dt>
  	<dd>精彩活动</dd>
  </dl>
 
 <!--  <dl class="oe_bar_4 <if condition="in_array(ACTION_NAME,array('guestBookMessage'))">current</if> " f="gourl" data-url="{:U('GuestBook/guestBookMessage')}">
    <dt></dt>
    <dd>关于我们</dd>
  </dl> -->
   <dl class="oe_bar_4 <if condition="in_array(ACTION_NAME,array('guestBookMessage'))">current</if> " f="gourl" data-url="{:U('Bbs/Index/index')}">
    <dt></dt>
    <dd>关于我们</dd>
  </dl>
  <div class="clear"></div>
</div>