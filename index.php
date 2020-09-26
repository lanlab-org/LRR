
    

<?php
$page='Home';
include 'Header.php';

session_start();

?>




<br><br><br>
<div class="row" style="width:80%;margin:auto;">

    <div class="col-md-4">
        <br><br>
        <img src="logo_text.png" style="width">
        <h1> Lab Report Repository System  </h1>
        <br><br>
    </div>
    
    
    
<div class="col-md-4 list-group">

    <br>
   
<h4 class="list-group-item active"> Sign in </h4>
<div class="list-group-item">

    <div class="panel-body">
<form method="post" action="Script.php" name="frm_login">
       <input type="hidden" name="frm_login" value="true"/>
Student ID / Email
<input type="text" name="user" placeholder="Email / Student Number" class="form-control">
 
  Password
<input type="password" class="form-control"  name="password" placeholder="password">
  <br> 
  <input type="submit" class="btn btn-primary" value="Login"><br> <a href="recover_password.php" style="font-weight:normal;color:orange">Reset my password</a>

<?php 

error_reporting(E_ALL);

if(isset($_SESSION['info_login'])) {
  echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['info_login'].'</div>';
  $_SESSION['info_login']=null;
}


// wrong pass
if(isset($_SESSION['wrong_pass'])) {
  echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['wrong_pass'].'</div>';
  $_SESSION['wrong_pass']=null;
}


if(isset($_SESSION['infoChangePassword'])) {
  echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['infoChangePassword'].'</div>';
  $_SESSION['infoChangePassword']=null;
}


?>
</form>

</div>
  
</div>
</div>
<div class="col-md-4 list-group">

    

    <br>
<h4 class="list-group-item active"> Student sign up </h4>
<div class="list-group-item">

<form method="post" action="Script.php" name="frm_signup_1">
    <input type="hidden" name="frm_signup_1" value="true"/>
    
    Student ID
<input type="text" name="student_id" placeholder="Entre your Student ID" class="form-control" required="">

Your Passport / National ID
  <input type="text" class="form-control"  name="passport" placeholder="(Optional)">
  <br>
  <input type="submit" name="frm_signup_1" class="btn btn-primary" value="Next"> <br> Click Next to set up password
<?php 

error_reporting(E_ALL);
if(isset($_SESSION['info_signup1'])) {
  echo  '<div class="alert alert-danger" role="alert">'.$_SESSION['info_signup1'].'</div>';
  $_SESSION['info_signup1']=null;
}

?>
  
</div>
</form>
</div>
</div>
</div>














<hr>

<div style="" id="footer">
LRRS was originally developed as a <a href="http://lanlab.org/course/2018f/se/homepage.html" style="color:white;">software engineering course project</a> by Mohamed Nor and Elmahdi Houzi.  Please submit your suggestions or bug reports to  lanhui _at_ zjnu.edu.cn.  Last updated on 18/04/2020 by Ashly. <a href="./homepage" style="color:white;">More information ...</a>
</div>

</body>

<style>
#footer{
 position:fixed;
 bottom:0;
 left:0;
background-color:#03417C;
color:#FFF;
text-align:center;
width:100%;
}
</style>
</html>

