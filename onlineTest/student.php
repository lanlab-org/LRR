<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>在线测试</title>
<link href="./css/student.css" rel="stylesheet" type="text/css">
    <!--php加载测试，和查询学生数据信息-->
    <?php

        include "../get_mysql_credentials.php";
        if(!session_id())
            session_start();

        // Connect to MySQL database
        $conn = new mysqli($servername,$mysql_username,$mysql_password,$dbname);
        if($conn->connect_error){
            die("连接失败：".$conn->connect_error);
        }
        /*这里的labReportID应该来自点击答题界面*/
        $labReportID = isset($_GET['LabReportID'])?$_GET['LabReportID']:"-1";   //查看原来的那个提交的界面所展示的信息
        $_SESSION['LabReportID'] = $labReportID;

        $studentID = isset($_SESSION['user_student_id'])?$_SESSION['user_student_id']:"-1";
        //echo $labReportID,"--",$studentID;
        if($studentID == "-1"){
            die("<a href='../index.php'>点击重新登录</a><br><br>你的信息已过期");
        }else if($labReportID == "-1"){
            die("<a href='../Courses.php'>点击返回</a><br><br>没有任何可提交的测试");
        }

        //如果这个报道不存在则直接提示返回
        $sqlQuery = "SELECT * FROM `lab_reports_table` where `Lab_Report_ID` = '$labReportID'";
        $resultLab = $conn->query($sqlQuery);
        if($resultLab->num_rows <= 0){
            $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
            die("<a href='$fallback'>点击返回</a><br><br>没有提交的测试");
        }

        //查询学生的提交测试的数据
        $sqlQuery = "SELECT * FROM `lab_report_submissions` where `Lab_Report_ID` = '$labReportID' and `Student_id` = '$studentID'";
        $resultLab = $conn->query($sqlQuery);
        //若学生已经提交则转至提交界面
        if($resultLab->num_rows > 0){  //当提交数据库中有数据时
            $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
            die("<a href='$fallback'>点击返回</a><br><br>你已经过提交测试了");
        }
        //查询数据库，得到测试文件的路径——判断是否错过提交日期在其他的页面已经处理
        $sqlQuery = "SELECT * FROM `lab_reports_table` where `Lab_Report_ID` = '$labReportID'";
        $resultLab = $conn->query($sqlQuery);
        if($resultLab->num_rows > 0){
            while($row = $resultLab->fetch_assoc()){
                $filepath = $row['Attachment_link_1'];      //获取问题文件的链接
                $quizTitle = $row['Title'];
                $courseID = $row['Course_ID'];
                $marks = $row['Marks'];
            }
        }

        //查找班级编号
        $sqlQuery = "SELECT `Course_Code` FROM `courses_table` where `Course_ID` = '$courseID'";
        $resultLab = $conn->query($sqlQuery);
        if($resultLab->num_rows > 0){
             while($row = $resultLab->fetch_assoc()){
                $courseCode = $row['Course_Code'];
            }
        }

        //把文件一次性全部读出到字符串中，然后进行分割
        $quizContents = trim(file_get_contents($filepath));

        //将读取到的文件内容存入数组中——数组每个单元存储的是一个问题的选项，标题和答案
        $arrQuiz = explode("\t\n",$quizContents);
        $singleList = array(null);
        $mulList = array(null);
        $fillList = array(null);

        //将元素进行分割，然后存入对应的题型的数组中
        $Qlength = count($arrQuiz);
        for($i = 0;$i < $Qlength;$i ++) {
            $value = $arrQuiz[$i];  //获取当前元素

            if(strlen(strstr($value,"quizTime:")) != 0){
                $str = trim(str_replace("quizTime:","",$value));
                $quizTime = intval($str);   //考试时间

            }elseif (strlen(strstr($value,"totalPoint:")) != 0){
                $str = trim(str_replace("totalPoint:","",$value));
                $totalPoint = intval($str);     //考试总分数
            }else{
                if(strlen(strstr($value,"[SINGLE]")) > 0){

                    $i++;   //$i开始读取数组中的数据
                    $j = 1;
                    while($i < $Qlength && $arrQuiz[$i] != null && !strlen(strstr($arrQuiz[$i],"[MUL]")) > 0){
                        $value = $arrQuiz[$i++];
                        //如果有title证明匹配到了问题
                        if(strlen(strstr($value,"title:")) > 0){
                            //将问题分割后，存入数组中
                            $singleList[$j++] = explode("\r",$value);
                        }
                    }      //当他的下一个不是[MUL]的时候继续执行

                }elseif(strlen(strstr($value,"[MUL]")) > 0){
                    //开始做多选题了
                    $i++;
                    $j = 1;
                    while($i < $Qlength && $arrQuiz[$i] != null && !strlen(strstr($arrQuiz[$i],"[FILL]")) > 0){
                        $value = $arrQuiz[$i++];
                        //如果有title证明匹配到了问题
                        if(strlen(strstr($value,"title:")) > 0){
                            //将问题分割后，存入数组中
                            $mulList[$j++] = explode("\r",$value);
                        }
                    }     //当他的下一个不是[FILL]的时候继续执行

                }elseif (strlen(strstr($value,"[FILL]")) > 0){
                    //将填空题的数据填充到集合中
                    $i++;
                    $j = 1;
                    while($i < $Qlength && $arrQuiz[$i] != null){
                        $value = $arrQuiz[$i++];
                        //如果有title证明匹配到了问题
                        if(strlen(strstr($value,"title:")) > 0){
                            //将问题分割后，存入数组中
                            $fillList[$j++] = explode("\r",$value);
                        }
                    }
                }
            }
        }

    ?>

