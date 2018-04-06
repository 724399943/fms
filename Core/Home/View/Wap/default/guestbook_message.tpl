<extend name="Wap/default/Common:base" />
<block name="main">
<div class="oe_nv" f="back"> <span>返回</span>
<div class="oe_nvcn"> 在线留言 </div>
</div>
<div class="oe_guestbook">
  <form method="post" action="{:U('GuestBook/addGuestBookMessage')}" name="myform" id="myform" onsubmit="return checkform();" />
  <dl>
    <dt>姓名：<font color='red'>*</font></dt>
    <dd>
      <input name="username" id="username" type="text" class="input_w1" />
    </dd>
  </dl>
  <dl>
    <dt> 标题：<font color='red'>*</font> </dt>
    <dd>
      <input name="title" id="title" type="text" class="input_w1" />
    </dd>
  </dl>
  <dl>
    <dt>邮箱：<font color='red'>*</font></dt>
    <dd>
      <input name="email" id="email" type="text" class="input_w1" />
    </dd>
  </dl>
  <dl>
    <dt>留言内容：<font color='red'>*</font></dt>
    <dd>
      <textarea name="content" id="content"></textarea>
    </dd>
  </dl>
  <dl>
    <dt>QQ：</dt>
    <dd>
      <input name="qq" id="qq" type="text" class="input_w1" />
    </dd>
  </dl>
  <dl>
    <dt>手机号码：</dt>
    <dd>
      <input name="mobile" id="mobile" type="text" class="input_w1" />
    </dd>
  </dl>
  <dl>
    <dt> 验证码：<font color='red'>*</font></dt>
    <dd>
      <input id="checkcode" name="checkcode" type="text" class="input_w2" />
      <img id="checkCodeImg" src="{:U('GuestBook/verify')}" style="vertical-align:middle;" /> <a href="javascript:refreshCc();">看不清楚，换一张</a></dd>
  </dl>
  <dl>
    <dd class="oe_guest_button">
      <input type="submit" name="btn_save" value=" 提交保存 " class="button_w1" />
    </dd>
  </dl>
  </form>
</div>
<block name="script">
    <script language="javascript">
          function checkform(){
          	if($("#username").val()==""){
          		alert("姓名不能为空.");
          		return false;
          	}
          	if($("#title").val()==""){
          		alert("标题不能为空.");
          		return false;
          	}
          	if($("#email").val()==""){
          		alert("邮箱不能为空.");
          		return false;
          	}
          	if($("#content").val()==""){
          		alert("留言内容不能为空.");
          		return false;
          	}
          	if($("#checkcode").val()==""){
          		alert("验证码不能为空.");
          		return false;
          	}
          	
          }
          function refreshCc() {
          	var ccImg = document.getElementById("checkCodeImg");
          	if (ccImg) {
          		ccImg.src= ccImg.src + '?t=' + Date.parse(new Date());
          	}
          }
    </script>
</block>
</block>