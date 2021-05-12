<?php

$from = "854273105@qq.com";
$to = "ext-dxshen@Hillstonenet.com";

  html_mail($from, $to, "ss", "sss");
  
// 对邮件地址进行中文的UTF-8编码转化
function format_mail_address($address){
  /*
     preg_match():搜索给定正则表达式的匹配
     $matches：如果提供了参数matches，它将被填充为搜索结果。
               $matches[0]将包含完整模式匹配到的文本， 
               $matches[1] 将包含第一个捕获子组匹配到的文本。
   */
    if(preg_match("|<([^<]+)>|", $address, $matches)){
      //mb_substr()： 函数返回字符串的一部分且是中文部分
      //strpos():函数查找字符串在另一字符串中第一次出现的位置（区分大小写）
      $name = mb_substr($address, 0, strpos($address, '<'));
      $name = trim($name);
      $mail = $matches[1];
      $address = "=?UTF-8?B?".base64_encode($name)."?= " . "<$mail>";
    }
    return $address;
  }

// 发送html格式的邮件
function html_mail($from, $to, $subject, $body){
    if(preg_match("|<([^<]+)>|", $from, $matches)){
      $from_name = mb_substr($from, 0, strpos($from, '<'));
      $from_mail = $matches[1];
      $from = "=?UTF-8?B?".base64_encode($from_name)."?= " . "<$from_mail>";
    }else{
      $from_mail = $from;
    }
    $headers[] = "From: $from";
    $headers[] = "X-Mailer: PHP";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-type: text/html; charset=utf8";
    $headers[] = "Reply-To: $from_mail";
    //base64_encode():本函数将字符串以 MIME BASE64 编码
    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    if(is_array($to)){
      foreach($to as $mail)
        $to_mails[] = format_mail_address($mail);
      $to = join(", ", $to_mails);
    }
    mail($to, $subject, $body, join("\r\n", $headers), "-f $from_mail");
  }

  