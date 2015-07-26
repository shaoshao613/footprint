<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午5:17
 */
include '../Visitor.php';
$userId=getUserId();
$mysql=new Mysql();
if(isset($_POST['from']))
	$from=$_POST['from'];
else
	$from=0;
if(isset($_POST['size']))
	$size=$_POST['size'];
else
	$size=30;
$waiting="";
if(isset($_POST['waiting'])&&$_POST['waiting']){
	$sendTime=time()-10*60;
	$waiting=" and timestamp>{$sendTime} ";
}

$sql="SELECT *,ft.id as footprintId FROM footprint ft inner join foot f WHERE ft.footId=f.id and ft.userId={$userId} order by timestamp desc limit {$from},{$size}";
if(isset($sendTime)){
	$sql="SELECT *,ft.id as footprintId FROM footprint ft inner join foot f WHERE ft.footId=f.id and ft.userId={$userId} {$waiting}order by timestamp desc limit {$from},{$size}";
}

$foot_array=$mysql->query_array($sql);
foreach($foot_array as $v){
	$footprint=new FootPrint($v);

	$footPrints[]=$footprint;
}
$res=[
	ok => 1,
	data => $footPrints,
];
echo json_encode($res);