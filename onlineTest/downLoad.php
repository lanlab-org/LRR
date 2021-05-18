<?php

/**
 * 下载文件
 */
$type = isset($_GET["type"])?$_GET["type"]:"";

if($type == "down"){
    $url = isset($_GET["file"])?$_GET["file"]:"";
    $start =  strrpos($url,"/");
    $fileName = substr($url,$start + 1);
    echo download($url,$fileName);
}

/**下载文件的方法**/
function download($filePath,$fileName){
    //$fileName = iconv("utf-8","gb2312",$fileName);
    if(!file_exists($filePath)){
        return "没有该文件";
    }
    header('Content-type:application/txt');
    header("Content-Disposition:filename=$fileName");

    readfile($filePath);
}
?>