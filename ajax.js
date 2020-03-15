$.ajax({
    type : "POST",
    //请求的媒体类型
    // contentType: "application/json;charset=UTF-8",
    //请求地址
    url : url,
    //数据，json字符串
    data : {
        'name':'value',
    },
    //请求成功,返回的是个对象（如果后台用$this->response）
    success : function(result) {
        var obj = result;
        var str = "";
        //向id为content的html标签中灌数据
        $('#content').html(str);
        str += "<li>键值:" + obj.键值 +"</li>";
        //$('#content').html(str);
    },
    //请求失败，包含具体的错误信息
    error : function(e){
        console.log(e.status);
        console.log(e.responseText);
    }
});

$.post(
    url,
    {
        name: 'value',
    },
    function(result) {
        var str = "";
        $('#content').html(str);
        var str = "用户名："+result.username+"<br>密码："+result.password;
        $('#content').html(str);
    }
);