<?php
//ini_set('display_errors',1);            //错误信息
//ini_set('display_startup_errors',1);    //php启动错误信息
//error_reporting(-1);                    //打印出所有的 错误信息
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午3:02
 */
include "../memcache.php";
include "../Mysql.php";
header("Content-type: application/json; charset=utf-8");
$UserList=array('BindUserId','userName','password');
foreach ($UserList as $k => $v) {
	if(isset($_GET[$v]))
		$_POST[$v]=$_GET[$v];
}

// todo BindUserId不存在的情况，已被人绑定的情况
$_POST['userId']=$_POST['BindUserId'];
$userName=$_POST['userName'];
$userId=$_POST['BindUserId'];
if(!isset($userId)){
	$myMemcache=new MyMemcache();
	$userId=time();
	$token=$myMemcache->get("u".$userId);
	$myMemcache->close();
}
$password=$_POST['password'];
$UserList=array('userId','userName','password');
foreach ($UserList as $k => $v) {
	if(!isset($_POST[$v])&&$v!='userId'){
		$error="param invalid ".$v;
		$res=[
			'ok' => 0,
			'error' => $error,
		];
		echo json_encode($res);
		die;
	}
	if(isset($key)){
		$key .= ",`" . $v."`";
		$value .= ",'" . mysql_real_escape_string($_POST[$v]) . "'";
	}else{
		$key="`".$v."`";
		$value= "'" . mysql_real_escape_string($_POST[$v]) . "'";
	}
}
$mysql = new Mysql();//连接数据库
$userName=$_POST['userName'];
$result=$mysql->select("*",'user','`username`',"'{$userName}'");
if(!$result) {
	$mysql->replace("user", "$key", "$value");
	$userId=mysql_insert_id();
	$mysql->close();
}else{
	$res=[
		'ok' => 0,
		'error' => 'username existed',
	];
	echo json_encode($res);
	die;
}




$myMemcache=new MyMemcache();
$expireTime=7*24*3600;
$token = md5(uniqid(rand(), TRUE));
$myMemcache->set("u".$userId,$token,$expireTime);
$myMemcache->set($token,$userId,$expireTime);
$myMemcache->close();
$user=[
	userId =>$userId,
	token => $token,
	expireTime => $expireTime,
];
$res=[
	ok => 1,
	data => $user,
];
echo json_encode($res);