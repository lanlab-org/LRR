<?php
    include 'NoDirectPhpAcess.php';
?>

<?php

/* 
 * This file contains the main Server-side scripts for the project.
 */

session_start();
date_default_timezone_set('Asia/Shanghai');

// Connect to MySQL database
include "get_mysql_credentials.php";
$con = mysqli_connect("localhost",  $mysql_username, $mysql_password, "lrr");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


error_reporting(0);





// #### FUNCTION CHECK FILE TYPES ////

function is_valid_student_number($student_id) {
    // zjnu student number has 12 digits, and starts with 20
    if (strlen($student_id) == 12  && is_numeric($student_id) == TRUE && substr($student_id, 0, 2) == "20")
        return TRUE;
    return FALSE;
}

// ############################### SIGN UP ##################################
if (!empty($_POST["frm_signup_1"])) {
    
    $student_id = trim( mysqli_real_escape_string($con, $_POST["student_id"]) );
    $passport = trim( mysqli_real_escape_string($con, $_POST["passport"]) );

    // validate student number
    if (! is_valid_student_number($student_id)) {
        $_SESSION["info_signup1"] = "Invalid student number.";
        header("Location: index.php");
        return;       
    }

    // passport should be empty (not used)
    if (strcmp($passport, '') != 0) {
        $_SESSION["info_signup1"] = "Passport is disused.  Please leave it empty.";
        header("Location: index.php");
        return;
    }


    // Check if this student number is a legal one
    $result = mysqli_query($con, "SELECT * FROM `students_data` WHERE Student_ID='$student_id'");   
    if(mysqli_num_rows($result) == 0)
    {
        $_SESSION["info_signup1"] = "Your entered student number could not be verified.  Please contact Student Management Office <lanhui at zjnu.edu.cn>.  Thanks.";
        header("Location: index.php");     
        return;		
    }
   
    $result98 = mysqli_query($con, "SELECT * FROM `users_table` WHERE Student_ID='$student_id'");
    if(mysqli_num_rows($result98) == 0)
    {
        $_SESSION['user_student_id'] = $student_id;        
        $_SESSION['user_passport'] = $passport;
        header("Location: signup.php");
        return;
    }
    else
    { 
        $_SESSION["info_signup1"] = "This Student ID is already in use! Please contact Student Management Office <lanhui at zjnu.edu.cn> for help.";
        header("Location: index.php");
        return;		
    } 
}





// ############################### CREATE STUDENT USER ##################################
if (!empty($_POST["frm_signup_2"])) {
    $fullname = mysqli_real_escape_string($con, $_POST["fullname"]);    
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $confirmpassword = mysqli_real_escape_string($con, $_POST["confirmpassword"]);
    $student_id = $_SESSION['user_student_id'];
    $passport =  $_SESSION['user_passport'];
    $_SESSION['user_fullname'] = $fullname;
    $_SESSION['user_type'] = "Student";
    $_SESSION['user_email'] = $email;

    // check confirmed password
    if ( strcasecmp( $password, $confirmpassword ) != 0 ){
        $_SESSION['info_signup2'] = "Password confirmation failed.";
        $_SESSION['user_fullname'] = null;  // such that Header.php do not show the header information.        
        header("Location: signup.php");
        return;
    }

    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['info_signup2'] = "Invalid email address.";
        header("Location: signup.php");
        return;
    }
   
    $upperLetter     = preg_match('@[A-Z]@',    $password);
    $smallLetter     = preg_match('@[a-z]@',    $password);
    $containsDigit   = preg_match('@[0-9]@',    $password);
    $containsSpecial = preg_match('@[^\w]@',    $password);
    $containsAll = $upperLetter && $smallLetter && $containsDigit && $containsSpecial;

    // check for strong password
    if(! $containsAll) {
        $_SESSION['info_signup2'] = "Password must have at least characters that include lowercase letters, uppercase letters, numbers and sepcial characters (e.g., !?.,*^).";
        header("Location: signup.php");
        return;
    }

    // check if email is taken
    $result = mysqli_query($con, "SELECT * FROM users_table WHERE email='$email'");
    if(mysqli_num_rows($result) != 0)
    {
        $_SESSION["info_signup2"]="Email adress ".$email."  is already in use.";
        $_SESSION['user_fullname'] = null;
        header("Location: signup.php"); 
        return;       
    }

    // apply password_hash()
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql= "INSERT INTO `users_table`(`Email`, `Password`, `Full_Name`, `UserType`, `Student_ID`, `Passport_Number`) VALUES "
        . "('$email','$password_hash','$fullname','Student','$student_id','$passport')";
    
    if ($con->query($sql) === TRUE) {
        header("Location: Courses.php");    
    } else {
        // echo "Error: " . $sql . "<br>" . $con->error;
        echo "Something really bad (SQL insertion error) happend during sign up.";
    }
}


    


