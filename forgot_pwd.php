<?php
include "Connect.php";
$con = mysqli_connect("localhost","root","","lrr") //connect to the database server
or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, "lrr") //select the database
or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
$student_id = mysqli_real_escape_string($con, $_POST["student_id"]);
$email = mysqli_real_escape_string($con, $_POST["email"]);

$student_id = trim($student_id);
$email = trim($email);

if (!empty($student_id)) {
    if (!empty($email)) {
        $query = "select * from " . $table_name . " where student_id='Student_ID' and email='$Email'";
    } else {
        $query = "select * from " . $table_name . " where student_id='$Student_ID'";
    }

} else {
    $query = "select * from " . $table_name . " where email='$Email'";
}

$result = mysqli_query($con, $query) or die('error');
$row = mysqli_fetch_array($result);


if (mysqli_num_rows($result)) {

    $to = $row['email'];
    $subject = "Password Reset";
    $body = "Hi " . $row['Full_Name'] .
        "<br />Your account password has been reset: <a href=\"$url/reset.php"> Please Click to set a new password</a><br /> <br /> Thanks";
    $headers = "From:" . $from_address;
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to, $subject, $body, $headers);
    //echo $body;
    echo "Please Check your Email for resetting your password";
    //header('Content-type: application/json');
    // echo json_encode( array('result'=>1,'txt'=>"Password has been successfully sent to your Email Address"));
} else {
    //echo json_encode( array('result'=>0,'txt'=>"User account doesn't Exist"));
    echo "User account doesn't Exist";
}
