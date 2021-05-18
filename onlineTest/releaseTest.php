<?php

use Quiz\QuizInfo;
include_once 'util.php';
include "../get_mysql_credentials.php";
//开启session
if(!session_id())
    session_start();

//session_unset();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>在线测试</title>
<link href="./css/releaseTest.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	function Submit(formName){

	    //检查班级号，时间，名称，总分
         var formObj = document.forms.namedItem(formName);

         var deadTime = formObj.ownerDocument.getElementsByName("deadTime")[0].value;
         if(deadTime.length <= 0){
             alert("请设置截止日期");
             return false;
         }

        var quizTime = formObj.ownerDocument.getElementsByName("quiztime")[0].value;
        if(quizTime.length <= 0){
            alert("请设定测试时间");
            return false;
        }

        var quizName = formObj.ownerDocument.getElementsByName("quizname")[0].value;
        if(quizName.length <= 0){
            alert("请输入测试名称");
            return false;
        }

        var selectObj = formObj.ownerDocument.getElementsByName("classID")[0];
        var index = selectObj.selectedIndex;    //获得选中的索引
        var classCode = selectObj.options[index].value;
        if(classCode.length <= 0){
            alert("请至少选择一个班级号");
            return false;
        }


	    //然后将文件保存到服务器端
        var res = confirm("是否确认发布测试？");
		// if(res){
		//     window.location.href = "successful.php";
        // }
        return res;
	}
	function Delete(id,keyName,itemIndex){
	    alert(id+"  " + keyName);
        //发送ajax请求对应的session内容
        if (window.XMLHttpRequest)
        {// IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// IE6, IE5 浏览器执行
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //删除对应的选项内容
                alert(xmlhttp.responseText);
                window.document.getElementById(id).parentNode.removeChild(window.document.getElementById(id));
            }
        }

        xmlhttp.open("POST","LocalRefresh.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        var data = "key=" + keyName+"&itemIndex="+itemIndex;
        xmlhttp.send(data);

	}

	//根据designTopic界面传递的数据，来动态添加对应的选项

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
    <form name="releaseForm" action="successful.php" method="post">
	<div class="div_all">
    <div class="div_all_top">
        <div class="div_all_title">在线考试系统</div>
      	<div class="div_all_ke">
            <span style="margin-left: 10%">考试名称：<input type="text" class="test_name" name="quizname" required/></span>
            <span style="margin-left: 30px;">时间：<input type="number" class="test_time" style="width: 100px;" name="quiztime" required/>分钟</span>
            <span style="margin-left: 30px;">截至时间：<input type="datetime-local" class="test_time" name="deadTime" required/></span>
            <!--总分随着加入的题目自动变化-->
            <span style="margin-left:20px;color: #d43c3c;">总分：<input type="number" class="test_time" style="width: 50px;" required name="totalpoints" value="<?php
                $score = isset($_SESSION['score'])?intval($_SESSION['score']):0;
                $score += intval(isset($_POST['score'])?$_POST['score']:0);  //得到分数
                $_SESSION['score'] = $score;
                echo $score ?>" readonly></span>

            <a style="text-decoration: none;cursor: pointer;" href="../Courses.php"><span style="margin-left:50px;border: 1px solid;border-radius: 5px">HOME</span></a>

        </div>
        <div class="div_all_ke">
            <span style="margin-left: 10%;width: 150px;display: inline-block">教&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;师：<span class="user_name">
                    <?php
                        echo isset($_SESSION['user_fullname'])?$_SESSION['user_fullname']:"";
                    ?>
                </span></span>
            <span style="margin-left: 10%;width: 220px;display: inline-block">班级：<span class="student_class">
                    <select class="select_class" style="width: 150px;" name="classID">
                        <option selected value="">请选择...</option>
                    <?php

                        /*$mysql_username = "root";
                        $mysql_password = "root";*/
                        $conn = new mysqli("localhost",$mysql_username,$mysql_password,"lrr");
                        if($conn->connect_error){
                            die("连接失败：".$conn->connect_error);
                        }
                        //这里查询数据库中的课程名称和编号，然后填充至这里
                        $LecturerID = isset($_SESSION['user_id'])?$_SESSION['user_id']:"-1";
                        $sqlQuery = "SELECT `Course_Code` FROM `courses_table` where `Lecturer_User_ID` = '$LecturerID'";
                        $result = $conn->query($sqlQuery);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $courseCode = $row['Course_Code'];
                                echo "<option value='$courseCode'>$courseCode</option>";
                            }
                        }
                        //关闭对象连接
                        $conn->close();
                    ?>
                    </select>
                </span>
            </span>
            <span style="display: inline-block;" >
                submitType:
                <span style="border: 1px solid;border-radius: 5px;cursor: pointer;">
                    <input type='radio' name='subType' checked style="cursor: pointer;" value='Individual' required=''> Invidual
                    <input type='radio' name='subType' style="cursor: pointer;" value='Group' required=''> Group
                </span>
            </span>

			<a href="designTopic.php" style="text-decoration: none"><span class="div_span_submit" style="margin-left: 5%">添加题目</span></a>
            <a href="javascript:releaseForm.submit();" style="text-decoration: none"><span class="div_span_submit" onClick="return Submit('releaseForm');">发布测试</span></a>
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
                <!--循环产生标签来生成网页-->
                <?php
                    //处理session中保存的数据
                    try {
                        $quizList = isset($_SESSION['Single'])?$_SESSION['Single']:array(null);   //得到单选的所有数组

                        //echo implode(",",$quizList);
                        //echo $quizList[0],$quizList[1];
                        if($quizList != null && count($quizList) > 1){
                            for($i = 1;$i< count($quizList);$i++){
                                $quizObj = $quizList[$i];
                                //将session中的所有选项全部输出到浏览器
                                echo "<li id='qu1_$i'><div class='div_radio'>";
                                //拼凑函数
                                echo "<h4><a name='selected1'>";
                                echo $quizObj->getTitle();
                                echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete('qu1_$i','Single','$i')\"></h4>";
                                //拼凑选项
                                $optionList = $quizObj->getOptionList();
                                for($j = 1;$j <= count($optionList);$j++){
                                    $text = $optionList[$j];//name就是option对应的值为1~n ;$text为选项的文本描述
                                    $value = chr($j + 64);   //value为此选项的值就答案描述
                                    echo "<div>$value:<input type='radio' value='$value' name='single$i'/><span>$text</span></div>";
                                }
                                echo "<h4 style='color: #FF0004'>题目分值：<a>";
                                echo $quizObj->getScore();
                                echo "分</a></h4>";
                                echo "<h4 style='color: #FF0004'>正确答案：<a>"; //id='answer"+($i+1)+"'
                                echo $quizObj->getAnswer();
                                echo "</a></h4></div></li>";
                            }
                        }
                    }catch (Exception $e){
                        $quizList = array("");
                        throw new Exception("session 未开启，或数值为空");
                    }

                    //处理每次请求中发送过来的问题
                    try {
                        $type = isset($_POST['type'])?$_POST['type']:"";     //获得题目的类型：单选，多选还是填空
                        if(strcmp(strval($type),"Single") == 0){

                            //将本次传递过来的值添加到页面并保存到session中
                            $i = count($quizList);  //因为按照下标从0开始算的
                            echo "<li id='qu1_$i'><div class='div_radio'>";
                            //拼凑函数
                            echo "<h4><a name='selected1'>";
                            echo isset($_POST['singleChoicetitle'])?$_POST['singleChoicetitle']:"";
                            echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete('qu1_$i','Single','$i')\"></h4>";

                            //拼凑选项
                            $index = 1;
                            $quizArr = array();
                            while(isset($_POST["$index"])){
                                $text = $_POST["$index"];
                              /*  echo "<script>alert($text);</script>";*/
                                $value = chr($index + 64);
                                $quizArr[$index] = $text;
                                echo "<div>$value:<input type='radio' value='$value' name='single$i'/><span>$text</span></div>";
                                $index ++;
                            }

                            echo "<h4 style='color: #FF0004'>题目分值：<a>";
                            echo isset($_POST['score'])?$_POST['score']:0;
                            echo "分</a></h4>";
                            echo "<h4 style='color: #FF0004'>正确答案：<a>"; //id='answer"+($i+1)+"'
                            echo $_POST['singleChoice'];
                            echo "</a></h4></div></li>";

                            /*******************将数据保存到对象中并添加值session中*******************/
                            $quiz = new QuizInfo();
                            $quiz->setTitle($_POST['singleChoicetitle']);   //得到选项的标签
                            $quiz->setOptionList($quizArr);    //保存选项
                            $quiz->setScore($_POST['score']);   //设置分数
                            $quiz->setAnswer($_POST['singleChoice']);  //将选项正确答案存入对象类中
                            $quizList[$i] = $quiz;
                            $quizList[0] = null;
                            $_SESSION['Single'] = $quizList;   //将选项保存到session中

                            $_POST = array();   //清空post中数据
                        }
                    }catch (Exception $e){
                        header("Location:../index.php");
                        $_POST = array();   //清空post中数据
                        throw new Exception("POST中请求数据为空");
                    }

                ?>
			<!--<li id="qu1_1">
                <div class="div_radio">
                  <h4><a name="selected1">单选题第一题</a><img src="./img/delete.png" alt="删除" style="margin-left: 80%;width: 30px;height: 30px;" onClick="Delete('qu1_1')"></h4>
                    <div><input type="radio" value="A" name="danxuan1"/><span>选项A</span></div>
                    <div><input type="radio" value="B" name="danxuan1"/><span>选项B</span></div>
                    <div><input type="radio" value="C" name="danxuan1"/><span>选项C</span></div>
                    <div><input type="radio" value="D" name="danxuan1"/><span>选项D</span></div>
                  <h4 style="color: #FF0004">正确答案：<a id="answer1">A</a></h4>
                </div>
			</li>
			<li id="qu1_2">
                <div class="div_radio">
                  <h4><a name="selected1">单选题第二题</a><img src="./img/delete.png" alt="删除" style="margin-left: 80%;width: 30px;height: 30px;" onClick="Delete('qu1_2')"></h4>
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
                    //读取session中保存的多选选项的值
                    try {
                        $quizList = isset($_SESSION['Mul'])?$_SESSION['Mul']:array(null);   //得到单选的所有数组
                        //echo count($quizList);
                        //echo implode(",",$quizList);
                        if($quizList != null && count($quizList) > 1){
                            for($i = 1;$i< count($quizList);$i++){
                                $quizObj = $quizList[$i];
                                //将session中的所有选项全部输出到浏览器
                                echo "<li id='qu2_$i'><div class='div_radio'>";
                                //拼凑函数
                                echo "<h4><a name='more1'>";
                                echo $quizObj->getTitle();
                                echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete('qu2_$i','Mul','$i')\"></h4>";
                                //拼凑选项
                                $optionList = $quizObj->getOptionList();
                                for($j = 1;$j <= count($optionList);$j++){
                                    $text = $optionList[$j];//name就是option对应的值为1~n ;$text为选项的文本描述
                                    $value = chr($j + 64);   //value为此选项的值就答案描述
                                    echo "<div>$value:<input type='checkbox' value='$value' name='mul$i";
                                    echo "[]'/><span>$text</span></div>";
                                }

                                echo "<h4 style='color: #FF0004'>题目分值：<a>";
                                echo $quizObj->getScore();
                                echo "分</a></h4>";
                                echo "<h4 style='color: #FF0004'>正确答案：<a>"; //id='answer"+($i+1)+"'
                                echo $quizObj->getAnswer();
                                echo "</a></h4></div></li>";
                            }
                        }
                    }catch (Exception $e){
                        $quizList = array();
                        throw new Exception("session 未开启，或数值为空");
                    }

                    //读取请求中的数据并生成点击按钮
                    try {
                        $type = isset($_POST['type'])?$_POST['type']:"";     //获得题目的类型：单选，多选还是填空
                        if(strcmp(strval($type),"Mul") == 0){

                            //将本次传递过来的值添加到页面并保存到session中
                            $i = count($quizList) ;  //因为按照下标从0开始算的
                            echo "<li id='qu2_$i'><div class='div_radio'>";
                            //拼凑函数
                            echo "<h4><a name='selected1'>";
                            echo isset($_POST['mulChoicetitle'])?$_POST['mulChoicetitle']:"";
                            echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete('qu2_$i','Mul','$i')\"></h4>";

                            //拼凑选项
                            $index = 1;     //当做下标为了获取选项文本框
                            $quizArr = array();
                            while(isset($_POST["$index"])){
                                $text = $_POST["$index"];
                                $value = chr($index + 64);
                                $quizArr[$index] = $text;
                                echo "<div>$value:<input type='checkbox' value='$value' name='mul$i";
                                echo "[]'/><span>$text</span></div>";
                                $index ++;
                            }

                            echo "<h4 style='color: #FF0004'>题目分值：<a>";
                            echo isset($_POST['score'])?$_POST['score']:0;
                            echo "分</a></h4>";

                            echo "<h4 style='color: #FF0004'>正确答案：<a>"; //id='answer"+($i+1)+"'
                            //循环遍历选中的多选按钮
                            $choices = $_POST["mulChoice"];
                            $answer = implode('',$choices);
                            echo $answer;
                            echo "</a></h4></div></li>";

                            /*******************将数据保存到对象中并添加值session中*******************/
                            $quiz = new QuizInfo();
                            $quiz->setTitle($_POST['mulChoicetitle']);   //得到选项的标签
                            $quiz->setOptionList($quizArr);    //保存选项
                            $quiz->setScore($_POST['score']);
                            $quiz->setAnswer($answer);  //将选项正确答案存入对象类中
                            $quizList[$i] = $quiz;
                            $quizList[0] = null;
                            $_SESSION['Mul'] = $quizList;   //将选项保存到session中

                            $_POST = array();   //清空post中数据
                        }
                    }catch (Exception $e){

                        $_POST = array();   //清空post中数据
                        throw new Exception("POST中请求数据为空");
                    }

                ?>
                <!--<li id="qu2_1">
                <div class="div_radio">
                  <h4><a name="more1">多选题第一题</a><img src="./img/delete.png" alt="删除" style="margin-left: 80%;width: 30px;height: 30px;" onClick="Delete('qu2_1')"></h4>
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
                    //读取session中保存的填空题的内容
                    try{
                        $quizList = isset($_SESSION['Fill'])?$_SESSION['Fill']:array(null);

                        if($quizList != null && count($quizList) > 1){
                            for($i = 1;$i < count($quizList);$i++){
                                $quizObj = $quizList[$i];
                                echo "<li id='qu3_$i'><div class='div_input_text_area'>";
                                //拼凑函数
                                echo "<h4><a name='input1'>";
                                echo $quizObj->getTitle();
                                echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete(";
                                echo "'qu3_$i','Fill','$i')\"></h4>";
                                //拼凑答题框
                                /*echo "<div><textarea placeholder='请输入您的答案……' class='text_area' name='fill$i'></textarea></div>";*/
                                echo "<h4 style='color: #FF0004'>题目分值：<a>";
                                echo $quizObj->getScore();
                                echo "分</a></h4>";
                                echo "<h4 style='color: #FF0004' >正确答案：<a id='answer4'>";
                                echo $quizObj->getAnswer();
                                echo "</a></h4>";
                            }
                        }
                    }catch(Exception $e){
                        $quizList = array();
                        throw new Exception("session 未开启，或数值为空");
                    }

                    //读取post请求中的数据
                    try{
                        $type = isset($_POST['type'])?$_POST['type']:"";
                        if(strcmp(strval($type),"Fill")==0){
                            $i = count($quizList);  //因为按照下标从0开始算的
                            echo "<li id='qu3_$i'><div class='div_input_text_area'>";
                            //拼凑函数
                            echo "<h4><a name='input1'>";
                            echo isset($_POST['fillBlanktitle'])?$_POST['fillBlanktitle']:"";
                            echo "</a><img src='./img/delete.png' alt='删除' style='margin-left: 80%;width: 30px;height: 30px;' onClick=\"Delete('qu3_$i','Fill','$i')\"></h4>";
                            //拼凑答题框
                            //echo "<div><textarea placeholder='请输入您的答案……' class='text_area' name='fill$i'></textarea></div>";
                            echo "<h4 style='color: #FF0004'>题目分值：<a>";
                            echo isset($_POST['score'])?$_POST['score']:0;
                            echo "分</a></h4>";
                            echo "<h4 style='color: #FF0004' >正确答案：<a id='answer4'>";
                            echo isset($_POST["fillBlankAnswer"])?$_POST["fillBlankAnswer"]:"";
                            echo "</a></h4>";
                            //将答题选项存入session中
                            $quiz = new QuizInfo();
                            $quiz->setTitle($_POST['fillBlanktitle']);
                            $quiz->setOptionList(array());      //存入一个空数组
                            $quiz->setScore($_POST['score']);
                            $quiz->setAnswer($_POST['fillBlankAnswer']);
                            $quizList[$i] = $quiz;
                            $quizList[0] = null;
                            $_SESSION['Fill'] = $quizList;

                            $_POST = array();   //清空post中数据
                        }
                    }catch (Exception $e){

                        $_POST = array();   //清空post中数据
                        throw new Exception("POST中请求数据为空");
                    }

                ?>
                <!--<li id="qu3_1">
                    <div class="div_input_text_area">
                        <h4><a name="input1">填空题第一题</a><img src="./img/delete.png" alt="删除" style="margin-left: 80%;width: 30px;height: 30px;" onClick="Delete('qu3_1')"></h4>
                        <div><textarea placeholder="请输入您的答案……" class="text_area" name="tiankong1"></textarea></div>
                        <h4 style="color: #FF0004" >正确答案：<a id="answer4">标准答案</a></h4>
                    </div>
                </li>-->
			</ul>
        </div>
		
    </div>
    </div>
    </form>
</body>
</html>
