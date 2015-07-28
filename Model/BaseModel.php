<?php
/**
 * Created by PhpStorm.
 * User: shaoting
 * Date: 15/7/26
 * Time: 下午2:02
 */
class FootPrint{
	public $foot;
	public $user;
	function __construct($array)
	{
		//if(trpos($fields, 'foot'))
			$this->foot = new Foot($array);
		//if(trpos($fields, 'user'))
		//	$this->foot = new Foot($array);
		$this->userId = $array['userId'];
		if(isset($array['footprintId'])){
			$this->id=$array['footprintId'];
		}else{
			$this->id=$array['id'];
		}
		$this->footId = $array['footId'];
		$this->initiative = $array['initiative'];
		$this->time = $array['time'];
		$this->timestamp = $array['timestamp'];
	}
}class Regular{
	function __construct($array)
	{
		if(!isset($array))
			return;
		$this->id = $array['id'];
		$this->host = $array['host'];
		$this->threshold = $array['threshold'];
	}
}
class User{
	public $userName;
	public $id;
	public $token;
	function __construct(){
	}
}
class Foot{
	public $id;
	public $url;
	public $title;
	function __construct($array){
		if(isset($array['footId'])){
			$this->id=$array['footId'];
		}else{
			$this->id=$array['id'];
		}
		$this->url=$array['url'];
		$this->title=$array['title'];
		$this->like=$array['like'];

	}
}