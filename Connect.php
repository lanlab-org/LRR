<?php
session_start();



$con=mysqli_connect("localhost","username","password","lrr");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
else
{
echo "Connected";
}

