<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<html>
<head>
<script src="http://www.w3school.com.cn/jquery/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    //资源目录
  $("#b01").click(function(){
  $.ajax({
  url:"http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
  type: "get",
  contentType: "application/x-www-form-urlencoded",
  dataType: "json",
  data:{'mod': 'getreslist'},
  success:function(res){
      
   $("#myDiv").html(JSON.stringify(res));
  },
  error:function(res){}

  });

  });
  
  //编辑
  $("#b02").click(function(){
  $.ajax({
  url:"http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
  type: "post",
  dataType: "json",
  data:{
      'mod': 'gisinput',
      'name': '面 - 标注信息',
      'types': 3,
      'lnglat': [["116.258446","37.686622"],["120.51","30.40"],["98.51", "40.40"]],
      'gisid': 18,
  },
  success:function(res){
      
   $("#myDiv").html(JSON.stringify(res));
  },
  error:function(res){}

  });

  });
  
});
</script>
</head>
<body>

<div id="myDiv"><h2></h2></div>
<button id="b01" type="button">获取资源目录</button>

<button id="b02" type="button">编辑标注信息</button>

</body>
</html>
