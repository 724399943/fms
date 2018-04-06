<extend name="Wap/fms/Common:base" />
<block name="main">
<div class="headCont JproductSearch" >
    <div class="categoryList">
        <div class="isearch">
        <form method="post" action="{:U('Search/search')}" id="serchform">
            <em class="sea sea2"></em>
            <input type="hidden" name="type" value="product" />
            <input type="search" name="keyword" placeholder="搜索商品" id="Jinput" value="{$keyword}" style="border: none">
            <em class="close" id="JclearInput"></em>
        </form>
        </div>

        <!-- 搜索结果 -->
        <div class="iseaResult">
          <if condition="empty($search)">
            <!-- 无结果 -->
            <div class="noResult">
                <img src="__PUBLIC__/Wap/fms/images/icon10.png">
                <p>暂无数据~</p>
            </div>
            <a href="{:U('Goods/goods')}" class="all">查看全部产品</a>

            <else />
            
            <!-- 有结果 -->
            <ul class="indgoc god">
            <volist name="search" id="vo">
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
            <a href="{:U('Goods/goods')}" class="all">查看全部产品</a>

          </if>
            
        </div>
    </div>
</div>
</block>
<block name="script">
  <script type="text/javascript">
  $(function(){
    $('.Jmenu').addClass("on");
    $('.JproductSearch').show();
  })
  var open = true;
  $(".Jmenu").click(function(){
        if( open == true ){
          $(this).removeClass("on");
          $('.JproductSearch').hide();
          // $(".JheadCont").fadeIn(200);
          // $("body").css({"overflow":"hidden"});
          open = false;          
        }else{
          $(this).addClass("on");
          $('.JproductSearch').show();
          // $(this).removeClass("on");
          // resetFun();
          open = true;
        }
    })
    $('.sea2').click(function(){
      $("#serchform").submit();
    })

  </script>
</block>