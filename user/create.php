<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午2:33
 */
include "../memcache.php";
$myMemcache=new MyMemcache();
$expireTime=7*24*3600;
$userId=time()/10%10000;
$token=$myMemcache->get("u".$userId);
while($token){
	$userId=$userId*2;
	$token=$myMemcache->get($userId);
}
$token = md5(uniqid(rand(), TRUE));
$myMemcache->set("u".$userId,$token);
$myMemcache->set($token,$userId);
$myMemcache->close();
$user=[
	userId =>$userId,
	token => $token,
];
$res=[
	ok => 1,
	data => $user,
];
echo json_encode($res);

include '../Mysql.php';
$mysql = new Mysql();//连接数据库
$userName="user".$userId;
$mysql->insert("user", "`userId`,`userName`",   "'{$userId}','{$userName}'");//插入数据
$mysql->close();//关闭连接