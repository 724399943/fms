<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title><block name="title">{$seo['tags']|default=$info['tags']}</block><block name="suffix"></block></title>
    <meta name="renderer" content="webkit">
    <meta name="description" content="{$seo['meta_description']|default=$info['meta_description']}">
    <meta name="keywords" content="{$seo['meta_keyword']|default=$info['meta_keyword']}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Wap/fms/css/base.css">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Wap/fms/css/fmsStyle.css">
    <block name="style"></block>
    <script type="text/javascript" src="__PUBLIC__/Wap/fms/js/jquery.min.js"></script>
    <script type="text/javascript">        
        /**              
         * 时间戳转换日期              
         * @param <int> unixTime    待时间戳(秒) 
         * @param <int>  type   返回的格式(0: Y-m-d 或者1: Y/m/d )                 
         * @param <bool> isFull    返回完整时间(false: Y-m-d 或者 true: Y-m-d H:i:s)              
         * @param <int>  timeZone   时区              
         */
        function UnixToDate(unixTime, type, isFull, timeZone) {
            if (typeof (timeZone) == 'number')
            {
                unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
            }
            var time = new Date(unixTime * 1000);
            var ymdhis = "";
            var month = (time.getUTCMonth()+1);
            if( month.toString().length < 2 ){
                month = "0" + month;
            }
            if( type == 0 ){
                ymdhis += time.getUTCFullYear() + "/";
                ymdhis +=  month + "/";
                ymdhis += time.getUTCDate(); 
            }else{
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();                
            }
            if (isFull === true)
            {
                ymdhis += " " + time.getUTCHours() + ":";
                ymdhis += time.getUTCMinutes() + ":";
                ymdhis += time.getUTCSeconds();
            }
            return ymdhis;
        }

        // 通用滚动方法
        function isScroll(bottomCall){
            var startX = 0, startY = 0;
            function touchSatrtFunc(evt) {
                  try
                  {

                      var touch = evt.touches[0]; //获取第一个触点  
                      var x = Number(touch.clientX); //页面触点X坐标  
                      var y = Number(touch.clientY); //页面触点Y坐标  
                      //记录触点初始位置  
                      startX = x;
                      startY = y;

                  } catch (e) {
                      alert( e.message);
                  }
            }
            //touchstart事件  
            document.body.addEventListener('touchstart', touchSatrtFunc, false);
            document.body.addEventListener('touchmove',scrlllfunction,false);
            function scrlllfunction (ev){
                var _point = ev.touches[0];
                 // window滚动
                var _top = document.body.scrollTop || document.documentElement.scrollTop;
                 // 什么时候到底部
                var bottomAdr = document.body.scrollHeight - window.innerHeight;
                  //判断是否滚到底部加载更多
                  if(_top >= bottomAdr-10 && _point.clientY < startY){                 
                      if(bottomCall){
                        bottomCall();
                      }
                  }
                  // 到达顶端
                  if (_top === 0) {
                      // 阻止向下滑动
                      if (_point.clientY > startY) {
                          ev.preventDefault();
                      } else {
                          // 阻止冒泡
                          // 正常执行
                          ev.stopPropagation();
                      }
                  } else if (_top == bottomAdr) {
                      // 到达底部
                      // 阻止向上滑动
                      if (_point.clientY < startY) {
                          ev.preventDefault();
                      } else {
                          // 阻止冒泡
                          // 正常执行
                          ev.stopPropagation();
                      }
                  } else if (_top > 0 && _top < bottomAdr) {
                      ev.stopPropagation();
                  } else {
                      ev.preventDefault();
                  }
            }
        }        
        $(function(){
           $(window).scroll(function(){
              var topScroll =$(window).scrollTop();              
              if(topScroll > 0){
                  $('.scroll-top-box').css({"display":"flex"});
              } else { 
                  $('.scroll-top-box').hide();                                  
              }                            
           }) 
           $('.scroll-top-box').click(function(){
              $(window).scrollTop(0);
           })  
        })
    </script>
</head>
    <block name="header">
        <include file="Wap/fms/Common:header" />
    </block>
    <block name="main"></block>

    <block name="footer">
        <div class="scroll-top-box">
          <img src="__PUBLIC__/Wap/fms/images/top.png">
        </div>
        <include file="Wap/fms/Common:footer" />
    </block>
    
    <block name="script">
      
    </block>
    <script type="text/javascript">
      var lastY;
      $("body").on('touchstart', function(event) {
          lastY = event.originalEvent.changedTouches[0].clientY;
      });
      $("body").on('touchmove', function(event) {
          var y = event.originalEvent.changedTouches[0].clientY;
          var st = $(this).scrollTop();
          if (y >= lastY && st <= 10) {
              lastY = y;
              if( event.preventDefault ){
                event.preventDefault();
              }else{
                event.returnValue = false;
              }
          }
          lastY = y;   
      });  
    </script>
</html>