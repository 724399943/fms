	<div class="swiper-container oe_banner">
		<div class="swiper-wrapper">
		<swiper name="swiper" sign="index" group_id="2"/>
		<volist name="swiper" id="vo">
			<div class="swiper-slide">
				<a href="{$vo['url']}"><img src="{$vo['image']}" style="height: 180px" /></a>
			</div>
		</volist>
		</div>
		<div class="swiper-pagination"></div>
	</div>
