<?php

//$servername = "localhost";
//$username = "root";
//$password = "";

// Create connection
$dsn = 'mysql:dbname=ngounou;host=127.0.0.1;port=3306';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);

	}
catch (PDOException $e) {
    echo 'connexion has failed : ' . $e->getMessage();
}
 $stmt = $dbh->query("SELECT * FROM course_students_table");
	while ($row = $stmt->fetch()) {
    $tablel_user[] = $row['Student_ID']."<br />\n";
}
session_start();
