<extend name="Wap/default/Common:base" />
<block name="main">
  <include file="Wap/default/Common:nav" />
  <article name="articleList" sign="'list'" catId="0"/>
  <include file="Wap/default/Common:search" />
  <div class="oe_article_list">
    <if condition="empty($articleList['list'])">
      <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
    <else />
    <volist name="articleList['list']" id="vo">
      <dl f="gourl" data-url="{:U('Article/articleDetail',array('id' => $vo['id']))}">
        <img src="{$vo['images']}" width="100px" height="100px" style="float:left">
        <dt style="padding-left:110px">
    	  <h3>{$vo['title']}</h3>
          <p>{:strCut($vo['content'],200)}</p>
        </dt>
      	<dd>
          <span style="left: 110px;">分类：{:getCategoryName($vo['cat_id'])}</span>
          <label>浏览：{$vo['hits']}  &#12288;{$vo['add_time']|date="Y/m/d",###}</label>
        </dd>
      </dl>
    </volist>
    <div class="clear"></div>
    </if>
  </div>
  <if condition="!empty($articleList['page'])">
    <div class="page-m">
      <div class="page-box">{$articleList['page']}</div>
    </div>
  </if>
</block>

