<div class="oe_nav">
  <span f="back">返回</span>
  <category name="categorys" moduleId="2" rootId="0" />
  <php>
    $url = CONTROLLER_NAME . '/' . ACTION_NAME;
  </php>
  <div class="swiper-container oe_navcn">
    <div class="swiper-wrapper oe_nav_con">
      <volist name="categorys" id="vo">
        <em class="swiper-slide <if condition="$vo['id'] eq $_GET['cat_id']">current</if> " f="gourl"  data-url="{:U($url,array('cat_id'=>$vo['id']))}">{$vo['cat_name']}</em> 
      </volist>
      
    </div>
  </div>
  <label f="search"></label>
</div>

