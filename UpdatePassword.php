<?php
$page='Update Password';
include 'Header.php';
//getting the user id
$user_d=$_SESSION['user_id'];
?>

<style>
.col-md-4{
  border-right: 1px solid skyblue;
}
</style>

<br>

<div class="row" style="width:80%;margin:auto; text-align:left;">
  <div class="col-md-6">
    <br>  Course Portal &gt; Students <br>
    <br><br>
  </div>
  <div class="col-md-6"></div>
</div>

<div class="row" style="width:80%;margin:auto; text-align:left;">
  <div class="col-md-6">

   <h4>Update Password </h4><hr>

   <div class="container">
    <!-- Tab panes -->
    <div class="tab-content">
      <div id="home" class="container tab-pane active"><br>
        <form method="post" action="Script.php">
          <input type="hidden" class="form-control " name="frm_update_password" value="true" required=""/>
          <div class="form-group">
            <label for="old_pwd">Old password</label>
            <input type="password" class="form-control" id="old_pwd"  name="old_pwd"  placeholder="Enter old password" required="">
          </div>

          <div class="form-group">
            <label for="old_pwd">New password</label>
            <input type="password" class="form-control" id="new_pwd"  name="new_pwd"  placeholder="Enter new password" required="">
          </div>

          <div class="form-group">
            <label for="old_pwd">Confirm password</label>
            <input type="password" class="form-control" id="conf_pwd"  name="conf_pwd"  placeholder="Confirm new password" required="">
          </div>
          <div class="form-text text-danger">
            <?php 
                //displaying error information returned by the backend
            if(isset($_SESSION['info_update_password'])) 
            {
              echo  $_SESSION['info_update_password'];
            }
            ?>
          </div>
          <div class="form-group">
           <input type="submit" class="btn btn-primary" value="Submit"><br>
         </div>  
       </form>

       <hr>



     </div>



   </div>
   <!--  Tab panes -->
 </div>
</div>