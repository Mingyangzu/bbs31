layui.define(['jquery', 'form', 'layer', 'element'], function (exports) {
    var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer,
            element = layui.element;

    $(function () {
        window.WeAdminEdit = function (title, url, w, h, id) {
            if (title == null || title == '') {
                title = false;
            }
            ;
            if (w == null || w == '') {
                w = ($(window).width() * 0.9);
            }
            ;
            if (h == null || h == '') {
                h = ($(window).height() - 50);
            }
            ;
            layer.open({
                type: 2,
                area: [w + 'px', h + 'px'],
                fix: false, //不固定
                title: title,
                content: url,
                success: function (layero, index) {
                    var body = layer.getChildFrame('body', index);
                    body.contents().find("#xgid").val(id)
                }
            });
        }

        window.WeAdminShow = function (title, url, w, h, id) { //新增用户方法
            if (title == null || title == '') {
                title = false;
            }
            ;
            if (url == null || url == '') {
                url = "404.html";
            }
            ;
            if (w == null || w == '') {
                w = ($(window).width() * 0.9);
            }
            ;
            if (h == null || h == '') {
                h = ($(window).height() - 50);
            }
            ;
            layer.open({
                type: 2,
                area: [w + 'px', h + 'px'],
                fix: false, //不固定
                maxmin: true,
                shadeClose: true,
                shade: 0.4,
                title: title,
                content: url
            });
        }
    })
})