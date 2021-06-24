<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加题目</title>

    <script type="text/javascript">
        onload=function(){
            setInterval(go,1000);
        };
        var x=5; //利用了全局变量来执行
        function go(){
            x--;
            if(x >= 0){
                document.getElementById("sp").innerHTML=x + "秒后自动返回";  //每次设置的x的值都不一样了。
            }else{
                location.href='../Courses.php';
            }
        }
    </script>

</head>

<body>
    <a href="../Courses.php">点击返回主页面</a>
    <br><br>
    <span id="sp">5秒后自动跳转主界面...</span>
<?php
use Quiz\QuizInfo;
include_once 'util.php';
include "../get_mysql_credentials.php";
//开启session
if(!session_id())
    session_start();
//将session中的序列化为一个文本文件
/*$_SESSION['Single'] = array("SINGLE");
$_SESSION['Single'] = array("SINGLE2");
$_SESSION['Mul'] = array("MUL");
$_SESSION['Fill'] = array("FILL");*/

//将session的数据序列化到硬盘中文件中
$dirPath = "./file/quizzes/";   //文件夹路径
$filename = (isset($_POST['classID'])?$_POST['classID']:"")
            . "_" . (isset($_POST["quizname"])?$_POST["quizname"]:"") . "_" . time() . ".txt";
$file = fopen($dirPath . $filename,"w");    //下次打开同样名字的测试，则数据会被覆盖

//先写测试标题
$quiztitle = "quizName:" . (isset($_POST["quizname"])?$_POST["quizname"]:"") . "\t\nquizTime:"
            . (isset($_POST["quiztime"])?$_POST["quiztime"]:"") . "\t\ntotalPoint:"
            . (isset($_POST["totalpoints"])?$_POST["totalpoints"]:"") . "\t\n";
$len = fwrite($file,$quiztitle);

//读取session中的数据然后存入txt文本中
$singleList = isset($_SESSION['Single'])?$_SESSION['Single']:array();
$mulList = isset($_SESSION['Mul'])?$_SESSION['Mul']:array();
$filList = isset($_SESSION['Fill'])?$_SESSION['Fill']:array();

//然后分别存入
//先写单选题
for($i = 1;$i < count($singleList);$i++){
    if($i == 1)  $len = fwrite($file,"\t\n[SINGLE]\t\n");
    $obj = $singleList[$i];
    $text = "";
    $text .= "title:$i.".$obj->getTitle()."\r";
    $optionList = $obj->getOptionList();
    for($j = 1;$j <= count($optionList);$j++){
        $value = chr($j + 64);
        $text .= $value . ":" . $optionList[$j] . "\r";
    }
    $text .= "score:" . $obj->getScore() . "\r";
    $text .= "answer:" . $obj->getAnswer() . "\t\n";
    $len = fwrite($file,$text);     //将文件写入
}
//多选题的选项
for($i = 1;$i < count($mulList);$i++){
    if($i == 1)  $len = fwrite($file,"\t\n[MUL]\t\n");
    $obj = $mulList[$i];
    $text = "";
    $text .= "title:$i.".$obj->getTitle()."\r";
    $optionList = $obj->getOptionList();
    for($j = 1;$j <= count($optionList);$j++){
        $value = chr($j + 64);
        $text .= $value . ":" . $optionList[$j] . "\r";
    }
    $text .= "score:" . $obj->getScore() . "\r";
    $text .= "answer:" . $obj->getAnswer() . "\t\n";
    $len = fwrite($file,$text);     //将文件写入
}
//填空题的文件写入
for($i = 1;$i < count($filList);$i++){
    if($i == 1)  $len = fwrite($file,"\t\n[FILL]\t\n");
    $obj = $filList[$i];
    $text = "";
    $text .= "title:$i.".$obj->getTitle()."\r";
    $text .= "score:" . $obj->getScore() . "\r";
    $text .= "answer:" . $obj->getAnswer()."\t\n";
    $len = fwrite($file,$text);     //将文件写入
}

