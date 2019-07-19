<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>layui/css/layui.css" />
        <script src="<?php echo $_G['gis']['dirstyle'];?>layui/layui.js" type="text/javascript"></script>
    </head>

    <body>
        <form class="layui-form" style="padding-top:10px;">
            <div class="layui-form-item">
                <label for="L_username" class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_username" name="username" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom:10px;">
                <label for="L_username" class="layui-form-label">一级目录</label>
                <div class="layui-input-inline">
                    <select name="resid" lay-filter="resid">

                    </select>
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom:10px;">
                <label for="L_username" class="layui-form-label">二级目录</label>
                <div class="layui-input-inline">
                    <select name="resid2" lay-filter="resid2">

                    </select>
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom:10px;">
                <label for="L_username" class="layui-form-label">三级目录</label>
                <div class="layui-input-inline">
                    <select name="resid3" lay-filter="resid3">

                    </select>
                </div>
            </div>
            <div class="layui-form-item" id="textbox" >
                <label for="L_username" class="layui-form-label">内容</label>
                <div class="layui-input-inline">
                    <textarea name="texts" lay-verify="required" autocomplete="off" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item" id="imgbox" >
                <label for="L_username" class="layui-form-label">图片上传</label>
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-danger" id="test7"><i class="layui-icon"></i>上传图片</button>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label"></label>
                <div style="width: 216px; margin-left:100px;">
                    <button type="button" class="layui-btn layui-btn-fluid" lay-filter="add" lay-submit="">确定</button>
                </div>
                <input type="hidden" value="" id="xgid" />
            </div>
        </form>
        <script>
                    layui.use(['form', 'jquery', 'util', 'layer', 'upload'], function () {
                        var form = layui.form,
                                $ = layui.jquery,
                                util = layui.util,
                                upload = layui.upload,
                                layer = layui.layer;
                        var loadindex = layer.load(1);
                        
                        var editgis = layui.sessionData('editgis');
                        var id = editgis.editgisid;  //$("#xgid").val(); //修改的ID
                        layer.ready(function () {
                            $.ajax({
                                type: "POST",
                                url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
                                dataType: "json",
                                data: {
                                    "gisid": id,
                                    "mod": "getgis"
                                },
                                success: function (res) {
                                    if (res.code == 0) {
                                        var data = res.data[0];
                                        console.log(data)
                                        $("input[name=username]").val(data.name);
                                        $("textarea[name=texts]").val(data.texts);
//                                    if (data.types == 1) {
//                                        $('#textbox').css({display: 'block'});
//                                        $('#imgbox').css({display: 'block'});
//                                    }
                                        getres('', 'resid0', data.resid);
                                        data.resid > 0 && getres(data.resid, 'resid', data.resid2);
                                        data.resid2 > 0 && getres(data.resid2, 'resid2', data.resid3);
                                        layui.sessionData('editgis', {key: 'editgisid', value: 0});
                                    } else {
                                        layer.msg(res.msg, {icon: 5});
                                    }
                                }
                            });
                            layer.close(loadindex);
                        });


                        form.on('select', function (obj) {
                            var selectname = obj.elem.name;
                            if (!obj.value) {
                                return false;
                            }
                            switch (selectname) {
                                case 'resid':
                                    getres(obj.value, selectname);
                                    changelevel([], 'resid');
                                    changelevel([], 'resid2');
                                    break;
                                case 'resid2':
                                    getres(obj.value, selectname);
                                    changelevel([], 'resid2');
                                    break;
                            }
                        });

                        var getres = function (fid, types, selectedid = false) {
                            $.ajax({
                                url: 'http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi',
                                data: {'mod': 'getres', 'resid': fid},
                                type: "post",
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code === 0) {
                                        changelevel(res.data, types, selectedid);
                                    } else {
                                        layer.msg("获取失败", {
                                            icon: 5
                                        });
                                        return false;
                                    }
                                }
                            });
                        }


                        function changelevel(level, types, selectedid = false) {
                            var levelhtmloption = '';
                            for (var i in level) {
                                var selectedid_str = '';
                                selectedid_str = (selectedid == level[i].id) ? 'selected' : '';
                                levelhtmloption += '<option value="' + level[i].id + '" ' + selectedid_str + '>' + level[i].name + '</option>';
                            }
                            switch (types) {
                                case 'resid0':
                                    var levelhtml = '<option value="" >请选择一级目录</option>';
                                    $("select[name=resid]").html(levelhtml + levelhtmloption);
                                    break;
                                case 'resid':
                                    var levelhtml = '<option value="" >请选择二级目录</option>';
                                    $("select[name=resid2]").html(levelhtml + levelhtmloption);
                                    break;
                                case 'resid2':
                                    var levelhtml = '<option value="" >请选择三级目录</option>';
                                    $("select[name=resid3]").html(levelhtml + levelhtmloption);
                                    break;
                            }
                            form.render('select');
                        }


                        var tupian;
                        //添加上传文件
                        upload.render({
                            elem: '#test7',
                            url: 'http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi',
                            data: {
                                "mod": "upimgs"
                            },
                            done: function (res) {
                                tupian = res.data;
                                console.log(res.data)
                            }
                        });
                        //监听提交
                        form.on('submit(add)', function (data) {
                            var field = data.field; //提交的数据
                            $.ajax({
                                type: "POST",
                                url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
                                dataType: "json",
                                data: {
                                    "name": field.username,
                                    "resid": field.resid,
                                    "resid2": field.resid2,
                                    "resid3": field.resid3,
                                    "texts": field.texts,
                                    "file": tupian,
                                    "gisid": id,
                                    "mod": "gisinput"
                                },
                                success: function (res) {
                                    console.log(res)
                                    if (res.code == 0) {
                                        layer.alert("修改成功", {
                                            icon: 6
                                        }, function () {
                                            // 获得frame索引
                                            var index = parent.layer.getFrameIndex(window.name);
                                            //关闭当前frame
                                            parent.layer.close(index);
                                        });
                                    } else {
                                        layer.alert(res.msg, {
                                            icon: 5
                                        });
                                    }
                                }
                            })
                        });

                    });
        </script>
    </body>

</html>