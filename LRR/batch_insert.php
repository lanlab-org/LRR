<?php

//数据库信息
$servername = "localhost";
$username = "root";
$password = "";
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
$source1=preg_replace ( "/\s(?=\s)/","\\1", $source);
//去除首尾巴的空格
$source2=trim($source1);
//根据空格拆分
$user = explode(' ',$source2);

//设置用户类型、初始密码、初始状态，Student_ID初始值为null
$email_type = $_POST["email_type"];
$type = $_POST["type"];
$pass = $_POST["pass"];
$statu = "Active";

//获取数据库的maxid
$sql_maxid = "select max(User_ID) from users_table";  
$result = $conn->query($sql_maxid);  
if ($result) {  
	if ($result->num_rows>0) {  
        		while ($rows = $result->fetch_array()) {  
           			 $maxid=$rows[0];
        		}
   	}else{  
       	 	$maxid=0; 
   	}
}else{  
    echo "<br>查询失败！";   
}

//插入数据
for($index=0;$index<count($user);$index++) {
	
	$sql_query = "SELECT Email FROM users_table where Email = '$user[$index]$email_type'";
	$result=mysqli_query($conn,$sql_query);
	if (mysqli_num_rows($result)>0) {//查重
   		echo "插入失败，用户名 ".$user[$index].$email_type." 已经存在<br>";
	} else {
 	   	$sql_insert = "INSERT INTO users_table (User_ID,Email,Password,Full_Name,UserType,Student_ID,Passport_Number,Status) VALUES 
							($maxid+1,'$user[$index]$email_type',$pass,'$user[$index]','$type',null,$pass,'$statu')";
		if (mysqli_query($conn, $sql_insert)) {
   			echo "用户名 ".$user[$index].$email_type." 插入成功";
			echo"</br>";
			$maxid +=1;
		} else {
 	   		echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
		}

	}
}

//返回按钮
echo "</br><input type='button' name='Back' onclick='javascript:history.back(-1);' value=' 返回 '>";

//释放缓存
$result->free();

//中断连接
mysqli_close($conn);

?>