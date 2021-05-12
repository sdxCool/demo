<?php

include  __DIR__."/../rest/Resources/Mysql.php";
use Tyrell\Mysql;

$mysqli=(new Mysql())->getOneMysqlConn();
if(!$mysqli){
  die("链接数据库错误！");
}
if($mysqli->query("truncate table staff;")){
    print_r("删除成功");
}else{
    print_r("删除失败");
}