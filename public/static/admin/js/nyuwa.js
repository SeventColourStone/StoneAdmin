/**
 * 通用操作类
 */

layui.define(['jquery', "layer"], function (exports) {
    "use strict";
    let MOD_NAME = 'nyuwa',
        layer = layui.layer,
        $ = layui.jquery;
    var nyuwa = new function () {
        function api() {
            console.log("请求了库api")
        }

        /**
         * 通用网络请求
         */
        function request() {
            console.log("请求了库request")
        }
    }


    exports(MOD_NAME, nyuwa);
})
