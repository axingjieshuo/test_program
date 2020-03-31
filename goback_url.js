/**
 * 用于页面跳转
 * 假如该页面有多个添加参数然后重定向到自己的操作，history.back(-1)就会一层一层返回
 * 所以记录跳转来源（本例子使用不同域名跳转），如果相同则加back_url数字，如果不同则back_url置1
 * 然后使用history.go(-back_url)跳转到相应层
 * @return {[type]} [description]
 */
function check_url(){
	var come_url = document.referrer;
	var self_url = window.location.host;

	var back_url = getCookie("back_url");
	
	if (-1 == parseInt(come_url.indexOf(self_url))) {
		//从其他地方过来的
		back_url = 1;
		setCookie('back_url',back_url,1);
	}else{
		if ("" == back_url) {
			back_url = 2;
		}else{
			back_url = parseInt(back_url) + 1;
		}
		setCookie('back_url',back_url,1);
		$(".top-addr").attr("href",'javascript:history.go(-'+ back_url +');');
	}
	
}