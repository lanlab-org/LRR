<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>提交题目</title>

</head>

<body>

    <!--将学生提交的答案数据保存到数据库中，将测试得分记录到总分中
    如何区分测试的是单选还是多选呢？根据name中包含的数据-->

<?php

//这里基本上要清空session
if(!session_id())
    session_start();

// Connect to MySQL database
include "../get_mysql_credentials.php";

$conn = new mysqli($servername,$mysql_username,$mysql_password,$dbname);
if($conn->connect_error){
    die("连接失败：".$conn->connect_error);
}


//根据课程编号，获取课程ID
$quiztitle = isset($_POST["quizname"])?$_POST["quizname"]:"";    //测试名称
$labReportID = isset($_SESSION['LabReportID'])?$_SESSION['LabReportID']:"-1";    //测试报告的ID
$studentID = isset($_SESSION["user_student_id"])?$_SESSION["user_student_id"]:"-1";   //学号
if($labReportID == "-1" || $studentID == "-1"){
    die("<a href='../index.php'>点击重新登录</a><br><br>你的信息已过期");
}

//如果这个报道不存在则直接提示返回
$sqlQuery = "SELECT * FROM `lab_reports_table` where `Lab_Report_ID` = '$labReportID'";
$resultLab = $conn->query($sqlQuery);
if($resultLab->num_rows <= 0){
    $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
    die("<a href='$fallback'>点击返回</a><br><br>没有提交的测试");
}

//echo $labReportID,"<br>";
//验证用户是否已经提交过测试
$sqlQuery = "SELECT * FROM `lab_report_submissions` where `Lab_Report_ID` = '$labReportID' and `Student_id` = '$studentID'";
//echo $sqlQuery,"<br>";
$resultLab = $conn->query($sqlQuery);
//若学生已经提交则转至提交界面
if($resultLab->num_rows > 0){  //当提交数据库中有数据时
    $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
    die("<a href='$fallback'>点击返回</a><br><br>你已经过提交测试了");
    //header("Location: ../index.php");   //返回上一目录
}

//将答案保存到数据库中
$singArr = array(null);
$mulArr = array(null);
$fillArr = array(null);

//存储答案的json字符串
$jsonSingle = "";
$jsonMul = "";
$jsonFill = "";

$achievement = 0;     //记录学生总成绩
//获取用户的单选题的答案
$i = 1; //学生填写的答案
while(isset($_POST["single$i"])){
    //SS$i  //得分
    $score = isset($_POST["SS$i"])?$_POST["SS$i"]:"";
    //获取正确答案
    $submitAnswer = isset($_POST["single$i"])?$_POST["single$i"]:"";    //单选为一个字符，故不用数组存储选项
//    print_r($submitAnswer);
//    echo "<br>";
    $singArr[$i] = $submitAnswer;    //将学生的答案保存到数组中
    //SA$i  //正确答案
    $answer = isset($_POST["SA$i"])?$_POST["SA$i"]:"";
    //匹配答案计算学生的得分---如果相等则成绩加题目分数
    if(strcmp($answer,$submitAnswer) == 0){
        $achievement += intval($score);
    }
    $i++;
}
//将单选成绩转为json字符串存入数据库中
$jsonSingle = addslashes(json_encode($singArr));

$i = 1; //学生填写的答案
while(isset($_POST["mul$i"])){
    //SS$i  //得分
    $score = isset($_POST["MS$i"])?$_POST["MS$i"]:"";
    //获取正确答案
    $submitAnswer = isset($_POST["mul$i"])?$_POST["mul$i"]:array();
    //print_r($submitAnswer);
    $mulArr[$i] = $submitAnswer;    //将学生的答案保存到数组中
    //SA$i  //正确答案
    $answer = isset($_POST["MA$i"])?$_POST["MA$i"]:"";
    //匹配答案计算学生的得分---如果相等则成绩加题目分数,始终顺序从上到下
    if(strcmp($answer,implode("",$submitAnswer)) == 0){
        $achievement += intval($score);
    }
    $i++;
}
//将多选成绩转为json字符串存入数据库中
$jsonMul = addslashes(json_encode($mulArr));

