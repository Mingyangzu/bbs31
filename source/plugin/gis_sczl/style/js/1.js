$(function () {
    // rem配置
    (function (doc, win) {
        var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function () {
                var clientWidth = docEl.clientWidth;
                if (!clientWidth) return;
                if (clientWidth >= 828) {
                    docEl.style.fontSize = '100px';
                } else {
                    docEl.style.fontSize = 100 * (clientWidth / 828) + 'px';
                }
            };
        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        recalc()
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);
    
    
    // map
    $("#add_google").on("click",function(){
    	$(".sl_map_box .gd_map").height(0);
    	var h = parseFloat( $(".sl_map_box").height());
    	$(".sl_map_box .google_map").height(h);
//  	$("#map_iframe").attr('src','google_iframe.html');
    	
    })
    $("#remove_google").on("click",function(){
    	$(".sl_map_box .google_map").height(0);
    	var h = parseFloat($(".sl_map_box").height());
    	$(".sl_map_box .gd_map").height(h);
//  	$("#map_iframe").attr('src','gd_iframe.html');
    })
})