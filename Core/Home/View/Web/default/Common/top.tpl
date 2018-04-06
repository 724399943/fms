<div class="oe_top">
  <div class="oe_topcn">
    <div class="oe_top_tel"> 
      <!--{assign var="contact" value="<!--{label name="contact"}-->"}--> 
      <!--{if empty($contact)}--> 
      <!-- 请在后台添加自定义HTML标签：contact  -->
      <!--{else}--> 
      <!--{$contact}--> 
      <!--{/if}--> 
    </div>
    <div class="oe_top_tips">
      <!--{assign var="toptips" value="<!--{label name="toptips"}-->"}--> 
      <!--{if empty($toptips)}--> 
      <!-- 请在后台添加自定义HTML标签：toptips -->
      <!--{else}--> 
      <!--{$toptips}--> 
      <!--{/if}--> 
    </div>
    <div class="oe_topsearch">
      <form method="post" action="" name="myform" id="myform" onsubmit="return checksearch();" >
      <span class="select">
          <select name="type" id="type">
              <option value="product" <if condition="product">selected</if>>&nbsp;产品&nbsp;</option>
              <option value="photo" <if condition="photo">selected</if>>&nbsp;图库&nbsp;</option>
              <option value="article" <if condition="article">selected</if>>&nbsp;文章&nbsp;</option>
              <option value="download" <if condition="download">selected</if>>&nbsp;下载&nbsp;</option>
              <option value="hr" <if condition="hr">selected</if>>&nbsp;招聘&nbsp;</option>
          </select>
      </span>
      <span class="input">
          <input type='text' name='keyword' id="keyword" value="" />
      </span>
          <input class='searchimage' type='submit' />
      </form>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="oe_big_logo">
  <div class="oe_logo">
    <div class="oe_logocn"><a href="<!--{$config.siteurl}-->"><img src="<!--{$config.logo}-->"> </a></div>
    <div class="oe_menu">
      <ul>
        <!--{assign var='mymenu' value=vo_category("type={sedmenu}")}--> 
        <!--{foreach $mymenu as $parent}-->
        <li f="show"> 
          <!--{if $parent.url==''}--> 
          <a><!--{$parent.catname}--></a> 
          <!--{else}--> 
          <a href="<!--{$parent.url}-->"><!--{$parent.catname}--></a> 
          <!--{/if}--> 
          <!--{if !empty($parent.childmenu)}-->
          <div id="show" style="display:none;">
            <img src="<!--{$skinpath}-->images/ico_menu_s.png" />
            <!--{foreach $parent.childmenu as $child}--> 
            <a href="<!--{$child.url}-->"><!--{$child.catname}--></a> 
            <!--{/foreach}--> 
          </div>
          <!--{/if}--> 
        </li>
        <!--{/foreach}-->
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>

<script language="javascript">
  function checksearch(){
    if($("#type").val()==""){
      alert("请选择搜索频道.");
      return false;
    }
    if($("#keyword").val()==""){
      alert("关键字不能为空.");
      return false;
    }	
  }
  $(function(){
    $("[f='show']").bind("mouseover", function(){ //鼠标经过
      $(this).addClass("current");
      $(this).children("#show").show();
    });
    $("[f='show']").bind("mouseout", function(){ //鼠标离开
      $(this).removeClass("current");
      $(this).children("#show").hide();
    });
  });
</script>