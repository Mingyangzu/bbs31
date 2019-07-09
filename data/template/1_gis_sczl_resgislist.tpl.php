<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>layui</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="/source/plugin/gis_sczl/style/layui/css/layui.css"  media="all">
        <style>
            .layui-table-cell .layui-form-checkbox[lay-skin=primary]{top: 5px;}
        </style>
    </head>
    <body>
        <div id='contentbox' style='display: none; margin: 20px auto;'>
            <form class="layui-form" action="/plugin.php?id=gis_sczl:resgis&amp;mod=resgisadd" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">所属目录</label>
                    <div class="layui-input-inline" style='display:block;min-width: 300px;margin-bottom: 5px;'>
                        <select name="reslist1" id='getresarr1'>
                            <option value="">请选择一级目录</option>
                            <option value="浙江" selected="">浙江省</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style='display: block;min-width: 300px;margin: 5px 0 5px 110px;'>
                        <select name="reslist2" id='getresarr2'>
                            <option value="">请选择二级目录</option>
                            <option value="杭州">杭州</option>
                            <option value="宁波" disabled="">宁波</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style='display: block;min-width: 300px;margin: 5px 0 5px 110px;'>
                        <select name="reslist3">
                            <option value="">请选择三级目录</option>
                            <option value="西湖区">西湖区</option>
                            <option value="余杭区">余杭区</option>
                            <option value="拱墅区">临安市</option>
                        </select>
                    </div>
                    <div class="layui-form-mid layui-word-aux"  style='margin: 5px 0 5px 110px;'>如不选择所属目录,默认添加为一级目录</div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="title" autocomplete="off" placeholder="请目录名" class="layui-input" style='width: 80%;'>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                    </div>
                </div>
            </form>
        </div>

        <table class="layui-hide" id="test"  lay-filter="complainList"></table>


        <script type="text/html" id="barDemo">
          <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
          <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script> 
        <script type="text/html" id="complain_toolbar">

            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delAll"><i class="layui-icon"></i>批量删除</button>
                <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon"></i>添加</button>
            </div>
        </script>



        <script src="/source/plugin/gis_sczl/style/layui/layui.js" type="text/javascript" charset="utf-8"></script>
        <script src="/source/plugin/gis_sczl/style/js/jquery-1.4.4.min.js" type="text/javascript"></script>

        <script>
layui.use('table', function () {
    var table = layui.table
            , form = layui.form;

    table.render({
        elem: '#test'
        , url: '/plugin.php?id=gis_sczl:resgis&mod=resgislist'
        , cellMinWidth: 85
        , toolbar: '#complain_toolbar'
        , cols: [[
                //      {type:'numbers'},
                {type: 'checkbox', }
                , {field: 'id', title: 'ID', width: 50, unresize: true, sort: true}
                , {field: 'name', title: '资源目录名', templet: '#nameTpl'}
                , {field: 'types', title: '层级'}
                , {field: 'fid', title: '上级目录', minWidth: 120, }
                //      ,{field:'sex', title:'性别', width:85, templet: '#switchTpl', unresize: true}
                //      ,{field:'lock', title:'是否锁定', width:110, templet: '#checkboxTpl', unresize: true}
                , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 150}
            ]]
        , page: true
    });

    table.on('tool(complainList)', function (obj) {
        var data = obj.data;
        console.log(data);
        switch (obj.event) {
            case 'edit':
                console.log(obj.event);
                var index = layer.open({
                    type: 1,
                    title: "资源目录",
                    area: ['40%', '80%'],
                    fix: false,
                    maxmin: true,
                    shadeClose: true,
                    shade: 0.4,
                    skin: 'layui-layer-rim',
                    content: $('#contentbox'),
                    cancel: function (index, res) {
                        $('#contentbox').css({'display': 'none'});
                    }
                });
//                
                break;
            case 'add':
                console.log(obj.event);
                break;
            case 'del':
                var delIndex = layer.confirm('确定删除id为' + data.id + "的资源目录吗?", function (delIndex) {
                    $.ajax({
                        url: '/plugin.php?id=gis_sczl:resgis',
                        data: {'mod': 'resgisdel', 'resid': data.id},
                        type: "post",
                        dataType: 'json',
                        success: function (suc) {
                            if (suc.msg == 'success') {
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(delIndex);
                                console.log(delIndex);
                                layer.msg("删除成功", {
                                    icon: 1
                                });
                            } else {
                                layer.msg("删除失败", {
                                    icon: 5
                                });
                            }
                        }
                    });
                    layer.close(delIndex);
                });
                break;
            case 'delAll':
                console.log(obj.event);
                break;
        }

    });

});

$('#getresarr1').change(function () {
    console.log($(this).val());
});

getres(1);

function getres(fid) {
    $.ajax({
        url: '/plugin.php?id=gis_sczl:resgis',
        data: {'mod': 'resgisarr', 'fid': fid},
        type: "post",
        dataType: 'json',
        success: function (res) {
            if (suc.msg == 'success') {
                var apphtml = '<option value="">请选择一级目录</option>';
                for (var i in res.data) {
                    apphtml.append('<option value="' + res.data[i].id + '">' + res.data[i].name + '</option>');
                }
                $("select[name=reslist1]").html(apphtml);
                layer.msg('添加成功', {
                    icon: 5
                });
            } else {
                layer.msg("获取失败", {
                    icon: 5
                });
            }
        }
    });
}


        </script>

    </body>
</html>