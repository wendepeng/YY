<?php 
/***
mysql数据库类
***/

defined('ACC')||exit('ACC Deined');

class mysql extends db{
	private static $instance=null;
	private $conn=null;
	private $conf=null;


	protected function __construct(){
		$this->conf=config::getInstance();

        $this->connect($this->conf->host,$this->conf->user,$this->conf->pwd);
        $this->select_db($this->conf->db);
        $this->setChar($this->conf->char);
	}

	public static function getInstance(){
		if(self::$instance instanceof self){
			return self::$instance;
		}else{
			self::$instance=new self();
			return self::$instance;
		}
	}

	public function connect($h,$u,$p){
		$this->conn=mysql_connect($h,$u,$p);
		if(!$this->conn){
			throw new Exception("Error connect Failed!");
		}
	}

	protected function select_db($db) {
        $sql = 'use ' . $db;
        if(!$this->query($sql)){
        	throw new Exception("Error use db Failed!");
        }
    }

    protected function setChar($char) {
        $sql = 'set names ' . $char;
        if(!$this->query($sql)){
        	throw new Exception("Error set names Failed!");
        }
    }

	public function query($sql) {
        $rs =  mysql_query($sql,$this->conn);
	    log::write($sql);
        return $rs;
    }

    public function execute($table,$arr,$mode='insert',$where = ' where 1 limit 1') {
        /*    insert into tbname (username,passwd,email) values ('',)
        /// 把所有的键名用','接起来
        // implode(',',array_keys($arr));
        // implode("','",array_values($arr));
        */
        
        if(!is_array($arr)) {
            return false;
        }

        if($mode == 'update') {
            $sql = 'update ' . $table .' set ';
            foreach($arr as $k=>$v) {
                $sql .= $k . "='" . $v ."',";
            }
            $sql = rtrim($sql,',');
            $sql .= $where;
            
            return $this->query($sql);
        }

        $sql = 'insert into ' . $table . ' (' . implode(',',array_keys($arr)) . ')';
        $sql .= ' values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';

        return $this->query($sql);
    }
}