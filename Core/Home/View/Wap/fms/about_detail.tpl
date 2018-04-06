<extend name="Wap/fms/Common:base" />
<block name="main">
	<div class="main pd">
        <about name="single" sign="about" tree_id="61" cat_id="61"/>
        <div class="absTop">
        <p class="title">{$single['title']}</p>

        <about name="aboutUs" sign="about" tree_id="61" cat_id="62"/>
        <p>{$aboutUs['title']}</p>
        </div>
        <div class="absMsg">
            {$aboutUs['content']}
        </div>
        <div class="absCall">
        <about name="contact" sign="about" tree_id="61" cat_id="71"/>
            <p class="title">{$contact['title']}</p>
            <div class="absEmail">
                <div id="email" class="content">
                    <p style="text-align:center;">
                        <span style="color:#4896ff;">官方邮箱</span>
                    </p>
                    <p style="text-align:center;">
                        <span style="color:#00D5FF;"><span style="color:#999999;">support@fmsmodel.com</span><span style="color:#999999;"></span><br>
                        </span>
                    </p>
                </div>
                <div id="service" class="content" style="display:none">
                    <p style="text-align:center;">
                        <span style="color:#4896ff;">长按关注小服</span>
                    </p>
                    <p style="text-align:center;">
                        <img src="/Static/Public/Wap/fms/images/qrcode.jpg" style="margin-top:10px;width:145px">
                    </p>
                </div>
                <div id="qq" class="content" style="display:none">
                    <p style="text-align:center;">
                        <span style="color:#4896ff;">官方交流群</span>
                    </p>
                    <p style="text-align:center;">
                        255198253
                    </p>
                </div>
            </div>
        </div>
        <div class="absCalltp">            
            <div class="item-wrap">
                <div class="item" data-tag="email">
                    <div class="triangle"></div>
                    <img src="__PUBLIC__/Wap/fms/images/icon3.png">
                    <p>邮箱</p>
                </div>
                <div class="item" data-tag="service">
                    <div class="triangle"></div>
                    <img src="__PUBLIC__/Wap/fms/images/icon4.png">
                    <p>官方客服</p>
                </div>
                <div class="item" data-tag="qq">
                    <div class="triangle"></div>
                    <img src="__PUBLIC__/Wap/fms/images/icon5.png">
                    <p>官方交流群</p>
                </div>
            </div>
        </div>
    </div>
</block>
<block name="script">
<script type="text/javascript">
    $('.item').click(function() {
        var tag = $(this).data('tag');
        $('.content').hide();
        $('#'+tag).show();
        $(".item-wrap").find(".triangle").hide();
        $(this).find(".triangle").show();
    });
</script>
</block>