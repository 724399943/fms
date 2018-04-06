<swiper name="swiper" sign="index" group_id="2"/>
<div class="banner">
  <div class="slideBox" id="focus">
    <div class="hd">
      <ul>
        <for start="0" end="$swiper['imgCount']">
            <li></li>
        </for>
      </ul>
    </div>
      <div class="bd">
        <ul>
    		<volist name="swiper['list']" id="vo">
    			<li>
    				<a href="{$vo['url']}">
		          <img src="{$vo['image']}">
		        </a>
    			</li>
    		</volist>
    		</ul>                          	
    	</div>                                  
	</div>
</div>