//示例
<div class="list">
<ul>内容会被存放到这里
</ul>
</div>

//上拉加载
function loaddata(page, obj, sc) {
    //替换页面参数的0000为数字
    var link = page.replace('0000', niunum);
    //自定义方法显示加载
    showLoader('正在加载中....');
    //"没有更多内容"时显示的东西
    var html = '<div class="blank-10"></div><div class="container" style="text-align: center;"><a class="text-center">没有更多内容</a></div>';
    //标记上面那个"没有更多内容"的提示只显示一次
    var end = 0;
    //jquery封装的get方法
    $.get(link, function (data) {
        if (data != 0) {
            //如果data有东西，就放进obj里面
            obj.append(data);
        }else{
            //如果data没有东西，就写"没有更多内容"的提示
            obj.append(html);
            //niulock为初始化记录变量
            niulock = 0;
            //隐藏自定义方法的加载动画
            hideLoader();
            return;
        }
        //niulock为初始化记录变量
        niulock = 0;
        //加载成功时隐藏自定义方法的加载动画
        hideLoader();
    }, 'html');
    if (sc === true) {
        //当页面滚动时 $(window).scroll(function());如果是div则$("div").scroll(function());
        $(window).scroll(function () {
            //获取滚动条的垂直偏移
            var wh = $(window).scrollTop();
            //$(document).height() 表示网页内容高度
            //$(window).height() 表示窗口可视区域高度
            //页面内容高度-窗口可视高度-70（下栏） = 剩下看不到的页面的高度
            var xh = $(document).height() - $(window).height() - 70;
            //如果偏移量大于剩下看不到页面的高度，就说明拖到底了
            if (!niulock && wh >= xh ) {
                //为初始化记录变量，防止瞬间多次加载页面
                niulock = 1;
                //页面页数加一
                niunum++;
                //替换页面的/p/0000为/p/niunum（页数）
                var link = page.replace('0000', niunum);
                //显示自定义加载页面
                showLoader('正在加载中....');
                //超时设置5秒setTimeout(function(),5000);
                var timeout = setTimeout(function(){
                    //初始化记录变量
                    niulock = 0;
                    //隐藏自定义加载动画
                    hideLoader();
                },5000);
                //jquery封装的get方法,$.get($url,$data[可选],function(...)[可选],dataType[规定预期的服务器响应的数据类型]);
                $.get(link, function (data) {
                    //请求成功函数
                    if (data != 0) {
                        if(timeout){ //清除定时器
                            //请求成功就清除
                            clearTimeout(timeout);
                            timeout = null;
                        }
                        //xxx.append() 方法在被选元素的结尾插入指定内容
                        obj.append(data);
                    }else if (end == 0){
                        //没有数据就显示"没有更多内容"的提示
                        obj.append(html);
                        //设置end为1，就不会再进这个判断，"没有更多内容"的提示只显示一次
                        end = 1;
                    }
                    //初始化记录变量
                    niulock = 0;
                    //隐藏自定义加载动画
                    hideLoader();
                }, 'html');
            }
        });
    }
}

//单独一个controller和view页面，将生成的内容放到class="list"的标签下面的ul标签里面
loaddata('<{$nextpage}>',$(".list ul"), true);


//controller文件：
$this->assign('nextpage', LinkTo('控制器/方法',$linkArr（参数）,array('p' => '0000')（分页）));
$this->display();

//LinkTo方法
function LinkTo($ctl, $vars = array(),$var2=array()) {
    $vars = array_merge($vars,$var2);
    foreach ($vars as $k => $v) {
        if (empty($v))
            unset($vars[$k]);
    }
    return U($ctl, $vars);
}