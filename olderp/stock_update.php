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
        if ( date('m') < 3 ) {
            $FY = date('y') - 1;
        }
        else {
            $FY = date('y');
        }

$sql = 'SELECT tblstockmaster.ItemID,tblstockmaster.PlantID,tblstockmaster.FY FROM tblstockmaster
 WHERE PlantID = 3 AND FY = "'.$FY.'"
ORDER BY tblstockmaster.ItemID ASC';

$result = $conn->query($sql);


$sql1 = 'SELECT tblhistory.ItemID,tblhistory.TType,tblhistory.TType2,SUM(tblhistory.BilledQty) AS SumQty FROM tblhistory
 WHERE PlantID = 3 AND FY = "'.$FY.'"
 GROUP BY tblhistory.ItemID,tblhistory.TType,tblhistory.TType2
ORDER BY tblhistory.ItemID ASC';

$result1 = $conn->query($sql1);
/*while($row2 = $result1->fetch_assoc()) {
    $sql3 = "UPDATE tblstockmaster SET IQty ='0.00', PQty ='0.00', PRQty ='0.00', SQty ='0.00', PRDQty ='0.00', SRDQty ='0.00', SRQty ='0.00', ADJQTY ='0.00'
    WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row2["ItemID"]."'";
    $conn->query($sql3);
}*/

while($row1 = $result1->fetch_assoc()) {
    
    $ADJ = 0;
    // Issue
    if($row1["TType"] == "A" && $row1["TType2"] == "Issue"){
        $sql4 = "UPDATE tblstockmaster SET IQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql4);
    }
    
    // Purchase
    if($row1["TType"] == "P" && $row1["TType2"] == "Purchase"){
        $sql5 = "UPDATE tblstockmaster SET PQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql5);
    }
    
    // Purchase Return
    if($row1["TType"] == "N" && $row1["TType2"] == "PurchaseReturn"){
        $sql6 = "UPDATE tblstockmaster SET PRQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql6);
    }
    
    // Order
    if($row1["TType"] == "O" && $row1["TType2"] == "Order"){
        $sql7 = "UPDATE tblstockmaster SET SQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql7);
    }
    // Production
    if($row1["TType"] == "B" && $row1["TType2"] == "Production"){
        $sql8 = "UPDATE tblstockmaster SET PRDQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql8);
    }
    
    // Sale Return Damage
    if($row1["TType"] == "R" && $row1["TType2"] == "Damage"){
        $sql9 = "UPDATE tblstockmaster SET SRDQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql9);
    }
    
    // Sale Return Fresh
    if($row1["TType"] == "R" && $row1["TType2"] == "Fresh"){
        $sql10 = "UPDATE tblstockmaster SET SRQty ='".$row1["SumQty"]."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql10);
    }
    
    // ADJ - Free Distribution
    if($row1["TType"] == "X" && $row1["TType2"] == "Free Distribution"){
        $ADJ = $ADJ + $row1["SumQty"];
    }
    
    // ADJ - Stock Damaged
    if($row1["TType"] == "X" && $row1["TType2"] == "Stock Damaged"){
        $ADJ = $ADJ + $row1["SumQty"];
    }
    // ADJ - Stock Adjustment
    if($row1["TType"] == "X" && $row1["TType2"] == "Stock Adjustment"){
        $ADJ = $ADJ + $row1["SumQty"];
    }
    
    // ADJ - Promotional Activity
    if($row1["TType"] == "X" && $row1["TType2"] == "Promotional Activity"){
        $ADJ = $ADJ + $row1["SumQty"];
    }
    if($ADJ > 0 ){
        $sql11 = "UPDATE tblstockmaster SET ADJQTY ='".$ADJ."' WHERE FY ='$FY' AND PlantID = 3 AND ItemID = '".$row1["ItemID"]."'";
            $conn->query($sql11);
    }

}
die;


?>