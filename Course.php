<?php
include 'NoDirectPhpAcess.php';
?>


<?php
$page='Courses+';
include 'Header.php';
$student_id = $_SESSION["user_student_id"];
$group_id = $_SESSION["user_group_id"];
$c_date = date("Y-m-d H:i");


if(!empty($_GET["url"]))
{
    $course_url = $_GET["url"];
    $result = mysqli_query($con,"SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`,"
                           . " `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`  "
                           . " , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table"
                           . " ON users_table.User_ID=courses_table.Lecturer_User_ID where URL='$course_url' ");
 
    if(mysqli_num_rows($result)==0) {

        echo "No course matching the given course URL: ".$course_url;

    } else {
        while($row = mysqli_fetch_assoc($result)) {
			$name = $row['Course_Name'];
            $code = $row['Course_Code'];
            $faculty = $row['Faculty'];	
            $lecturer = $row['Full_Name'];
            $academic = $row['Academic_Year'];
            $url = $row['URL'];
            $course_id = $row['Course_ID'];
            // also get teaching assistant names(if any)
            $ta_result = mysqli_query($con, "SELECT Full_Name FROM users_table where User_ID in (select TA from course_ta where Course_ID='$course_id');");
            if (mysqli_num_rows($ta_result) == 0) {
                echo    "  <div class='alert' style='margin-left:20px;border-bottom:2px solid #1D91EF;'> <a href='~\..\Courses.php?course=$url'>
  Courses > $name ($code) > Lab Reports <br> <span style='font-size:8pt'>Faculty: $faculty  | Year: $academic | Lecturer: $lecturer  </span>
       </a></div> ";                
            } else {
                $ta_name = "";
                while ($row = mysqli_fetch_assoc($ta_result)) {
                    $ta_name = $ta_name.$row['Full_Name']." ";
                }
                $ta_name = trim ($ta_name);
                echo    "  <div class='alert' style='margin-left:20px;border-bottom:2px solid #1D91EF;'> <a href='~\..\Courses.php?course=$url'>
  Courses > $name ($code) > Lab Reports <br> <span style='font-size:8pt'>Faculty: $faculty  | Year: $academic | Lecturer: $lecturer | Teaching Assistant: $ta_name </span>
       </a></div> ";                
            }
        }
    }
}
?>

<div class="row" style='margin-left:20px;float:left'>
    
<?php
    
if (isset($_SESSION['info_ReMarking'])) {
    echo '<hr><div class="alert alert-info" role="alert" style="float:left;">' . $_SESSION['info_ReMarking'] . '</div>';
    $_SESSION['info_ReMarking']=null;
}
   
if (isset($_SESSION['info_courses'])) {
    echo '<hr><div class="alert alert-info" role="alert" style="float:left;">' . $_SESSION['info_courses'] . '</div>';
    $_SESSION['info_courses']=null;
}
?>
    
</div>



<?php

