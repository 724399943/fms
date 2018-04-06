<extend name="Wap/default/Common:base" />
<block name="main">
  <include file="Wap/default/Common:nav" />
  <product name="goodsList" sign="'list'" cid="0"/>
  <include file="Wap/default/Common:search" />
  <div class="oe_product_list">
    <if condition="empty($goodsList['list'])">
      <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
    <else />
      <volist name="goodsList['list']" id="vo">
          <dl f="gourl" data-url="{:U('Product/productDetail',array('id' => $vo['id']))}">
            <dt><img src="{$vo['upload_files']}" alt="{$vo['product_name']}" title="{$vo['product_name']}"  /></dt>
        	<dd>
        	  <h3>{$vo['product_name']}</h3>
        	  <p>型号：{$vo['product_sn']} &#12288; 价格：<span>{$vo['bprice']}元</span></p>
        	</dd>
        	<div class="clear"></div>
          </dl>
      </volist>
      <div class="clear"></div>
    </if>
  </div>
  <if condition="!empty($goodsList['page'])">
  
   <div class="page-m">
      <div class="page-box">{$goodsList['page']}</div>
    </div>
  </if>
</block>
  <block name="script">
  <script type="text/javascript">
    $(function(){
    	$max_width = ($(window).width()-100);
        $(".oe_product_list dl dd").css({"width":$max_width});
    });
      $(function(){
      //滑动效果
      var mySwiper = new Swiper ('.swiper-container', {
        slidesPerView : 3,
        slidesPerGroup : 3
      });
    });
    </script>
  </block>
