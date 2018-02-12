
    var coundown = 60;
    var global = [];
    function displayDyInfo(obj){
        var codestr = 's';
        var clickValue = false;
        if(global.length==0){
            var si = setInterval(function() {
                if(coundown > 0) {
                    obj.html(coundown + codestr);
                    obj.css({"color":"#a3a3a3","border":"1px solid #a3a3a3"});
                    coundown--;
                } else if (coundown == 0) {
                    obj.html("重新发送");
                    obj.css({"color":"#1a8e6b","border":"1px solid #1a8e6b"});
                    clearInterval(global.pop());
                }
            }, 1000);
            global.push(si);
            clickValue = true;
            coundown = 60;
        }
        return clickValue;
    }
    function sendDyCode(obj,phone){
        var repeatClick = displayDyInfo(obj);
        if(!repeatClick){
            return false;
        }
        $.ajax({
            url:baseUrl+'sendSms/'+phone,
            type:'GET',
            dataType:'json',
            /*crossDomain: true,
            async: true,
            contentType : 'application/json',*/
            success:function(data){
                if(!data.result){
                    $('.error-message').text(data.message);
                }else{
                    $('.error-message').text('');
                }
            },error:function(){
                console.log('error');
            }
        });
    }
    function codeClear(){
        coundown = 60;
        global = [];
    }