if( $_SESSION['user_type'] == "Student")
{
    
    ?>
    <hr>

    <div class="row" style="width:95%;margin:auto; text-align:left;">

    <div class="col-md-9">
    
    <!-- Nav tabs -->

    <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#menu1">New</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2">Missed</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu3">Submitted</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu4">Marked</a>
    </li>
    
    <!----------Delete Course Button----------->
    <li>
<html>
<body>
	
<div class="modal fade" id="delcourse">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Please confirm!</h2>
                    <button type="button" class="close red" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure about deleting this course? This action can not be reversed!</p>
                </div>
                    <div class="modal-footer"> 
                    <form method="POST" action="">
                        <button type="button" class="btn action-button blue" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="submit" class="btn action-button red" value="Delete"/>
                    </form>

                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="button" class="btn action-button red" data-toggle="modal" data-target="#delcourse">Delete Course</button>
            </div>
        </div>
    </div>
</div>
  
 <?php

// Connect to MySQL database
$con = mysqli_connect("localhost",  $mysql_username, $mysql_password, "lrr");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if(isset($_POST['submit'])){
    header("Location: Courses.php");
    $result = mysqli_query($con, "DELETE FROM course_students_table WHERE Course_ID='$course_id'");
    
}
 ?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" 
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" 
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
  <style>

    /*--------------------[ Delete Course Button ]*/
.action-button {
	font-family: 'Pacifico', cursive;
	font-size: 18px;
	color: #FFF;
	text-decoration: none;	
}
.red {
	background-color: #E74C3C;
	border-bottom: 5px solid #BD3E31;
	text-shadow: 0px -2px #BD3E31;
}
.blue {
	background-color: #4d4dff;
	border-bottom: 5px solid #4d4dff;
	text-shadow: 0px -2px #4d4dff;
}
</style>
</body>
</html>
</li>
    </ul>
    
    <div class="tab-content">
    <div id="menu1" class="container tab-pane active"><br>
        
<?php

    // Get groups of this students
    $sql="SELECT course_group_members_table.Course_Group_id FROM course_group_members_table INNER JOIN course_groups_table ON course_group_members_table.Course_Group_id = course_groups_table.Course_Group_id WHERE course_group_members_table.Student_ID=$student_id and course_groups_table.Course_id=$course_id";
 
    $resultx1 = mysqli_query($con, $sql);   
    while($row = mysqli_fetch_assoc($resultx1))
    {
        $_SESSION['group_id'] = $row['Course_Group_id'];
    }  
 
    $group_id = $_SESSION['group_id'];

    if($group_id == "")
    {
        $group_id = 0; // no group.  If the student has a group, the group number should be greater than 0.
    }

    // Show the assignment iff the following conditions are met: (1)
    // Before the deadline (2) Before the students' extended deadline (if any)
    // (3) none of the student's group members have already submitted
    // the assignment.

    $var = "SELECT Type, Lab_Report_ID, Marks, `Course_ID`, `Posted_Date`, `Deadline`, `Instructions`, lab_reports_table.Title, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, `Attachment_link_4`".
         " FROM `lab_reports_table`".
         " WHERE Course_ID=$course_id".
         " AND (Deadline > '$c_date' OR Lab_Report_ID IN (SELECT `Lab_Report_ID` FROM `extended_deadlines_table` WHERE Student_ID=$student_id AND Extended_Deadline_Date > '$c_date' AND Lab_Report_ID IN (SELECT Lab_Report_ID FROM lab_reports_table WHERE Course_ID=$course_id)))".
         " AND Lab_Report_ID NOT IN (SELECT Lab_Report_ID FROM lab_report_submissions WHERE Course_Group_id IN (SELECT Course_Group_id FROM course_group_members_table WHERE Student_ID=$student_id))".
         " ORDER BY Lab_Report_ID DESC";

    $result1 = mysqli_query($con, $var);
   
    if(mysqli_num_rows($result1)==0)
    {
        echo "No active assignments for this course so far.";
    } else {
        
        while($row = mysqli_fetch_assoc($result1)) {
			$title=$row['Title'];
            $type=$row['Type'];
            $Marks=$row['Marks'];
            $ins=$row['Instructions'];
            $posted=$row['Posted_Date'];	
            $deadline=$row['Deadline'];
            $att1=$row['Attachment_link_1'];
            $att2=$row['Attachment_link_2'];
            $att3=$row['Attachment_link_3'];
            $att4=$row['Attachment_link_4'];
            $labid=$row['Lab_Report_ID'];

            $full_link = "<a href='~\..\Lab_Report_Assignments\\$att1'>$att1</a>";      
                                     
            if($att2!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att2'>$att2</a>";    
            }
            if($att3!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att3'>$att3</a>";    
            }
                                     
            if($att4!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att4'>$att4</a>";    
            }
            echo "   <k href='#'>   <div class='btn btn-default break-word' style='dislay:block; word-wrap: break-word; border: 1px solid #F0F0F0;border-left: 4px solid #03407B;'>
  $title ($type) <br> <span style='font-size:8pt'> $ins</span> 
   <br> <span style='font-size:8pt'>Posted : $posted &nbsp;&nbsp;&nbsp;&nbsp; Deadline :   $deadline   &nbsp;&nbsp;&nbsp;&nbsp;($Marks Marks)  &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<a href='~\..\SubmitLab.php?id=$labid&url=$url' class='btn-sm btn-info' style='margin-left:50px;'> Submit Lab Report</a><br> Attachments : $full_link </span>  
</div></k>";
                
        }}
    echo "";
    ?>
    
    </div>



    
    <div id="menu2" class="container tab-pane"><br>

<?php
    $group_id=$_SESSION['group_id'];
    if($group_id == ""){$group_id = -1;} // Individual assignment does not require the student to have a group id.  Therefore, the group is an empty string. To make the following SQL statement work properly, initialize the group id to -1.
    $result  = mysqli_query($con,"SELECT Lab_Report_ID,Marks, `Course_ID`, `Posted_Date`, `Deadline`, `Instructions`, lab_reports_table.Title, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, `Attachment_link_4`
          FROM `lab_reports_table`
          WHERE 
Lab_Report_ID not in (select Lab_Report_ID from lab_report_submissions where (Student_id=$student_id or Course_Group_id=$group_id)) and Course_ID=$course_id and Deadline < '$c_date'"
                            . ""
                            . ""
                            . ""
                            . ""
                            . ""
                            . ""
                            . "ORDER by Lab_Report_ID DESC");



    if(mysqli_num_rows($result)==0)
    {
        echo "You missed no lab reports in this course.";
     
    } else {
        while($row = mysqli_fetch_assoc($result)) {
			$title=$row['Title'];
            $marks=$row['Marks'];
            $ins=$row['Instructions'];
            $posted=$row['Posted_Date'];	
            $deadline=$row['Deadline'];
            $att1=$row['Attachment_link_1'];
            $att2=$row['Attachment_link_2'];
            $att3=$row['Attachment_link_3'];
            $att4=$row['Attachment_link_4'];
            $id=$row['Lab_Report_ID'];
                             
                                     
                                     
                                  
            $full_link="<a href='~\..\Lab_Report_Assignments\\$att1'>$att1</a>";      
                                     
            if($att2!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att2'>$att2</a>";    
            }
            if($att3!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att3'>$att3</a>";    
            }
                                     
            if($att4!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att4'>$att4</a>";    
            }
            ;   
   
            echo "<div class='btn btn-default break-word' style='dislay:block; word-wrap: break-word; border: 1px solid #F0F0F0;border-left: 4px solid #03407B;'><span class='btn-sm btn-warning' style='margin-left:0px;'>MISSED</span> $title ($marks Marks) <br> <span style='font-size:8pt'> $ins</span> 
   <br> <span style='font-size:8pt'>Posted: $posted<br> Deadline: $deadline  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br> Attachments : $full_link </span>
</div>";
                
        }}
    echo "";
    ?>  
           
    </div>



    <div id="menu3" class="container tab-pane"><br>
<?php


    $group_id = $_SESSION['group_id'];
    if($group_id==""){$group_id=-1;}  // This fixes "Submitted report not shown" http://118.25.96.118/bugzilla/show_bug.cgi?id=176


    $sql_stmt = "SELECT Lab_Report_ID, Marks, `Course_ID`, `Posted_Date`, `Deadline`, `Instructions`, lab_reports_table.Title, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, `Attachment_link_4`
         FROM `lab_reports_table`
         WHERE Lab_Report_ID in (select Lab_Report_ID from lab_report_submissions"
              . " where Status='Pending' and (Student_id=$student_id or Course_Group_id=$group_id)  and Course_ID=$course_id) ORDER by Lab_Report_ID DESC";
          
    $resultx  = mysqli_query($con, $sql_stmt);


    if(mysqli_num_rows($resultx)==0)
    {
        echo "You have no lab report submissions in this course.";
     
    } else {
        while($row = mysqli_fetch_assoc($resultx)) {
            $lab_repo_id=$row['Lab_Report_ID'];
			$title=$row['Title'];
            $marks=$row['Marks'];
            $ins=$row['Instructions'];
            $posted=$row['Posted_Date'];	
            $deadline=$row['Deadline'];
            $att1=$row['Attachment_link_1'];
            $att2=$row['Attachment_link_2'];
            $att3=$row['Attachment_link_3'];
            $att4=$row['Attachment_link_4'];
            $id = $row['Lab_Report_ID'];
            if( $c_date < $deadline)
            {
                $submittedx="<a  href='~\..\SubmitLab.php?id=$id&url=$url' class='btn-sm btn-default'><i class='fa fa-check-circle'></i> Re-Submit </a>";
            }
            
            $full_link = "<a href='~\..\Lab_Report_Assignments\\$att1'>$att1</a>";
            
            if($att2!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att2'>$att2</a>";    
            }
            if($att3!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att3'>$att3</a>";    
            }
                                     
            if($att4!=""){
                $full_link= $full_link."| <a href='~\..\Lab_Report_Assignments\\$att4'>$att4</a>";    
            }
   
            echo "   <k href='#'>   <div class='btn btn-default break-word' style='dislay:block; word-wrap: break-word; border: 1px solid #F0F0F0;border-left: 4px solid #03407B;'>
  $title <br> <span style='font-size:8pt'> $ins</span> 
   <br> <span style='font-size:8pt'>Posted : $posted  Deadline :   $deadline  ($marks Marks) &nbsp; &nbsp;  $submittedx&nbsp; <span class='btn-sm btn-success' style='margin-left:50px;'><i class='fa fa-Edit-circle'></i>  Submitted </span>
<br> Submitted files: ";


            $Sub_result = mysqli_query($con,"SELECT `Submission_ID`, `Submission_Date`, lab_report_submissions.Lab_Report_ID,
lab_report_submissions.Student_id sub_std, lab_report_submissions.Course_Group_id, `Attachment1`,
`Notes`, `Attachment2`, `Attachment3`, `Attachment4`, `Marks`, lab_report_submissions.Status, 
`Title`,users_table.Full_Name,course_group_members_table.Student_ID
FROM `lab_report_submissions`
Left JOIN users_table  on users_table.Student_ID=lab_report_submissions.Student_id
left JOIN course_group_members_table on course_group_members_table.Course_Group_id=lab_report_submissions.Course_Group_id
where Lab_Report_ID=$lab_repo_id and (lab_report_submissions.Student_id='$student_id')"); 

            if(mysqli_num_rows($Sub_result) == 0)
            {
                echo "No Attachments found.";
     
            } else {
                while($row = mysqli_fetch_assoc($Sub_result)) {
                    $at1=$row['Attachment1'];
                    $at2=$row['Attachment2'];
                    $at3=$row['Attachment3'];
                    $at4=$row['Attachment4'];

                    $base_at1 = basename($at1);
                    $base_at2 = basename($at2);
                    $base_at3 = basename($at3);
                    $base_at4 = basename($at4);
                    
                    $full_link = "<a href='~\..\Download.php?file=$at1&attachment=1'>$base_at1</a>";  // prevent students from directly accessing their classmates' submissions
                    
                    if($at2!=""){
                        $full_link= $full_link." | <a href='~\..\Download.php?file=$at2&attachment=2'>$base_at2</a>";    
                    }
                    if($at3!=""){
                        $full_link= $full_link." | <a href='~\..\Download.php?file=$at3&attachment=3'>$base_at3</a>";    
                    }
                        
                    if($at4!=""){
                        $full_link= $full_link." | <a href='~\..\Download.php?file=$at4&attachment=4'>$base_at4</a>";    
                    }

                    echo $full_link;

                }
            }





            echo "</span></div></k>";

  
                
        }}
    echo "";
    ?>  
           
           
    </div>        
          
          
          
          
<?php
    $sqli=mysqli_query($con, "SELECT * from course_groups_table WHERE Course_Group_id=$group_id and Course_id=$course_id");
    while($row = mysqli_fetch_assoc($sqli)) 
    { $Group_Leader=$row['Group_Leader'];
        $Group_Member=$row['Group_Member'];
        $Group_Member2=$row['Group_Member2'];
        $Group_Member3=$row['Group_Member3'];
        $Group_Member4=$row['Group_Member4'];
    }
    ?>
          
          
          
    <div id="menu4" class="container tab-pane"><br>
<?php
    $resultx  = mysqli_query($con,"SELECT `Submission_ID`, `Submission_Date`, lab_reports_table.`Lab_Report_ID`, `Student_id`, "
                             . "`Course_Group_id`, `Notes`, lab_report_submissions.`Marks`,
        lab_report_submissions.Remarking_Reason,
        `Status`, lab_reports_table.Title Lab_Title,lab_reports_table.Marks Original_marks FROM `lab_report_submissions` "
                             . "INNER JOIN lab_reports_table on lab_reports_table.Lab_Report_ID=lab_report_submissions.Lab_Report_ID "
                             . "WHERE (lab_report_submissions.Student_id='$student_id' 
        or (lab_report_submissions.Student_id='$Group_Leader' and lab_report_submissions.Course_Group_id='$group_id')
        or (lab_report_submissions.Student_id='$Group_Member' and lab_report_submissions.Course_Group_id='$group_id')
        or (lab_report_submissions.Student_id='$Group_Member2' and lab_report_submissions.Course_Group_id='$group_id')
        or (lab_report_submissions.Student_id='$Group_Member3' and lab_report_submissions.Course_Group_id='$group_id')
        or (lab_report_submissions.Student_id='$Group_Member4' and lab_report_submissions.Course_Group_id='$group_id')
        )and" 
                             . ""
                             . ""
                             . ""
                             . " lab_reports_table.Lab_Report_ID  in (select Lab_Report_ID from lab_report_submissions"
                             . " where  (Status='Marked' or Status='Remarking') and (Student_id=$student_id or Course_Group_id=$group_id)  and Course_ID=$course_id) ORDER by Submission_ID DESC");

    


    if(mysqli_num_rows($resultx)==0)
    {
        echo "You have no marked submissions in this course";
     
    } else { while($row = mysqli_fetch_assoc($resultx)) {
			$title=$row['Lab_Title'];
            $marks=$row['Marks'];
            $Originalmarks=$row['Original_marks'];
            $ins=$row['Instructions'];
            $posted=$row['Posted_Date'];	
            $deadline=$row['Deadline'];
            $att1=$row['Attachment_link_1'];
            $att2=$row['Attachment_link_2'];
            $att3=$row['Attachment_link_3'];
            $att4=$row['Attachment_link_4'];
            $id=$row['Lab_Report_ID'];
            $Submission_ID=$row['Submission_ID']; 
            $notes=$row['Notes'];
            $status= $row['Status'];
            $remarking_reason=$row['Remarking_Reason'];
            if($status=='Marked')
            {
                $rm_data="\Script.php?remarking=yes&id=$Submission_ID&url=$url&status=Remarking";
                $remarking="<button  onclick='remarking(\"$rm_data\")' class='btn-sm btn-success'>  Request Remarking </button>";
            }
            if($status=='Remarking')
            {
                $remarking="<span  style='color:orange'><i class='fa fa-info-circle'></i> Remarking Request sent </span> <br> Remarking Reason:<i>$remarking_reason </i> <br>";
                              
            }
                              
   
            echo "   <k href='#'>   <div class='btn btn-default break-word' style='dislay:block; word-wrap: break-word; border: 1px solid #F0F0F0;border-left: 4px solid #03407B;'>
  $title  <b> ($marks Marks out of $Originalmarks)</b><br><small> Lecturer Feedback : $notes </small> &nbsp; $remarking   <br> Submission files :";
                


            $Sub_result = mysqli_query($con,"SELECT `Submission_ID`, `Submission_Date`, lab_report_submissions.Lab_Report_ID,
  lab_report_submissions.Student_id sub_std, lab_report_submissions.Course_Group_id, `Attachment1`,
  `Notes`, `Attachment2`, `Attachment3`, `Attachment4`, `Marks`, lab_report_submissions.Status, 
  `Title`,users_table.Full_Name,course_group_members_table.Student_ID
  FROM `lab_report_submissions`
  Left JOIN users_table  on users_table.Student_ID=lab_report_submissions.Student_id
  left JOIN course_group_members_table on course_group_members_table.Course_Group_id=lab_report_submissions.Course_Group_id
  where Lab_Report_ID=$id and lab_report_submissions.Student_id='$student_id'"); 
  
            if(mysqli_num_rows($Sub_result)==0)
            {
                echo "No Attachments found.";
       
            } else { while($row = mysqli_fetch_assoc($Sub_result)) {
                    $at1=$row['Attachment1'];
                    $at2=$row['Attachment2'];
                    $at3=$row['Attachment3'];
                    $at4=$row['Attachment4'];
  
                    $full_link="<a href='~\..\Lab_Report_Submisions\\$at1'>$at1</a>";      
                                       
                    if($at2!=""){
                        $full_link= $full_link."| <a href='~\..\Lab_Report_Submisions\\$at2'>$at2</a>";    
                    }
                    if($at3!=""){
                        $full_link= $full_link."| <a href='~\..\Lab_Report_Submisions\\$at3'>$at3</a>";    
                    }
                          
                    if($at4!=""){
                        $full_link= $full_link."| <a href='~\..\Lab_Report_Submisions\\$at4'>$at4</a>";    
                    }
  
                    echo $full_link;
  
                }
            }







        }}
    echo "</div></k>";
    ?>  
           
           
    </div>      
          
    </div>
    
    </div>
    
    <div class="col-md-3">
    <h3>Class Groups</h3>  
       
<?php
    $resultx1 = mysqli_query($con,"SELECT `Course_Group_id`  FROM `course_groups_table` WHERE  Course_id=$course_id");
    while($row = mysqli_fetch_assoc($resultx1)) {$count_groups=$row['Course_Group_id'];} 

     
    echo " <button onclick='CreateGroup()' class='btn btn-primary'> Create Group</button>";
    
    ?>
    
    
  
    <hr>
<?php
    
    $result = mysqli_query($con,"  SELECT `ID`, course_group_members_table.Course_Group_id, `Student_ID`,
         `Status`,course_groups_table.Group_Name,course_groups_table.Course_id
FROM `course_group_members_table`  INNER JOIN course_groups_table on 
course_groups_table.Course_Group_id=course_group_members_table.Course_Group_id WHERE Student_id=$student_id and course_groups_table.Course_id=$course_id");
 
    if(mysqli_num_rows($result)==0)
    {
        echo "You have no Group in this Course";
    } else { while($row = mysqli_fetch_assoc($result)) {
			$name=$row['Group_Name'];
            $id=$row['Course_Group_id'];
            $status=$row['Status'];
                        
                        
            $extra=" -  <a href='#' class='' onclick='invite($id)'> Invite Others</a></small>";
                       
            if($status=="Invited")
            {
                $extra2="   <a href='#' class='' onclick='accept($id,1)'>Accept</a></small>";  
                $extra3="   <a href='#' class='' onclick='accept($id,0)'>Decline</a></small>"; 
                                
            }
            echo "<div  class='btn-default'><small> $name ($status)  $extra  $extra2  $extra3</small></div>";
                        
            $rs2=mysqli_query($con,"SELECT `ID`, `Course_Group_id`, course_group_members_table.Student_ID, 
                            course_group_members_table.`Status`,users_table.Full_Name FROM `course_group_members_table` 
INNER JOIN users_table on users_table.Student_ID=course_group_members_table.Student_ID
where course_group_members_table.Course_Group_id=$id");
                        
            while($row = mysqli_fetch_assoc($rs2)) {
                $name=$row['Full_Name'];
                $id=$row['Course_Group_id'];
                $status=$row['Status'];
                $Student_ID=$row['Student_ID'];
                        
                        
                echo "<li><small> $name-$Student_ID ($status)</small></li>";
                        
            }
                        
                        
                        
                        
                        
                        
                        
        }
    }
    ?>
    

    
  
    
    </div>
    
    </div>
    



<?php
}
include 'Footer.php';
?>


<script src="./css/jquery-1.11.1.min.js"></script>
<script src="./css/jquery-ui.min.js"></script>
<link rel="stylesheet" href="./css/jquery-ui.css" />

<script>

function CreateGroup() {
    
    
    try
    {
        

        $('<form id="frm" method="get" action="Script.php"><input type="hidden" name="creategroup" value="true">\n\
 <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" > Group Name  <input type="text" name="name">\n\
<input type="hidden" name="url" value="<?php echo $url; ?>">  <input type="hidden" name="id" value="<?php echo $course_id; ?>">    </form>').dialog({
    modal: true,
    title:'Create Group',
    buttons: {
        'Create Group': function () {
            $('#frm').submit();
	    
            $(this).dialog('close');
        },
        'X': function () {
	    
            $(this).dialog('close');
        }
	
    }
});

    } catch(e){ alert(e); }
}




function invite(id) {
    
    
    try
    {
        

        $('<form id="frm" method="get" action="Script.php"><input type="hidden" name="groupinvite" value="true">\n\
 <input type="hidden" name="groupid" value="'+id+'" > Enter Student_ID to Invite  <input type="text" name="student_id">\n\
<input type="hidden" name="url" value="<?php echo $url; ?>">  <input type="hidden" name="courseid" value="<?php echo $course_id; ?>">    </form>').dialog({
    modal: true,
    title:'Invite Students to Group',
    buttons: {
        'Invite': function () {
            $('#frm').submit();
	    
            $(this).dialog('close');
        },
        'X': function () {
	    
            $(this).dialog('close');
        }
	
    }
});

    } catch(e){ alert(e); }
}








function accept(id,val) {
    
    try
    {
        

        $('<form id="frm" method="get" action="Script.php"><input type="hidden" name="acceptinvite" value="true">\n\
 <input type="hidden" name="groupid" value="'+id+'" > \n\  <input type="hidden" name="action" value="'+val+'" > \n\
\n\
 <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" > \n\
<input type="hidden" name="url" value="<?php echo $url; ?>">  <input type="hidden" name="courseid" value="<?php echo $course_id; ?>">    </form>').dialog({
    modal: true,
    title:'Respond to Group Invite',
    buttons: {
        'Confirm': function () {
            $('#frm').submit();
	    
            $(this).dialog('close');
        },
        'X': function () {
	    
            $(this).dialog('close');
        }
	
    }
});

    } catch(e){ alert(e); }
}



function remarking(data)
{
    
    var details = prompt("Please enter your remarking reasons","");
    
    window.location.href = data+"&details="+details;
}
  
</script>
    
