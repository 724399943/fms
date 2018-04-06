<div class="headWrap">

	   <header class="head tone">
        <a class="ico menu Jmenu"></a>
        <h1 class="y-confirm-order-h1"><img src="__PUBLIC__/Wap/fms/images/logo.png" class="logo"></h1>
        <a href="{:U('About/aboutUs')}" class="about"></a>
  </header>
    <div class="headCont JheadCont">
        <div class="categoryList">
          <div class="isearch">
          <form method="post" action="{:U('Search/search')}" id="serform">
              <em class="sea"></em>
              <input type="hidden" name="type" value="product" />
              <input type="search" name="keyword"  placeholder="搜索商品" id="Jinput" style="border: none">
              <em class="close" id="JclearInput"></em>
          </form>
          </div>
          <!-- 商品分类 -->
          <div class="clsfiy">
            <goodsCategory name="firstCat" pid="0" />
            <volist name="firstCat" id="first">
              <div class="Jclslist">
                <p class="title">{$first['category_name']}<em class="ico"></em></p>
                <goodsCategory name="category" pid="$first['id']" />
                <ul class="clsul JclsUl">
                  <volist name="category" id="vo">
                   <a href="{:U('Goods/goods',array('catId'=>$vo['id']))}"><li>{$vo['category_name']}</li></a>
                  </volist>
                </ul>
              </div>
            </volist>
          </div>
        </div>
    </div>
</div> 
<script type="text/javascript">
    var openbol = true;

    // 菜单
    $(".Jmenu").click(function(){
        if( openbol == true ){
          $(this).addClass("on");
          $(".JheadCont").fadeIn(200);
          $("body").css({"overflow":"hidden"});
          openbol = false;          
        }else{
          $(this).removeClass("on");
          resetFun();
          openbol = true;
        }
    })

    $("#Jinput").bind("input",function(){
      if( $(this).val().length > 0 ){
        $("#JclearInput").show();
      }else{
        $("#JclearInput").hide();
      }
    })

    $("#JclearInput").click(function(){
      $("#Jinput").val("");
    })

    function resetFun(){
      $(".JheadCont").fadeOut(200);
      $("body").css({"overflow":"auto"});
      $("#Jinput").val("");
      $("#JclearInput").hide();
    }
    
    $('.sea').click(function(){
      $("#serform").submit();
    })

    $(".Jclslist").on("click",".title",function(){
      $(this).toggleClass('on');
      $(this).siblings(".JclsUl").toggle();
    })
</script>