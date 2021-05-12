<?php
include  __DIR__."/../rest/Resources/CurlPost.php";
include  __DIR__."/../rest/Resources/Mysql.php";
use Tyrell\Mysql;
use Tyrell\CurlPost;

$mysqli=(new Mysql())->getOneMysqlConn();
$url=(new Mysql())->getUrl();
$cookie=(new Mysql())->getCookie();

$resultSession=getSessionKey($cookie);
$session=substr($resultSession,188);
$r = json_decode($session,true);
$sessionkey= new stdClass();
$sessionkey=$r['sessionkey'];

for($i=1;$i<18;$i++){
    $result=do_post($url,$i,$sessionkey,$cookie);   
    $risk = json_decode($result,true);
    $dataFr =new stdClass();
    $dataFr = $risk['datas'];

    $length=count($dataFr);
    for($x=0;$x<$length;$x++){
        $mysqliResult =$mysqli->query("insert into staff(name,email) values ('{$dataFr[$x]['lastname']}','{$dataFr[$x]['email']}')");
    }
}

//向浏览器发送post请求数据

function do_post($url,$i,$sessionkey,$cookie) {  
    $ch = curl_init ();
    curl_setopt($ch, CURLOPT_POSTFIELDS,"dataKey=".$sessionkey."&current=$i&sortParams=%5B%5D&");
    curl_setopt($ch, CURLOPT_TIMEOUT, 80 );
    curl_setopt($ch, CURLOPT_COOKIE,$cookie); //cookie
    curl_setopt($ch, CURLOPT_REFERER, "http://oa.hillstonenet.com/wui/index.html");       
    $r = (new CurlPost)->do_post_template($ch,$url);
    return $r;
}

//拿到sessionkey
function getSessionKey($cookie){
    $url = 'http://oa.hillstonenet.com/api/hrm/search/getHrmSearchResult'; //url地址
    $post = "tabkey=default_3&showAllLevel=1&virtualtype=&resourcename=&manager=&subcompany=&department=&telephone=&mobile=&mobilecall=&jobtitle=&"; //POST数据
    $ch = curl_init($url); //初始化
    curl_setopt($ch,CURLOPT_HEADER,1); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    curl_setopt($ch, CURLOPT_COOKIE,$cookie); //发送POST数据
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}