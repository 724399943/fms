<extend name="Wap/default/Common:base" />
<block name="main">
<include file="Wap/default/Common:top" />
<include file="Wap/default/Common:left" />
<include file="Wap/default/Common:search" />
<include file="Wap/default/Common:banner" />
    <div class="oe_index_product">
      <h2>最新产品<span f="gourl" style="display:none;">更多&gt;&gt;</span></h2>
        <div class="oe_index_productcn">
        <product name="goodsList" sign="index" cid="0"/>
          <volist name="goodsList['list']" id="vo">
            <dl f="gourl" data-url="{:U('Product/productDetail',array('id' => $vo['id']))}">
              <dt><img src="{$vo.upload_files}" style="width: 113.333px; height: 113.333px;" /></dt>
              <!-- <dt><img src="{$vo.thumb_files}" style="width: 113.333px; height: 113.333px;" /></dt> -->
              <dd>
                <h3>{$vo['product_name']}</h3>
              <p>型号：{$vo['product_sn']}</p>
              <p>价格：<span>{$vo['bprice']}</span></p>
              </dd>
            </dl>
          </volist>
        <div class="clear"></div>
      </div>
   </div>

   <div class="oe_index_article">
      <h2>精彩活动<span f="gourl" style="display:none;">MORE</span></h2>
      <information name="information" sign="index" cid="0"/>
      <volist name="information" id="vo">
        <dl f="gourl" data-url="{:U('Article/articleDetail',array('id' => $vo['id']))}">
          <dt>
          <h3>{$vo['title']}</h3>
          <p>{$vo['summary']}</p>
        </dt>
        <dd>
            <span>分类：{:getCategoryName($vo['cat_id'])}</span>
            <label>浏览：{$vo['hits']}  &#12288;</label>
        </dd>
        </dl>
      </volist>
      <div class="clear"></div>
      <div class="oe_index_article_more" f="gourl" style="display:none;">更多资讯</div>
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