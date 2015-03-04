<?php
/****
日志记录类
*/
defined('ACC')||exit('ACC Denied');

class Log {
    const LOGFILE = 'curr.log'; 

    // 写日志的
    public static function write($cont) {
        $cont .= "\r\n";

        $log = ROOT . 'log/' . self::LOGFILE;
        if(!is_dir(ROOT . 'log/')){
            mkdir(ROOT . 'log/');
        }
        if(!file_exists($log)) { 
            touch($log);    
        }

        // 判断是否备份
        if(self::isBak($log)){  
            self::bak();     
        } 
        
        $fh = fopen($log,'ab');
        fwrite($fh,$cont);
        fclose($fh); 
    }

    // 备份日志
    public static function bak() {
        // 就是把原来的日志文件,改个名,存储起来
        // 改成 年-月-日.bak这种形式
        $log = ROOT . 'log/' . self::LOGFILE;
        $bak = ROOT . 'log/' . date('ymd') . mt_rand(10000,99999) . '.bak';
        return rename($log,$bak);
    }

    // 读取并判断日志的大小
    public static function isBak($log) {
        // 清除缓存
        clearstatcache(true,$log);
        $size = filesize($log);
        if($size <= 1024 * 1024) { //大于1M
            return false;
        }
        
        return true;
    }
}




