<?php

namespace Tyrell;

class Mysql
{

    //连接到某个具体数据库
    function getOneMysqlConn()
    {
        $result = $this->getFileDate();
        $dbusername = $result["dbusername"];
        $dbpassword = $result["dbpassword"];
        $dbname = $result["dbname"];
        $mysqli = new \mysqli("localhost", $dbusername, $dbpassword, $dbname, 3306);
        $mysqli->set_charset("utf8");
        return $mysqli;
    }


    //连接到Mysql数据库
    function getMysqlConn()
    {
        $result = $this->getFileDate();
        $dbusername = $result["dbusername"];
        // $dbpassword = "root";
        $dbpassword =  $result["dbpassword"];
        $mysqli = new \mysqli("localhost", $dbusername, $dbpassword, "", 3306);
        $mysqli->set_charset("utf8");
        return $mysqli;
    }

    //返回数据库名
    function dbName()
    {
        $result = $this->getFileDate();
        $dbName = $result["dbname"];
        return $dbName;
    }
    
    //拿到url
    function getUrl(){
        $result = $this->getFileDate();
        $url = $result["url"];
        return $url;
    }

    //拿到cookie
    function getCookie(){
        $result = $this->getFileDate();
        $cookie = $result["cookie"];
        return $cookie;
    }

    //得到query查询结果
    function getResult($query)
    {
        $result = array();
        $result['result'] = $this->filterData($query);
        $result['total'] = $this->getTotal($query);
        return $result;
    }

    //查询过滤的数据
    function filterData($query)
    {
        $sql = "select * from " . $query["table"];
        $where = $this->getWhere($query['conditions']);
        $sql = $sql . ' where  ' . $where;
        $sql = $sql . ' limit ' . $query['start'] . ',' . $query['limit'];
        $conn = $this->getOneMysqlConn();
        $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    function getWhere($conditions)
    {
        $where = "(1=1)";
        for ($i = 0; $i < count($conditions); $i++) {
            $condition = $conditions[$i];
            $where = $where . " and " . $condition['field'] . " like '%" . $condition['value'] . "%'";
        }
        return $where;
    }

    //得到数据库中的总条数
    function getTotal($query)
    {
        $sql = "select count(*) as total from " . $query["table"];
        $where = $this->getWhere($query['conditions']);
        $sql = $sql . ' where  ' . $where;
        $conn = $this->getOneMysqlConn();
        $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $result[0]['total'];
    }

    //单纯查询总表
    function getTotalList($listName)
    {
        $mysqli = $this->getOneMysqlConn();
        $sql = "select * from " . $listName;
        $mysqliResult = $mysqli->query($sql);
        $result = $mysqliResult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    //添加
    function addItem($fields,$values,$tableName) {
        $sql = "insert into " . $tableName;
        $sql = $sql."(".$fields[0].",".$fields[1].",".$fields[2].",".$fields[3].")";
        $sql = $sql . " values" . "("."'".$values[0]."'".","."'".$values[1]."'".","."'".$values[2]."'".","."'".$values[3]."'".")";
        $conn = $this->getMysqlConn();
        $result = $conn->query($sql);
        return $result;
    }

    function updateSqlLink($fields,$values){
        $length = count($fields);
        for($i=0;$i<$length;$i++){
            $set = $set . $fields[$i] ." = " . "'".$values[$i]."'" . ",";
        }
        $set = rtrim($set, ",");
        return $set;
    }

    //修改数据
    function updateItem($fields,$values,$tableName,$id) {    
        $sql = "update " . $tableName;
        $set = $this->updateSqlLink($fields,$values);
        $sql = $sql . ' set ' . $set;
        $sql = $sql. ' where id= ' . $id;
        $conn = $this->getMysqlConn();
        $result = $conn->query($sql);
        return $result;
    }

    //删除
    function deleteItem($tableName, $id)
    {
        $sql = "delete from " . $tableName . " where id = " . $id;
        $conn = $this->getOneMysqlConn();
        $mysqliResult = $conn->query($sql);
    }

    //提取文件数据
    function getFileDate()
    {
        //1.文件路径
        $file_path = "E:/pra/frRemind/init.conf";
        // $file_path = "../init.conf";
        //2.判断是否有这个文件
        if (file_exists($file_path)) {
            if ($fp = fopen($file_path, "a+")) {
                //读取文件
                $content = file_get_contents($file_path);
                //去掉换行和空格的影响
                // fclose($fp);
                // $str = str_replace(array("/r/n", "/r", "/n"), "", $content);
                $result = json_decode($content, true);
                return $result;
            } else {
                throw new \Exception("The file cann`t open!");
            }
        } else {
            throw new \Exception("There is no this file!");
        }
    }
}
