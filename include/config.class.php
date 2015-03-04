<?php 
/***
配置读取类
***/

defined('ACC')||exit('ACC Denied');

class config{
	protected static $instance=null;
	protected $items=array();

	final protected function __construct(){
		include(ROOT."include/config.php");
		$this->items=$_CFG;
	}

    public static function getInstance(){
    	if(self::$instance instanceof self){
    		return self::$instance;
    	}else{
    		self::$instance=new self();
    		return self::$instance;
    	}
    }	

    public function __get($key){
    	if(array_key_exists($key, $this->items)){
    		return $this->items[$key];
    	}else{
    		return null;
    	}
    }

    public function __set($key,$value){
    	$this->items[$key]=$value;
    }
}

$conf=config::getInstance();