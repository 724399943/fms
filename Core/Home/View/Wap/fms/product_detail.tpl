<extend name="Wap/fms/Common:base" />
<block name="main">
	<div class="main pd">
        <div class="banner">
            <div class="slideBox" id="focus">
                <div class="hd">
                    <ul>
                    <for start="0" end="$info['imgCount']">
                        <li></li>
                    </for>
                    </ul>
                </div>
                <div class="bd">
                    <ul>
                        <volist name="info['goodsImages']" id="vo">
                        <li>
                            <a href="javascript:;">
                                <img src="{$vo}">
                            </a>
                        </li>     
                        </volist>                   
                    </ul>                           
                </div>                                  
            </div>
        </div>
        <!-- <php>dump($info['attrData'])</php> -->
        <div class="detail">
            <p class="name db-overflow">{$info['goods_name']}</p>
            <notempty name="info['attrData']">
                <p class="title">规格参数</p>
                <ul class="detul">
                    <volist name="info['attrData']" id="vo">
                        <li>
                            {$vo['attr_name']} ：<volist name="vo['attrValue']" id="v1">{$v1['attr_value']};</volist>
                        </li>
                    </volist>
                </ul>
            </notempty>
                <div class="detcont">
                    <p class="title">产品详情</p>
                    <div>
                        {$info['goodsDesc']}
                    </div>
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