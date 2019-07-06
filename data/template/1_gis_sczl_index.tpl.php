<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<html>
<head>
<script src="http://www.w3school.com.cn/jquery/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
//    var domainstr = 'http://silu.topmy.cn';
    var domainstr = 'http://bbs31.com';
    
$(document).ready(function(){
    //资源目录
  $("#b01").click(function(){
  $.ajax({
  url: domainstr +"/plugin.php?id=gis_sczl:gisapi",
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
  url: domainstr +"/plugin.php?id=gis_sczl:gisapi",
  type: "post",
  dataType: "json",
  data: {
      "mod": "gisinput",
      "name": "面 - 标注信息sdfsdf",
      "types": 3,
      "lnglat": [["116.258446","37.686622"],["120.51","30.40"],["98.51", "40.40"]],
      "resid": 1,
      "resid2": 3,
      "resid3": 8,
//      'gisid': 19,
  },
  success:function(res){
      
   $("#myDiv").html(JSON.stringify(res));
  },
  error:function(res){}

  });

  });
  
  
  
   //编辑
  $("#b03").click(function(){
  $.ajax({
  url: domainstr +"/plugin.php?id=gis_sczl:gisapi",
  type: "post",
  dataType: "json",
  data: {
      "mod": "getres",
      "resid": 1,
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


<button id="b03" type="button">获取资源列表信息</button>

</body>
</html>
