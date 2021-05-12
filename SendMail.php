<?php
include  __DIR__ . "/../rest/Resources/Mysql.php";
include  "formatMail.php";

use Tyrell\Mysql;

$mysqli = (new Mysql())->getOneMysqlConn();


//1、获取四个列表
$frlist = (new Mysql())->getTotalList("fr"); //这是一个数组
$frlistlength = count($frlist);

$frTipList = (new Mysql())->getTotalList("fr_tip");
$frtiplength = count($frTipList);

$checkDate = (new Mysql())->getTotalList("check_date");
$checkDatelength = count($checkDate);

$staff = (new Mysql())->getTotalList("staff");
$nowdatetime = new DateTime();

$setting = (new Mysql())->getTotalList("setting");

function findCheck($frid, $type, $assign)
{
    global $mysqli;
    $flag = true;
    $a = "SELECT *from check_date where fr_id=$frid  and assign='$assign' and type='$type'";
    $result = $mysqli->query($a)->num_rows;
    if ($result != null)
        $flag = false;
    return $flag;
}

function SendMail($startTime, $settingTime, $frid, $description, $type, $frAssign, $showtype, $showMailFrom)

{
    global $mysqli;
    $a = "select * from staff where name='$frAssign'";
    $recipientAddress = $mysqli->query($a)->fetch_all(MYSQLI_ASSOC);
    $assign = urlencode($frAssign);

    $conf = (new Mysql())->getFileData();
    $from = $conf['sendMailFrom'];
    $url = $conf['url'];
    $url = 'http://' . $url . "/rest/check?frid=$frid&type=$type&assign=$assign";

    $interval = date_diff(new DateTime($startTime), new DateTime())->days + 1;
    if ($interval <= $settingTime) {
        $message = "您的" . ":" . $frid . "-" . $description . "的FR还有" . $interval . "天" . $showtype . ",如果您看到此信息请点击后方链接：" . $url . $showMailFrom;
        //$r = mail($recipientaddress[0]['email'], 'FR开始提醒', $message);
        $r = html_mail($from, $recipientAddress[0]['email'], 'FR开始提醒', $message);
        var_dump($r);
    }
}


if ($setting[0]['fr_remind'] == '1') {
    for ($x = 0; $x < $frlistlength; $x++) {
        $frid = $frlist[$x]['id'];
        $assign = $frlist[$x]['assign'];
        $description = $frlist[$x]['description'];
        $sql = 'select * from fr_tip where fr_id=' . $frid;
        $frtipexist = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
        if (!$frtipexist) {
            //判断check表里面有没有确认
            $frstartTime = $frlist[$x]['cf_webui_fs_start'];
            if ($frstartTime != '') {
                $type = '1';
                $flag = findCheck($frid, $type, $assign);
                if ($flag) {
                    SendMail($frstartTime, $setting[0]['fr_start_days'], $frid, $description, $type, $assign, "开始", " (此消息为系统发送的提醒)");
                }
            }
            $frToqaTime = $frlist[$x]['cf_webui_submit_to_qa'];
            if ($frToqaTime != '') {
                $type = '2';
                $flag = findCheck($frid, $type, $assign);
                if ($flag) {
                    SendMail($frToqaTime, $setting[0]['fr_toqa_days'], $frid, $description, $type, $assign, "提交QA", " (此消息为系统发送的提醒)");
                }
            }
        }

        if ($frtipexist && (count($frtipexist) == 1)) {
            if ($frtipexist[0]['type'] == '1' && $frlist[$x]['cf_webui_submit_to_qa'] != '') {
                $type = '2';
                $frToqaTime = $frlist[$x]['cf_webui_submit_to_qa'];
                $flag = findCheck($frid, $type, $assign);
                if ($flag) {
                    SendMail($frToqaTime, $setting[0]['fr_toqa_days'], $frid, $description, $type, $assign, "提交QA", " (此消息为系统发送的提醒)");
                }
            }
            if ($frtipexist[0]['type'] == '2' && $frlist[$x]['cf_webui_fs_start'] != '') {
                $type = '1';
                $frstartTime = $frlist[$x]['cf_webui_fs_start'];
                $flag = findCheck($frid, $type, $assign);
                if ($flag) {
                    SendMail($frstartTime, $setting[0]['fr_start_days'], $frid, $description, $type, $assign, "开始", " (此消息为系统发送的提醒)");
                }
            }
        }
    }
}

for ($x = 0; $x < $frtiplength; $x++) {
    for ($y = 0; $y < $frlistlength; $y++) {
        if ($frTipList[$x]['fr_id'] == $frlist[$y]['id']) {
            $showMailFrom = " (此消息来自用户设置的提醒)";
            $frid = $frTipList[$x]['fr_id'];
            $frtipType = $frTipList[$x]['type'];
            $assign = $frlist[$y]['assign'];
            $description = $frTipList[$x]['description'];
            if ($frlist[$y]['cf_webui_fs_start'] != '' && $frtipType == '1') {
                //--判断check_date中有没有相应数据
                $flag = findCheck($frid, $frtipType, $assign);
                //获取发送人地址

                if ($flag) {
                    $startTime = $frlist[$y]['cf_webui_fs_start'];
                    $settingTime = $frTipList[$x]['date'];
                    $showtype = '开始';
                    SendMail($startTime, $settingTime, $frid, $description, $frtipType, $assign, $showtype, $showMailFrom);
                }
            }
            if ($frlist[$y]['cf_webui_submit_to_qa'] != '' && $frtipType == '2') {
                $flag = findCheck($frid, $frtipType, $assign);

                if ($flag) {
                    $startTime = $frlist[$y]['cf_webui_submit_to_qa'];
                    $settingTime = $frTipList[$x]['date'];
                    $showtype = '提交QA';
                    SendMail($startTime, $settingTime, $frid, $description, $frtipType, $assign, $showtype, $showMailFrom);
                }
            }
        }
    }
}