// ################################ LOGIN  #####################################

if (!empty($_POST["frm_login"])) {
    
    $user = mysqli_real_escape_string($con, $_POST["user"]); // user could be a 12-digit student number or an email address
    $is_student_number = 0;
  
    // Validate student number
    if ( is_valid_student_number($user) ) {
        $is_student_number = 1;        
    }
    
    // Validate email address if what provided is not a student number
    if (! $is_student_number && !filter_var($user, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["info_login"] = "Invalid email address: " . "$user";
        header("Location: index.php");
        return;
    }

    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $result = mysqli_query($con, "SELECT * FROM users_table WHERE (Student_ID='$user') OR (Email='$user')");
    if(mysqli_num_rows($result) == 0)
    {
        $_SESSION["info_login"] = "Inavlid user name information.";
        echo $_SESSION["info_login"];
        header("Location: index.php");        
    }
    else 
    { 
        while($row = mysqli_fetch_assoc($result)) {
            //  verify the hashed password and unhashed password
            $sha512pass = hash('sha512', $password); // for backward compatibility.  Old passwords were hashed using SHA512 algorithm.
            if(password_verify($password, $row["Password"]) or $sha512pass == $row["HashPassword"]) {

                $_SESSION['user_id'] = $row['User_ID'];
                $_SESSION['user_email'] = $row['Email'];
                $_SESSION['user_student_id'] = $row['Student_ID'];
                $_SESSION['user_type'] = $row['UserType'];
                $_SESSION['user_fullname'] = $row['Full_Name'];
     
                if( $_SESSION['user_type'] == "Student")
                {
                    header("Location: Courses.php");
                }     

                if( $_SESSION['user_type'] == "Lecturer")
                {
                    header("Location: Courses.php");
                }
     
                if( $_SESSION['user_type'] == "TA")
                {
                    header("Location: Courses.php");
                }
      
                if( $_SESSION['user_type'] == "Admin")
                {
                    header("Location: Admin.php");
                }
            //  report wrong pass if not correct
            } else {
                $_SESSION["wrong_pass"] = "Wrong Password.";
                header("Location: index.php");  
            }
        }
    }
}





// ################################ Recover Password  #####################################

if (!empty($_POST["frm_recover_password"])) {

    $student_id = mysqli_real_escape_string($con,$_POST["sno"]);
    $email = mysqli_real_escape_string($con,$_POST["email"]);

    // validate student number
    if (strlen($student_id) != 12  || is_numeric($student_id) == FALSE) {
        echo "Invalid student number.";
        return;       
    }

    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return;
    }


    $result = mysqli_query($con, "SELECT * FROM users_table WHERE Email='$email' and Student_ID='$student_id'");
    if(mysqli_num_rows($result)==0)
    {
        $_SESSION["info_recover_password"]="Email address is not recognised.";
        $_SESSION["info_recover_password"] = "Identity not recognized.  Try again or send an inquiry email message to lanhui at zjnu.edu.cn.";
        header("Location: recover_password.php");        
    } else 
    {
        $result = mysqli_query($con, "DELETE FROM users_table WHERE Email='$email' and Student_ID='$student_id'");
        $_SESSION["info_recover_password"] = "<b>Reset done.  Please go to the sign up page and sign up again</b>.";
        header("Location: recover_password.php");
    }
}





// ################################ RESET Password  #####################################

if (!empty($_POST["frm_reset_password"])) {
    $password=mysqli_real_escape_string($con,$_POST["password"]);
    $token=mysqli_real_escape_string($con,$_POST["token"]);
    $email=mysqli_real_escape_string($con,$_POST["email"]);
    $result = mysqli_query($con,
                           "SELECT * FROM Users_Table WHERE email='$email'");
    if(mysqli_num_rows($result)==0)
    {
    
        echo "invalid email";
        return;
       
    }
    else 
    { 
        while($row = mysqli_fetch_assoc($result)) {

            $userid=$row['User_ID'];

            $email=$row['Email'];
            $id=$row['Student_ID'];
    
            $user_token=$userid*$userid*$userid+$userid*0.00343;
            if($user_token==$token)
            {
                // Password Update

                // Password Update
                $hashed_password=hash('sha512', $password);
                $sql= "UPDATE users_table set HashPassword='$hashed_password' where User_ID=$userid;";
                if ($con->query($sql) === TRUE) {
       
                    error_reporting(0);

                    $_SESSION["info_login"]=" Password changed successfully , you can login now with your new password ";
                    header("Location: index.php");
                                   
                }
                else {
                    echo "Error: " . $sql . "<br>" . $con->error;
                }

            } else
            {
                echo "Invalid Token ";
            }

    


        }
    }
}





