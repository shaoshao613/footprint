<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午5:17
 */


include "../Visitor.php";
$mysql=new Mysql();
if(isset($_POST['from']))
	$from=$_POST['from'];
else
	$from=0;
if(isset($_POST['size']))
	$size=$_POST['size'];
else
	$size=30;

$sql="SELECT *,ft.id as footprintId FROM footprint ft inner join foot f WHERE ft.footId=f.id order by `time` desc,`like` desc,`timestamp` desc limit {$from},{$size}";
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