//将填空题成绩转为json字符串存入数据库中
$i = 1; //学生填写的答案
while(isset($_POST["fill$i"])){
    //SS$i  //得分
    $score = isset($_POST["FS$i"])?$_POST["FS$i"]:"";
    //获取正确答案
    $submitAnswer = isset($_POST["fill$i"])?$_POST["fill$i"]:"";
    $fillArr[$i] = $submitAnswer;    //将学生的答案保存到数组中
    //SA$i  //正确答案
    $answer = isset($_POST["FA$i"])?$_POST["FA$i"]:"";
    //匹配答案计算学生的得分---如果相等则成绩加题目分数,始终顺序从上到下
    if(strcmp($answer,$submitAnswer) == 0){
        $achievement += intval($score);
    }
    $i++;
}
//将成绩转为json字符串存入数据库中
$jsonFill = addslashes(json_encode($fillArr));

//将文件夹名称，数据，插入数据库中
date_default_timezone_set('Asia/Shanghai');

//查询学生的小组编号
$groupID = 0;
$sqlSelect = "SELECT * from `course_group_members_table` where `Student_ID` = '$studentID'";
$result = $conn->query($sqlSelect);
if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $groupID = $row['Course_Group_id'];
    }
}

date_default_timezone_set('PRC');
$time = time();
$submitTime = date("Y-m-d H:i",$time);      //提交日期

//将学生的答题结果存入数据库
//Attachment2:存储学生单选题答案
//Attachment3:存储学生多选题答案
//Attachment4:存储学生填空题答案
$sqlInsert = "INSERT INTO `lab_report_submissions`(`Submission_Date`,`Lab_Report_ID`,`Student_id`,`Course_Group_id`,".
            "`Attachment2`,`Attachment3`,`Attachment4`,`Marks`,`Status`,`Title`,`Visibility`)".
            "VALUES('$submitTime','$labReportID','$studentID','$groupID','$jsonSingle','$jsonMul','$jsonFill',".
            "'$achievement','Marked','$quiztitle','Private')";      //默认为私有——因为是测试

//插入数据
if ($conn->query($sqlInsert) === TRUE) {
    //echo "新记录插入成功<br>";
} else {
    echo "Error: " . $sqlInsert . "<br>" . $conn->error;
}

//查询测试的成绩单的txt文件保存路径
$sqlSelect = "SELECT * from `lab_reports_table` where `Lab_Report_ID` = '$labReportID'";
$result = $conn->query($sqlSelect);
if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $quizResultPath = $row['Attachment_link_2'];       //Attachment_link_2保存成绩结果
    }
}

//更新学生的分数并写入txt文件中，并保存
$quizResult = trim(file_get_contents($quizResultPath));     //先取出内容到内存中

$file = fopen($quizResultPath,"w");     //在打开写文件

//以"\n"为分割符
$quizResultArr = explode("\n",$quizResult);
//print_r($quizResultArr);
$studentName = isset($_SESSION['user_fullname'])?$_SESSION['user_fullname']:"null";
//echo "<br>",$studentID,"---",$studentName,"<br>";
foreach ($quizResultArr as $value){
    //trim($value);
    if(!empty($studentID) && !empty($studentName)){

        if(strstr($value,$studentID) != FALSE and strstr($value,$studentName) != FALSE ){
            $stuInfo = array_unique(explode("\t",$value));     //将所有的空格元素进行合并

            //进行数据写入
            $len = fwrite($file,sprintf("%20s\t",$stuInfo[0]).sprintf("%30s\t",$stuInfo[1]).sprintf("%10d\n",$achievement));
        }else{
            //echo "脑子有病？";
            $len = fwrite($file,$value."\n");   //将数据写入文件中
        }
    }

}
//关闭文件输入流
flush();
if($file != null){
    fclose($file);
}

//关闭对象连接
$conn->close();

//清除对应的session数据
unset($_SESSION['LabReportID']);
?>


    <script type="text/javascript">
        window.onload= tips;
        function tips (){
            alert("提交成功");
            setInterval(go,1000);
        };
        var x=5; //利用了全局变量来执行
        function go(){
            x--;
            if(x >= 0){
                document.getElementById("sp").innerHTML=x + "秒后自动返回";  //每次设置的x的值都不一样了。
            }else{
                location.href='../Courses.php';    //应该返回课程主页
            }
        }
    </script>


    <a href="../Courses.php">点击返回</a>
    <br><br>
    <span id="sp">5秒后自动返回</span>
</body>
</html>