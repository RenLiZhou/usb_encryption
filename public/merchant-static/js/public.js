﻿var _jM = new jModule();
function jModule(){};

// Get param.
jModule.prototype.getParam = function(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!= null) return unescape(r[2]); return null;
}

jModule.prototype.changeSize = function(limit){
    var size = "";
    if(limit < 0.1 * 1024){                            //小于0.1KB，则转化成B
        size = limit.toFixed(2) + "B"
    }else if(limit < 0.1 * 1024 * 1024){            //小于0.1MB，则转化成KB
        size = (limit/1024).toFixed(2) + "KB"
    }else if(limit < 0.1 * 1024 * 1024 * 1024){        //小于0.1GB，则转化成MB
        size = (limit/(1024 * 1024)).toFixed(2) + "MB"
    }else{                                            //其他转化成GB
        size = (limit/(1024 * 1024 * 1024)).toFixed(2) + "GB"
    }

    var sizeStr = size + "";                        //转成字符串
    var index = sizeStr.indexOf(".");                    //获取小数点处的索引
    var dou = sizeStr.substr(index + 1 ,2)            //获取小数点后两位的值
    if(dou == "00"){                                //判断后两位是否为00，如果是则删除00
        return sizeStr.substring(0, index) + sizeStr.substr(index + 3, 2)
    }
    return size;
}

