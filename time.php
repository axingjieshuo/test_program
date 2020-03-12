<?php 

	/**
	*	n天前（-）/后（+）的00：00：00的时间戳
	*	dayTime(0)为今天凌晨时间戳
	*	dayTime(-1)为昨天凌晨时间戳，以此类推
	*	本周时间戳：dayTime((date("w")==0? -6 : date("w")-1))
	*	@param	int day
	*	@return string
	*/
	function dayTime($day = 0){
		return strtotime(date('Y-m-d',time() + ($day * 86400)).' 00:00:00');
	}


	/**
	*	n月前（-）/后（+）的00：00：00的时间戳
	*	monthTime(0)为本月凌晨时间戳
	*	monthTime(-1)为上个月凌晨时间戳，以此类推
	*	@param	int month
	*	@return string
	*/
	function monthTime($month = 0){
		return echo strtotime(date('Y-m', strtotime(date('Y-m-01').' '.$month.' month')).'-01 00:00:00');
	}

?>