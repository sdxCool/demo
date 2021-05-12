<?php

include  __DIR__."/../rest/Resources/CurlPost.php";
include  __DIR__."/../rest/Resources/Mysql.php";
use Tyrell\Mysql;
use Tyrell\CurlPost;

$url="https://fr.hillstonenet.com/getProject.php";  

$resultRoot=do_post($url);
$regId = "/productid:'(.*?)'/";
preg_match_all($regId,$resultRoot,$idRoot);

$regName = "/name:'(.*?)'/";
preg_match_all($regName,$resultRoot,$regNameArray);
$tmp = array();
$k=0;
$i=0;
$regNameArrayLength = count($regNameArray[1]);
$idRootLength = count($idRoot[1]);
for($j=0;$j<$regNameArrayLength;$j++){
    $num = substr_count($regNameArray[1][$j],")");
    if($num==1){
        while($i<$idRootLength){
            $productId = $idRoot[1][$i];
            $tmp[$k]=$regNameArray[1][$j];
            $resultSecRoot=do_post_two($url,$tmp[$k],$productId);
            $projectName = "/projectname:'(.*?)'/";
            preg_match_all($projectName,$resultSecRoot,$sec);
            $lengPro = count($sec[1]);
            for($w=0;$w<$lengPro;$w++){
                $resultThirdRoot = do_post_three($url,$sec[1][$w],$productId);
                $risk = json_decode($resultThirdRoot,true);
                $mysqli=(new Mysql())->getOneMysqlConn();
                $mysqli->set_charset("utf8");
                $dataFr =new stdClass();
                $dataFr = $risk['fr_risk'];
                if($dataFr!=null){
                    $length=count($dataFr);
                    for($h=0;$h<$length;$h++){
                        $assign = $dataFr[$h]["assign"];
                        $reg = "/WEBUI:(.*?)\\[/";
                        preg_match_all($reg,$assign,$change);
                        $assign = implode($change[1]);
                        $sql = "insert into fr (id,description,cf_webui_fs_start,cf_webui_fs_end,cf_webui_ds_start,cf_webui_ds_end,
                        cf_webui_coding_start,cf_webui_coding_end,cf_webui_jd_start,cf_webui_jd_end,cf_webui_code_review_start,
                        cf_webui_code_review_end,cf_webui_ut_start,cf_webui_ut_end,cf_webui_submit_to_qa,assign) 
                        values ('{$dataFr[$h]['bug_id']}',
                        '{$dataFr[$h]['short_desc']}',
                        '{$dataFr[$h]['cf_webui_fs_start']}',
                        '{$dataFr[$h]['cf_webui_fs_end']}',
                        '{$dataFr[$h]['cf_webui_ds_start']}',
                        '{$dataFr[$h]['cf_webui_ds_end']}',
                        '{$dataFr[$h]['cf_webui_coding_start']}',
                        '{$dataFr[$h]['cf_webui_coding_end']}',
                        '{$dataFr[$h]['cf_webui_jd_start']}',
                        '{$dataFr[$h]['cf_webui_jd_end']}',
                        '{$dataFr[$h]['cf_webui_code_review_start']}',
                        '{$dataFr[$h]['cf_webui_code_review_end']}',
                        '{$dataFr[$h]['cf_webui_ut_start']}',
                        '{$dataFr[$h]['cf_webui_ut_end']}',
                        '{$dataFr[$h]['cf_webui_submit_to_qa']}',
                        '{$assign}'
                        )";
                        $mysqliResult = $mysqli->query($sql);
                    }
                }
                
            }
            $k++;
            $i++;
            break;
        }
    }
}

function do_post($url) {              
    $ch = curl_init ();//curl_init — 初始化 cURL 会话
    curl_setopt($ch, CURLOPT_POSTFIELDS, "userid=478&loginname=dyjiang%40Hillstonenet.com&type=tree2&isactive=1");
    curl_setopt($ch, CURLOPT_TIMEOUT, 60 );
    curl_setopt($ch, CURLOPT_COOKIE, "Bugzilla_login=1973; LASTORDER=bugs.bug_id; Bugzilla_logincookie=JWTq965lkY; BUGLIST=9289%3A11817%3A12508%3A12812%3A13009%3A13557%3A14118%3A14342%3A14605%3A14913%3A15027%3A15197%3A15281%3A15520%3A15594%3A15780%3A15783%3A15957%3A16059%3A16278%3A16570%3A16858%3A17340%3A17474%3A17555%3A17756%3A17790%3A17929%3A17952%3A18063%3A18087%3A18119%3A18220%3A18252%3A18256%3A18263%3A18274");       
    $r = (new CurlPost)->do_post_template($ch,$url);
    return $r;
}
function do_post_two($url,$cookieName,$productId) {              
    $ch = curl_init ();//curl_init — 初始化 cURL 会话
    curl_setopt($ch, CURLOPT_POSTFIELDS, "id=-1&n=".$cookieName."&lv=0&isproduct=true&productid=".$productId."&productname=StoneOS&userid=478&loginname=dyjiang%40Hillstonenet.com&type=tree2&isactive=1");
    curl_setopt($ch, CURLOPT_TIMEOUT, 60 );
    curl_setopt($ch, CURLOPT_COOKIE, "Bugzilla_login=1973; LASTORDER=bugs.bug_id; Bugzilla_logincookie=JWTq965lkY; BUGLIST=9289%3A11817%3A12508%3A12812%3A13009%3A13557%3A14118%3A14342%3A14605%3A14913%3A15027%3A15197%3A15281%3A15520%3A15594%3A15780%3A15783%3A15957%3A16059%3A16278%3A16570%3A16858%3A17340%3A17474%3A17555%3A17756%3A17790%3A17929%3A17952%3A18063%3A18087%3A18119%3A18220%3A18252%3A18256%3A18263%3A18274");       
    $r = (new CurlPost)->do_post_template($ch,$url);
    return $r;
}

function do_post_three($url,$name,$id) {              
    $ch = curl_init ();//curl_init — 初始化 cURL 会话
    curl_setopt($ch, CURLOPT_POSTFIELDS, "type=echarts&n=".$name."&productid=".$id." ");
    curl_setopt($ch, CURLOPT_TIMEOUT, 90 );
    curl_setopt($ch, CURLOPT_COOKIE, "Bugzilla_login=1973; LASTORDER=bugs.bug_id; Bugzilla_logincookie=JWTq965lkY; BUGLIST=9289%3A11817%3A12508%3A12812%3A13009%3A13557%3A14118%3A14342%3A14605%3A14913%3A15027%3A15197%3A15281%3A15520%3A15594%3A15780%3A15783%3A15957%3A16059%3A16278%3A16570%3A16858%3A17340%3A17474%3A17555%3A17756%3A17790%3A17929%3A17952%3A18063%3A18087%3A18119%3A18220%3A18252%3A18256%3A18263%3A18274");       
    $r = (new CurlPost)->do_post_template($ch,$url);
    return $r;
}
