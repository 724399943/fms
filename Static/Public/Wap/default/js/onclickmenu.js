/**
 * [OECMS] (C)2010-2099 oephp.com,oecms.cn Inc.
 * Email: service@phpcoo.com
 * $LastTime 2016.03.19 Design by De$
*/
$(document).ready(function(){
	var Plug1 = 0; 
	var Plug2 = 1; 
	var i = 0;
	var SideBar = $("#web-sidebar");
	var SideBar_dt = SideBar.find("dt.part2");
	var SideBar_dd = SideBar.find("dd.part3dom");
	var part1_dom = $("#part1-id0"); 
	var part2_dom = $("#part2-id0"); 
	var part_dom_dd = part1_dom.next("dd.part3dom");
	SideBar_dd.css("display","none");
	part1_dom.addClass("ondown");
	part2_dom.addClass("ondown");
	if(Plug1 == 1){ SideBar_dd.css("display","block"); SideBar_dt.addClass("part_on"); i = 1;} 
	if(Plug2 == 1 && part1_dom.length!=0){
			part_dom_dd.css("display","block");
			i = 1; 
	}
	SideBar_dt.click(function(){
		i++;if(i>1)i=0;
		i==1?SideBar_on($(this)):SideBar_out($(this));	
	});
   //****************
});

function SideBar_on(dom){
	var dd = dom.next("dd.part3dom")
	dom.addClass("part_on");
	dom.removeClass("part_out");
	dd.css("display","block");
}

function SideBar_out(dom){
	var dd = dom.next("dd.part3dom")
	dom.addClass("part_out");
	dom.removeClass("part_on");
	dd.css("display","none");
}