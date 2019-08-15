<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/index.css" />
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>layui/css/layui.css" />
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>layui/layui.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/fafang.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="layui-row" style="margin:15px 20px;">
            <form class="layui-form layui-col-md12 we-search">
                <div class="layui-input-inline">
                    <select name="types">
                        <option value="">请选择分类型
                        <option>
                        <option value="1">点</option>
                        <option value="2">线</option>
                        <option value="3">面</option>
                        <option value="4">矩形</option>
                        <option value="5">圆</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <input type="text" name="name" placeholder="请输入名称" autocomplete="off" class="layui-input">
                </div>
                <p class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></p>
            </form>
        </div>
        <table class="layui-hide" id="test" lay-filter="test"></table>
        <script type="text/html" id="barDemo">
            <?php if($umsg ) { ?>
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            <?php } ?>
        </script>
        <script>
            layui.use(['table', 'form'], function () {
                var table = layui.table,
                        layer = layui.layer,
                        form = layui.form;

                table.render({
                    elem: '#test',
                    url: 'http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi&mod=searchlist',
                    title: '用户数据表',
                    id: 'testReload',
                    cols: [
                        [{
                                title: '序号',
                                width: 80,
                                type: 'numbers'
                            }, {
                                field: 'name',
                                title: '名称'
                            }, {
                                fixed: 'right',
                                title: '操作',
                                toolbar: '#barDemo'
                            }]
                    ],
                    page: true,
                    parseData: function (res) { //将原始数据解析成 table 组件所规定的数据
                        console.log(res)
                        return {
                            "code": res.code, //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data //解析数据列表
                        };
                    }
                });
                //搜索查询
                form.on('submit(sreach)', function (data) {
                    var f = data.field;
                    var loading = layer.load();
                    table.reload("testReload", {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        },
                        where: {
                            name: f.name,
                            types: f.types
                        },
                        done: function () {
                            layer.close(loading);
                        }
                    }, data)
                });
                //监听行工具事件
                table.on('tool(test)', function (obj) {
                    var data = obj.data;
                    console.log(data)
                    if (obj.event === 'del') {
                        layer.confirm('确定要删除该项标注信息?', function (index) {
                            obj.del();
                            $.ajax({
                                type: 'POST',
                                url: 'http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi',
                                data: {
                                    "gisid": data.id,
                                    "mod": "gisdel"
                                },
                                dataType: 'json',
                                success: function (res) {
                                    console.log(res)
                                    if (res.code == 0) {
                                        layer.msg('删除成功')
                                        form.render();
                                        layer.close(index);
                                    }
                                }
                            });

                        });
                    } else if (obj.event === 'edit') {
                        var index = parent.layer.getFrameIndex(window.name);
                        layui.sessionData('editgis', {key: 'editgisid', value: data.id});
                        parent.WeAdminEdit('修改信息', '/plugin.php?id=gis_sczl:gismap_map&mod=edit', 600, 510, data.id);

                    }
                });
            })
        </script>
    </body>
</html>
