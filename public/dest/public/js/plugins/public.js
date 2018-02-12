var baseUrl = 'http://172.16.201.90:8888/';
var imgUrl = "http://file001.zhuniu.com/";
isWeiXin();
//表单数据转换JSON
var DataDeal = {
    formToJson: function (data) {
        data=data.replace(/&/g,"\",\"");
        data=data.replace(/=/g,"\":\"");
        data="{\""+data+"\"}";
        return data;
    }
};
//获取浏览器参数
function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
//日期戳转换
function changeDate(str){
    var da = str;
    da = new Date(parseInt(da));
    var year = da.getFullYear()+'-';
    var month = da.getMonth()+1+'-';
    var date = da.getDate();
    return [year,month,date].join('');
}
//时间戳转换
function changeTime(str){
    var da = str;
    da = new Date(parseInt(da));
    var year = da.getFullYear()+'-';
    var month = add(da.getMonth()+1)+'-';
    var date = add(da.getDate())+' '; var hours = add(da.getHours())+':';var mi = add(da.getMinutes())+':'; var s = add(da.getSeconds());
    return [year,month,date,hours,mi,s].join('');
}
//日期单数加0
function add(str){
    var s = str + '';
    if(s.length == 1){
        return '0'+s;
    }else{
        return s;
    }
}
//判断是否是微信浏览器
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger') {
        $('header').hide();
        return true;
    }else{
        return false;
    }
}