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
            <form class="layui-form" action="" >
                <input type='hidden' name='editres'>
                <div class="layui-form-item">
                    <label class="layui-form-label">所属目录</label>
                    <div class="layui-input-inline" style='display:block;min-width: 300px;margin-bottom: 5px;'>
                        <select name="level1" lay-filter="level1">
                            <option value="">请选择一级目录</option>
                            <option value="浙江" selected="">浙江</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style='display: block;min-width: 300px;margin: 5px 0 5px 110px;'>
                        <select name="level2" lay-filter="level2">
                            <option value="">请选择二级目录</option>
                        </select>
                    </div>
                    <!--                    <div class="layui-input-inline" style='display: block;min-width: 300px;margin: 5px 0 5px 110px;'>
                                            <select name="level3" lay-filter="level3">
                                                <option value="">请选择三级目录</option>
                                            </select>
                                        </div>-->
                    <div class="layui-form-mid layui-word-aux"  style='margin: 5px 0 5px 110px;'>如不选择所属目录,默认添加为一级目录</div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="title" autocomplete="off" placeholder="请填写目录名" class="layui-input" style='width: 80%;' lay-verify="required">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="addres">立即提交</button>
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
        <!--<script src="/source/plugin/gis_sczl/style/js/jquery-1.4.4.min.js" type="text/javascript"></script>-->

        <script>
            layui.use(['table', 'form', 'jquery'], function () {
                var table = layui.table, form = layui.form, $ = layui.jquery;
                var level1 = <?php echo $level1;?>;

                table.render({
                    elem: '#test'
                    , url: '/plugin.php?id=gis_sczl:resgis&mod=resgislist'
                    , cellMinWidth: 85
                    , toolbar: '#complain_toolbar'
                    , cols: [[
                            //      {type:'numbers'},
                            {checkbox: true}
                            , {field: 'id', title: 'ID', width: 50, unresize: true, sort: true}
                            , {field: 'name', title: '资源目录名', templet: '#nameTpl'}
                            , {field: 'types', title: '层级'}
                            , {field: 'title', title: '上级目录', minWidth: 120, }
                            , {field: 'fid', title: '上级目录ID', minWidth: 120, }
                            , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 150}
                        ]]
                    , page: true
                    , id: 'reslist'
                });

                table.on('tool(complainList)', function (obj) {
                    var data = obj.data;
                    switch (obj.event) {
                        case 'edit':
                            console.log(obj);
                            var level2 = [];
                                level2[0] = {'id': obj.data.fid, 'name': obj.data.title};
                                
                            if(obj.data.types == 2){
                                changelevel(level1, 'level0', obj.data.fid);
                            }else if(obj.data.types == 3 && obj.data.fid > 0){
                                changelevel(level1, 'level0', obj.data.ffid);
                                changelevel(level2, 'level1', obj.data.fid);
                            }
                            $("input[name=name]").val(obj.data.name);
                            $("input[name=editres]").val(obj.data.id);
                            
                            
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
                                    changelevel(level1, 'level0');
                                    changelevel([], 'level1');
                                    $("input[name=name]").val('');
                                    $("input[name=editres]").val('');
                                }
                            });
                            break;
                        case 'del':
                            var delIndex = layer.confirm('确定删除id为' + data.id + "的资源目录吗?", function (delIndex) {
                                delsub(data.id, obj);
                            });
                            break;
                    }
                });

                table.on('toolbar(complainList)', function (obj) {
                    var data = obj.data;
                    switch (obj.event) {
                        case 'add':
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
                                    changelevel(level1, 'level0');
                                    changelevel([], 'level1');
                                    $("input[name=name]").val('');
                                    $("input[name=editres]").val('');
                                }
                            });
                            break;
                        case 'delAll':
                            var delIndex = layer.confirm('确定要批量删除选中的资源目录吗?', function (delIndex) {
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
//                                            console.log(delIndex);
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


                //监听提交
                form.on('submit(addres)', function (data) {
//                    layer.msg(JSON.stringify(data.field));
                    if (!data.field.name) {
                        layer.msg('名称不能为空!');
                        return false;
                    }  
                    data.field.mod = 'resgisadd';
                    data.field.editres = $("input[name=editres]").val();
                    $.ajax({
                        url: '/plugin.php?id=gis_sczl:resgis',
                        data: data.field,
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            if (res.msg == 'success') {
                                layer.close(1);
                                layer.msg("成功", {
                                    icon: 1
                                });
                                table.reload('reslist', {});
                            } else {
                                layer.msg("失败", {
                                    icon: 5
                                });
                                return false;
                            }
                        }
                    });
                    return false;
                });

                if (level1) {
                    changelevel(level1, 'level0');
                }
                function changelevel(level, types, selectedid = false) {
                    var levelhtmloption = '';
                    for (var i in level) {
                        var selectedid_str = '';
                        selectedid_str = (selectedid == level[i].id) ? 'selected' : '';
                        levelhtmloption += '<option value="' + level[i].id + '" '+ selectedid_str +'>' + level[i].name + '</option>';
                    }
                    switch (types) {
                        case 'level0':
                            var levelhtml = '<option value="" >请选择一级目录</option>';
                            $("select[name=level1]").html(levelhtml + levelhtmloption);
                            break;
                        case 'level1':
                            var levelhtml = '<option value="" >请选择二级目录</option>';
                            $("select[name=level2]").html(levelhtml + levelhtmloption);
                            break;
                        case 'level2':
                            var levelhtml = '<option value="" >请选择三级目录</option>';
                            $("select[name=level3]").html(levelhtml + levelhtmloption);
                            break;
                    }

                    form.render('select');
                }

                form.on('select', function (obj) {
                    var selectname = obj.elem.name;
                    if (obj.value && selectname != 'level3') {
                        getres(obj.value, selectname);
                    }

                });

                var getres = function (fid, types) {
                    $.ajax({
                        url: '/plugin.php?id=gis_sczl:resgis',
                        data: {'mod': 'resgisarr', 'fid': fid},
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            if (res.msg == 'success') {
                                changelevel(res.data, types);
                            } else {
                                layer.msg("获取失败", {
                                    icon: 5
                                });
                                return false;
                            }
                        }
                    });
                }


            });


        </script>

    </body>
</html>