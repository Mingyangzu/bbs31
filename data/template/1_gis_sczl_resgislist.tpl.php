<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>标注信息管理</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="/source/plugin/gis_sczl/style/layui/css/layui.css"  media="all">
        <style>
            .layui-table-cell .layui-form-checkbox[lay-skin=primary]{top: 5px;}
        </style>
    </head>
    <body>

        <table class="layui-hide" id="test"  lay-filter="complainList"></table>

        <script type="text/html" id="complain_toolbar">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delAll"><i class="layui-icon"></i>批量删除</button>
            </div>
        </script>

        <script src="/source/plugin/gis_sczl/style/layui/layui.js" type="text/javascript" charset="utf-8"></script>

        <script>
            layui.use(['table', 'form', 'jquery'], function () {
                var table = layui.table, form = layui.form, $ = layui.jquery;
                var types_text = ['', '点', '线', '面', '矩形', '圆'];

                table.render({
                    elem: '#test'
                    , url: '/plugin.php?id=gis_sczl:resgis&mod=resgislist'
                    , cellMinWidth: 85
                    , toolbar: '#complain_toolbar'
                    , cols: [[
                            {checkbox: true}
                            , {field: 'id', title: 'ID', width: 50, unresize: true, sort: true}
                            , {field: 'title', title: '关联文章', width: 230}
                            , {field: 'aid', title: '文章ID', width: 80}
                            , {field: 'gisucode', title: '关联标识'}
                            , {field: 'types', title: '类型', width: 60, templet: function (e) {
                                    return types_text[e.types];
                                }}
                            , {field: 'icon', title: '图标/填充色',width: 110, templet: function (e) {
                                    return  e.icon ? "<img src=" + e.icon + " class='imgs' style='width:20px; height: 30px;'>" : "<div style='width:25px;height:25px;background:" + e.backgrse + " ; border-radius: 3px;'></div>";
                                }}
                            , {field: 'radius', title: '圆半径', width: 100}
                            , {field: 'create_time', title: '添加时间', width: 190}
                    ]]
                    , page: true
                    , id: 'reslist'
                });

                table.on('toolbar(complainList)', function (obj) {
                    var data = obj.data;
                    switch (obj.event) {
                        case 'delAll':
                            var delIndex = layer.confirm('确定要批量删除选中的标识吗?', function (delIndex) {
                                var checkarr = table.checkStatus('reslist');
                                checkarr = checkarr.data;
                                var subid = [];
                                if (checkarr) {
                                    $.each(checkarr, function (index, val) {
                                        subid[index] = val.id;
                                    });
                                    delsub(subid, obj);
//                                parent.location.reload();
                                    table.reload('reslist', {});
                                }
                            });
                            break;
                    }

                });

                function delsub(resid, obj) {
                    $.ajax({
                        url: '/plugin.php?id=gis_sczl:resgis',
                        data: {'mod': 'resgisdel', 'resid': resid},
                        type: "post",
                        dataType: 'json',
                        success: function (suc) {
                            if (suc.msg == 'success') {
                                (obj.event == 'del') && obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(1);
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
                    layer.close(1);
                }

            });

        </script>

    </body>
</html>