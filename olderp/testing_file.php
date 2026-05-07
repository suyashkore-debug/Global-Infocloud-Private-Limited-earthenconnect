<?php
$dbhost = "localhost";
 $dbuser = "domaingl_er_puers1";
 $dbpass = "rgl_?UoB%=9u!W";
 $db = "domaingl_erP_Crazy1";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
//echo date('Y-m-d H:i:s') . ' => '. date('Y-m-d H:i:s', strtotime('+4 hours')) . '<br>'; //die;
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//$cur_date = date('Y-m-d');
$cur_date = "2021-06-30";
$sql = "SELECT * FROM tblcheck_in_out_app2 WHERE date='$cur_date'";
$result = $conn->query($sql);
date_default_timezone_set('Asia/Kolkata');
//echo date('Y-m-d H:i:s') . ' => '. date('Y-m-d H:i:s', strtotime('+4 hours')); die;
if ($result->num_rows > 0) {
  // output data of each row
  $i = 1;
  while($row = $result->fetch_assoc()) {
      
    $locc_list = unserialize($row['location_list']);
    
    echo "sr no :". $i ."- date: " . $row["date"]. " - staff_id: " . $row["staff_id"]. " - Loc. List : " . $locc_list. "<br>";
    $i++;
    
    /*$date = $row["date"];
    $staff_id = $row["staff_id"];
    //$check_out = serialize(date('H:i:s'));
    $check_out = serialize(date('23:59:00'));
    $type_check = '2';
    $check_out_loc = $row['check_in_loc'];
    $check_in_loc_name = $row['check_in_loc_name'];
    
    $sql2 = "UPDATE tblcheck_in_out_app2 SET check_out='$check_out',type_check='$type_check',check_out_loc='$check_out_loc',check_out_loc_name='$check_in_loc_name' WHERE date='$date' AND staff_id='$staff_id'";
    $conn->query($sql2);
    
    $cdate = date('Y-m-d');
	$datetime = $cdate.' '.date('H:i:s');
   
    $sql3 = "INSERT INTO tblcheck_in_out (staff_id, date, type_check) VALUES ('$staff_id', '$datetime', '$type_check')";
    $conn->query($sql3);*/
  }
} else {
  echo "0 results";
}
$conn->close();
?>