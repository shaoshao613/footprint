<?php

class Mysql
{
    private $host;
    private $user;
    private $pass;
    private $database;
    private $charset;

    function __construct()
    {
        $this->connect();
    }

    private function connect()//连接函数
    {
        // $link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS) or die ("连接数据库服务器失败!");
//        $link = mysql_connect("127.0.0.1", "root", "root") or die ("连接数据库服务器失败!");
       $link = mysql_connect("127.0.0.1", "root", "6161361") or die ("连接数据库服务器失败!");
        if ($link) {
            mysql_select_db("footprint", $link);
            //   mysql_select_db(SAE_MYSQL_DB,$link);
            //your code goes here
        }
        mysql_query("set names utf8");
    }

    function query($sql)
    {
        $result = mysql_query($sql);
        return $result;
    }

    function query_array($sql)
    {
        $select = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_array($select)) {
            array_push($rows, $row);
        }
        return $rows;
    }
    
    function select($sql, $tab, $col, $value)//选择函数
    {
        $select = mysql_query("select $sql from $tab where $col=$value");
        $row = mysql_fetch_array($select);
//		echo "select $sql from $tab where $col=$value";
        return $row;
    }

    function select_array($sql, $tab, $col, $value)//选择函数
    {
        $select = mysql_query("select $sql from $tab where $col=$value");
        $rows = array();
        while ($row = mysql_fetch_array($select)) {
            array_push($rows, $row);
        }
        return $rows;
    }

    function insert($tab, $col, $value)//插入数据函数
    {
        mysql_query("INSERT INTO $tab ($col)values($value)");
        return "INSERT INTO $tab($col)values($value)";
//        return mysql_insert_id();
    }

    function replace($tab, $col, $value)//插入数据函数
    {
        mysql_query("REPLACE INTO $tab ($col)values($value)");
        return "REPLACE INTO $tab($col)values($value)";
    }

    function update($tab, $col, $new_value, $colm, $value)//更新数据函数
    {
        mysql_query("UPDATE $tab SET $col=$new_value where $colm=$value");
    }

    function delete($tab, $col, $value)//删除数据函数
    {
        mysql_query("DELETE FROM $tab where $col=$value");
    }

    function close()//关闭连接函数
    {
        mysql_close();

    }
}

?>