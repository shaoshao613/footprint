<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 下午11:12
 */
include "../Visitor.php";

$userId=getUserId();

if(!isset($_GET['regularId'])){
	return json_encode([
		ok => 0,
		error => "参数缺失",
	]);
	die;
}
$mysql=new Mysql();
$removeSql="delete from user_regular where user_id={$userId} and regular_id={$_GET['regularId']}";
echo $removeSql;
$mysql->query($removeSql);
$mysql->close();
return json_encode([
	ok => 1,
]);


