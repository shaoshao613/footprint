<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/25
 * Time: 下午1:05
 */
class MyMemcache{
	public $connect;
	function __construct(){
		$this->connect = new Memcached;  //声明一个新的memcached链接
		$this->connect->setOption(Memcached::OPT_COMPRESSION, false); //关闭压缩功能
		$this->connect->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
		$this->connect->addServer('7e3d812f3aa14d09.m.cnhzaliqshpub001.ocs.aliyuncs.com', 11211); //添加OCS实例地址及端口号
		$this->connect->setSaslAuthData('7e3d812f3aa14d09', 'Aliyun6161361'); //设置OCS帐号密码进行鉴权,如已开启免密码功能，则无需此步骤
	}
	function set($key,$value,$expire=null){
		$this->connect->set($key,$value,$expire);//设置数据在缓存中的过期时间
	}
	function close(){
		$this->connect->quit();
	}
	function get($key){
		return $this->connect->get($key);
	}
	function delete($key){
		return $this->connect->delete($key);
	}
}
