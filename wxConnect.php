<?php
	
	//微信appid
	$this->appid = ;
	//微信appsecret
	$this->appsecret = ;

	/**
	 * 第一步：去微信请求code
	 * @return mixed
	 */
	public function wx_login_one(){
		//用于重定向地址backurl，get方式获取目标url
		$backurl = I('get.backurl');
		//用于验证的state
		$state = md5(uniqid(rand(), TRUE));
		//存到session里
		session('state', $state);
		session('backurl',$backurl);
		//跳到微信授权
		$login_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . urlencode(__HOST__ . U('XXXXX(目标controller)/wx_login_two')) . '&response_type=code&scope=snsapi_userinfo&state=' . $state . '#wechat_redirect';
		header("location:{$login_url}");
		$this->display();
	}
	
	/**
	 * 第二步：用code，appid，appsecret换access_token和openid
	 * 第三步：用access_token和openid换用户名和头像
	 * @return mixed
	 */
	public function wx_login_two(){
		//检测state参数，如果从微信授权跳转过来
		if ($_REQUEST['state'] == session('state')) {
			import('@/Net.Curl');
			$curl = new Curl();
			//微信授权失败
			if (empty($_REQUEST['code'])) {
				$this->error('授权后才能登陆');
			}
			//用code换access_token和openid
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $_REQUEST['code'] . '&grant_type=authorization_code';
			$str = $curl->get($token_url);
			$params = json_decode($str, true);
			//如果报错
			if (!empty($params['errcode'])) {
				echo '<h3>error:</h3>' . $params['errcode'];
				echo '<h3>msg  :</h3>' . $params['errmsg'];
				die;
			}
			//如果没有获得openid也算失败
			if (empty($params['openid'])) {
				$this->error('登录失败');
			}
			//用access_token和openid换用户名和头像
			$info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $params['access_token'] . '&openid=' . $params['openid'] . '&lang=zh_CN';
			$info = $curl->get($info_url);
			$info = json_decode($info, true);
			$data = array(
				'type' => 'weixin', 
				'open_id' => $params['openid'], 
				'token' => $params['refresh_token'], 
				'nickname' => $info['nickname'], 
				'headimgurl' => $info['headimgurl']
			);
			//进入数据库找人或者注册
			$this->wx_login_three($data);
		}
	}

	/**
	 * 从数据库中找这个人，没找到就注册
	 * @param  array  $data 用户注册信息
	 * @return mixed 
	 */
	private function wx_login_three($data) {
		//返回地址
		$backurl = session('backurl');
		//根据openid和type去connect表中拿用户数据
		$connect = D(第三方授权表)->where(array('type' => $data['type'], 'open_id' => $data['open_id']))->find();
		//如果没找到就新增一个
		if(empty($connect)){
			$connect = $data;
			$connect['connect_id'] = D(第三方授权表)->add($data);
		}
		//如果没有uid，就说明这个用户没有注册
		if(empty($connect['uid'])){
			//注册
			//......
			//注册成功返回
			$this->wx_login_back($backurl);
		} else {
			//设置userid
			setuid($connect['uid']);
			session('access', $connect['connect_id']);
			//授权成功返回
			$this->wx_login_back($backurl);
		}
		die;
	}

	/**
	 * 跳转
	 * @param  str    $url 跳转地址
	 * @return mixed
	 */
	public function wx_login_back($url){
		$login_url = $url;
		header('Location:{$login_url}');
		die;
	}
