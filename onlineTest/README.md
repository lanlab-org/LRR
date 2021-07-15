#在线测试扩展功能
源文件在LRR项目根路径下的onlineTest中，里面有三个文件夹和9个.php文件

##文件夹说明
###CSS文件夹：
存放有3个.css样式文件，用于修饰.php文件中的html元素
###file文件夹：
1. quizzes子文件夹：
用来存放老师每次发放的测试的.txt文件，此测试文件记录了老师发布的测试习题的内容
   
2. score子文件夹：
用来存放每个测试对应的测试得分统计的.txt文件,内容记录了学生的学号，姓名和测试的得分
   
###img文件夹
存放了online quizzes的前端界面图片，以及部分使用说明图片
##PHP文件说明
1. answerResult.php文件：此.php文件主要处理的业务是，当老师发布测试后，
   可以通过此界面来查看不同测试的每个学生的答题情况和测试结果，使用步骤如下：
   1. 打开老师课程管理主界面，然后点击**show Quiz**的按钮
   1. 根据图片所示进行操作
    ![使用步骤](/onlineTest/img/instructions/answer1.png "step1")
      
2. designTopic.php文件：此.php文件主要处理的业务是，老师可以根据自己的需要，
   选择添加填空题，选择题和多选题。使用过程比较简单，不做赘述。
   
3. downLoad.php文件：此文件用来辅助下载学生测试成绩的.txt文件，由于项目根路径下
   的文件下载的[DownLoad.php文件](/Download.php)下载文件总是找不到文件，又怕
   改了源文件后，引入更大的缺陷，故重新编写了一份新的.php文件
   
4. LocalRefresh.php文件：用于响应[releaseTest.php文件]中删除题目的请求，以及
   [answerResult.php文件]中的根据选中的测试名称动态的刷新测试班级编号，和所在此
   测试班级中的所有学生的学号
   
5. releaseTest.php文件：老师编辑测试题目的平台，使用步骤如下：
    1. 打开老师课程管理主界面，然后点击**Create Quiz**的按钮
    2. 根据图片所示进行操作
       ![使用步骤](/onlineTest/img/instructions/release1.png "step2")
6. student.php文件：学生提交老师布置的测试题目的界面，详细界面请点击[查看](/homepage/screenshots/Student_answer_topics.png)
7. submited.php文件：当学生答题完成，点击提交按钮之后，系统会自动跳转至此.php文件完成
   数据的存储
   
8. successful.php文件：当老师点击**发布测试**按钮,系统会跳转至此.php文件
    将数据存入数据中
   
9. util.php文件：里面包含一个类，主要是用来存储测试题目的信息
###源文件修改说明
####Course.php文件修改：
1. 修改一:
   ![使用步骤](/onlineTest/img/instructions/c.png "update1")
   
2. 修改二:
   ![使用步骤](/onlineTest/img/instructions/c2.png "update2")

2. 修改二:
   ![使用步骤](/onlineTest/img/instructions/c3.png "update3")
   
####Courses.php文件修改：
1. 修改一:
    ![使用步骤](/onlineTest/img/instructions/courses.png "update4")
   
####screenshots.html文件修改
1. 修改一:
   ![使用步骤](/onlineTest/img/instructions/s1.png "update5")
2. 修改效果:
   ![使用步骤](/onlineTest/img/instructions/s2.png "update6")
