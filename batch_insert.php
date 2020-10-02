<?php

// Code contributed by Xu Xiaopeng and his team (https://github.com/lanlab-org/LRR/pull/39/files#diff-b69ba96bf0e469383b373e8c9de257c0)
//数据库信息


include "get_mysql_credentials.php";
$servername = "localhost";
$username = $mysql_username;
$password = $mysql_password;
$dbname = "lrr";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 检测连接
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//获得用户名数据
$source = $_POST['users'];

//如有多个空格，删除剩一个空格
$source1 = preg_replace('/\s\s+/', ' ', $source);


//去除首尾巴的空格
$source2 = trim($source1);

//根据空格拆分
$user = explode(' ', $source2);


//插入数据
for($index=0; $index < count($user); $index++) {
    $result = mysqli_query($conn, "SELECT * FROM `students_data` WHERE Student_ID='$user[$index]'");    
    if (mysqli_num_rows($result) < 1) {
	if (! mysqli_query($conn, "REPLACE INTO `students_data`(`Student_ID`, `Passport_Number`) VALUES('$user[$index]', '')" ) ) {
            echo "SQL Error: " . $sql_stmt . "<br>" . mysqli_error($conn);
	} else {
	    echo "<p>Student number $user[$index] added.</p>";
	}
    } else {
       echo "<p><b>Student number $user[$index] already exists.</b></p>";
    }
}

//返回按钮
echo "</br><input type='button' name='Back' onclick='javascript:history.back(-1);' value=' 返回 '>";

//释放缓存
$result->free();

//中断连接
mysqli_close($conn);

?>
