<?php

include  __DIR__."/../rest/Resources/CurlPost.php";
include  __DIR__."/../rest/Resources/Mysql.php";
use Tyrell\Mysql;
use Tyrell\CurlPost;

initDatabase();//初始化数据库

function initDatabase(){
  $mysqli=(new Mysql())->getMysqlConn();
  if(!$mysqli){
    die("链接数据库错误！");
  }
  $dbName = (new Mysql())->dbName();
  $mysqli->query("create database if not exists "."`".$dbName."`");
  $mysqli->select_db($dbName);

  //初始化fr表
  $createFr="CREATE TABLE IF NOT EXISTS `fr` ( `id` bigint(20) NOT NULL ,
      `description` varchar(255) DEFAULT NULL,
      `cf_webui_fs_start` varchar(255) DEFAULT NULL,
      `cf_webui_ds_end` varchar(255) DEFAULT NULL,
      `cf_webui_fs_end` varchar(255) DEFAULT NULL,
      `cf_webui_ds_start` varchar(255) DEFAULT NULL,
      `cf_webui_coding_end` varchar(255) DEFAULT NULL,
      `cf_webui_coding_start` varchar(255) DEFAULT NULL,
      `cf_webui_jd_end` varchar(255) DEFAULT NULL,
      `cf_webui_jd_start` varchar(255) DEFAULT NULL,
      `cf_webui_code_review_end` varchar(255) DEFAULT NULL,
      `cf_webui_code_review_start` varchar(255) DEFAULT NULL,
      `cf_webui_ut_end` varchar(255) DEFAULT NULL,
      `cf_webui_ut_start` varchar(255) DEFAULT NULL,
      `cf_webui_submit_to_qa` varchar(255) DEFAULT NULL,
      `assign` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  if($mysqli->query($createFr)){
    print_r( 'table fr is created succssfully');
    print_r("\n");
  }
  else{
    print_r("Error");
  }


  //初始化frTip表
  $createFrTipList="
  CREATE TABLE IF NOT EXISTS `fr_tip` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `fr_id` int(11) DEFAULT NULL,
    `description` varchar(255) DEFAULT NULL,
    `type` varchar(255) DEFAULT NULL,
    `date` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
  if($mysqli->query($createFrTipList)){
    print_r( 'table frTip is created succssfully');
    print_r("\n");
  }
  else{
    print_r("Error");
  }

  //初始化checkDate表
  $createCheckDateList="
  CREATE TABLE IF NOT EXISTS `check_date` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `fr_id` int(11) DEFAULT NULL,
      `type` varchar(255) DEFAULT NULL,
      `fr_tip_id` int(11) DEFAULT NULL,
      `assign` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
    if($mysqli->query($createCheckDateList)){
      print_r( 'table checkDate is created succssfully');
      print_r("\n");
    }
    else{
      print_r("Error");
    }

  //初始化员工信息表
  $createStaffList="
  CREATE TABLE IF NOT EXISTS `staff` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `email` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
    if($mysqli->query($createStaffList)){
      print( 'table staff is created succssfully');
      print_r("\n");
    }
    else{
      print_r("Error");
    }

    //初始化setting表
    $createSettingList="
    CREATE TABLE IF NOT EXISTS `setting` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `fr_start_days` varchar(100) DEFAULT NULL,
      `fr_toqa_days` varchar(100) DEFAULT NULL,
      `bug_start_days` varchar(100) DEFAULT NULL,
      `bug_toqa_days` varchar(100) DEFAULT NULL,
      `fr_remind` varchar(100) DEFAULT NULL,
      `bug_remind` varchar(100) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;";
    if($mysqli->query($createSettingList)){
      print( 'table setting is created succssfully');
      print_r("\n");
    }
    else{
      print_r("Error");
    }
}
