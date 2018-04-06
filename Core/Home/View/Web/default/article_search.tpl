<extend name="Wap/default/Common:base" />
<block name="main">
<include file="Wap/default/Common:search" />
<div class="oe_nav">
  <span f="back">返回</span>
  <em>搜索：{$keyword}</em>
  <label f="search"></label>
</div>
<div class="oe_article_list">
 <if condition="empty($search)">
    <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
  <else />
    <volist name="search" id="vo">
      <dl f="gourl" data-url="{:U('Article/articleDetail',array('id' => $vo['id']))}">
        <dt>
    	  <h3>{$vo['title']}</h3>
          <p>{:strCut($vo['content'],200)}/p>
        </dt>
      	<dd>
          <span>分类：{:getCategoryName($vo['cat_id'])}</span>
          <label>浏览：{$vo['hits']}  &#12288;{$info['add_time']|date="Y-m-d",###}</label>
        </dd>
      </dl>
    </volist>
  <div class="clear"></div>
  </if>
</div>
<div class="page-m">
    <div class="page-box">{$page}</div>
</div>
</block>