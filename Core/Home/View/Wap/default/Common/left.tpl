<div class="oe_left" style="display: none;">
  <div class="oe_left_top">
    <span f="nav">导航</span>
  </div>
  <category name="category" moduleId="0" rootId="0"/>
  <volist name="category" id="vo">
      <dl id="nav_{$vo.catid}" f="gourl" data-url="{:U($vo['url'])}"> 
        <dt style="background:url({$vo['images']}) 0px 15px no-repeat; background-size:18px;"></dt> 
      <dd>{$vo.cat_name}</dd> 
      </dl>
  </volist>

</div>
