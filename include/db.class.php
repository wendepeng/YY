<?php 
/***
数据库类,从扩展性考虑先设计为抽象类
***/

defined('ACC')||exit('ACC Denied!');

abstract class db{

	/*
    连接服务器
    parms $h 服务器地址
    parms $u 用户名
    parms $p 密码
    return bool
    */
	public abstract function connect($h,$u,$p);
	

	/*
	执行查询操作
	parms $sql sql语句
	*/
	public abstract function query($sql);
	

	/*
	执行增加，更新，删除操作
	parms $sql sql语句
	*/
	public abstract function execute($table,$arr,$mode='insert',$where = ' where 1 limit 1');
}
