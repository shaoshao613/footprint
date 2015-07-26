<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午2:49
 */

//
//
// todo 测试用待删除
if(!isset($_GET['token'])){
	$_GET['token']=11223;
}

include '../Visitor.php';
$_GET['userId']=getUserId();
//$_GET['url']="http://www.baidu.com";
//$_GET['initiative']="http://www.baidu.co";
//$_GET['title']="http://www.baidu.com";
//$_GET['time']=21324;
if(!isset($_GET['footId'])){
	$KeyList=array('userId','url','title','time');
	foreach ($KeyList as $k => $v) {
		if(!isset($_GET[$v])){
			$res=[
				'ok' => 0,
				'error' => "param invalid ",
			];
			echo json_encode($res);
			return;
		}
	}

}


$mysql = new Mysql();//连接数据库
$myMemcache=new MyMemcache();


if(isset($_GET['footId'])){
	$_GET['like']=$myMemcache->get("like".$_GET['footId'])?$myMemcache->get("like".$_GET['footId'])+1:1;
	$_GET['id']=$_GET['footId'];
	$FootList=array('id','like');
	$mysql->update("foot","like","{$_GET['like']}","id","{$_GET['id']}");
}else{
	$memValue=$myMemcache->get($_GET['url']);
	if($memValue) {
		$_GET['id']=$memValue;
		$footprintId=$myMemcache->get($_GET['userId']."foot:".$_GET['id']);
		if($footprintId){
			echo json_encode([
				'ok' => 1,
				'data' => [
					id=>$footprintId,
				],
			]);
			die;
		}
		$_GET['like']=$myMemcache->get("like".$_GET['id'])?$myMemcache->get("like".$_GET['id'])+1:1;
		$FootList=array('url','title','id','like');
	}else{
		$_GET['like']=1;
		$FootList=array('url','title');
	}
	foreach ($FootList as $k => $v) {
		if(isset($key)){
			$key .= ",`" . $v."`";
			$value .= ",'" . mysql_real_escape_string($_GET[$v]) . "'";
		}else{
			$key="`".$v."`";
			$value= "'" . mysql_real_escape_string($_GET[$v]) . "'";
		}
	}
	$mysql->replace("foot", "$key",   "$value");//插入数据
	unset($value);
	unset($key);
}
$_GET['footId']=mysql_insert_id();
//echo $_GET['footId'];
$myMemcache->set("like".$_GET['footId'],$_GET['like']);
if(isset($_GET['url']))
	$myMemcache->set($_GET['url'],$_GET['footId']);
$_GET['timestamp']=time();
$footprintId=$myMemcache->get($_GET['userId']."foot:".$_GET['footId']);
$FootPrintList=array('userId','footId','initiative','time','timestamp');
foreach ($FootPrintList as $k => $v) {
	if(isset($key)){
		$key .= ",`" . $v."`";
		$value .= ",'" . mysql_real_escape_string($_GET[$v]) . "'";
	}else{
		$key="`".$v."`";
		$value= "'" . mysql_real_escape_string($_GET[$v]) . "'";
	}
}
$footprintId=$myMemcache->get($_GET['userId']."foot:".$_GET['footId']);
if($footprintId){
	// todo 重复浏览一个网页的情况
}else{
	$mysql->insert("footprint", "$key",   "$value");
	$footprintId=mysql_insert_id();
	echo $_GET['userId']."foot:".$_GET['footId'];
	$myMemcache->set($_GET['userId']."foot:".$_GET['footId'],$footprintId);
}


$footprint=[
	id => $footprintId,
];
$res=[
	'ok' => 1,
	'data' => $footprint,
];
echo json_encode($res);




$mysql->close();//关闭连接