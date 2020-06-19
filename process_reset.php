<?php
session_start();
include "Connect.php";

$con = mysqli_connect("localhost","root","","lrr") //connect to the database server
or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, "lrr") //select the database
or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
//$student_id=mysql_real_escape_string($_POST["student_id"]);
$password = mysqli_real_escape_string($con, $_POST["password1"]);
$student_id = $_SESSION['student_id'];

//check if user is in reset process
$query = "select * from " . $table_name . " where student_id='$Student_ID'";
$result = mysqli_query($con, $query) or die('error');
$row = mysqli_fetch_array($result);
$email = $row['email'];

if (mysqli_num_rows($result)) {
    //pwd = crypt($password);
    if (phpversion() >= 5.5) {
        $pwd = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $pwd = crypt($password, '987654321'); //Hash used to suppress  PHP notice
    }

    $query = "update " . $table_name . "	 set password='$Password' , where student_id='$Student_ID'";
    $result = mysqli_query($con, $query) or die('error');

    //send email for the user with password

    $to = $email;
    $subject = "Password Reset";
    $body = "Hi student " . $Full_Name .
        "<br /> Your new password is updated successfully<br />";

    $headers = "From:" . $from_address;
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to, $subject, $body, $headers);
    echo "Password updated Successfully";
} else {
    echo "Cannot change password:User already active please login";
}
