<div class="oe_topsearch" style="display: none;">
  <span class="oe_jian" f="search"></span>
  <form method="post" action="{:U('Search/search')}" id="serform" />
  <div class="search">
    <select name="type" id="type">
    	<option value="product" <if condition="product">selected</if>>&nbsp;产品&nbsp;</option>
    	<option value="photo" <if condition="photo">selected</if>>&nbsp;图库&nbsp;</option>
    	<option value="article" <if condition="article">selected</if>>&nbsp;文章&nbsp;</option>
    	<option value="download" <if condition="download">selected</if>>&nbsp;下载&nbsp;</option>
    	<option value="hr" <if condition="hr">selected</if>>&nbsp;招聘&nbsp;</option>
    </select><input type='text' name='keyword' id="keyword" value="" /><input class='searchimage' type='submit' f='btnser' />
  </div>
  </form>
</div>

