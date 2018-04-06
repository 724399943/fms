<extend name="Wap/fms/Common:base" />
<block name="main">
    <goods name="goods" sign="index" category_id="0" />
	<div class="main pd">
        <div class="indWrap">
            <include file="Wap/fms/Common:banner" />
            <div class="warp-box-item">
              <div class="item on"><a href="/Goods/goods/catId/2.html"><img src="__PUBLIC__/Wap/fms/images/p1.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/3.html"><img src="__PUBLIC__/Wap/fms/images/p2.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/4.html"><img src="__PUBLIC__/Wap/fms/images/p3.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/5.html"><img src="__PUBLIC__/Wap/fms/images/p4.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/6.html"><img src="__PUBLIC__/Wap/fms/images/p5.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/8.html"><img src="__PUBLIC__/Wap/fms/images/p6.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/15.html"><img src="__PUBLIC__/Wap/fms/images/p7.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/9.html"><img src="__PUBLIC__/Wap/fms/images/p8.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/10.html"><img src="__PUBLIC__/Wap/fms/images/p9.png"></a></div>
              <div class="item "><a href="/Goods/goods/catId/11.html"><img src="__PUBLIC__/Wap/fms/images/p10.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/12.html"><img src="__PUBLIC__/Wap/fms/images/p11.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/13.html"><img src="__PUBLIC__/Wap/fms/images/p12.png"></a></div>
              <div class="item"><a href="/Goods/goods/catId/14.html"><img src="__PUBLIC__/Wap/fms/images/p13.png"></a></div>
              <div class="item on"><a href="/Goods/goods/catId/16.html"><img src="__PUBLIC__/Wap/fms/images/p14.png"></a></div>
              <div class="item"><a href="javascript:alert('敬请期待');"><img src="__PUBLIC__/Wap/fms/images/p15.png"></a></div>
              <div class="item on"><a href="javascript:;"><img src="__PUBLIC__/Wap/fms/images/p16.png"></a></div>
            </div>
            
            <div class="ind_cont">
                <div class="ind_goods" style="margin-bottom:20px">
                    <div class="area-title">
                        <p>精彩视频</p>
                        <a href="javascript:;">更多</a>
                    </div>
                    <swiper name="indexVideo" sign="video" group_id="4"/>
                    {$indexVideo}
                </div>
                <div class="ind_goods">
                    <div class="area-title">
                        <p>最新产品</p>
                        <a href="{:U('Product/product')}">更多</a>
                    </div>
                    <ul class="indgoc" style="background: #fff;padding:0 15px">
                     <goods name="goodsList" sign="index" category_id="0" />
                     <volist name="goodsList['list']" id="vo">
                        <li>
                            <a href="{:U('Goods/goodsDetail',array('id' => $vo['id']))}" class="ali">
                              <div class="imgbox">
                                  <img src="{$vo['goods_image']}">                  
                              </div>
                              <p class="name db-overflow">{$vo['goods_name']}</p>
                            </a>
                        </li>
                      </volist>
                    </ul>
                </div>
                <div class="ind_active">
                    <div class="area-title">
                        <p>精彩活动</p>
                        <a href="{:U('Article/article')}">更多</a>
                    </div>
                    <ul class="indcle" style="padding: 0 15px">
                    <information name="information" sign="index" cid="0"/>
                    <volist name="information" id="vo">
                        <li>
                            <a href="{:U('Article/articleDetail',array('id' => $vo['id']))}" class="ali">
                                <div class="imgbox">
                                    <img src="{$vo['image']}">
                                </div>
                                <div class="artcle">
                                    <p class="title db-overflow"><strong class="db-overflow">{$vo['title']}</strong></p>
                                    <!-- <p class="msg db-overflow">{$vo['summary']}</p> -->
                                    <p class="time">{$vo['add_time']|date="Y/m/d",###}</p>
                                </div>
                            </a>
                        </li>
                    </volist>
                    </ul>
                </div>
                <div class="copyright">版权所有 2018 索立得保留所有权利</div>
            </div>
        </div>            
    </div>
</block>
<block name="script">
	<script type="text/javascript" src="__PUBLIC__/Wap/fms/js/TouchSlide.1.0.js"></script>
	<script type="text/javascript">
		TouchSlide({ 
          slideCell:"#focus",
          titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
          mainCell:".bd ul", 
          effect:"left", 
          autoPlay:true,//自动播放
          autoPage:"<li></li>", //自动分页
          switchLoad:"_src" //切换加载，真实图片路径为"_src" 
        });	
	</script>
</block>