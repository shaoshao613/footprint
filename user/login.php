<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 上午11:12
 */
include "../memcache.php";
include "../Mysql.php";
header("Content-type: application/json; charset=utf-8");
$UserList=array('userId','userName','password');
foreach ($UserList as $k => $v) {
	if(isset($_GET[$v]))
		$_POST[$v]=$_GET[$v];
}

$UserList=array('userName','password');
foreach ($UserList as $k => $v) {
	if(!isset($_POST[$v])){
		$error="param invalid ".$v;
		$res=[
			'ok' => 0,
			'error' => $error,
		];
		echo json_encode($res);
		die;
	}
}
$mysql=new Mysql();
$userName=$_POST['userName'];
$result=$mysql->select("*",'user','`username`',"'{$userName}'");

if(!$result||$result['password']!=$_POST['password']){
	$res=[
		'ok' => 0,
		'error' => 'username or password not correct',
	];
	echo json_encode($res);
}else{
	$myMemcache=new MyMemcache();
	$expireTime=7*24*3600;
	$userId=$result['userId'];
	$token = md5(uniqid(rand(), TRUE));
	$myMemcache->set("u".$userId,$token,$expireTime);
	$myMemcache->set($token,$userId,$expireTime);
	$myMemcache->close();
	$user=[
		userId =>(int)($userId),
		userName => $userName,
		token => $token,
		expireTime => $expireTime,
	];
	$res=[
		'ok' => 1,
		'data' => $user
	];
	echo json_encode($res);
}

