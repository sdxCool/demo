<?php

namespace Tyrell;

class CurlPost{
    function do_post_template($ch,$url){
        curl_setopt($ch, CURLOPT_URL, $url );//curl_setopt — 设置 cURL 传输选项
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//false 禁止 cURL 验证对等证书（peer's certificate）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true 将curl_exec()获取的信息以字符串返回，而不是直接输出
        $result = curl_exec ( $ch );//curl_exec — 执行 cURL 会话,这个函数应该在初始化一个 cURL 会话并且全部的选项都被设置后被调用。
        curl_close ( $ch );
        return $result;
    }

}