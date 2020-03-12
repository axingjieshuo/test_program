<?php
	
	public function redis_shop(){
		//取出库存gross，假设为3
		$gross = 3;

		//如果库存>0,继续执行
		if ($gross > 0) {
			$r = new redis();
			//设置一个key为“xxx+商品id”，此例中使用test_num_buy
			$r_key = 'test_num_buy';
			$least_num = $r->lLen($r_key);
			//如果队列长度大于库存，直接返回
			if ($least_num > $gross) {
				return '卖完了';
			}
			//压入队列
			$this_num = $r->rPush($r_key,$userid);
			//设置5秒过期
			$r->expire($r_key,5);
			//如果压入队列的返回值，也就是队列长度大于库存，直接返回
			if ((int)$this_num > $gross) {
				return '卖完了';
			}
			//休眠两秒替代一系列生成订单付款等操作
			sleep(2);

			//去shop库中减去库存gross
			$res = M('shop')->where($map)->setDec('gross');
		}
		return $res;
	}
?>