<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/27
 * Time: 下午10:02
 */
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
include "../Visitor.php";
$userId=getUserId();
$mysql=new Mysql();
//$regular=new Regular(null);
//$regular->host="http://www.google.com/";
//$regular->threshold=100;
//$regulars[]=$regular;
//
//$regular=new Regular(null);
//$regular->host="http://www.jd.com/";
//$regular->threshold=10000;
//$regulars[]=$regular;
//var_dump($_POST['data']);
$regulars=json_decode($_POST['data']);
$myMemcache=new MyMemcache();
foreach($regulars as $item){
	$key=$item->threshold."_".$item->host;
	if($myMemcache->get($key)){
		$id=$myMemcache->get($key);
	}else{
		$mysql->insert("regular","`host`,`threshold`","'{$item->host}','{$item->threshold}'");
		$id=mysql_insert_id();
		$myMemcache->set($key,$id);
	}
	$time=time();
	$mysql->replace("user_regular","`user_id`,`regular_id`,`time`","'{$userId}','{$id}','{$time}'");
}
$data=json_encode($regulars);
echo $data;