jModule.prototype.validate = {
    ifNullUseDefault : function(v, def){
        if(_jM.validate.isEmpty(v)){
            return def;
        }
        return v;
    },
    // 是否是有效的手机号
    isMobile : function(v){
        v = $.trim(v);
        return v.match(/^1\d{10}$/);
    },
    // 是否是有效的邮箱
    isEmail : function(v){
        v = $.trim(v);
        return v.match(/^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/);
    },
    // 是否是空字符串或者null
    isEmpty : function(v){
        v = $.trim(v);
        return v== null || v=="" || v.length<=0;
    },
    // 是否不为空字符串
    isNotEmpty :function(v){
        v = $.trim(v);
        return v!=null && v!="" && v.length>0;
    },
    // 是否是有效的数字，包括整数与小数
    isNumber : function(v){
        v = $.trim(v);
        return !isNaN(v);
    },
    // 是否是整数
    isInteger : function(v){
        v = $.trim(v);
        return v.match(/^[0-9]*$/)
    },
    // 是否是货币数字，小数点后两位
    isBigDecimal : function(v){
        v = $.trim(v);
        if(isNaN(v)){return false;}

        var index = v.lastIndexOf(".");
        if(index==-1){
            return true;
        }else{
            return index>=v.length-3;
        }
    },
    // 是否是有效的数字，且在指定的范围内
    isNumberRangeIn : function(v, min, max){
        v = $.trim(v);
        if(isNaN(v)){return false;}
        var v2 = parseFloat(v);
        if(v2 < min || v2> max){
            return false;
        }
        return true;
    },
    // 是否是货币数字，且在指定的范围内
    isDecimalRangeIn : function(v, min, max){
        v = $.trim(v);
        if(_jM.validate.isBigDecimal(v)){
            var v2 = parseFloat(v);
            return v2>=min && v2<=max;
        }
        return false;
    },
    // 是否全是字母
    isCharacter : function(v){
        v = $.trim(v);
        return v.match(/^[A-Za-z]+$/);
    },
    // 是否全是数字
    isDigital : function(v){
        v = $.trim(v);
        return v.match(/^[0-9]*$/);
    },
    // 是否包含非法的字符
    isIllegalCharacter :function(v){
        v = $.trim(v);
        return v.match(/^((?![！~@#￥%……&*]).)*$/);
    },
    // 字符串长度是否在有效的范围内
    isLengthBetween:function(v, min, max){
        v = $.trim(v);
        return v!=null && v.length>=min && v.length<=max;
    },
    // 文件&文件夹可用名
    isFileName : function(v){
        v = $.trim(v);
        return v.match('^[^\\\\\\/:*?\\"<>|]+$');
    },
    //截取
    replace: function(obj){
        let a = obj.toString().split('.');
        let b = a[0];
        let c = a[1];
        let d = '';
        if(a.length > 1){
            if(c.length > 8){
                d = b + '.' + c.substr(0, 8);
            }else {
                d = b + '.' + c;
            }
        }else {
            d = obj
        }
        return d
    },

    //过滤空格 特殊字符
    isSpace: function(v){
        var str =  v.replace(/\s+/g,"");
        var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\]<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
        var rs = '';
        for(var i = 0; i < str.length; i++){
            rs = rs + str.substr(i, 1).replace(pattern, '');
        }
        return rs;
    },
    /*只能为数字 或者小数*/
    chkNum: function(num){
        var re = num.replace(/[^\d.]/g, "");
        re = re.replace(/^\./g, "");
        re = re.replace(/\.{2,}/g, ".");
        re = re.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
        return re
    },
};

// Set cookie
jModule.prototype.setCookie = function(name, value, days, path){
    var Days = days;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ encodeURIComponent(value) + ";expires=" + exp.toGMTString()+";path="+path;
}

// Get cookies
jModule.prototype.getCookie = function(name){
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return decodeURIComponent(arr[2]);
    else
        return null;
}

/**
 * 最多显示几位小数，默认两位
 */
jModule.prototype.maxDecimal = function(num, max) {
    var result = parseFloat(num);
    if (isNaN(result)) {
        return false;
    }
    var defaultDec = max || 6;
    var numArr = (result + "").split(".");
    if(numArr.length == 1){
        return result + ".00";
    }
    else if(numArr.length == 2){
        if(numArr[1].length > defaultDec){
            var rs = parseFloat(numArr[0] + "." + numArr[1].substr(0, defaultDec)) + "";
            if(rs.indexOf(".") == -1){
                return rs + ".00";
            }else if(rs.split(".")[1].length < 2){
                return rs + "0";
            }
            return rs;
        }
        if(numArr[1].length < 2){
            return numArr[0] + "." + numArr[1] + "0";
        }
        return result + "";
    }
    return "0";
}

// 防止用户重复点击
jModule.prototype.disabled = function(obj) {
    $(obj).attr("disabled", true);
}

jModule.prototype.undisabled = function(obj, func) {
    $(obj).attr("disabled", false);
}

jModule.prototype.validates = function(obj, fields= {}, icons) {
    obj.bootstrapValidator({
        feedbackIcons: icons ? icons : {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: fields
    });
}

/**
 * 加减乘除
 */
jModule.prototype.floatObj = {
    isInteger:function(obj) {
        return Math.floor(obj) === obj
    },

    /*
     * 将一个浮点数转成整数，返回整数和倍数。如 3.14 >> 314，倍数是 100
     * @param floatNum {number} 小数
     * @return {object}
     *   {times:100, num: 314}
     */
    toInteger:function(floatNum) {
        var ret = {times: 1, num: 0};
        if (_jM.floatObj.isInteger(floatNum)) {
            ret.num = floatNum;
            return ret
        }
        var strfi = floatNum + '';
        var dotPos = strfi.indexOf('.');
        var len = strfi.substr(dotPos + 1).length;
        var times = Math.pow(10, len);
        var intNum = parseInt(floatNum * times + 0.5, 10);
        ret.times = times;
        ret.num = intNum;
        return ret
    },

    /*
     * 核心方法，实现加减乘除运算，确保不丢失精度
     * 思路：把小数放大为整数（乘），进行算术运算，再缩小为小数（除）
     *
     * @param a {number} 运算数1
     * @param b {number} 运算数2
     * @param op {string} 运算类型，有加减乘除（add/subtract/multiply/divide）
     *
     */
    operation:function(a, b, op) {
        var o1 = _jM.floatObj.toInteger(a);
        var o2 = _jM.floatObj.toInteger(b);
        var n1 = o1.num;
        var n2 = o2.num;
        var t1 = o1.times;
        var t2 = o2.times;
        var max = t1 > t2 ? t1 : t2;
        var result = null;
        switch (op) {
            case 'add':
                if (t1 === t2) { // 两个小数位数相同
                    result = n1 + n2
                } else if (t1 > t2) { // o1 小数位 大于 o2
                    result = n1 + n2 * (t1 / t2)
                } else { // o1 小数位 小于 o2
                    result = n1 * (t2 / t1) + n2
                }
                return result / max;
            case 'subtract':
                if (t1 === t2) {
                    result = n1 - n2
                } else if (t1 > t2) {
                    result = n1 - n2 * (t1 / t2)
                } else {
                    result = n1 * (t2 / t1) - n2
                }
                return result / max;
            case 'multiply':
                result = (n1 * n2) / (t1 * t2);
                return result;
            case 'divide':
                result = (n1 / n2) * (t2 / t1);
                return result
        }
    },

    // 加减乘除的四个接口
    add:function(a, b) {
        return _jM.floatObj.operation(a, b, 'add')
    },
    // 减
    subtract:function(a, b) {
        return _jM.floatObj.operation(a, b, 'subtract')
    },
    // 乘
    multiply:function(a, b) {
        return _jM.floatObj.operation(a, b, 'multiply')
    },
    // 除
    divide:function(a, b) {
        return _jM.floatObj.operation(a, b, 'divide')
    }
}


jModule.prototype.ajax = function (config) {
    var async = (config.async == null) ? true : config.async;
    var contentType = (config.contentType == null) ? "application/x-www-form-urlencoded; charset=utf-8" : config.contentType;
    var data = config.data;
    var csrftoken = $('meta[name="csrf-token"]').attr('content');

    if ("application/json; charset=utf-8" == contentType) {
        data = JSON.stringify(data);  // 对json对象序列化
    }

    $.ajax({
        url: config.url,
        type: config.type,
        dataType: 'json',
        contentType: contentType,
        timeout: 60000,  // 超时时间
        async: async,   // 默认异步
        data: data,
        headers: {
            "X-CSRF-TOKEN": csrftoken
        },
        complete: function () {
            config.complete && config.complete();
        },
        error: function(error){
            var errorInfo;
            switch (error.status) {
                case 400:
                    errorInfo = 'Request Error'; break;
                case 401:
                    errorInfo = 'Unauthenticated request'; break;
                case 403:
                    errorInfo = 'Request without permission'; break;
                case 404:
                    errorInfo = 'Request not found'; break;
                case 500:
                    errorInfo = 'Service exception:';
                    if (error.responseJSON) {
                        errorInfo += error.responseJSON.message ? error.responseJSON.message : error.responseJSON.exception
                    }
                    break;
                case 501:
                    errorInfo = 'Service not implemented'; break;
                case 0:
                    errorInfo = 'Please refresh and try again'; break;
                case 419:
                    errorInfo = 'Please refresh and try again'; break;    // 适用于csrfToken
                default:
                    errorInfo = error.statusText;
                    if (error.responseJSON) {
                        errorInfo += error.responseJSON.message ? error.responseJSON.message : error.responseJSON.exception
                    }
            }
            config.error(errorInfo);
        },
        success: function(res){
            if (res.code == 0) {  // 逻辑码正确
                config.success(res.msg, res.data);
                return false;
            }else if (res.code == 1001) {  // 未登录的逻辑
                window.top.location.href = '/merchant/login';
                return false;
            }else if (res.code == 1001) {  // 无权限的逻辑

            }
            config.error(res.exception)
        }
    });
}

jModule.prototype.submit = function (jqdom, data, succallable, errcallable, completecallable) {
    data = data || {}
    var loadindex = _jM.dialogLoad('Loading');
    _jM.ajax({
        url: jqdom.attr('data-url'),
        type: jqdom.attr('data-type'),
        data: data,
        complete: function () {
            _jM.dialogClose(loadindex);
            completecallable && completecallable();
        },
        error: function (errMsg) {
            _jM.dialogErMsg(errMsg);
            errcallable && errcallable(errMsg);
        },
        success: function (msg, data) {
            _jM.dialogSuccess('', function (msg, data) {
                _jM.dialogCloseCurIf();
                succallable && succallable(msg, data);
            });
        }
    });
}

jModule.prototype.dialogSuccess = function (msg, callable) {
    msg = msg || "Successful";
    layer.alert(msg, {icon: 6,closeBtn: 0}, function (index) {
        layer.close(index);
        callable && callable();
    })
}

jModule.prototype.dialogError = function (msg, callable) {
    msg = msg || "Failure";
    layer.alert(msg, {icon: 5,closeBtn: 0}, function (index) {
        layer.close(index);
        callable && callable();
    })
}

jModule.prototype.dialogLoad = function (msg) {
    var index = msg ? layer.msg(msg, {icon: 16, shade: 0.01, time: false}) : layer.load(1, {shade: [0.1,'#fff']})
    return index;
}

jModule.prototype.dialogConfirm = function (msg, callable1, callable2) {
    layer.confirm(msg, {btn: ["OK", "cancel"]}, function (index) {
        layer.close(index);
        callable1();
    }, function (index) {
        layer.close(index);
        callable2 && callable2();
    })
}

jModule.prototype.dialogMsg = function (msg) {
    return layer.msg(msg);
}

jModule.prototype.dialogOkMsg = function (msg) {
    return layer.msg(msg, {icon: 1});
}

jModule.prototype.dialogErMsg = function (msg) {
    return layer.msg(msg, {icon: 2});
}

jModule.prototype.dialogOpen = function (title, content) {
    var index = layer.open({
        title: title,
        type: 2,
        content: content,
        area: ['100%', '100%'],
        maxmin: false,
        success: function (layero, index) {
            layer.full(index);
            $(window).on("resize", resizeFun = function () {
                layer.full(index);
                layer.iframeAuto(index);
            });
        },
        cancel: function(index){
            $(window).off("resize", resizeFun);
        }

    })
}

jModule.prototype.dialogPop = function (param) {
    var wh = param.area || ['45%', '60%'];
    var title = param.title || false;
    var maxmin = param.maxmin || false;
    var popIndex = layer.open({
        title: title,
        type: 2,
        content: param.content,
        shadeClose: true,
        shade: 0.8,
        area: wh,
        maxmin: maxmin,
        success: function (layero, index) {
            if (param.selector) {
                // selector为触发按钮的选择器
                var ibtn = layer.getChildFrame(param.selector, popIndex);
                ibtn.click(function () {
                    layer.close(popIndex);
                });
            }
        },
        end: function () {  // 层被销毁时执行
            param.end && param.end();
        }
    })
}

jModule.prototype.dialogCloseCurIf = function () {
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}

jModule.prototype.dialogModal = function (content, area, title) {
    area = area || ["420px", "240px"];
    title = title || false;
    return layer.open({type: 1, title: title, area: area, content: content})
}

jModule.prototype.dialogTips = function (msg, selector) {
    layer.tips(msg, selector, {tips: 3});
}

jModule.prototype.dialogPrompt = function (tilte, val, callable) {
    layer.prompt({title: tilte, value: val}, function(val, index){
        layer.close(index);
        callable && callable(val);
    });
}

jModule.prototype.dialogPwd = function (tilte, val, callable) {
    layer.prompt({title: tilte, value: val, formType: 1}, function(val, index){
        layer.close(index);
        callable && callable(val);

    });
}

jModule.prototype.dialogText = function (tilte, val,callable) {
    layer.prompt({title: tilte, value: val, formType: 2}, function(val, index){
        layer.close(index);
        callable && callable(val);
    });
}

jModule.prototype.dialogClose = function (index) {
    layer.close(index);
}

jModule.prototype.dialogCloseAll = function (type) {
    type = type || '';
    layer.closeAll(type);
}

jModule.prototype.dialogHint = function (msg, callable) {
    $.confirm({
        title: 'Prompt',
        content: msg,
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'OK',
                btnClass: 'btn-red',
                action: callable
            },
            close: {
                text: 'Cancel'
            }
        }
    });
}

jModule.prototype.paginatorSelect = function (obj, url) {
    var per_page = $(obj).val();
    location.href = url + "&per_page=" + per_page;
}

jModule.prototype.checkAll = function (obj, checkedName) {
    var isActive = $(obj).prop("checked");
    if(isActive){
        $("input[name='"+ checkedName +"']").prop("checked", true);
    }else{
        $("input[name='"+ checkedName +"']").prop('checked', false);
    }
}

jModule.prototype.getFormJson = function(form) {
    var o = {};
    var a = $(form).serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}
