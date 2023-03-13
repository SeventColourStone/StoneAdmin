/**
 * 获取控制器详细权限，并决定展示哪些按钮或dom元素
 */
layui.$(function () {
    let $ = layui.$;
    $.ajax({
        url: "/app/admin/rule/permission",
        dataType: "json",
        success: function (res) {
            let style = '';
            let codes = res.data || [];
            let isSupperAdmin = false;
            // codes里有*，说明是超级管理员，拥有所有权限
            if (codes.indexOf('*') !== -1) {
                $("head").append("<style>*[permission]{display: initial}</style>");
                isSupperAdmin = true;
            }
            if (self !== top) {
                top.Admin.Account.isSupperAdmin = isSupperAdmin;
            } else {
                window.Admin.Account.isSupperAdmin = isSupperAdmin;
            }
            if (isSupperAdmin) return;

            // 细分权限
            layui.each(codes, function (k, code) {
                codes[k] = '*[permission^="'+code+'"]';
            });
            if (codes.length) {
                $("head").append("<style>" + codes.join(",") + "{display: initial}</style>");
            }
        }
    });
});