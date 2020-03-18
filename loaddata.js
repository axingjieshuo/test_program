//示例
<div class="list">
<ul>内容会被存放到这里
</ul>
</div>

//上拉加载
function loaddata(page, obj, sc) {
    var link = page.replace('0000', niunum);
    showLoader('正在加载中....');
    var html = '<div class="blank-10"></div><div class="container" style="text-align: center;"><a class="text-center">没有更多内容</a></div>';
    var end = 0;
    $.get(link, function (data) {
        if (data != 0) {
            console.log(data);
            obj.append(data);
        }else{
            obj.append(html);
            niulock = 0;
            hideLoader();
            return;
        }
        niulock = 0;
        hideLoader();
    }, 'html');
    if (sc === true) {
        $(window).scroll(function () {
            var wh = $(window).scrollTop();
            var xh = $(document).height() - $(window).height() - 70;
            if (!niulock && wh >= xh ) {
                niulock = 1;
                niunum++;
                var link = page.replace('0000', niunum);
                showLoader('正在加载中....');
                var timeout = setTimeout(function(){
                    niulock = 0;
                    hideLoader();
                },5000);
                $.get(link, function (data) {
                    if (data != 0) {
                        if(timeout){ //清除定时器
                            clearTimeout(timeout);
                            timeout = null;
                        }
                        obj.append(data);
                    }else if (end == 0){
                        //没有数据就显示没有更多内容的提示
                        obj.append(html);
                        end = 1;
                    }
                    niulock = 0;
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