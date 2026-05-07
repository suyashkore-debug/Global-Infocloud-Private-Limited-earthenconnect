<?php
$servername = "localhost";
$username = "domaingl_er_puers1";
$password = "rgl_?UoB%=9u!W";
$dbname = "domaingl_erP_Crazy1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//$sql = "SELECT * FROM tblcheck_in_out_app";


$sql2 = "SELECT tblcheck_in_out_app2.check_in,
tblcheck_in_out_app2.check_in_loc, 
tblcheck_in_out_app2.check_in_loc_name, 
tblcheck_in_out_app2.check_out,
tblcheck_in_out_app2.check_out_loc, 
tblcheck_in_out_app2.check_out_loc_name,
tblstaffVisitLoc2.location_list,
tblstaffVisitLoc2.location_name_list,
tblstaffVisitLoc2.location_trav,
tblstaffVisitLoc2.travDate,
tblstaffVisitLoc2.staff_id
FROM tblcheck_in_out_app2
INNER JOIN tblstaffVisitLoc2
ON tblcheck_in_out_app2.staff_id=tblstaffVisitLoc2.staff_id AND tblcheck_in_out_app2.date=tblstaffVisitLoc2.travDate";
$result = $conn->query($sql2);


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
       
            echo "<br>". $row["staff_id"]. " = ". $row["check_in"]. " = " . $row["check_in_loc"] . " = " . $row["check_in_loc_name"] . " = " . $row["check_out"] . " = " . $row["check_out_loc"] . " = " . $row["check_out_loc_name"] . " = " . $row["location_list"] . " = " . $row["location_name_list"] . " = " . $row["location_trav"] . " = " . $row["travDate"] . "<br>";
            
    }
} else {
    echo "0 results";
}


?>