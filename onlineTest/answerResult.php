<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>测试结果</title>
<link href="./css/releaseTest.css" rel="stylesheet" type="text/css">

    <?php
    if(!session_id())
        session_start();
    // Connect to MySQL database
    include "../get_mysql_credentials.php";

    $conn = new mysqli("localhost",$mysql_username,$mysql_password,"lrr");
    if($conn->connect_error){
        die("连接失败：".$conn->connect_error);
    }

    //查找要查询成绩的学生的信息
    $studentName = isset($_POST['studentName'])?$_POST['studentName']:"";
    $teacherName = isset($_SESSION['user_fullname'])?$_SESSION['user_fullname']:"";     //这个界面只针对于老师而言
    $lecturerID = isset($_SESSION['user_id'])?$_SESSION['user_id']:"-1";

    /**查询这个老师所有的测试***/
    /**
     * 先查询所有的课程
     * 在根据课程号，查询报告内容
     */
    $courseList = array();
    $sqlQuery = "SELECT * FROM `courses_table` where `Lecturer_User_ID` = '$lecturerID' or `TA_User_ID` = '$lecturerID'";
    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0) {
        $i = 0;
        while ($row = $resultLab->fetch_assoc()) {
            $courseList[$i++] = $row['Course_ID'];  //将课程编号存储在数组中
        }
    }else{
        $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
        die("<a href='$fallback'>点击返回</a><br><br>没有发布任何课程");
    }

    //查询此老师名下的所有报告测试
    $courseStr = implode(",",$courseList);
    $sqlQuery = "SELECT * FROM `lab_reports_table` where `Course_ID` in ($courseStr) and `Instructions` = 'quiz'";
    //echo $sqlQuery;
    $labReportList = array(array(),array());
    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0) {
        $i = 0;
        while ($row = $resultLab->fetch_assoc()) {
            $labReportList[0][$i] = $row['Lab_Report_ID'];  //报告编号存入
            $labReportList[1][$i++] = $row['Title'];    //将报告标题存入
        }
    }else{
        $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
        die("<a href='$fallback'>点击返回</a><br><br>没有发布任何，请先发布至少一个测试");
    }

    $labReportID = isset($_POST['LabReportID'])?$_POST['LabReportID']:"-1";   //查看原来的那个提交的界面所展示的信息
    if($labReportID == "-1" && count($labReportList[0]) > 0){   //查找所有的报告编号的数目
        $lastIndex = count($labReportList);         //查询最近的一次报告数据
        $labReportID = $labReportList[0][$lastIndex];
        $quizTitle = $labReportList[1][$lastIndex];
    }
    //查询数据库，得到测试文件的路径——判断是否错过提交日期在其他的页面已经处理
    $sqlQuery = "SELECT * FROM `lab_reports_table` where `Lab_Report_ID` = '$labReportID'";     //当页面第一次加载时默认加载最新的一次测试
    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0){
        while($row = $resultLab->fetch_assoc()){
            $filepath = $row['Attachment_link_1'];
            $quizResultPath = $row['Attachment_link_2'];
            $quizTitle = $row['Title'];
            $courseID = $row['Course_ID'];
            $marks = $row['Marks'];    //测试的成绩
        }
    }else{
        $fallback =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"../index.php";
        die("<a href='$fallback'>点击返回</a><br><br>没有提交的测试");
    }

    //echo $courseID;
    //查找班级班号——根据测试所属的班级号$courseCode变量中
    $sqlQuery = "SELECT `Course_Code` FROM `courses_table` where `Course_ID` = '$courseID'";
    $resultLab = $conn->query($sqlQuery);
    //echo $resultLab->num_rows;
    if($resultLab->num_rows > 0){
        while($row = $resultLab->fetch_assoc()){
            $courseCode = $row['Course_Code'];
        }
    }

    //查询这个班级中的所有学生的ID和姓名---为学生ID号的下拉菜单做准备
    $sqlQuery = "SELECT DISTINCT c.`Student_ID`,u.`Full_Name` FROM `course_students_table` `c` left join `users_table` `u` ".
                "on c.`Student_ID` = u.`Student_ID` where `Course_ID` = '$courseID'";

    $resultLab = $conn->query($sqlQuery);
    if($resultLab->num_rows > 0){
        $i = 1;
        $studentList = array();     //存储学生的学号和姓名
        while($row = $resultLab->fetch_assoc()){
            $studentList[$i++] = array($row['Student_ID'],$row['Full_Name']);
        }
    }

    /********************************应该更改***************************/
    $singleAnswer = array();
    $mulAnswer = array();
    $fillAnswer = array();
    //还需要查询提交的数据库的表单，
    $studentID = isset($_POST['studentID'])?$_POST['studentID']:"-1";
    //将学生的答题情况罗列出来
    $sqlQuery = "SELECT * FROM `lab_report_submissions` where `Lab_Report_ID` = '$labReportID' and `Student_id` = '$studentID'";

    //echo $sqlQuery,"<br>";
    $resultLab = $conn->query($sqlQuery);
    //echo $resultLab->num_rows,"<br>";
    $achievement = 0;  //默认学生成绩为0分
    //若学生已经提交则转至提交界面
    if($resultLab->num_rows > 0){   //当有提交的测试成绩时

        while($row = $resultLab->fetch_assoc()){    //只取最近提交的一次
            $submitTime = $row['Submission_Date'];
            //echo $submitTime,"<br>";
            $achievement = $row['Marks'];       //查询对应学生的成绩
            $attachment = $row['Attachment1'];  //允许老师添加附件给学生的答题
            $comment = $row['Notes'];        //老师的评论
            //$singleAnswer = json_decode($row['Attachment2']);
            //数组保存了答案
            $singleAnswer = json_decode($row['Attachment2']);   //单选答案 ---将json字符串恢复成数组
            $mulAnswer = json_decode($row['Attachment3']);  //多选答案  ---将json字符串恢复成数组
            $fillAnswer = json_decode($row['Attachment4']);     //填空答案  ---将json字符串恢复成数组
        }
    }

    /********************************读取处理测试文件***********************/
    //把文件一次性全部读出到字符串中，然后进行分割
    $quizContents = trim(file_get_contents($filepath));
    //将读取到的文件内容存入数组中——数组每个单元存储的是一个问题的选项，标题和答案
    $arrQuiz = explode("\t\n",$quizContents);
    //print_r($arrQuiz);

    $singleList = array(null);
    $mulList = array(null);
    $fillList = array(null);
    //将元素进行分割，然后存入对应的题型的数组中
    for($i = 0;$i < count($arrQuiz);$i ++) {
        $value = $arrQuiz[$i];  //获取当前元素

        if(strpos($value,"quizTime:") === 0){
            $str = trim(str_replace("quizTime:","",$value));
            $quizTime = intval($str);   //考试时间

        }elseif (strpos($value,"totalPoint:") === 0){
            $str = trim(str_replace("totalPoint:","",$value));
            $totalPoint = intval($str);     //考试总分数
        }elseif(strpos($value,"[SINGLE]") === 0){
            //开始做单选和多选了
            $i++;   //$i开始读取数组中的数据
            $j = 1;
            do{
                $value = $arrQuiz[$i++];
                //如果有title证明匹配到了问题
                if(strpos($value,"title:") === 0){
                    //将问题分割后，存入数组中
                    $singleList[$j++] = explode("\r",$value);
                }
            }while(!(strpos($arrQuiz[$i],"[MUL]") === false));      //当他的下一个不是[MUL]的时候继续执行

            //开始做多选题了
            $i++;
            $j = 1;
            do{
                $value = $arrQuiz[$i++];
                //如果有title证明匹配到了问题
                if(strpos($value,"title:") === 0){
                    //将问题分割后，存入数组中
                    $mulList[$j++] = explode("\r",$value);
                }
            }while(!(strpos($arrQuiz[$i],"[FILL]") === 0));     //当他的下一个不是[FILL]的时候继续执行

            //将填空题的数据填充到集合中
            $i++;
            $j = 1;
            while($i < count($arrQuiz)){
                $value = $arrQuiz[$i++];
                //如果有title证明匹配到了问题
                if(strpos($value,"title:") === 0){
                    //将问题分割后，存入数组中
                    $fillList[$j++] = explode("\r",$value);
                }
            }
        }
    }

    ?>

