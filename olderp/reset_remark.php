<?php
$dbhost = "localhost";
 $dbuser = "erpsoftw_erpuser";
 $dbpass = ')$^j.BXki2eJ';
 $db = "erpsoftw_erpdatabase";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ( date('m') <= 3 ) {
            $fy = date('y') - 1;
        }
        else {
            $fy = date('y');
        }

$sql = 'SELECT * FROM tblordermaster WHERE OrderStatus = "O" AND ChallanID IS null AND fy="'.$fy.'"';

$result = $conn->query($sql);

$sql2 = "UPDATE tblordermaster SET remark=NULL WHERE fy='$fy' AND OrderStatus='O' AND ChallanID IS null";
    $conn->query($sql2);

?>