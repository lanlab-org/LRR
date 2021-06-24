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
        //return "没有该文件";

        header('HTTP/1.1 404 NOT FOUND');
    }else{
        $file = fopen ( $filePath, "rb" );

        //告诉浏览器这是一个文件流格式的文件
        Header ( "Content-type: application/octet-stream" );

        //请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );

        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header ( "Accept-Length: " . filesize ( $filePath ) );

        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。

        Header ( "Content-Disposition: attachment; filename=" . $fileName );

        //读取文件内容并直接输出到浏览器
        echo fread ( $file, filesize ( $filePath ) );

        fclose ( $file );

        exit ();
    }
    /*header('Content-type:application/txt');
    header("Content-Disposition:filename=$fileName");

    readfile($filePath);*/
}
?>