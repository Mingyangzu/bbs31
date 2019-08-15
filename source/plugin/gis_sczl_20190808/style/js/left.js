var Height = $(document).height() - 120;
$("#test12").css("height",Height)
//左侧切换
$(".leftHeadTab li").click(function() {
	$(this).siblings('li').removeClass('active')
	$(this).addClass('active');
})
//左侧点击划入划出
//$('.fixed').css({
//	"height": Height
//});
$(".contract_btn").css({
	"top": Height / 2.5
})
var isHiden = true;
$(".contract_btn").click(function() {
	isHiden = !isHiden;
	if (isHiden) {
		$('.fixed').removeClass("yidong");
		$('.fixed').addClass("sadfg");
		$('.contract_btn img').css({
			"transform": "rotate(360deg)"
		});
	} else {
		$('.fixed').removeClass("sadfg");
		$('.fixed').addClass("yidong");
		$('.contract_btn img').css({
			"transform": "rotate(180deg)"
		});
	}
})
//资源目录  信息录入  切换
$(".dtcx_Resourcecatalog").click(function() {
	$(".tab_left").css({
		"display": "block"
	})
	$(".xxlr").css({
		"display": "none"
	})
})
$(".dtcx_displayControl").click(function() {
	$("#container").addClass("wagvc")
	map.on('click', function(e) {
		$("#lngs").val(e.lnglat.lng)
		$("#lats").val(e.lnglat.lat)
	});
	$(".tab_left").css({
		"display": "none"
	})
	$(".xxlr").css({
		"display": "block"
	})
})
var setting = {
	check: {
		enable: true
	},
	data: {
		key: {
			title: "title"
		},
		simpleData: {
			enable: true
		}
	}
};
