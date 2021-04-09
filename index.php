<?php
  $page='Home';
  require 'Header.php';
  session_start();
?>

<?php
  // if the user has already logged in, then clicking the LRRS icon should not display the login page (i.e., index.php).
if (isset($_SESSION["user_fullname"])) {
    header("Location: Courses.php");
}
?>

<br><br><br>

<div class="row" style="width:85%;margin:auto;">
  <div class="col-md-4">
    <br><br>
    <img src="logo.png" style="width:40%; position:relative; right:-95px; top:1px;">
    <br><br>
    <div style="width:20%; position:relative; right:-90px; font-family: Poppins-Regular;">
    <h1>Lab Report Repository</h1>
    <br><br>
    </div>
    </div>
    <br>
    <div style = "position:relative; left:240px; top:-2px;">
    <h4 class="list-group-item active" style="font-weight:normal;font-family: Poppins-Regular;"> Sign in </h4>
    <div class="list-group-item">

    <div class="panel-body">

    <form method="post" action="Script.php" name="frm_login">
    <input type="hidden" name="frm_login" value="true"/>
    Student ID / Email
    <input type="text" name="user" placeholder="Email / Student Number" class="form-control" required="required" />
    <br>
    Password
    <input type="password" class="form-control"  name="password" placeholder="password" required="required" />
    <div class="text-center">
    <br><input type="submit" class="btn-primary" value="Login">
    </div>
    <br> <a href="recover_password.php" style="font-weight:normal;color:#2471A3font-family: Poppins-Regular;
    font-size: 17px;">Reset my password</a>
    <div class="text-center">
    <br><span class="txt1">Don't have an account?</span>
         <a class="txt2" href="signup.php" style="font-weight:normal">Sign Up</a>
        </a>
    </div>

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
      
    </div>
    </form>
    </div>
</div>
</div>
</div>

</div>


<div style="" id="footer">
    LRR was originally developed as a <a href="http://lanlab.org/course/2018f/se/homepage.html" style="color:white;">software engineering course project</a> by Mohamed Nor and Elmahdi Houzi.  Please submit your suggestions or bug reports to  lanhui _at_ zjnu.edu.cn.  Last updated on 18/04/2020 by Ashly. <a href="./homepage" style="color:white;">More information ...</a>
    </div>

</body>

<style>
    /*------------------------------------------------------------------
[ Login Button ]*/
.btn-primary {
            color: white;
            border-radius: 5px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            background: rgb(75, 184, 240);
            padding:5px 162px;
            font-family: Poppins-Regular;
            font-size: 23px;
            line-height: 1.5;
        }

#footer{
position:fixed;
bottom:0;
left:0;
background-color:#03417C;
color:#FFF;
text-align:center;
width:100%;
}
.txt1 {
  font-family: Poppins-Regular;
  font-size: 18px;
  line-height: 1.5;
  color: #666666;
}
.txt2 {
  font-family: Poppins-Regular;
  font-size: 19px;
  line-height: 1.5;
  color: #2471A3;
}

</style>

</html>
