<?php
$dbhost = "localhost";
 $dbuser = "erpsoftw_erpuser";
 $dbpass = ")$^j.BXki2eJ";
 $db = "erpsoftw_erpdatabase";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
//echo date('Y-m-d H:i:s') . ' => '. date('Y-m-d H:i:s', strtotime('+4 hours')) . '<br>'; //die;
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$cur_date = date('Y-m-d');
$sql = "SELECT * FROM tblcheck_in_out_app2 WHERE date='$cur_date' AND type_check='1'";
$result = $conn->query($sql);
date_default_timezone_set('Asia/Kolkata');
//echo date('Y-m-d H:i:s') . ' => '. date('Y-m-d H:i:s', strtotime('+4 hours')); die;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
     
    //echo "date: " . $row["date"]. " - staff_id: " . $row["staff_id"]. " " . $row["type_check"]. "<br>";
    $date = $row["date"];
    $staff_id = $row["staff_id"];
    
    $sql1 = "SELECT * FROM tbltravel_report WHERE date='$date' AND staff_id='$staff_id' ORDER BY id DESC LIMIT 1";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
    /*print_r($row1);
    echo "<br>";
    echo "<br>";*/
    
    $check_out = serialize(date('23:59:00'));
    $type_check = '2';
    /*$check_out_loc = $row['check_in_loc'];
    $check_in_loc_name = $row['check_in_loc_name'];*/
    $check_out_loc = serialize($row1['location_list']);
    $check_in_loc_name = serialize($row1['location_name_list']);
    /*echo $check_out;
    echo "<br>";
    echo $check_out_loc;
    echo "<br>";
    echo $check_in_loc_name;
    echo "<br>";
    echo "<br>";
    echo "<br>";*/
    $sql2 = "UPDATE tblcheck_in_out_app2 SET check_out='$check_out',type_check='$type_check',check_out_loc='$check_out_loc',check_out_loc_name='$check_in_loc_name' WHERE date='$date' AND staff_id='$staff_id'";
    $conn->query($sql2);
    
    $cdate = date('Y-m-d');
	$datetime = $cdate.' '.date('H:i:s');
   
    $sql3 = "INSERT INTO tblcheck_in_out (staff_id, date, type_check) VALUES ('$staff_id', '$datetime', '$type_check')";
    $conn->query($sql3);
  }
} else {
  echo "0 results";
}
$conn->close();
?>