<?php


namespace Tyrell;

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;
/**
 * @uri /check
 * @uri /check/:id
 */
class check extends Resource
{
    // private  $mysqli;
    // function setup(){
    //     $this->$mysqli = new \mysqli("localhost","root","123456","demo",3306);
    //     $this->$mysqli ->set_charset("utf8");
    // }
    /**
     * @method GET
     * @param  str $name
     * @return str
     */
    public function getObject()
    {

        $mysqli = (new Mysql)-> getMysqlConn();

        $frid=$_GET['frid'];
        $type=$_GET['type'];

        $assign=$_GET['assign'];
        $fields = ["fr_id","type","assign"];
        $values = [$frid,$type,$assign];
       (new Mysql)->addItem($fields, $values, "check_date");
        return "您已确认提醒！";
    }


}