<script type="text/javascript">
	function Delete(id){
		window.document.getElementById(id).parentNode.removeChild(window.document.getElementById(id));
	}

    function changStuID(obj,textId){
        var textObj = document.getElementById(textId);
        //获取选中的option
        var index = obj.selectedIndex;
        var option = obj.options[index];

        var inner = option.innerHTML;
        textObj.value = inner.substring(0,(inner.indexOf('*')));

    }

    function choiceQuiz(obj,className,selectStuName,labIDName){
	    //得到选中的option的下标
        var quizIndex = obj.selectedIndex;
        var labReportID = obj.options[quizIndex].value; //得到选中测试的ID值-用于AJAx请求

	    var classObj = document.getElementsByName(className)[0];    //得到课程编号的对象

        var stuSelectObj = document.getElementsByName(selectStuName)[0];  //得到学生ID的select对象值

        var labObj = document.getElementsByName(labIDName)[0];  //这个为form表单中input的标签对象

        labObj.value = labReportID;     //更新lab值

        //发送Ajax请求
        if (window.XMLHttpRequest){// IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
            xmlhttp=new XMLHttpRequest();
        }else{// IE6, IE5 浏览器执行
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){

                var array = JSON.parse(xmlhttp.responseText);
                classObj.value = array[0];    //课程编号

                //删除所有的option
                stuSelectObj.options.length = 0;
                stuSelectObj.add(new Option('请选择学生...',null));
                //动态生成学生的ID和name值
                for(var i = 1;i < array.length;i++){
                    var optionNode = document.createElement("option");
                    optionNode.value = array[i][0];
                    optionNode.innerHTML = array[i][1] + "*" + array[i][0];
                    stuSelectObj.appendChild(optionNode);
                }

            }
        }

        xmlhttp.open("POST","LocalRefresh.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        var data = "type=showQuiz&labReportID="+labReportID;  //将报告的ID值发送出去
        xmlhttp.send(data);
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
            <span style="margin-left: 10%">考试名称：
                <!--制作一个下拉菜单，来显示测试，然后ajax请求，自动弹出课程号，选择学生的，然后点击查看-->
                <select class="select_number" name="quizName" onchange="choiceQuiz(this,'classCode','studentID','LabReportID');">
                        <!--//将课程的ID应该固定死，而学生的ID则需要动态的生成-->
                    <?php
                    echo "<option selected value='$labReportID'>$quizTitle</option>";
                    //从后往前输出，---即最近的测试在最前面
                    for($i = count($labReportList[0]) -1;$i >= 0 ;$i--){
                        $tempReportID = $labReportList[0][$i];
                        $tempReportTitle = $labReportList[1][$i];
                        if($tempReportID != $labReportID){
                            echo "<option value='$tempReportID'>$tempReportTitle</option>";
                        }
                    }
                    //保存测试的报告ID值
                    ?>
                </select>
                <!--<input type="text" class="test_name" value="<?php /*echo $quizTitle;*/?>" readonly/>-->
            </span>
            <!--<span style="margin-left: 10%">时间：<input type="text" class="test_time" value="1:00:00" readonly/></span>-->
            <span style="margin-left: 10%;color: #d43c3c;">考试学生：<input type="text" class="test_time" value="<?php echo $studentName;?>" placeholder="学生姓名..." style="width: 100px;" readonly></span>
            <span style="margin-left: 10%;color: #d43c3c;">成绩：<input type="text" class="test_time" value="<?php echo $achievement,"/",$marks;?>" style="width: 50px;" readonly>分</span>

            <a style="text-decoration: none" href="download.php?type=down&file=<?php echo $quizResultPath;?>"><span class="div_span_submit">成绩汇总</span></a>

            <a style="text-decoration: none;cursor: pointer;" href="../Courses.php"><span style="margin-left:20px;border: 1px solid;border-radius: 5px">HOME</span></a>

        </div>
        <div class="div_all_ke">
            <span style="margin-left: 10%;display: inline-block">教&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;师：<span class="user_name"><?php echo $teacherName;?></span></span>
            <span style="margin-left: 10%;display: inline-block">班级：<span class="student_class">
                    <input name="classCode" type="text"  value="<?php echo $courseCode;?>" readonly/>
             </span></span>
            <form name="stuform" action="answerResult.php" style="display: inline;" method="post">
                <span style="margin-left: 5%;display: inline-block">学生学号：<span class="student_number">
                        <!--//这里的点击事件时为了传递post请求数据-->
                        <select class="select_number"  name="studentID" onchange="changStuID(this,'stuname');">
                            <option selected>请选择学生...</option>
                            <?php
                            foreach ($studentList as $stu){
                                $stuID = $stu[0];
                                $stuName = $stu[1];
                                echo "<option value='$stuID'>$stuName*$stuID</option>";
                            }
                            //保存测试的报告ID值
                            echo "<input id='stuname' type='hidden' name='studentName' value=''>";  //通过代码为input填充数据
                            echo "<input type='hidden' name='LabReportID' value='$labReportID'>";
                            ?>
                        </select>
                    </span>
                </span>
            </form>
            <!--下载测试结果的链接-->

            <!--<a style="text-decoration: none" href="javascript:<?php /*echo $quizResultPath;*/?>"><span class="div_span_submit">成绩汇总</span></a>-->
            <a style="text-decoration: none" href="javascript:stuform.submit();"><span class="div_span_submit">查看</span></a>
        </div>

    </div>
    <!--题-->
    <div style="margin-top: 10px;">
        <div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
            <span> &nbsp;&nbsp;&nbsp;&nbsp;一、单选题</span>
        </div>
        <!--选择题-->
        <div class="div_all_selected">
			<ul class="order_number">
                <?php
                    //print_r($singleList);
                    //echo count($singleList);
                    for($i = 1;$i<count($singleList);$i++){
                        $option = $singleList[$i];
                        $answer = isset($singleAnswer[$i])?$singleAnswer[$i]:"";    //正确答案
                        $quizTitle = str_replace("title:","",$option[0]);
                        echo "<li><div class='div_radio'><h4><a name='selected$i'>$quizTitle</a></h4>";  //得到标题
                        for($j = 1;$j < count($option)-2;$j++){
                            $value = chr($j + 64);

                            if(strcmp($answer,$value) == 0){    //匹配答案是否相同
                                echo "<div>$value:<input type='radio' value='$value' checked name='single$i'/><span>$option[$j]</span></div>";
                            }else{
                                echo "<div>$value:<input type='radio' value='$value' name='single$i'/><span>$option[$j]</span></div>";
                            }
                        }

                        $marks = trim(str_replace("score:","",$option[$j++]));     //得到分数
                        $score = 0;
                        $correct = trim(str_replace("answer:","",$option[$j]));     //得到正确答案

                        if($answer == $correct){
                            $score = $marks;
                        }
                        echo "<h4 style='color: #FF0004'>得分情况：<a>$score/$marks</a></h4>";
                        echo "<h4 style='color: #FF0004'>正确答案：<a>$correct</a></h4>";
                        echo "</div></li>";
                    }
                ?>
			<!--<li id="qu1_1">
			<div class="div_radio">
			  <h4><a name="selected1">单选题第一题</a></h4>
			  	<div><input type="radio" value="A" name="danxuan1"/><span>选项A</span></div>
				<div><input type="radio" value="B" name="danxuan1"/><span>选项B</span></div>
				<div><input type="radio" value="C" name="danxuan1"/><span>选项C</span></div>
				<div><input type="radio" value="D" name="danxuan1"/><span>选项D</span></div>
			  <h4 style="color: #FF0004">正确答案：<a id="answer1">A</a></h4>
			</div>
			</li>
			<li id="qu1_2">
			<div class="div_radio">
			  <h4><a name="selected1">单选题第二题</a></h4>
			  	<div><input type="radio" value="A" name="danxuan1"/><span>选项A</span></div>
				<div><input type="radio" value="B" name="danxuan1"/><span>选项B</span></div>
				<div><input type="radio" value="C" name="danxuan1"/><span>选项C</span></div>
				<div><input type="radio" value="D" name="danxuan1"/><span>选项D</span></div>
			  <h4 style="color: #FF0004">正确答案：<a id="answer2">A</a></h4>
			</div>
			</li>-->
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
                    $answer = "";
                    $option = $mulList[$i];    //options也是个一维数组
                    $answerArr = isset($mulAnswer[$i])?$mulAnswer[$i]:array();      //用一维数组来存放此题目中的所有数据
                    $quizTitle = str_replace("title:","",$option[0]);
                    echo "<li><div class='div_radio'><h4><a name='more$i'>$quizTitle</a></h4>";  //得到标题
                    //遍历动态生成选项
                    for($j = 1;$j < count($option)-2;$j++){
                        $value = chr($j + 64);   //题目选项

                        if(in_array($value,$answerArr)){
                            echo "<div>$value:<input type='checkbox' value='$value' checked name='mul$i";
                            $answer .= $value;
                        }else{
                            echo "<div>$value:<input type='checkbox' value='$value' name='mul$i";
                        }
                        echo "[]'/><span>$option[$j]</span></div>";
                    }

                    $marks = trim(str_replace("score:","",$option[$j++]));     //得到分数
                    $score = 0;
                    $correct = trim(str_replace("answer:","",$option[$j]));     //得到正确答案

                    if($answer == $correct){
                        $score = $marks;
                    }
                    echo "<h4 style='color: #FF0004'>得分情况：<a>$score/$marks</a></h4>";
                    echo "<h4 style='color: #FF0004'>正确答案：<a>$correct</a></h4>";
                    echo "</div></li>";
                }
                ?>

			<!--<li id="qu2_1">
			<div class="div_radio">
			  <h4><a name="more1">多选题第一题</a></h4>
			  	<div><input type="checkbox" value="A" name="duoxuan1"/><span>选项A</span></div>
				<div><input type="checkbox" value="B" name="duoxuan1"/><span>选项B</span></div>
				<div><input type="checkbox" value="C" name="duoxuan1"/><span>选项C</span></div>
				<div><input type="checkbox" value="D" name="duoxuan1"/><span>选项D</span></div>
			  <h4 style="color: #FF0004" >正确答案：<a id="answer3">A B</a></h4>
			</div>
			</li>-->
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
                    $answer = isset($fillAnswer[$i])?$fillAnswer[$i]:"";  //学生提交的答案

                    $quizTitle = str_replace("title:","",$option[0]);
                    echo "<li><div class='div_input_text_area'><h4><a name='input$i'>$quizTitle</a></h4>";  //得到标题
                    //编辑填空内容
                    echo "<div><input type='text' placeholder='请输入您的答案……' name='fill$i' value='$answer'/></div>";

                    $marks = trim(str_replace("score:","",$option[1]));     //得到分数
                    $score = 0;
                    $correct = trim(str_replace("answer:","",$option[2]));     //得到正确答案

                    if($answer == $correct){
                        $score = $marks;
                    }
                    echo "<h4 style='color: #FF0004'>得分情况：<a>$score/$marks</a></h4>";
                    echo "<h4 style='color: #ff0004'>正确答案：<a>$correct</a></h4>";
                    echo "</div></li>";
                }
                ?>
			<!--<li id="qu3_1">
			<div class="div_input_text_area">
				<h4><a name="input1">填空题第一题</a></h4>
				<div><textarea placeholder="请输入您的答案……" class="text_area" name="tiankong1" readonly></textarea></div>
				<h4 style="color: #FF0004" >正确答案：<a id="answer4">标准答案</a></h4>
			</div>
			</li>-->
			</ul>
        </div>
		
    </div>
</div>
	
</body>
</html>
