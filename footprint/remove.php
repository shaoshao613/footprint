<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 下午5:25
 */
include "../Visitor.php";

$_GET['userId']=getUserId();
$KeyList=array('userId','footprintId');
foreach ($KeyList as $k => $v) {
		if(!isset($_GET[$v])){
			$res=[
				'ok' => 0,
				'error' => "param invalid ",
			];
			echo json_encode($res);
			die;
		}
}
$mysql=new Mysql();
$result=$mysql->select("*","footprint","`footId`","'{$_GET['footprintId']}'");
if($result){
	if($result['userId']==$_GET['userId']){
		$myMemcache=new MyMemcache();
		$footprintId=$result['id'];
		$removeFootprint="delete from footprint where id={$footprintId}";
		//echo $removeFootprint;
		$mysql->query($removeFootprint);
		$myMemcache->delete($_GET['userId']."foot".$result['footId']);
		if($myMemcache->get("like".$result['footId'])&&$myMemcache->get("like".$result['footId'])==1){
			//$removeFoot="delete from foot where id={$result['footId']}";
			//$mysql->query($removeFoot);
		}
		echo json_encode([
			'ok' => 1
		]);

	}else{
		echo json_encode([
			'ok' => 0,
			'error' => "no permission",
		]);
		die;
	}
}
else{
    echo json_encode([
        ok => 0,
        error => "footId not exsit",
    ]);
}


$mysql->close();