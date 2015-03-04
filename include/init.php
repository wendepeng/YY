<?php 
/***
框架初始化文件，任何文件都要包含
***/

defined('ACC')||exit('ACC Denied');
define('DEBUG',true);
define('ROOT',str_replace('\\', '/', dirname(__FILE__).'/../'));


//包含核心文件
require(ROOT.'include/config.class.php');
require(ROOT.'include/log.class.php');
require(ROOT.'include/db.class.php');
require(ROOT.'include/mysql.class.php');

/***
PHPExcelExporter::outputArray2Excel(['wendeng'],'test.xls');
*/
require(ROOT.'include/PHPExcel/PHPExcelExporter.php');
require(ROOT . 'include/lib_base.php');

//设置报错级别
if(defined('DEBUG')) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

//过滤参数
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);

spl_autoload_register('frame_autoload');

function frame_autoload($class) {
    if(strtolower(substr($class,-5)) == 'model') {
        require(ROOT . 'Model/' . $class . '.class.php');
    } else {
        require(ROOT . 'include/' . $class . '.class.php');
    }
}



