<extend name="Wap/default/Common:base" />
<block name="main">
<include file="Wap/default/Common:search" />
  <div class="oe_nav">
    <span f="back">返回</span>
    <em>查看详情</em>
    <label f="search"></label>
  </div>
  <div class="oe_product_detail">
    <h1 class="title">{$info['product_name']}</h1>
    <div class="oe_product_con">
      <ul>
        <!-- <li><b>编号 : </b><span>{$info['product_sn']}</span></li>
        <li><b>分类 : </b><span f="gourl" data-url="" style="color: blue">{:getCategoryName($info['cat_id'])}</span></li>
        <li><b>日期 : </b><span>{$info['add_time']|date="Y-m-d",###}</span></li>
        <li><b>价格 : </b><span>{$info['bprice']}</span></li> -->
        <!-- <php>dump($info)</php> -->
        <volist name="info['attrInfo']" id="vo">
          <li>
            <b>{$vo['attr_name']} : </b>
              <span>
                <volist name="vo['attrValue']" id="v1">
                  {$v1['attr_value']};
                </volist>
              </span>
          </li>
        </volist>
        <div class="clear"></div>
  	</ul>
    </div>
    <div class="oe_product_img">
      <ul>
        <div class="swiper-container oe_banner">
          <div class="swiper-wrapper">
          <swiper name="swiper" sign="images" group_id="$info['id']"/>
          <volist name="swiper" id="vo">
            <div class="swiper-slide">
              <a href=""><img src="{$vo['goods_image']}" style="height: 180px" /></a>
            </div>
          </volist>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <!-- <li><img src="{$info['upload_files']}" /></li> -->
        <!--{if !empty($info.gallery)}-->
        <!--{foreach $info.gallery as $val}-->
  	  <li>
  	    <h3><!--{$val.imgname}--></h3>
  	    <img border='0' src='<!--{$val.imgurl}-->'>
  	  </li>
  	  <!--{/foreach}-->
  	  <!--{/if}-->
      </ul>
    </div>
    <div class="oe_product_text">
      {$info['content']}
    </div>
    <div class="nepr">
      <if condition="!empty($previous['id'])">
        上一条：<a href="{:U('Product/productDetail',array('id' => $previous['id']))}">{$previous['product_name']}</a><br>
      <else />
         下一条： 没有了 <br>
      </if>
      <if condition="!empty($next['id'])">
        下一条：<a href="{:U('Product/productDetail',array('id' => $next['id']))}">{$next['product_name']}</a><br>
      <else />
         下一条： 没有了 <br>
      </if>
    </div>
  </div>
</block>
<block name="script">
  <script type="text/javascript">
      $(function(){
        //滑动效果
        var mySwiper = new Swiper ('.swiper-container', {
          loop : true,
          pagination: '.swiper-pagination',
          // autoplay:3000,
          autoplayDisableOnInteraction : false
        });


      });
  </script>
</block>