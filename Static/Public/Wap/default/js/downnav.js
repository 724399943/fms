/**
 * [OECMS] (C)2010-2099 oephp.com,oecms.cn Inc.
 * Email: service@phpcoo.com
 * $LastTime 2016.03.19 Design by De$
*/
function overnav(dom){
                var my = dom.find('ul');
                var my_w = dom.outerWidth(true) + 18;
                var my_h = dom.outerHeight(true);
	                my.css({
					    "position" : "absolute",
					    "display" : "block",
					    "height" : "auto",
					    "left" : "-9px",
					    "top" : my_h,
					    "width" : my_w
					});
}
function outnav(dom){
    var my = dom.find('ul');
	    my.css({
			"display" : "none"
		});
}