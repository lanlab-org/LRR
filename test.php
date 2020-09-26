<?php



error_reporting(E_ALL);
ini_set('display_errors', 'on');





echo phpversion();

$hashed_password1 = hash('sha512', '123a');
$hashed_password2 = hash('sha512', '123a');
echo "Hash1=".$hashed_password1;
echo "<hr>Hash2=".$hashed_password2;




$con=mysqli_connect("localhost","username","password","lrr");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
else
{
echo "Connected ";
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$query  = "SELECT * from users_table;";
$result = mysqli_query($con, $query);


if (mysqli_num_rows($result) > 0) {

    echo "<table>";

    while ($row = mysqli_fetch_assoc($result)) {

$id=$row["User_ID"];
$pass=$row["Password"];
$hash_pass=hash('sha512', $pass);
$inner_query  = "update users_table set HashPassword='$hash_pass' where User_ID=$id;";
if ($con->query($inner_query) === TRUE) { echo " User # $id updated<br>";  }

        //echo "<tr>";
        //echo "<td>{$row['Password']}</td><td>{$row['Email']}</td>";
        //echo "</tr>";

    }

    echo "</table>";


}


