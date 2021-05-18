<?php

/**
 * 用来局部刷新理session中的数据
 */

if(!session_id())
    session_start();

/***********动态更新报到信息************/
$type = isset($_POST["type"])?$_POST["type"]:"-1";
if($type == "showQuiz"){
    // Connect to MySQL database
    include "../get_mysql_credentials.php";

    $conn = new mysqli("localhost",$mysql_username,$mysql_password,"lrr");
    if($conn->connect_error){
        die("连接失败：".$conn->connect_error);
    }

    $labReportID = $_POST['labReportID'];
    //查询数据库---然后返回存入json对象中,之后在解析出来
    //先查询报道号对应的课程编号
    $sqlQuery = "SELECT DISTINCT c.`Course_ID`,c.`Course_Code` FROM `courses_table` c left join `lab_reports_table` l ".
        "on c.`Course_ID` = l.`Course_ID` where l.`Lab_Report_ID` = '$labReportID'";

    $formData = array();    //第一位传递课程编号，第二位传递所以学生的ID和name
    $courseID = "";
    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0) {
        while ($row = $resultLab->fetch_assoc()) {
            $courseID = $row['Course_ID'];
            $formData[0]= $row['Course_Code'];  //将课程编号存储在数组中
        }
    }

    //查询此课程中的所有学生的ID和name
    $sqlQuery = "SELECT DISTINCT u.`Full_Name`,u.`Student_ID` FROM `users_table` u left join `course_students_table` c ".
                "on u.`Student_ID` = c.`Student_ID` where c.`Course_ID` = '$courseID'";

    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0) {
        $i = 1;
        while ($row = $resultLab->fetch_assoc()) {
            $stuName = $row['Full_Name'];
            $stuID = $row['Student_ID'];  //将课程编号存储在数组中
            $formData[$i++] = array($stuID,$stuName);
        }
    }
    //将数据转义然后在恢复数据
    $json = json_encode($formData); //进行传输
//    stripslashes();  //恢复转义之后
//    addslashes();   //转义字符
    echo $json;
}else{

    /*************动态删除题目***********/

    $keyName = $_POST['key'];
    $itemIndex = $_POST['itemIndex'];
    echo $keyName;
    echo $itemIndex;
//$itemIndex = intval(); //session中的子项的数据
    $quizArr = isset($_SESSION["$keyName"])?$_SESSION["$keyName"]:array();
    $quizArr[0] = "";   //占用1号空位，防止结构被改变
    array_splice($quizArr,$itemIndex,1);
    $quizArr[0] = null;
//unset($quizArr[0]);     //恢复一号空位的数据
//array_values();
//更新session中保存的数字
    $_SESSION["$keyName"] = $quizArr;

//清空post中的数据
    unset($_POST['key']);
    unset($_POST['itemIndex']);

    $item = count($_SESSION["$keyName"]);
    echo print_r($quizArr);
}

?>