<script type="text/javascript">
	function Submit(){
		var result=window.confirm("是否确认提交？");
        return result;
	}

    onload=function(){
        setInterval(go,1000);
    };

	var minute = <?php echo $quizTime;?>;
    var second= 1; //利用了全局变量来执行
    function go(){
        second--;
        if(second >= 0 && minute > 0){
            var text = "";
            if(second < 10){
                text =  minute + ":0" + second;
            }else {
                text = minute + ":" + second;
            }
            document.getElementById("quizTime").innerHTML= text;  //每次设置的x的值都不一样了。
            if(second == 0){
                minute --;
                second = 60;
            }
        }else{
            //考试结束，自动提交试卷
            location.href='submited.php';
        }
    }
</script>
<style type="text/css">
.order_number{
	list-style-type: decimal;
	font-family: "宋体";
	font-weight: bold;
}
</style>
</head>

<body>
	<div class="div_all">
    <div class="div_all_top">
        <div class="div_all_title">在线考试系统</div>

      	<div class="div_all_ke">
            <span style="margin-left: 10%">考试名称：<span class="test_name"><?php echo $quizTitle;?></span></span>
            <span style="margin-left: 20%">时间：<span id="quizTime"><?php echo $quizTime.":00"?></span> </span>   <!--然后开始倒计时-->
            <span style="margin-left: 20%;color: #d43c3c;">总分：<span class="test_score"><?php echo $totalPoint;?>分</span></span>
        </div>
        <div class="div_all_ke">
            <span style="margin-left: 10%">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：<span class="user_name"><?php echo isset($_SESSION['user_fullname'])?$_SESSION['user_fullname']:"";?></span></span>
            <span style="margin-left: 20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;班级：<span class="user_name"><?php echo $courseCode;?></span></span>
            <a href="javascript:topicForm.submit();" style="text-decoration: none;"><span class="div_span_submit" style="margin-left: 20%" onClick="return Submit();">提交</span></a>
        </div>

    </div>
    <form name="topicForm" action="submited.php" method="post">
        <!--隐藏标签传输数据-->
        <input type="hidden" name="quizname" value="<?php echo $quizTitle;?>">
        <input type="hidden" name="labReportID" value="<?php echo $labReportID;?>">

    <!--题-->
    <div style="margin-top: 10px;">
        <div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
            <span> &nbsp;&nbsp;&nbsp;&nbsp;一、单选题</span>
        </div>
        <!--选择题-->
        <div class="div_all_selected">
			<ul class="order_number">

                <?php

                    for($i = 1;$i < count($singleList);$i++){
                        $option = $singleList[$i];    //options也是个一维数组

                        $quizTitle = str_replace("title:","",$option[0]);
                        echo "<li><div class='div_radio'><h4><a name='selected$i'>$quizTitle</a></h4>";  //得到标题
                        //遍历动态生成选项
                        for($j = 1;$j < count($option)-2;$j++){
                            $value = chr($j + 64);
                            echo "<div>$value:<input type='radio' value='$value' name='single$i'/><span>$option[$j]</span></div>";
                        }
                        $answer = "SA$i";
                        $score = "SS$i";
                        $scoreValue = trim(str_replace("score:","",$option[$j++]));
                        $answerValue = trim(str_replace("answer:","",$option[$j]));
                        echo "<input type='hidden' name='$score' value='$scoreValue'>";
                        echo "<input type='hidden' name='$answer' value='$answerValue'>";

                        echo "</div></li>";
                    }
                ?>

			</ul>
        </div>
        <div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;二、多选题</span>
        </div>
        <!--多选题-->
        <div class="div_all_more">
			<ul class="order_number">
                <?php
                for($i = 1;$i < count($mulList);$i++){
                    $option = $mulList[$i];    //options也是个一维数组

                    $quizTitle = str_replace("title:","",$option[0]);
                    echo "<li><div class='div_radio'><h4><a name='more$i'>$quizTitle</a></h4>";  //得到标题
                    //遍历动态生成选项
                    for($j = 1;$j < count($option)-2;$j++){
                        $value = chr($j + 64);
                        echo "<div>$value:<input type='checkbox' value='$value' name='mul$i";
                        echo "[]'/><span>$option[$j]</span></div>";
                    }
                    $answer = "MA$i";
                    $score = "MS$i";
                    $scoreValue = trim(str_replace("score:","",$option[$j++]));
                    $answerValue = trim(str_replace("answer:","",$option[$j]));
                    echo "<input type='hidden' name='$score' value='$scoreValue'>";
                    echo "<input type='hidden' name='$answer' value='$answerValue'>";

                    echo "</div></li>";
                }
                ?>

			</ul>
        </div>
        <!--填空题-->
        <div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;三、填空题</span>
        </div>
        <div class="div_all_input">
			<ul class="order_number">
                <?php
                for($i = 1;$i < count($fillList);$i++){
                    $option = $fillList[$i];    //options也是个一维数组

                    $quizTitle = str_replace("title:","",$option[0]);
                    echo "<li><div class='div_input_text_area'><h4><a name='input$i'>$quizTitle</a></h4>";  //得到标题

                    //编辑填空内容
                    echo "<div><input type='text' placeholder='请输入您的答案……' required name='fill$i'/></div>";
                    $answer = "FA$i";
                    $score = "FS$i";
                    $scoreValue = trim(str_replace("score:","",$option[1]));
                    $answerValue = trim(str_replace("answer:","",$option[2]));
                    echo "<input type='hidden' name='$score' value='$scoreValue'>";
                    echo "<input type='hidden' name='$answer' value='$answerValue'>";

                    echo "</div></li>";
                }
                ?>

			</ul>
        </div>
    </div>
        <!--end 题目-->
    </form>
</div>
	
</body>
</html>
