<extend name="Wap/default/Common:base" />
<block name="main">
<include file="Wap/default/Common:search" />
<div class="oe_nav">
  <span f="back">返回</span>
  <em>查看详情</em>
  <label f="search"></label>
</div>
<div class="oe_article_detail">
  <h1 class="title">{$info['title']} </h1>
  <div class="oe_article_des">
    发布者：{$info['author']}
	浏览次数：{$info['hits']}
	时间：{$info['add_time']|date="Y-m-d",###}
  </div>
  <div class="oe_article_text">
    {$info['content']}
  </div>
  <div>
  <if condition="!empty($previous['id'])">
     上一篇：<a href="{:U('Article/articleDetail',array('id' => $previous['id']))}">{$previous['title']}</a><br>
  <else />
     上一篇： 没有了 <br>
  </if>
  <if condition="!empty($next['id'])">
     下一篇：<a href="{:U('Article/articleDetail',array('id' => $next['id']))}">{$next['title']}</a><br><br>
  <else />
     下一篇： 没有了 <br>
  </if>
  </div>
</div>
</block>