//关闭文件输入流
flush();
if($file != null){
    fclose($file);
}

//将文件夹名称，数据，插入数据库中
date_default_timezone_set('Asia/Shanghai');

$conn = new mysqli($servername,$mysql_username,$mysql_password,$dbname);
if($conn->connect_error){
    die("连接失败：".$conn->connect_error);
}
//根据课程编号，获取课程ID
$quiztitle = isset($_POST["quizname"])?$_POST["quizname"]:"";
$cousseCode = isset($_POST['classID'])?$_POST['classID']:"";
$sqlQuery = "SELECT `Course_ID` from `courses_table` where `Course_Code` = '$cousseCode'";
$result = $conn->query($sqlQuery);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $courseID = $row['Course_ID'];
    }
}

/***********************************建立文本保存学生的成绩***************************/
//建立一个txt文件，用来保存学生的成绩
$dirScorePath = "./file/score/";   //文件夹路径
$fileScoreName = "answer_".(isset($_POST['classID'])?$_POST['classID']:"")
    . "_" . (isset($_POST["quizname"])?$_POST["quizname"]:"") . "_" . time() . ".txt";

$file = fopen($dirScorePath . $fileScoreName,"w");    //下次打开同样名字的测试，则数据会被覆盖
//写入测试文件头的
$len = fwrite($file,"#".$quiztitle."\n");
$stuNo = "student.no";
$stuName = "student.name";
$stuScore = "score";
$len = fwrite($file,sprintf("%20s\t",$stuNo).sprintf("%30s\t",$stuName).sprintf("%10s\n",$stuScore));
//查询此课程相关的所有学生的姓名，学号
$sqlQuery = "SELECT DISTINCT * FROM course_students_table c left join users_table u on c.Student_ID = u.Student_ID".
            " where `Course_ID` = '$courseID'";
$result = $conn->query($sqlQuery);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $stuID = $row['Student_ID'];
        $stuName = $row['Full_Name'];
        //记录每个学生的成绩
        $len = fwrite($file,sprintf("%20s\t",$stuID).sprintf("%30s\t",$stuName).sprintf("%10d\n",0));
    }
}
//关闭文件输入流
flush();
if($file != null){
    fclose($file);
}

$marks = isset($_POST['totalpoints'])?$_POST['totalpoints']:0;
date_default_timezone_set('PRC');
$time = time();
$quizTime = $_POST['quiztime'];
$postedTime = date("Y-m-d H:i",$time);      //发布日期
//$deadTime = date("Y-m-d H:i",strtotime("+$quizTime minute",$time));     //截止日期
$deadTime = isset($_POST['deadTime'])?$_POST['deadTime']:date("Y-m-d H:i",strtotime("+$quizTime minute",$time));    //提交的截止时间
$deadTime = str_replace("T"," ",$deadTime);
$submitType = isset($_POST['subType'])?$_POST['subType']:'Individual';  //提交类型为个人还是小组
$sqlInsert = "INSERT INTO `lab_reports_table`( `Course_ID`,`Posted_Date`,`Deadline`,`Instructions`,`Title`,`Attachment_link_1`,`Attachment_link_2`,`Marks`,`Type`)".
                "VALUES ('$courseID','$postedTime','$deadTime','quiz','$quiztitle','$dirPath$filename','$dirScorePath$fileScoreName','$marks','$submitType')";

//插入数据
if ($conn->query($sqlInsert) === TRUE) {
    echo "<br><br>新的测试已发布";
} else {
    echo "<br><br>Error: " . $sqlInsert . "<br>" . $conn->error;
}
//关闭对象连接
$conn->close();

/**********************************清空session中的数据***************************/
unset($_SESSION['Single']);
unset($_SESSION['Mul']);
unset($_SESSION['Fill']);
unset($_SESSION['score']);
?>
</body>
</html>