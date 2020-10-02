<?php
$csv = array_map('str_getcsv', file('./../../lrr_submission/KeepItSafe.txt'));
$mysql_username = $csv[0][0];
$mysql_password = $csv[0][1];
?>
