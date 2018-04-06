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
        <li><b>编号 : </b><span>{$info['product_sn']}</span></li>
        <li><b>分类 : </b><span f="gourl" data-url="<!--{$catinfo.wapurl}-->" style="color: blue">{:getCategoryName($info['cat_id'])}</span></li>
        <li><b>日期 : </b><span>{$info['add_time']|date="Y-m-d",###}</span></li>
        <li><b>价格 : </b><span>{$info['bprice']}</span></li>
        <div class="clear"></div>
  	</ul>
    </div>
    <div class="oe_product_img">
      <ul>
        <li><img src="{$info['upload_files']}" /></li>
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