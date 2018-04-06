/**
 * [OECMS] (C)2010-2099 oephp.com,oecms.cn Inc.
 * Email: service@phpcoo.com
 * $LastTime 2016.03.19 Design by De$
*/
$(document).ready(function () {

	//跳转链接
	$("[f='gourl']").bind("click", function(){
		$_url = $(this).attr("data-url");
		if (typeof($_url) == "undefined") {
			$_url = "";
		}
		if ($_url.length > 0) {
			window.location.href = $_url;
		}
	});

	//查询点击事件
	$('[f="search"]').bind("click",function(){
		$(".oe_topsearch").slideToggle();
	});

	//导航点击事件
	$('[f="nav"]').bind("click", function(){
		$(".oe_left").slideToggle();
	});

	//返回前一页
	$('[f="back"]').bind("click",function(){
		window.history.back();
	});

});

//图片缩略图效果
function oecmsDrawImage(ImgD, FitWidth, FitHeight){
    var image = new Image();
    image.src = ImgD.src;
    if(image.width>0 && image.height>0){
        if(image.width/image.height>= FitWidth/FitHeight){
            if(image.width>FitWidth){
                ImgD.width=FitWidth;
                ImgD.height=(image.height*FitWidth)/image.width;
            }else{
                ImgD.width=image.width; 
                ImgD.height=image.height;
            }
        }else{
            if(image.height>FitHeight){
                ImgD.height=FitHeight;
                ImgD.width=(image.width*FitHeight)/image.height;
            }else{
                ImgD.width=image.width; 
                ImgD.height=image.height;
            } 
        }
    }
    //居中
    if(ImgD.height < FitHeight ){
        var paddH = parseInt((FitHeight - ImgD.height)/2);
        ImgD.style.paddingTop    = paddH+"px";
        ImgD.style.paddingBottom = paddH+"px";
    }
    if(ImgD.width < FitWidth ){
        var paddW = parseInt((FitWidth - ImgD.width)/2);
        ImgD.style.paddingRight = paddW+"px";
        ImgD.style.paddingLeft  = paddW+"px";
    }
 }