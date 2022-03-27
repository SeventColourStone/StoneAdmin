/**
 * 通用操作类
 */

layui.define(['jquery', "layer",'toast','notice',"context"], function (exports) {
    "use strict";
    let MOD_NAME = 'nyuwa',
        layer = layui.layer,
        notice = layui.notice,
        toast = layui.toast,
        context = layui.context,
        $ = layui.jquery;
        $.ajaxSetup({
            contentType:"application/x-www-form-urlencoded;charset=utf-8",
            complete:function(XMLHttpRequest,textStatus){
                //通过XMLHttpRequest取得响应结果
                let response = XMLHttpRequest.responseText;
                try{
                    let res = JSON.parse(response);
                    if (res.code ==200){

                    }else if (res.code == 1001){
                        // toast.error(res.message)
                        toast.error({
                            title: '账号过期',
                            message: res.message,
                        });
                        //弹出登录跳转弹窗
                        layer.confirm('你的账号已经过期，是否重新登录？', {
                            btn: ['登录',] //按钮
                        }, function(){
                            top.location.replace('/ui/login');//确定按钮跳转地址
                        });
                    }else{
                        toast.error(res.message)
                    }
                }catch(e){
                }
            },
            statusCode: {
                404: function() {
                    // window.location.replace("/ui/common/404");
                },
                504: function() {
                    // window.location.replace("/ui/common/404");
                },
                500: function() {
                    // window.location.replace("/ui/common/500");
                },
            }
        });
    var nyuwa = new function () {

        this.preRequestParams = function (url, where = {}) {
            let type = "Post", dataType ="json";
            let timestamp = new Date().getTime();
            let defaultObj = {_:timestamp};//注入时间戳
            Object.assign(where,defaultObj);
            return {
                url: url,
                type: type,
                dataType: dataType,
                beforeSend: function (XMLHttpRequest) {
                    XMLHttpRequest.setRequestHeader("Authorization", "Bearer "+context.get("token"));
                },
                data: where
            };
        }

        this.request = function (url, where = {}, callback) {
            // let type = "Post", dataType ="json";
            // let timestamp = new Date().getTime();
            // let defaultObj = {_:timestamp};//注入时间戳
            let paramsObj = this.preRequestParams(url,where)
            Object.assign(paramsObj, {success: callback});
            //添加header
            console.log("请求了库request")
            $.ajax(paramsObj);
        };

        this.tableRequest = function (url,where = {},isAuth = true) {
            //这里统一包装table request的请求操作
            let timestamp = new Date().getTime();
            let defaultObj = {_:timestamp};//注入时间戳
            Object.assign(defaultObj,where);
            let paramsObject = {
                url: url,
                where:defaultObj,
                response: {
                    statusName: 'code' //规定数据状态的字段名称，默认：code
                    ,statusCode: 200 //规定成功的状态码，默认：0
                    ,msgName: 'message' //规定状态信息的字段名称，默认：msg
                    // ,countName: 'data.pageInfo.total' //规定数据总数的字段名称，默认：count
                    // ,dataName: 'data.items' //规定数据列表的字段名称，默认：data
                },
                request: {
                    limitName: 'pageSize' ,//每页数据量的参数名，默认：limit
                },
                parseData: function(res){ //res 即为原始返回的数据
                    if (res.code == 200){
                        //正常业务
                        return {
                            "count": res.data.pageInfo.total, //解析数据长度
                            "data": res.data.items, //解析数据列表
                            "code": res.code, //解析接口状态
                            "msg": res.message //解析提示文本
                        };
                    }else if (res.code == 1001){
                        console.log(res.message)
                        // toast.error(res.message)
                        toast.error({
                            title: '账号过期',
                            message: res.message,
                        });
                        //弹出登录跳转弹窗
                        layer.confirm('你的账号已经过期，是否重新登录？', {
                            btn: ['登录',] //按钮
                        }, function(){
                            top.location.replace('/ui/login');//确定按钮跳转地址
                        });
                        return {
                            "count": 0, //解析数据长度
                            "data": [], //解析数据列表
                            "code": res.code, //解析接口状态
                            "msg": res.message //解析提示文本
                        };
                    }else{
                        toast.error(res.message)
                        return {
                            "count": 0, //解析数据长度
                            "data": [], //解析数据列表
                            "code": res.code, //解析接口状态
                            "msg": res.message //解析提示文本
                        };
                    }

                },
            };
            if (isAuth){
                //从缓存获取token
                paramsObject.headers = { Authorization:"Bearer "+context.get("token")}
            }
            return paramsObject;
        }

        /**
         * 递归转换格式
         * @param arr
         * @returns {*}
         */
        this.treeDataSource=(arr)=>{
            if(!Array.isArray(arr)){return;}
            console.log("进来了")
            return  arr.map((v,i)=>{
                return {
                    ...v,
                    id:v.id,
                    name:v.label,
                    // order: v.sort,
                    // isClose: v.enable==1?false:true,
                    // icon: <CarryOutOutlined />,
                    children:v.children?this.treeDataSource(v.children):[]
                }
            })
        }

        /**
         * [通过参数名获取url中的参数值]
         * 示例URL:http://htmlJsTest/getrequest.html?uid=admin&rid=1&fid=2&name=小明
         * @param  {[string]} queryName [参数名]
         * @return {[string]}           [参数值]
         */
        this.GetQueryValue = function(queryName) {
            let query = decodeURI(window.location.search.substring(1));
            let vars = query.split("&");
            for (let i = 0; i < vars.length; i++) {
                let pair = vars[i].split("=");
                if (pair[0] == queryName) { return pair[1]; }
            }
            return null;
        }

        this.GetAllQuery = function() {
            let query = decodeURI(window.location.search.substring(1));
            let vars = query.split("&");
            let theRequest = new Object();
            for (let i = 0; i < vars.length; i++) {
                let pair = vars[i].split("=");
                theRequest[pair[0]] = pair[1];
            }
            return theRequest;
        }

        /**
         * 去除对象中所有符合条件的对象
         * @param {Object} obj 来源对象
         * @param {Function} fn 函数验证每个字段
         */
        this.compactObj = function(obj, fn = this.isEmpty) {
            for (let i in obj) {
                if (typeof obj[i] === 'object') {
                    this.compactObj(obj[i], fn)
                }
                if (fn(obj[i])) {
                    delete obj[i]
                }
            }
        }

        // 删除空对象 删除'', null, undefined
        this.isEmpty = function(foo) {
            if (typeof foo === 'object') {
                for (let i in foo) {
                    return false
                }
                return true
            } else {
                return foo === '' || foo === null || foo === undefined
            }
        }
    }


    exports(MOD_NAME, nyuwa);
})
