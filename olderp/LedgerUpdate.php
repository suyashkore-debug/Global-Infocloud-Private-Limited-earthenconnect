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
$FY = "22";
$PlantID = '1';
$act = "ROUNDOFF";
$passedFrom = "SALESRTN";
$Debitsql = 'SELECT VoucherID, SUM(Amount) AS DebitAmt FROM `tblaccountledger` WHERE PassedFrom = "'.$passedFrom.'" AND FY ="'.$FY.'" AND PlantID = "'.$PlantID.'" AND TType = "D" AND AccountID != "ROUNDOFF" GROUP BY VoucherID ORDER BY VoucherID ASC';
$DebitResult = $conn->query($Debitsql);


while($DebitRow = $DebitResult->fetch_assoc()){
    $DebitRow1[] = $DebitRow;
}

$Creditsql = 'SELECT VoucherID, SUM(Amount) AS CreditAmt FROM `tblaccountledger` WHERE PassedFrom = "'.$passedFrom.'" AND FY ="'.$FY.'" AND PlantID = "'.$PlantID.'" AND TType = "C" AND AccountID != "ROUNDOFF" GROUP BY VoucherID ORDER BY VoucherID ASC';
$CreditResult = $conn->query($Creditsql);


while($CreditRow = $CreditResult->fetch_assoc()){
    $CreditRow1[] = $CreditRow;
}
$rs = 0;
foreach($DebitRow1 as $value) {
    
    foreach($CreditRow1 as $value1) {
        if($value["VoucherID"] == $value1["VoucherID"]){
            $roundoff = $value1["CreditAmt"] - $value["DebitAmt"];
            
            $roundoff1 = number_format($roundoff, 2, ".", "");
            $rs = $rs + $roundoff1;
            if($roundoff1 > 0 ){
                $ttype = "D";
            }else{
                $ttype = "C";
            }
            $aa = abs($roundoff1);
            /*echo $value["VoucherID"].' - '.$value1["CreditAmt"].' - '.$value["DebitAmt"].' = '.$roundoff1." - ".$ttype." - ".$aa;
            
            echo "<br>";*/
            $sql10 = "UPDATE tblaccountledger SET Amount ='".abs($roundoff1)."' , TType = '".$ttype."' WHERE PassedFrom = '".$passedFrom."' AND FY ='".$FY."' AND PlantID = '".$PlantID."' AND VoucherID = '".$value["VoucherID"]."' AND AccountID = '".$act."'";
            $conn->query($sql10);
        }
    }
}



?>
