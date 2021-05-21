<?php

session_start();

// Allow legal person to download files instead of using direct URL access
// Adapted from https://www.runoob.com/w3cnote/php-download-file.html


// 修改这一行设置你的文件下载目录
$file = "./../../lrr_submission".$_GET['file'];
$filename = basename($file);

// 判断文件是否存在
if(!file_exists($file)) die("File does not exist.");
 
//  文件类型，作为头部发送给浏览器
$type = filetype($file);
 
// 获取时间和日期
$today = date("F j, Y, g:i a");
$time = time();

if ( (isset($_SESSION["user_student_id"]) && strpos($file, $_SESSION["user_student_id"])) || $_SESSION['user_type'] == "Lecturer" || $_SESSION['user_type'] == "TA") {
    // 发送文件头部
    header("Content-type: $type");
    header('Content-Disposition: attachment;filename="'.urldecode($filename).'"');
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    // 发送文件内容
    set_time_limit(0);
    readfile($file);
} else {
    echo "Nothing to download.  Contact lanhui _at_ zjnu.edu.cn if you think otherwise.";
}

?>
