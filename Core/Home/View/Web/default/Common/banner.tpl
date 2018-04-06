<div class="fullSlide">
  <swiper name="swiper" sign="index" group_id="2"/>
  <div id="full-screen-slider">
    <empty name="swiper">
      <p style="padding:10px;text-align:center;">在后台添加广告位“index_slide_banner“幻灯片和广告图1920x288px</p>
      <else/>
      <ul id="slides">
          <volist name="swiper" id="vo">
            <li style="background:url('{$vo['image']}') no-repeat center top"> <a href="{$vo['url']}" target="{$vo['target']}"></a>
            </li>
          </volist>
      </ul>
    </empty>
  
  </div>
</div>