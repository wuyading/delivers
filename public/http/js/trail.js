var longitude = $('input[name="longitude"]').val();
var latitude = $('input[name="latitude"]').val();
var map = new AMap.Map("container", {
        resizeEnable: true,
        zoom: 18
    }),
    lnglatXY = [longitude,latitude]; //已知点坐标
function regeocoder() {  //逆地理编码
    var geocoder = new AMap.Geocoder({
        radius: 1000,
        extensions: "all"
    });
    geocoder.getAddress(lnglatXY, function(status, result) {
        if (status === 'complete' && result.info === 'OK') {
            geocoder_CallBack(result);
        }
    });
    var marker = new AMap.Marker({  //加点
        map: map,
        position: lnglatXY
    });
    map.setFitView();
}
function geocoder_CallBack(data) {
    var address = data.regeocode.formattedAddress;
}

var geolocation;
map.plugin('AMap.Geolocation', function() {
    geolocation = new AMap.Geolocation({
        enableHighAccuracy: true,
        timeout: 10000,
        buttonOffset: new AMap.Pixel(10, 20),
        zoomToAccuracy: true,
        buttonPosition:'RB'
    });
    map.addControl(geolocation);
    geolocation.getCurrentPosition();
    AMap.event.addListener(geolocation, 'complete', onComplete);
    AMap.event.addListener(geolocation, 'error', onError);
});

function onComplete(data) {
    AMap.service('AMap.Driving',function(){//回调函数
        //实例化StationSearch
        //构造路线导航类
        var driving = new AMap.Driving({
            map: map,
            panel: "panel"
        });
        //根据起终点经纬度规划驾车导航路线
        driving.search(new AMap.LngLat(data.position.getLng(), data.position.getLat()), new AMap.LngLat(longitude,latitude));
    })
}
function onError(data) {
    alert('定位失败');
}