// ############################### CREATE Lecturer/TA USER ##################################
if (!empty($_POST["frm_createlecturrer"])) {
    $email=mysqli_real_escape_string($con,$_POST["email"]);
    $passport=mysqli_real_escape_string($con,$_POST["passport"]);
    $fullname=mysqli_real_escape_string($con,$_POST["fullname"]);
    $type=mysqli_real_escape_string($con,$_POST["type"]);
    $password=$passport;
    // check if email is taken
    $result = mysqli_query($con,
                           "SELECT * FROM Users_Table WHERE email='$email'");
    if(mysqli_num_rows($result)!=0)
    {
        $_SESSION["info_Admin_Users"]="Email adress : ".$email." is already in use.";
        header("Location: Admin.php");        
    }
    $sql= "INSERT INTO `users_table`(`Email`, `Password`, `Full_Name`, `UserType`, `Passport_Number`) VALUES "
        . "('$email','$password','$fullname','$type','$passport')";
    
    if ($con->query($sql) === TRUE) {
        $_SESSION["info_Admin_Users"]=$type." user Created successfully : email ".$email." and $password as Password.";
        header("Location: Admin.php"); 
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}





// #### FUNCTION CHECK FILE TYPES ////

function is_valid_file_format($file) {
 
 
    $allowed =  array('pdf', 'rtf', 'jpg','png', 'doc', 'docx', 'xls', 'xlsx','sql','txt','md','py','css','html',
                      'cvc','c','class','cpp','h','java','sh','swift','zip','rar','ods','xlr','bak','ico','swf');
   
    $filename = $_FILES[$file]['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $result = in_array($ext,$allowed);
    return $result;
}





// #### FUNCTION CREATE DIRECTORIES  ////
 
function Create_dir($upPath)
{
    try {
        // full path 
        $tags = explode('/', $upPath);            // explode the full path
        $mkDir = "";

        foreach($tags as $folder) {          
            $mkDir = $mkDir . $folder ."/";   // make one directory join one other for the nest directory to make
            echo '"'.$mkDir.'"<br/>';         // this will show the directory created each time
            if(!is_dir($mkDir)) {             // check if directory exist or not
                mkdir($mkDir, 0777);          // if not exist then make the directory
            }
        }	
    }
    catch (Exception $e) {
        return FALSE;
    }
    return $upPath;
}


function mkdirs($path)
{
    if (file_exists($path))
        return $path;
    $result = mkdir($path, 0777, true);
    if ($result) {
        return $path;
    }
    return $result;
}



// ############################### #Post Assignment ##################################
if (!empty($_POST["frm_uploadlab"])) {
        
     
        
    $course_id=mysqli_real_escape_string($con,$_POST["course_id"]);
    $deadlinedate=$_POST["deadlinedate"];
    $deadlinetime=$_POST["deadlinetime"];
    $instructions=mysqli_real_escape_string($con,$_POST["instructions"]);
    $title=mysqli_real_escape_string($con,$_POST["title"]);
    $marks=mysqli_real_escape_string($con,$_POST["marks"]);
    //  $url=mysqli_real_escape_string($con,$_POST["url"]);
    $url = $_SESSION['url']; //using real_escape_string was failing to redirect to the main page
    $type = mysqli_real_escape_string($con, $_POST["type"]);
         
         
    $deadline = $deadlinedate." ".$deadlinetime;
    $date =  date("Y-m-d H:i");
            
       
       
    // GET UPLOADED FILES
       
    $target_dir = Create_dir("Lab_Report_Assignments/".$title."/");


    $rnd=rand(10,1000);
    $rnd=""; // no more required , creating folder for each lab
    $targetfile  = $target_dir.$rnd.$_FILES['attachment1']['name'];
    $targetfile2 = $target_dir.$rnd.$_FILES['attachment2']['name'];
    $targetfile3 = $target_dir.$rnd.$_FILES['attachment3']['name'];
    $targetfile4 = $target_dir.$rnd.$_FILES['attachment4']['name'];
             
          

    $count=0;           
           
 
    if(!is_valid_file_format("attachment1") && $_FILES["attachment1"]["name"]!="")
    {
        echo "Invalid File Type for Attachment 1";
        return;
    }
    if(!is_valid_file_format("attachment2") && $_FILES["attachment2"]["name"]!="")
    {
        echo "Invalid File Type for Attachment 2";
        return;
    }
    if(!is_valid_file_format("attachment3") && $_FILES["attachment3"]["name"]!="")
    {
        echo "Invalid File Type for Attachment 3";
        return;
    }
  
    // use 4 for missing file

    if (move_uploaded_file($_FILES['attachment1']['tmp_name'], $targetfile)) {
        $count++;
    } else { 
        echo $_FILES['attachment1']['error'];
    }
  
    if (move_uploaded_file($_FILES['attachment2']['tmp_name'], $targetfile2)) {
        $count++;
    } else { 
        echo $_FILES['attachment2']['error'];
    }
  
    if (move_uploaded_file($_FILES['attachment3']['tmp_name'], $targetfile3)) {
        $count++;
    } else { 
        echo $_FILES['attachment3']['error'];
    }
  
    if (move_uploaded_file($_FILES['attachment4']['tmp_name'], $targetfile4)) {
        $count++;
    } else { 
        echo $_FILES['attachment4']['error'];
    }
  
  
    echo $count." File(s) uploaded";
  
    //CLEAN
    $targetfile="";
    $targetfile2="";
    $targetfile3="";
    $targetfile4="";
      
    if($_FILES['attachment1']['name']!=""){   $targetfile  = "/".$title."/".$_FILES['attachment1']['name']; }
    if($_FILES['attachment2']['name']!=""){   $targetfile2 = "/".$title."/".$_FILES['attachment2']['name']; }
    if($_FILES['attachment3']['name']!=""){   $targetfile3 = "/".$title."/".$_FILES['attachment3']['name']; }
    if($_FILES['attachment4']['name']!=""){   $targetfile4 = "/".$title."/".$_FILES['attachment4']['name']; }
        
    $sql="INSERT INTO `lab_reports_table`(`Course_ID`, `Posted_Date`, `Deadline`, `Instructions`,
                     `Title`, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, `Attachment_link_4`,Marks,Type) 
                     VALUES ('$course_id','$date','$deadline','$instructions','$title','$targetfile','$targetfile2','$targetfile3','$targetfile3',$marks,'$type')";
      
      
    
    if ($con->query($sql) === TRUE) {
       
        $_SESSION["info_courses"] = $type." lab report assignment posted successfully.";
        header("Location: Courses.php?course=".$url); 
   
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}





function checksize($file)
{
    $result = $_FILES["$file"]['size']/(1024*1024);
      
    if($result > 3)
    {
        return FALSE;
    }
    return TRUE;
}
 
 
 
// ############################### Submit Assignment ##################################
if (!empty($_POST["frm_submitlab"])) {
        
    $lab_id = mysqli_real_escape_string($con, $_POST["lab_id"]);
    $student_id = $_POST["student_id"];
    $group_id = $_POST["group_id"];
  
    $instructions = mysqli_real_escape_string($con, $_POST["instructions"]);
    $title = mysqli_real_escape_string($con, $_POST["title"]);
    
    $url = mysqli_real_escape_string($con, $_POST["url"]);
    
    $deadline = $deadlinedate." ".$deadlinetime;
    $date = date("Y-m-d H:i");
    
    // GET UPLOADED FILES
    $labName = mysqli_query($con,"SELECT * FROM `lab_reports_table` WHERE Lab_Report_ID=$lab_id");
    while($row = mysqli_fetch_assoc($labName)) 
    {
        $lab_name = $row['Title'];
        $_SESSION['Sub_Type'] = $row['Type']; // submission type, either Individual or Group
    } 

    $upload_folder = "Lab_Report_Submisions"; // old place for storing students' submissions
    $upload_folder = "./../../lrr_submission";
    $target_dir = mkdirs($upload_folder."/".$student_id."/".$url."/".$lab_name."/"); # url is actually course code plus academic year, e.g., CSC3122020
    $targetfile  = $target_dir.$_FILES['attachment1']['name'];
    $targetfile2 = $target_dir.$_FILES['attachment2']['name'];
    $targetfile3 = $target_dir.$_FILES['attachment3']['name'];
    $targetfile4 = $target_dir.$_FILES['attachment4']['name'];
          
    $count = 0;
        
    //check zise
    if(!checksize("attachment1"))
    {
        echo "1 MB is the maximum file size allowed";
        return;
    }
    if(!checksize("attachment2") && $_FILES["attachment2"]["name"] != "")
    {
        echo "1 MB is the maximum file size allowed";
        return;
    }
    if(!checksize("attachment3") && $_FILES["attachment3"]["name"] != "")
    {
        echo "1 MB is the maximum file size allowed";
        return;
    }


    
    if(!is_valid_file_format("attachment1"))
    {
        echo "Invalid File Type for Attachment 1";
        return;
    }
    if(!is_valid_file_format("attachment2") && $_FILES["attachment2"]["name"] != "")
    {
        echo "Invalid File Type for Attachment 2";
        return;
    }
    if(!is_valid_file_format("attachment3") && $_FILES["attachment3"]["name"] != "")
    {
        echo "Invalid File Type for Attachment 3";
        return;
    }

    if($_FILES["attachment1"]["error"] != 0) {
        echo "Error when uploading the file.";
        return;
    } 

    // use 4 for missing file

    if (move_uploaded_file($_FILES['attachment1']['tmp_name'], $targetfile)) {
        $count++;
    } else { 
        echo $_FILES['attachment1']['error'];
    }

    if (move_uploaded_file($_FILES['attachment2']['tmp_name'], $targetfile2)) {
        $count++;
    } else { 
        echo $_FILES['attachment2']['error'];
    }

    if (move_uploaded_file($_FILES['attachment3']['tmp_name'], $targetfile3)) {
        $count++;
    } else { 
        echo $_FILES['attachment3']['error'];
    }

    if (move_uploaded_file($_FILES['attachment4']['tmp_name'], $targetfile4)) {
        $count++;
    } else { 
        echo $_FILES['attachment4']['error'];
    }


    echo $count." File(s) uploaded";

    //CLEAN
    $targetfile1 = "";
    $targetfile2 = "";
    $targetfile3 = "";  
    $targetfile4 = "";

    if(strlen($_FILES['attachment1']['name']) > 2 ) { // why greater than 2???
        $targetfile = "/".$student_id."/".$url."/".$lab_name."/".$_FILES['attachment1']['name'];
    }
   
    if(strlen($_FILES['attachment2']['name']) > 2 ) {
        $targetfile2 = "/".$student_id."/".$url."/".$lab_name."/".$_FILES['attachment2']['name']; }
 
    if(strlen($_FILES['attachment3']['name']) > 2 ) {
        $targetfile3 = "/".$student_id."/".$url."/".$lab_name."/".$_FILES['attachment3']['name'];}
   
    if(strlen($_FILES['attachment4']['name']) > 2 ) {
        $targetfile4 = "/".$student_id."/".$url."/".$lab_name."/".$_FILES['attachment4']['name'];
    }

    // When $group_id is not properly initialized, use integer 0 as its value.
    // This temporarily fixed the "Students unable to submit assignment after a recent change" bug at http://118.25.96.118/bugzilla/show_bug.cgi?id=65
    if (trim($group_id) === '') { // when $group_id is an empty string or contains only whitespace characters.
        $group_id = 0; // FIXME
    }

    $sql1 = "DELETE FROM lab_report_submissions where Lab_Report_ID=$lab_id and Student_id=$student_id and Course_Group_id=$group_id";
    if ($con->query($sql1) === TRUE) {
    }

    
    $sql="INSERT INTO `lab_report_submissions`(`Submission_Date`, `Lab_Report_ID`, `Student_id`,"
        . " `Course_Group_id`, `Attachment1`, `Notes`, `Attachment2`, `Attachment3`, `Attachment4`, `Status`, `Title`,`Remarking_Reason`)"
        . " VALUES ('$date',$lab_id,$student_id,$group_id,'$targetfile','$instructions','$targetfile2','$targetfile3','$targetfile4',"
        . "'Pending','$title','')";

    if ($con->query($sql) === TRUE) {
        if($_SESSION['Sub_Type']=='Individual')
        {
            $con->query($sql = "UPDATE `lab_report_submissions` SET `Course_Group_id` = '0' WHERE `lab_report_submissions`.`Lab_Report_ID` = '$lab_id'");
        }
    
        $_SESSION["info_courses"] = "Thanks.  Your lab report assignment is submitted successfully.";
        header("Location: Course.php?url=".$url); 

    } else {
        echo "Error: <br>" . $con->error;
    }
}


// JOIN COURSE
if (!empty($_GET["JoinCourse"])) {
	   
    $id = $_GET["id"];
    $student_id = $_GET["std"];
    $joining = $_GET["joining"];
    $status = "Pending";
            
    if($joining == 0){ $status = "Joined";}
            
    $sql="INSERT INTO `course_students_table`(`Course_ID`, `Student_ID`,`Status`) VALUES ('$id','$student_id','$status')";
    
    if ($con->query($sql) === TRUE) {
  
        if($joining==0)
        {
            $_SESSION["info_Courses_student"] = "You enrolled in this course successfully.";
        }
        else {
            $_SESSION["info_Courses_student"] = "Course enrollment request was sent to the lecturer.";
        }
         
         
        header("Location: Courses.php"); 
       
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





#MARK LAB REPORT
  
if (!empty($_GET["savemarks"])) {
	   
    $id=$_GET["id"];
    $marks=$_GET["marks"];
    $total=$_GET["total"];
    $feedback=$_GET["feedback"];
    $header=$_GET["header"];
    $labid=$_GET["labid"];
    $status="Marked";
            
    if($marks>$total)
    {
        echo " Marks could not be greater than total";
        return;
    }
    $date=  date("Y-m-d H:i");
    $feedback="<br>@$date : ".$feedback;
        
    $sql="UPDATE `lab_report_submissions` SET `Marks`='$marks',`Status`='$status',"
        . ""
        . "Notes=if(Notes is null, ' ', concat(Notes, '$feedback'))"
        . ""
        . " WHERE Submission_ID=$id
              ";
    
    if ($con->query($sql) === TRUE) {
         
  
        $_SESSION["info_Marking"]="Lab Report Submission Marked";
        header("Location: Submissions.php?id=".$labid."&header=".$header."&total=".$total); 
  
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





#Update Report Visibility  
if (!empty($_GET["updatevisibility"])) {
	   
    $id=$_GET["id"];
    $marks=$_GET["marks"];
    $total=$_GET["total"];
    $status=$_GET["status"];
    $header=$_GET["header"];
    $labid=$_GET["labid"];
           
            
           
    $sql="UPDATE `lab_report_submissions` SET `Visibility`='$status' WHERE Submission_ID=$id
              ";
    
    if ($con->query($sql) === TRUE) {
        
        $_SESSION["info_Marking"]="Lab Report Visibility Updated";
        header("Location: Submissions.php?id=".$labid."&header=".$header."&total=".$total); 
  
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





#Remarking Request
  
if (!empty($_GET["remarking"])) {
	   
    $id=$_GET["id"];
    $url=$_GET["url"];
           
    $status= $_GET["status"];
    $details=$_GET["details"];
           
    $sql="UPDATE `lab_report_submissions` SET `Status`='Remarking',Remarking_Reason='$details' WHERE Submission_ID=$id
              ";
    
    if ($con->query($sql) === TRUE) {
         
  
        $_SESSION["info_ReMarking"]="Remarking Request Sent";
        header("Location: Course.php?url=".$url); 
  
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





#Create Group Request
  
if (!empty($_GET["creategroup"])) {
	   
    $student_id=$_GET["student_id"];
    $url=$_GET["url"];
    $id=$_GET["id"];
    $name= $_GET["name"];
            
           
    $sql="INSERT INTO `course_groups_table`(`Group_Name`, 
                  `Group_Leader`, `Course_id`) VALUES ('$name',$student_id,$id)";
 
      
            
    if ($con->query($sql) === TRUE) {
         
         
        $resultx1 = mysqli_query($con,"Select Max(Course_Group_id) as cnt from course_groups_table");
        while($row = mysqli_fetch_assoc($resultx1)) {$gid=$row['cnt'];} 
         
     
        $sql="INSERT INTO `course_group_members_table`( `Course_Group_id`, `Student_ID`, `Status`) 
                          VALUES ($gid,$student_id,'Created')";
        if ($con->query($sql) === TRUE) {
            $_SESSION["info_ReMarking"]="Course group Created";
            header("Location: Course.php?url=".$url); 
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
  
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}  





//---------------------------------------Invite Group Request and add a new member into the database------------------------------------
  
if (!empty($_GET["groupinvite"])) {
	   
    $student_id=$_GET["student_id"];
    $url=$_GET["url"];
    $courseid=$_GET["courseid"];
    $groupid=$_GET["groupid"];
               
    // if(($_SESSION['Group_Member4']=='0') or ($_SESSION['Group_Member3']=='0') or ($_SESSION['Group_Member2']=='0') or ($_SESSION['Group_Member']=='0')){
    $sql="INSERT INTO `course_group_members_table`( `Course_Group_id`, `Student_ID`, `Status`) 
                          VALUES ($groupid,$student_id,'Invited')";
    if ($con->query($sql) === TRUE) {

        $resultx1 = mysqli_query($con,"SELECT * FROM course_groups_table where Course_Group_id ='$groupid'");
   
        while($row = mysqli_fetch_assoc($resultx1)) 
        {
            $Group_Member=$row['Group_Member']; 
            $Group_Member4=$row['Group_Member4'];
            $Group_Member2=$row['Group_Member2'];
            $Group_Member3=$row['Group_Member3'];
            $_SESSION['Group_Member4']=$Group_Member4;
            $_SESSION['Group_Member3']=$Group_Member3;
            $_SESSION['Group_Member2']=$Group_Member2;
            $_SESSION['Group_Member']=$Group_Member;

            if($Group_Member=='0'){ 
                mysqli_query($con,"UPDATE `course_groups_table` SET `Group_Member` = ('" . $student_id . "') WHERE `course_groups_table`.`Course_Group_id` = '$groupid'");
                $_SESSION["info_ReMarking"]=$student_id . " was invited to the group";
                header("Location: Course.php?url=".$url);
            }elseif($Group_Member2=='0'){
                mysqli_query($con,"UPDATE `course_groups_table` SET `Group_Member2` = ('" . $student_id . "') WHERE `course_groups_table`.`Course_Group_id` = '$groupid'");
                $_SESSION["info_ReMarking"]=$student_id . " was invited to the group";
                header("Location: Course.php?url=".$url);
            }elseif($Group_Member3=='0'){
                mysqli_query($con,"UPDATE `course_groups_table` SET `Group_Member3` = ('" . $student_id . "') WHERE `course_groups_table`.`Course_Group_id` = '$groupid'");
                $_SESSION["info_ReMarking"]=$student_id . " was invited to the group";
                header("Location: Course.php?url=".$url);
            }elseif($Group_Member4=='0'){
                mysqli_query($con,"UPDATE `course_groups_table` SET `Group_Member4` = ('" . $student_id . "') WHERE `course_groups_table`.`Course_Group_id` = '$groupid'");
                $_SESSION["info_ReMarking"]=$student_id . " was invited to the group";
                header("Location: Course.php?url=".$url);
            } else {
                $_SESSION["info_ReMarking"]= " You cant add any more members";
                header("Location: Course.php?url=".$url);
     
            }
        }
    }
}





#Accept deny Group Invite
  
if (!empty($_GET["acceptinvite"])) {
	   
    $student_id=$_GET["student_id"];
    $url=$_GET["url"];
    $action=$_GET["action"];
    $groupid=$_GET["groupid"];
            
    if($action==1)
    {
        $sql="Update  `course_group_members_table` set Status='Joined' where  Course_Group_id =$groupid and student_id=$student_id 
                         ";  
    }
    else
    {
        $sql="Delete from  `course_group_members_table`  where  Course_Group_id =$groupid and student_id=$student_id 
                         "; 
    }
          
    if ($con->query($sql) === TRUE) {
        $_SESSION["info_ReMarking"]=" Group Invite Updated";
        header("Location: Course.php?url=".$url); 
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
  
}





#Extend Deadline
  
if (!empty($_GET["extenddeadline"])) {
	   
    $id=$_GET["id"];
    $date=$_GET["date"];
    $time=$_GET["time"];
    $type=$_GET["type"];
             
    $stdid=$_GET["stdid"];
    $reason =$_GET["reason"];
    $url =$_GET["url"];
    $deadline=$date." ".$time;
             
            
    if($type==1)
    {
        $sql="UPDATE `lab_reports_table` SET  `Deadline`='$deadline'  WHERE Lab_Report_ID=$id"; 
                          
    }
    else
    {
        $sql="INSERT INTO `extended_deadlines_table`(`Student_ID`, "
            . "`Lab_Report_ID`, `Extended_Deadline_Date`,"
            . " `ReasonsForExtension`) VALUES ($stdid,$id,'$deadline','$reason')";
                  
    }
                
          
    if ($con->query($sql) === TRUE) {
        
          
        $_SESSION["info_courses"]=" Lab Report Deadline extended successfully.";
        header("Location: Courses.php?course=".$url);
          
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
  
}





#IGNORE Remarking Request
  
if (!empty($_GET["ignoreremarking"])) {
	   
	
    $id=$_GET["id"];
    $total=$_GET["total"];
    $header=$_GET["header"];
           
    $subid=$_GET["subid"];
            
           
    $sql="UPDATE lab_report_submissions SET Status='Marked' WHERE Submission_ID=$subid";
    
             
              
    if ($con->query($sql) === TRUE) {
         
  
        
        $_SESSION["info_Marking"]="Remarking Request Ignored , Submission Updated to 'Marked' status";
        header("Location: Submissions.php?id=".$id."&header=".$header."&total=".$total); 

    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





#Assign TA
  
if (!empty($_GET["assignTA"])) {
	   
	
    $id=$_GET["id"];
    $ta=$_GET["ta"];
            
           
    $sql="INSERT INTO `course_ta`(`Course_ID`, `TA`) VALUES ($id,$ta)";
    
             
              
    if ($con->query($sql) === TRUE) {
         
  
        $_SESSION["info_Admin_Courses"]=$type." Course TA Assigned ";
        header("Location: Admin.php");
                                 
        

    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
            
}





//ACCEPT STUDNTS JOINING COURSSS
 
if (!empty($_GET["AcceptStudent"])) {
	   
    $id=$_GET["id"];
    $rs=$_GET["rs"];
         
    if($rs=="yes")
    {
        $sql="Update  course_students_table set Status='Joined' Where ID=$id";
    
                
    } else {
        $sql="Delete FROM  course_students_table Where ID=$id";
    }
           
    if ($con->query($sql) === TRUE) {
         
  
        if($rs=="yes")
        {
            $_SESSION["info_courses"]="Course Joining request Approved.";
        }
        else {
            $_SESSION["info_courses"]="Course Joining request Declined & Removed.";
        }
   
        header("Location: Courses.php"); 
   
    }
    else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

}





//action=passchange&uid=1&pass=1929
 
if (!empty($_GET["action"])) {
	   
    $action=$_GET["action"];
    $uid=$_GET["uid"];

    
    $pass = $_GET["pass"];
    $pass = password_hash($pass, PASSWORD_DEFAULT);


    $status=$_GET["status"];


    // validate uid
    if (intval($uid) < 0) {
        header("Location: index.php");
        return;       
    }

		 
    if($action=="passchange")
    {
        $sql= "UPDATE users_table set Password='$pass' where User_ID=$uid;";
        if ($con->query($sql) === TRUE) {
            error_reporting(0);
            echo "Password has been changed";
            // return;
            $_SESSION["infoChangePassword"]=$type." User password was changed successfully.";
            header("Location: index.php");
        } else {
            // echo "Error: " . $sql . "<br>" . $con->error;
            echo "Something really bad happened while changing password.  Contact lanhui at zjnu.edu.cn.  Thanks!";
        }
    }


    if($action=="statuschange")
    {
        $sql= "UPDATE users_table set Status='$status' where User_ID=$uid;";
        if ($con->query($sql) === TRUE) {
            $_SESSION["info_Admin_Users"]=$type." user  Status updated successfully ";
            header("Location: Admin.php");
        } else {
            // echo "Error: " . $sql . "<br>" . $con->error;
            echo "Something really bad happened while changing status.  Contact lanhui at zjnu.edu.cn.  Thanks!";	  
        }  	   
    }
}





// ############################### CREATE STUDENT USER ##################################
if (!empty($_POST["frm_createCourse"])) {
    $name=mysqli_real_escape_string($con,$_POST["name"]);
    $academic=mysqli_real_escape_string($con,$_POST["academic"]);
    $lecturer=mysqli_real_escape_string($con,$_POST["lecturer"]);
    $ta=mysqli_real_escape_string($con,$_POST["ta"]);
    $faculty=mysqli_real_escape_string($con,$_POST["faculty"]);
    $code=mysqli_real_escape_string($con,$_POST["code"]);
    $url=mysqli_real_escape_string($con,$_POST["url"]);  
    $verify=mysqli_real_escape_string($con,$_POST["verify"]);
    $who=mysqli_real_escape_string($con,$_POST["l"]);
                  
    if($url=="")
    {
        $url= $code.$academic;
    }
                       
                     
    if($ta=="")
    {
        $ta=0;
    }
          
    // check if email is taked
    //     $result = mysqli_query($con,
    //        "SELECT * FROM courses_table WHERE Course_Name='$name'");
    //   if(mysqli_num_rows($result)!=0)
    //    {
    //        $_SESSION["info_Admin_Courses"]="Course Name : ".$name." already used.";
    //        header("Location: Admin.php");        
    //    }
    //    
  
    $sql="INSERT INTO `courses_table`(`Course_Name`, `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`) 
            VALUES ('$name','$academic','$faculty','$lecturer','$ta','$code','$url','$verify')";
    
    
    if ($con->query($sql) === TRUE) {
        $_SESSION["info_Admin_Courses"]="Course portal was Created successfully.";
        if($who=="l")
        {
            header("Location: Courses.php");    
        } else
        {
            header("Location: Admin.php"); 
        }
         
    
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}





// Export grade
 
if (!empty($_GET["exportgrade"])) {
	   
    $lab=$_GET["lab"];
    $lab_name=$_GET["lab_name"];
    
       
       
    error_reporting(0);
       
    $select = "SELECT lab_reports_table.Title as 'LAB_Report', lab_reports_table.Marks as Lab_Marks,
 `Submission_Date`, lab_report_submissions.Student_id, users_table.Full_Name as Student_Name,  lab_report_submissions.Marks,`Notes`
FROM `lab_report_submissions`

INNER JOIN lab_reports_table on lab_reports_table.Lab_Report_ID=lab_report_submissions.Lab_Report_ID

INNER JOIN users_table on users_table.Student_ID=lab_report_submissions.Student_id


WHERE lab_report_submissions.Lab_Report_ID=$lab";


    $export  = mysqli_query($con,$select);
       
       
       
    $fields = mysqli_num_fields ( $export );

     
    for ( $i = 0; $i < $fields; $i++ )
    {
        $header .= mysqli_fetch_field_direct( $export , $i )->name. "\t";
    }


    while( $row = mysqli_fetch_row( $export ) )
    {
        $line = '';
        foreach( $row as $value )
        {                                            
            if ( ( !isset( $value ) ) || ( $value == "" ) )
            {
                $value = "\t";
            }
            else
            {
                $value = str_replace( '"' , '""' , $value );
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $data .= trim( $line ) . "\n";
    }
    $data = str_replace( "\r" , "" , $data );

    if ( $data == "" )
    {
        $data = "\n(0) Records Found!\n";                        
    }

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$lab_name Garde Sheet.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$data";
           
}
