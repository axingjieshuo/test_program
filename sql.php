<?php 

	/**
	* 定时任务创建“某个”表，假设为comment_年月，意思为XX年XX月winkcomment分表
	*/
	public function create_comment(){
		// 实例化一个model对象 没有对应任何数据表
		$Model = new \Think\Model();
		$ym_date = date('Ym', strtotime(date('Y-m-01') . ' +1 month'));
		$query = "CREATE TABLE IF NOT EXISTS `comment_".$ym_date."` 
		( 
		`cid` CHAR(16) NOT NULL COMMENT '评论id', 
		`wid` CHAR(16) NOT NULL COMMENT '帖子id' , 
		`userid` INT NOT NULL COMMENT '用户id' , 
		`content` VARBINARY(3000) NOT NULL COMMENT '评论内容' , 
		`image` VARCHAR(755) NULL COMMENT '图片预留字段' , 
		`at_id` INT NULL COMMENT '回复哪个人' , 
		`time` VARCHAR(16) NOT NULL COMMENT '时间' , 
		PRIMARY KEY (`cid`) ) 
		ENGINE = InnoDB COMMENT = '瞬间的评论分表';";
		$res = $Model->execute($query);
	}
?>