<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午2:49
 */

//
//
$connect = new Memcached;  //声明一个新的memcached链接
$connect->setOption(Memcached::OPT_COMPRESSION, false); //关闭压缩功能
$connect->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
$connect->addServer('7e3d812f3aa14d09.m.cnhzaliqshpub001.ocs.aliyuncs.com', 11211); //添加OCS实例地址及端口号
$connect->setSaslAuthData('7e3d812f3aa14d09', 'Aliyun6161361'); //设置OCS帐号密码进行鉴权,如已开启免密码功能，则无需此步骤
echo "dsf".$connect->get(11223);
$connect->close();