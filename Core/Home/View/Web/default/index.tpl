<extend name="Web/default/Common:base" />
<block name="main">
<include file="Web/default/Common:top" />
<include file="Web/default/Common:left" />
<include file="Web/default/Common:search" />
<include file="Web/default/Common:banner" />

<div class="oe_index_product">
  <div class="title">
    <h2>产品展示</h2>
    <p><img src="__PUBLIC__/Web/default/images/ico_index_product.png" /></p>
  </div>
  <div class="oe_in_prodct_list">
    <div class="hd"> <a class="next"></a>
      <ul>
      </ul>
      <a class="prev"></a> </div>
    <div class="bd">
      <ul class="picList">
      <product name="goodsList" sign="index" cid="0"/>
        <volist name="goodsList['list']" id="vo">
          <li>
            <div class="pic">
              <a href="{:U('Product/productDetail',array('id' => $vo['id']))}" target="_blank">
                <img src="{$vo.thumb_files}" alt="{$vo.product_name}" onload="javascript:oecmsDrawImage(this, 163, 163);" />
              </a>
            </div>
            <div class="title">
              <a href="{:U('Product/productDetail',array('id' => $vo['id']))}" target="_blank">{$vo.product_name}</a>
            </div>
          </li>
        </volist>
      </ul>
    </div>
  </div>
  <script type='text/javascript' src='<!--{$skinpath}-->js/jquery.SuperSlide.2.1.1.js'></script> 
  <script type="text/javascript">
      jQuery(".oe_in_prodct_list").slide({titCell:".hd ul",mainCell:".bd ul",scroll:2,autoPage:true,effect:"left",autoPlay:true,vis:6,delayTime:900});
  </script> 
</div>



</block>    


<block name="script">
  <script type="text/javascript">
      $(function(){
        //滑动效果
        var mySwiper = new Swiper ('.swiper-container', {
          loop : true,
          pagination: '.swiper-pagination',
          autoplay:3000,
          autoplayDisableOnInteraction : false
        });


        $max_width = ($(window).width()-35)/3;
          $(".oe_index_productcn dl").css({"width":$max_width});
          $(".oe_index_productcn dl dt img").css({"width":$max_width});
          $(".oe_index_productcn dl dt img").css({"height":$max_width});
      });
  </script>
</block>