var Height = $(document).height()-120;
//左侧切换
$(".leftHeadTab li").click(function() {
	$(this).siblings('li').removeClass('active')
	$(this).addClass('active');
})
//左侧点击划入划出
//$('.fixed').css({
//	"height": Height
//});
$(".contract_btn").css({"top":Height/2.5})
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
		$("#lngs").val(e.lnglat.lat)
		$("#lats").val(e.lnglat.lng)
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
	},
	callback: {
		onCheck: onCheck
	}
};

var zNodes = [{
		id: 1,
		pId: 0,
		name: "世界遗产名录",
		title: ""
	},
	{
		id: 11,
		pId: 1,
		name: "丽江古城",
		title: ""
	},
	{
		id: 111,
		pId: 11,
		name: "中国拉萨布达拉宫历史建筑群",
		title: ""
	},
	{
		id: 112,
		pId: 11,
		name: "乌兹别克斯坦沙赫利苏伯兹历史中心",
		title: ""
	},
	{
		id: 113,
		pId: 11,
		name: "印度阿旃陀石窟",
		title: ""
	},
	{
		id: 114,
		pId: 11,
		name: "阿富汗查姆回教寺院尖塔和考古遗址",
		title: ""
	},
	{
		id: 12,
		pId: 1,
		name: "阿富汗查姆回教寺院尖塔和考古遗址",
		title: ""
	},
	{
		id: 121,
		pId: 12,
		name: "清代",
		title: ""
	},
	{
		id: 13,
		pId: 1,
		name: "印度阿旃陀石窟",
		title: ""
	},
	{
		id: 131,
		pId: 13,
		name: "清代",
		title: ""
	},
	{
		id: 2,
		pId: 0,
		name: "古代丝绸之路",
		title: ""
	},
	{
		id: 21,
		pId: 2,
		name: "汉长安城未央宫遗址",
		title: ""
	},
	{
		id: 211,
		pId: 21,
		name: "耕地面积百分比_AD0",
		title: ""
	},
	{
		id: 212,
		pId: 21,
		name: "耕地面积百分比_AD100",
		title: ""
	},
	{
		id: 213,
		pId: 21,
		name: "耕地面积百分比_AD200",
		title: ""
	},
	{
		id: 22,
		pId: 2,
		name: "唐长安城大明宫遗址",
		title: ""
	},
	{
		id: 221,
		pId: 22,
		name: "牧草地面积百分比_AD0",
		title: ""
	},
	{
		id: 222,
		pId: 22,
		name: "牧草地面积百分比_AD100",
		title: ""
	},
	{
		id: 223,
		pId: 22,
		name: "牧草地面积百分比_AD200",
		title: ""
	},
	{
		id: 3,
		pId: 0,
		name: "预",
		title: ""
	},
	{
		id: 31,
		pId: 3,
		name: "平均气温",
		title: ""
	},
	{
		id: 311,
		pId: 31,
		name: "西安冬季平均气温",
		title: ""
	},
	{
		id: 312,
		pId: 31,
		name: "南五台6到7月平均气温",
		title: ""
	},
	{
		id: 313,
		pId: 31,
		name: "华山5到6月平均气温",
		title: ""
	},
	{
		id: 32,
		pId: 3,
		name: "平均温差",
		title: ""
	},
	{
		id: 321,
		pId: 32,
		name: "陕西和山西平均温差",
		title: ""
	}
];

function onCheck(e, treeId, treeNode) {
	count();
}

function setTitle(node) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	var nodes = node ? [node] : zTree.transformToArray(zTree.getNodes());
	for (var i = 0, l = nodes.length; i < l; i++) {
		var n = nodes[i];
		n.title = "[" + n.id + "] isFirstNode = " + n.isFirstNode + ", isLastNode = " + n.isLastNode;
		zTree.updateNode(n);
	}
}

function count() {
	function isForceHidden(node) {
		if (!node.parentTId) return false;
		var p = node.getParentNode();
		return !!p.isHidden ? true : isForceHidden(p);
	}
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		checkCount = zTree.getCheckedNodes(true).length,
		nocheckCount = zTree.getCheckedNodes(false).length,
		hiddenNodes = zTree.getNodesByParam("isHidden", true),
		hiddenCount = hiddenNodes.length;

	for (var i = 0, j = hiddenNodes.length; i < j; i++) {
		var n = hiddenNodes[i];
		if (isForceHidden(n)) {
			hiddenCount -= 1;
		} else if (n.isParent) {
			hiddenCount += zTree.transformToArray(n.children).length;
		}
	}

	$("#isHiddenCount").text(hiddenNodes.length);
	$("#hiddenCount").text(hiddenCount);
	$("#checkCount").text(checkCount);
	$("#nocheckCount").text(nocheckCount);
}

function showNodes() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getNodesByParam("isHidden", true);
	zTree.showNodes(nodes);
	setTitle();
	count();
}

function hideNodes() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getSelectedNodes();
	if (nodes.length == 0) {
		alert("请至少选择一个节点");
		return;
	}
	zTree.hideNodes(nodes);
	setTitle();
	count();
}

$(document).ready(function() {
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	$("#hideNodesBtn").bind("click", {
		type: "rename"
	}, hideNodes);
	$("#showNodesBtn").bind("click", {
		type: "icon"
	}, showNodes);
	setTitle();
	count();
});
