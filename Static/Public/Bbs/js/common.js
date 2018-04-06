Date.prototype.pattern=function(fmt) {
    var o = {
        "M+" : this.getMonth()+1, //月份
        "d+" : this.getDate(), //日
        "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
        "H+" : this.getHours(), //小时
        "m+" : this.getMinutes(), //分
        "s+" : this.getSeconds(), //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S" : this.getMilliseconds() //毫秒
    };
    var week = {
        "0" : "/u65e5",
        "1" : "/u4e00",
        "2" : "/u4e8c",
        "3" : "/u4e09",
        "4" : "/u56db",
        "5" : "/u4e94",
        "6" : "/u516d"
    };
    if(/(y+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    if(/(E+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);
    }
    for(var k in o){
        if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
    return fmt;
}

/*判断电话格式*/
function isTelephone (telephone) {
    var telReg = !!telephone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[6780]|18[0-9]|14[57])[0-9]{8}$/);
    //如果手机号码不能通过验证
    return telReg;
}

/*判断邮箱格式*/
function isEmail (email) {
    var emailReg = !!email.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    //如果邮箱不能通过验证
    return emailReg;
}

//时间转化
(function($) {
    $.extend({
        myTime: {
            /**
             * 当前时间戳
             * @return <int>        unix时间戳(秒)  
             */
            CurTime: function(){
                return Date.parse(new Date())/1000;
            },
            /**              
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20  日期格式              
             * @return <int>        unix时间戳(秒)              
             */
            DateToUnix: function(string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                        )).getTime() / 1000;
            },
            /**              
             * 时间戳转换日期              
             * @param <int> unixTime    待时间戳(秒)              
             * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
             * @param <int>  timeZone   时区              
             */
            UnixToDate: function(unixTime, isFull, timeZone) {
                if (typeof (timeZone) == 'number')
                {
                    unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                }
                var time = new Date(unixTime * 1000);
                var ymdhis = "";
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();
                if (isFull === true)
                {
                    ymdhis += " " + time.getUTCHours() + ":";
                    ymdhis += time.getUTCMinutes() + ":";
                    ymdhis += time.getUTCSeconds();
                }
                return ymdhis;
            }
        }
    });
})(jQuery);

/*倒计时*/
function countTime(endtime, obj ,callback ,callback2) {         
   var timer = setInterval(function() {     
       if(endtime >= 0) {
            var day = Math.floor(endtime/60/60/24),
                hour = Math.floor(endtime/60/60%24),
                minutes = Math.floor(endtime/60%60),
                seconds = Math.floor(endtime%60);
             if(hour<10) {
                hour = "0"+hour;
             }
             if(minutes<10) {
                minutes = "0"+minutes;
             }
             if(seconds<10) {
                seconds = "0"+seconds;
             }                  
             callback(day,hour,minutes,seconds,obj);     
             -- endtime;
        } else {        
            clearInterval( timer );
            callback2(obj); 
        }        
    }, 1000);     
}

/*计算年龄*/
function calcAge(birthday) {
    var r = birthday.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
    if(r == null)return false;
    var d = new Date(r[1], parseInt(r[3]) - 1, r[4]);     
    if (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]) {   
        var Y = new Date().getFullYear();
        return (Y - r[1]);
        // console.log("年龄   =   " + (Y - r[1]) + "   周岁");   
    } else {
        alert("输入的日期格式错误！");   
    }
}

function timer() {
    var value = Number($('#Jverify').text());
    if (value > 1) {
        document.getElementById("Jverify").innerHTML = value-1;
        //$('#Jverify').text(value - 1);
    } else {
        document.getElementById("Jverify").innerHTML = '重新获取';
        //$('#Jverify').text('重新获取');
        getVerifySign = true;
        return false;
    }
    window.setTimeout("timer()", 1000);
}


/*分页*/
//pageIndex 为当前页  pageCount为总页数 type为当前tab ，部分tab传0；
function setCommentPage(pageIndex, pageCount, type) {
    var temp="";
    var pageType="";
    if(type ==0 ){
        pageType="";
    }else{
        pageType = type;
    }
    //如果页数超过1时候显示分页
    if (pageCount > 1){
        if(pageIndex!=1){
            //如果不是第一页的时候显示上一页
            var k=pageIndex-1;
            temp +='<a id="Jpageprev" class="pageButton prev" ><em></em></a>';
        }else{
            //是第一页的时候隐藏上一页
            temp +='';
        }
        for (i = 1; i <= pageCount;i++)
        {
            if (i == pageIndex)//当前页
            {
                temp +="<a id='comment"+ i+ "' class='pageButton cur Jclick' href='javascript:;'>";
                temp +=i+"</a>";
            }
            else
            {
                if(pageIndex-i>=4&&i!=1)  //只显示当前页前三个页码
                {
                    temp+="<a class='pageButton'>...</a>";
                    i=pageIndex-3;//将页码跳到没有省略的页码
                }
                else
                {
                    if(i>=pageIndex+3&&i!=pageCount)  //只显示当前页的后两个页码
                    {
                        if(pageIndex == pageCount-4){
                            temp +="<a id='comment"+ (pageCount-1) +"' class='pageButton Jclick' page='"+ (pageCount-1) +"' data-type='"+ pageType +"' data-done='0' href='javascript:;'>";
                            temp +=(pageCount-1)+"</a>";
                        }else{
                            temp+="<a class='pageButton'>...</a>";
                        }
                        i=pageCount;  //将页码跳到最后一页
                    }
                    temp +="<a id='comment"+ i +"' class='pageButton Jclick' page='"+ i +"' data-type='"+ pageType +"' data-done='0' href='javascript:;'>";
                    temp +=i+"</a>";
                }
            }
        }
        if(pageIndex!=pageCount)
        {
            //不是最后一页的时候
            var k=pageIndex+1;
            temp +='<a id="Jpageprev" class="pageButton next" ><em></em></a>';
        }else{
            //最后一页的时候
            temp +='';
        }
    }
    if( pageCount==1 ){
        //temp +="<a id='comment"+ 1+ "' class='pageButton cur Jclick' href='javascript:;'>" + 1 + "</a>";
    }
    document.getElementById('pageBox'+pageType).innerHTML=temp;
}

