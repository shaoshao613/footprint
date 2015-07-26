<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 上午11:32
 */

header("Content-type: application/json; charset=utf-8");
include 'memcache.php';
include 'Mysql.php';
include 'Model/BaseModel.php';

function getUserId(){
	if(isset($_POST['token']))
		$token=$_POST['token'];
	else
		$token=$_GET['token'];
	$memcache=new MyMemcache();
	$userId=$memcache->get($token);
	$memcache->close();
	if(!$userId){
		$res=[
			'ok' => 0,
			'error' => 'token not valid',
		];
		echo json_encode($res);
		die;
	}
	return $userId;
}
