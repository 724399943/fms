/**
 * [OECMS] (C)2010-2099 oephp.com,oecms.cn Inc.
 * Email: service@phpcoo.com
 * $LastTime 2016.03.19 Design by De$
*/
$(function(){
	
	//自动缩略图片
	$("[f='drawimg']").bind("load", function(){
		$_width = $(this).attr("data-width");
		$_height = $(this).attr("data-height");
		oecmsDrawImage(this, $_width, $_height);
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