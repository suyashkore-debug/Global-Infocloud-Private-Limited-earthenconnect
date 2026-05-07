<?php
$dbhost = "localhost";
 $dbuser = "erpsoftw_erpuser";
 $dbpass = ")$^j.BXki2eJ";
 $db = "erpsoftw_erpdatabase";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tblstaff_permissions ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if($row["days"] >0){
        echo "staff_id: " . $row["staff_id"]. " <br> feature: " . $row["feature"]. "<br>capability :" . $row["capability"]. "<br>days :" . $row["days"]. "<br>year :" . $row["year"]. "<br>plant_id :" . $row["plant_id"]. "<br><br>";
    $new_days = $row["days"] - 1;
    $feature = $row["feature"];
    $staff_id = $row["staff_id"];
    $capability = $row["capability"];
    $year = $row["year"];
    $plant_id = $row["plant_id"];
    
  /*$sql2 = "UPDATE tblstaff_permissions SET days='$new_days' WHERE feature='$feature' AND staff_id='$staff_id' AND capability='$capability' AND year='$year' AND plant_id='$plant_id'";
    $conn->query($sql2);*/
    }
    }
}
?>