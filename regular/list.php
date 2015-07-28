<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 下午9:57
 */
include "../Visitor.php";
$userId=getUserId();

$mysql=new Mysql();
$result=$mysql->select("*","`user_regular`","`user_id`","{$userId}");

if($result){
	//echo "SELECT * FROM user_regular u inner join regular r WHERE r.id=u.regular_id and u.user_id={$userId}";
	$regular_array=$mysql->query_array("select * from (SELECT u.*,r.host,r.threshold FROM user_regular u inner join regular r WHERE r.id=u.regular_id and u.user_id={$userId} order by time desc ) a group by host");
}else{
	//echo "select * from (SELECT *,count(r.id) count FROM user_regular u inner join regular r WHERE r.id=u.regular_id group by r.id)a limit 10";
	//无规则时返回大家用的最多的规则
	$regular_array=$mysql->query_array("select * from (SELECT u.*,r.host,r.threshold,count(r.id) count FROM user_regular u inner join regular r WHERE r.id=u.regular_id group by r.id)a group by host order by time desc limit 10");

}
foreach($regular_array as $v){
	$regular=new Regular($v);
	$regulars[]=$regular;
}
$res=[
	ok => 1,
	data => $regulars,
];
echo json_encode($res);