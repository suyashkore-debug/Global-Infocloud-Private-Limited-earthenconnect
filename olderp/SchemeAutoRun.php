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
// Set the new timezone
date_default_timezone_set('Asia/Kolkata');

$cur_date = date('Y-m-d') ." 00:00:00";
$CurDT = date('Y-m-d H:i:s');
$sql = "SELECT * FROM tblschememaster WHERE StartDate <= '$cur_date' AND EndDate >= '$cur_date' AND Approve = 'Y'";
//echo "<pre>";
//echo $sql;
//$sr = 1;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $SchemeID = $row["SchemeID"];
        $SchemeFrom = $row["StartDate"];
        $SchemeTo = $row["EndDate"];
        
        $Sql2 = "SELECT * FROM tblschemedetails WHERE SchemeID = '$SchemeID'";
        $result2 = $conn->query($Sql2);
        $allItem = array();
        $Item_UnitDisc = array();
        
        $FromDate = date('Y-m-d')." 00:00:00";
        $ToDate = date('Y-m-d')." 23:59:59";
        $states = $row["StateID"];
        $PlantID = $row["PlantID"];
        $FY = $row["FY"];
        $client_type = $row["DistributorType"];
        $schemenarration = $row["narration"];
        $UserID = $row["UserID2"];
        while($row2 = $result2->fetch_assoc()) {
            if($row2['ActYN'] == "y" || $row2['ActYN'] == "Y"){
                $ItemID = "'".$row2['ItemID']."'";
                array_push($allItem,$ItemID);
                $record = array(
                    "ItemID"=>$row2['ItemID'],
                    "DiscAmt"=>$row2['DiscAmt'],
                    "SlabQtyCases"=>$row2['SlabQty'],
                    "CaseQty"=>$row2['CaseQty']
                );
                array_push($Item_UnitDisc,$record);
            }
        }
        if(empty($allItem)){
            
        }else{
            $AllItemString = implode(',', $allItem);
        $SQLTRAN = "SELECT tblhistory.AccountID,tblhistory.TransID
          FROM tblhistory
          INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
          INNER JOIN tblchallanmaster ON tblchallanmaster.ChallanID = tblhistory.BillID AND tblchallanmaster.PlantID = tblhistory.PlantID AND tblchallanmaster.FY = tblhistory.FY 
          WHERE tblhistory.PlantID = '$PlantID' AND tblhistory.FY = '$FY' AND tblhistory.BillID IS NOT NULL AND tblhistory.TType = 'O' AND tblhistory.TType2='Order' AND 
           tblhistory.TransDate2 >= '$SchemeFrom' AND tblhistory.TransDate2 <='$SchemeTo' AND tblhistory.IsSchemeYN = 'N' AND tblclients.state = '$states' AND tblclients.DistributorType = '$client_type' AND 
           tblhistory.ItemID IN($AllItemString) AND tblchallanmaster.Gatepassuserid IS NOT NULL GROUP BY tblhistory.AccountID,tblhistory.TransID";
        $resultTRANS = $conn->query($SQLTRAN);
        
        
        $Sql3 = "SELECT tblhistory.AccountID,tblclients.company,tblitems.description,tblitems.hsn_code,tblhistory.TransID,tblhistory.ItemID,tblhistory.cgst,tblhistory.sgst,tblhistory.igst,SUM(tblhistory.ChallanAmt) AS TaxableAmt,SUM(tblhistory.BilledQty) AS BilledQty
          FROM tblhistory
          INNER JOIN tblitems ON tblitems.item_code = tblhistory.ItemID AND tblitems.PlantID = tblhistory.PlantID 
          INNER JOIN tblclients ON tblclients.AccountID = tblhistory.AccountID AND tblclients.PlantID = tblhistory.PlantID 
          WHERE tblhistory.PlantID = '$PlantID' AND tblhistory.FY = '$FY' AND tblhistory.BillID IS NOT NULL AND tblhistory.TType = 'O' AND tblhistory.TType2='Order' AND 
           tblhistory.TransDate2 >= '$SchemeFrom' AND tblhistory.TransDate2 <='$SchemeTo' AND tblhistory.IsSchemeYN = 'N' AND tblclients.state = '$states' AND tblclients.DistributorType = '$client_type' AND 
           tblhistory.ItemID IN($AllItemString) GROUP BY tblhistory.AccountID,tblhistory.ItemID,tblhistory.TransID";
           $result3 = $conn->query($Sql3);
           
           
            while($row3 = $result3->fetch_assoc()){
                $ItemWiseDetails[] = $row3;
            }
        //echo $SQLTRAN;
        $ord11 = 1;
            while($rowTRANS = $resultTRANS->fetch_assoc()) {
                
                //$narration = "Scheme ".$SchemeID." from ".str_replace('-','/',$FromDate).' To '.str_replace('-','/',$ToDate).' for '.$schemenarration;
                $AccountID = $rowTRANS["AccountID"];
                if($PlantID == "1"){
                    $name = "next_credit_number_for_cspl";
                }elseif($PlantID == "2"){
                    $name = "next_credit_number_for_cff";
                }elseif($PlantID == "3"){
                    $name = "next_credit_number_for_cbu";
                }
                
                $SQLGetCD = "SELECT * FROM tbloptions WHERE FY ='$FY' AND name= '$name'";
                $resultCD = $conn->query($SQLGetCD);
                $rowCD = $resultCD->fetch_assoc();
                $CDID = 'CR'.$FY.$rowCD['value'];
                
                $narration = "CN against Sch. No. ".$SchemeID.", ".$rowTRANS["TransID"].', '.$schemenarration;
                
                $ShemeAmtSum = 0;
                $CGSTSUM = 0;
                $SGSTSUM = 0;
                $IGSTSUM = 0;
                $NetAmtSUM = 0;
                $GSTAmtSUM = 0;
                foreach($ItemWiseDetails as $value) {
                    $ord1 = 1;
                    if($rowTRANS["TransID"] == $value["TransID"]){
                        $ShemeAmt = 0;
                        $GSTPer = 0;
                        $GSTAmt = 0;
                        $NetAmt = 0;
                        $CGST = 0;
                        $SGST = 0;
                        $IGST = 0;
                        $CGSTPer = 0;
                        $SGSTPer = 0;
                        $IGSTPer = 0;
                        $Match = 0;
                        foreach($Item_UnitDisc as $value1){
                            $SlabQty = $value1["SlabQtyCases"] * $value1["CaseQty"];
                            if($value1["ItemID"] == $value["ItemID"] && $SlabQty <= $value["BilledQty"]){
                                $Match = 1;
                                $ItemID = $value["ItemID"];
                                $TransID = $value["TransID"];
                                $hsn_code = $value["hsn_code"];
                                $GSTPer = $value["cgst"] + $value["sgst"] + $value["igst"];
                                $ShemeAmt = $value1["DiscAmt"] * $value["BilledQty"];
                                $NetAmt = ($ShemeAmt +($ShemeAmt*$GSTPer) / 100);
                                /*$NetAmt = $value1["DiscAmt"] * $value["BilledQty"];
                                $ShemeAmt = ($NetAmt / (100 + $GSTPer)) * 100;*/
                                $GSTAmt = $NetAmt - $ShemeAmt;
                                
                                $ShemeAmtSum +=$ShemeAmt;
                                $NetAmtSUM += $NetAmt;
                                $GSTAmtSUM += $GSTAmt;
                            }
                        }
                        
                        if($states == "UP"){
                            $CGST = $GSTAmt/2;
                            $SGST = $GSTAmt/2;
                            $CGSTPer = $GSTPer/2;
                            $SGSTPer = $GSTPer/2;
                            $CGSTSUM += $CGST;
                            $SGSTSUM += $SGST;
                        }else{
                            $IGST = $GSTAmt;
                            $IGSTPer = $GSTPer;
                            $IGSTSUM += $IGST;
                        }
                        if($Match > 0){
                            $sql42 = "INSERT INTO tblcdnotehistory (fy, plantid, billno,transdate,TransID,ttype,AccountID,itemid,hsncode,rate,gst,cgst,cgstamt,sgst,sgstamt,igstamt,igst,amount,ordinalno) 
                            VALUES ('$FY', '$PlantID', '$CDID','$CurDT','$TransID','C','$AccountID','$ItemID','$hsn_code','$ShemeAmt','$GSTPer','$CGSTPer','$CGST','$SGSTPer','$SGST','$IGST','$IGSTPer','$NetAmt','1')";
                            $conn->query($sql42);
                            
                            $SchemeUpdateSQL = "UPDATE tblhistory SET IsSchemeYN ='Y' WHERE FY ='$FY' AND PlantID = '$PlantID' AND AccountID = '$AccountID' AND TransID = '$TransID' AND ItemID = '$ItemID'";
                            $conn->query($SchemeUpdateSQL);
                    
                            /*echo $sql42;
                            echo "<br>";*/
                            $ord1++;
                        }
                    }
                }
                if($ShemeAmtSum > 0){
                    $sql41 = "INSERT INTO tblcdnote (FY, IsAutopost,SchemeID, plantid, BT,Billno,Transdate,AccountID,SaleAmt,cgstamt,sgstamt,igstamt,BillAmt,RndAmt,passedfrom,Userid,narration) 
                        VALUES ('$FY', 'Y','$SchemeID','$PlantID', 'C','$CDID','$CurDT','$AccountID','$ShemeAmtSum','$CGSTSUM','$SGSTSUM','$IGSTSUM','$NetAmtSUM','$NetAmtSUM','SALESRECEIPT','$UserID','$narration')";
                        $conn->query($sql41);
                        /*echo $sql41;
                        echo "<br>";*/
                        
                        $sql4 = "INSERT INTO tblaccountledger (PlantID, FY, Transdate,VoucherID,TransDate2,AccountID,TType,Amount,Narration,PassedFrom,OrdinalNo,UserID) 
                        VALUES ('$PlantID', '$FY', '$CurDT','$CDID','$CurDT','$AccountID','C','$NetAmtSUM','$narration','CDNOTE','$ord11','$UserID')";
                        $conn->query($sql4);
                       /*echo $sql4;
                        echo "<br>";*/
                        
                        $sql5 = "INSERT INTO tblaccountledger (PlantID, FY, Transdate,VoucherID,TransDate2,AccountID,TType,Amount,Narration,PassedFrom,OrdinalNo,UserID) 
                        VALUES ('$PlantID', '$FY', '$CurDT','$CDID','$CurDT','CLAIM','D','$ShemeAmtSum','$narration','CDNOTE','$ord11','$UserID')";
                        $conn->query($sql5);
                        /*echo $sql5;
                        echo "<br>";
                        echo "<br>";echo "<br>";*/
                        
                        if($GSTAmtSUM > 0){
                            if($states == "UP"){
                                $sql51 = "INSERT INTO tblaccountledger (PlantID, FY, Transdate,VoucherID,TransDate2,AccountID,TType,Amount,Narration,PassedFrom,OrdinalNo,UserID) 
                                VALUES ('$PlantID', '$FY', '$CurDT','$CDID','$CurDT','CGST','D','$CGSTSUM','$narration','CDNOTE','$ord11','$UserID')";
                                $conn->query($sql51);
                                /*echo $sql51;
                        echo "<br>";*/
                                
                                $sql52 = "INSERT INTO tblaccountledger (PlantID, FY, Transdate,VoucherID,TransDate2,AccountID,TType,Amount,Narration,PassedFrom,OrdinalNo,UserID) 
                                VALUES ('$PlantID', '$FY', '$CurDT','$CDID','$CurDT','SGST','D','$SGSTSUM','$narration','CDNOTE','$ord11','$UserID')";
                                $conn->query($sql52);
                                /*echo $sql52;
                        echo "<br>";*/
                            }else{
                                $sql53 = "INSERT INTO tblaccountledger (PlantID, FY, Transdate,VoucherID,TransDate2,AccountID,TType,Amount,Narration,PassedFrom,OrdinalNo,UserID) 
                                VALUES ('$PlantID', '$FY', '$CurDT','$CDID','$CurDT','IGST','D','$IGSTSUM','$narration','CDNOTE','$ord11','$UserID')";
                                $conn->query($sql53);
                                /*echo $sql53;
                        echo "<br>";*/
                            }
                        }
                        /*echo "<br>";echo "<br>";*/
                    // CD Note number increment
                    $NextCD = $rowCD['value'] + 1;
                    $CDUpdateSQL = "UPDATE tbloptions SET value ='$NextCD' WHERE FY ='$FY' AND name = '$name'";
                    $conn->query($CDUpdateSQL);
                }
            }
        }
    }
}
$conn->close();
?>