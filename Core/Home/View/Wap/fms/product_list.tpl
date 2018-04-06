<extend name="Wap/fms/Common:base" />
<block name="main">
<php>$cat_id = I('get.catId','0')</php>
  <goods name="goodsList" sign="'list'" category_id="$cat_id"/>
  <div class="main pd">
      <if condition="empty($goodsList['list'])">
        <p style="padding:10px;text-align:center;">没有符合条件的信息</p>
      <else />
        <div class="godlist">
            <ul class="indgoc god" id="Jgodlist">
              <volist name="goodsList['list']" id="vo">
                <li>
                    <a href="{:U('Goods/goodsDetail',array('id' => $vo['id']))}" class="ali">
                      <div class="imgbox">
                          <img src="{$vo['goods_image']}" >               
                      </div>
                      <p class="name db-overflow">{$vo['goods_name']}</p>
                    </a>
                </li>
              </volist>
            </ul>
            <p class="nomore Jnomore">我也是有底线的~</p>
            <div class="pull-up JpullUp">
                <img src="__PUBLIC__/Wap/fms/images/icon2.png">
            </div>
        </div>
      </if>
  </div>

</block>
<block name="script">
    <script type="text/javascript">
        var page = 1,is_load = true;
        var catId = {:I('get.catId','0')};
        function getData(){
          $.ajax({
            url: '{:U('Goods/getGoodsList')}',
            type: 'post',
            dataType: 'json',
            data: {index: 'list', page: page, category_id:catId },
          })
          .done(function(data) {
            console.log(data);
            var url = "{:U('Goods/goodsDetail')}";
            var list ='';
            if(data['data']['list'].length > 0){
              for (var i = 0; i < data['data']['list'].length; i++) {
                var dlist = data['data']['list'][i];
                list += '<li><a href="'+url+'?id='+dlist['id']+'" class="ali"><div class="imgbox"><img src="'+dlist['goods_image']+'" ></div><p class="name db-overflow">'+dlist['goods_name']+'</p></a></li>';
              }
              is_load = true;
            }else{
              $(".Jnomore").show();
            }
            $('#Jgodlist').append(list);
            $(".JpullUp").hide();
          })       
        }

        function loadMore(){
          if( is_load == true ){
            $(".JpullUp").show();
            page++;
            getData();
            is_load = false;
          }
        }
        isScroll(loadMore);
        // document.addEventListener('scroll',function(){
        //   var _top = document.documentElement.scrollTop || document.body.scrollTop;
        //   if(_top + window.innerHeight >= document.body.clientHeight){
        //     $(".JpullUp").show();
        //     page++;
        //     console.log(page);
        //     getData();
        //   }
        // })
        
       
    </script>
</block>