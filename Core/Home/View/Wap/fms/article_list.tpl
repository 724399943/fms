<extend name="Wap/fms/Common:base" />
<block name="main">
	<div class="main pd">
          <!-- <p style="padding:10px;text-align:center;">没有符合条件的信息</p> -->
            <ul class="article-cate" id="JcateTab">
                <li data-id="2" data-page="1" data-none="0" class="cate-active">FMS巡回游</li>
                <li data-id="74" data-page="0" data-none="0">公益活动</li>
                <li data-id="75" data-page="0" data-none="0">抽奖福利</li>
            </ul>
            <div class="artlist">
                <ul class="indcle Jartlist" style="display:none;">
                    
                </ul>
                <ul class="indcle Jartlist" style="display:none;">
                     
                </ul>
                <ul class="indcle Jartlist" style="display:none;">
                       
                </ul>    
                <p class="nomore Jnomore">我也是有底线的~</p>             
                <div class="pull-up JpullUp">
                    <img src="__PUBLIC__/Wap/fms/images/icon2.png">
                </div>
            </div>
    </div>
</block>
<block name="script">
    <script type="text/javascript">
    $(function(){
        getData();
        $(".Jartlist").eq(0).show();
    })
        var page = 1,catId = 2,is_load = true,listIndex = 0;

        function getData(){          
          $.ajax({
            url: '{:U('Article/loadArticleList')}',
            type: 'post',
            dataType: 'json',
            data: { catId:catId, page: page },
          })
          .done(function(data) {
            var url = "{:U('Article/articleDetail')}";
            var list ='';
            if(data['data']['list'].length > 0){
              for (var i = 0; i < data['data']['list'].length; i++) {
                var dlist = data['data']['list'][i];
                var time = UnixToDate(dlist['add_time'],0,false);
                list += '<li><a href="'+url+'?id='+dlist['id']+'" class="ali"><div class="imgbox"><img src="'+dlist['image']+'"></div><div class="artcle"><p class="title db-overflow"><strong>'+dlist['title']+'</strong></p><p class="time">'+time+'</p></div></a></li>';
              }
              is_load = true;
            }else{
                $("#JcateTab li").eq(listIndex).attr("data-none",1);
                $(".Jnomore").show();                
            }
            $('.Jartlist').eq(listIndex).append(list);            
            $(".JpullUp").hide();
          })       
        }        

        function loadMore(){
            if( is_load == true ){
                $(".JpullUp").show();
                page = $("#JcateTab li").eq(listIndex).attr("data-page");
                page++;
                getData();
                $("#JcateTab li").eq(listIndex).attr("data-page",page);
                is_load = false;
            }
        }
        isScroll(loadMore);
        // document.addEventListener('scroll',function(){
        //   var _top = document.documentElement.scrollTop || document.body.scrollTop;
        //   if(_top + window.innerHeight >= document.body.clientHeight){
        //     $(".JpullUp").show();
        //     page++;
        //     getData();
        //   }
        // })
         
        $("#JcateTab li").click(function(){
            listIndex = $(this).index();
            $("#JcateTab li").removeClass("cate-active");
            $(this).addClass("cate-active");
            catId = $(this).attr("data-id");
            page = $(this).attr("data-page");
            if( page == 0 ){
                page++;
                getData();                
                $(this).attr("data-page",page);
            }         
            if( $(this).attr("data-none") == 1 ){
                $(".Jnomore").show();    
            }else{
                $(".Jnomore").hide();
            }        
            $(".Jartlist").hide(); 
            $(".Jartlist").eq(listIndex).show();                  
        })
    </script>
</block>