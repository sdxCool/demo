<?php
namespace Tyrell;
use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

    

/**
 * @uri /frTip
 * @uri /frTip/:id
 */
class frTip extends Resource
{
    /**
     * @method GET
     * @param  str $name
     * @return str
     */
    public function getFrTip()
    {
        $querystr = $_GET["query"] ;
        $query = json_decode($querystr,true);
        $query["start"] = $_GET["start"];
        $query["limit"] = $_GET["limit"];
        $query["table"] = "fr_tip";
        $result = (new Mysql())-> getResult($query);
        return json_encode($result);
    }


    /**
     * @method POST
     * @accepts application/json
     * @provides application/json
     * @json
     * @return Response
     */
    public function postFrTip()//新增
    {
        $mysqli = (new Mysql)-> getOneMysqlConn();
        $param = json_decode($this->request->data,true);//解析成数组，第二个参数表示是否转成数组
        $tipfrId=(int)$param['fr_id'];
        $sql="select description from fr where id=$tipfrId";
        $result = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
        $description=$result[0]['description'];
        $fields = ["fr_id","description","type","date"];
        $values = [$tipfrId,$description,$param['type'],$param['date']];
        $r = (new Mysql)->addItem($fields, $values, "fr_tip");
        $result["success"] =  $r;
        return json_encode( $result);

    }


    /**
     * @method PUT
     * @accepts application/json
     * @provides application/json
     * @json
     * @return Response
     */
    public function putFrTip() //修改
    {
        // $mysqli = new \mysqli("localhost","root","123456","demo",3306);  
        // $mysqli->set_charset("utf8");   

        $mysqli = (new Mysql)->getOneMysqlConn(); 

        $param = json_decode($this->request->data,true);//解析成数组，第二个参数表示是否转成数组
        $tipfrId=$param['fr_id'];
        $sql="select description from fr where id=$tipfrId";
        $result = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
        $fr_name=$result[0]['short_desc'];
        $fields = ["fr_id","description","type","date"];
        $fr_id = (int)$param['fr_id'];
        $values = [$fr_id,$fr_name,$param['type'],$param['date']];
        $r = (new Mysql)->updateItem($fields, $values, "fr_tip",(int)$param['id']);
        $result["success"] =  $r;
        return json_encode( $result);
    }

    /**
     * @method DELETE
     * @accepts application/json
     * @provides application/json
     * @json
     * @return Response
     */
    public function destroyFrTip()
    {
        $param = json_decode($this->request->data,true);//得到前台数据
        $id = $param["id"];
        $r = (new Mysql)->deleteItem("fr_tip",$id);
        $result["success"] =  $r;
        return json_encode( $result);
    }

}
