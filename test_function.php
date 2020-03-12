<?php 
	
	/**
	*	连贯操作示例
	*	关键在于返回值需要返回本体，也就是对象，才可以继续调用对象中的下一个方法
	*/
	class test{
		private $man = array(
			'name'	=> '',
			'age'	=> '',
			'sex'	=> ''
		);
		function set_name($name){
			$this->man['name'] = $name;
			return $this;
		}
		function set_age($age){
			$this->man['age'] = $age;
			return $this;
		}
		function set_sex($sex){
			$this->man['sex'] = $sex;
			return $this;
		}

		function show_man(){
			return $this->man;
		}
	}

	$m = new test();
	$res = $m->set_name('张')->set_age(16)->set_sex('男')->show_man();
	var_dump($res);
?>  