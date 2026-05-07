<html>
    <form method="POST" action="">
        <lable>
            Account ID
        </lable>
        <input type="text" name="accountID" id="accountID">
        <input type="submit" name="submit" value="submit"> 
    </form>
</html>


<?php
if(isset($_POST['submit'])){
    $AccountID = $_POST['accountID'];
        $dbhost = "localhost";
         $dbuser = "erpsoftw_erpuser";
         $dbpass = '79)$^j.BXki2lJ';
         $db = "erpsoftw_erpdatabase";
        
        // Create connection
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
        
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
                if ( date('m') <= 3 ) {
                    $FY = date('y') - 1;
                }
                else {
                    $FY = date('y');
                }
            //$FY = 21;
        $month_array = array(4,5,6,7,8,9,10,11,12,1,2,3);
        
       
        $sql = 'SELECT tblclients.AccountID,tblaccountbalances.BAL1,tblaccountbalances.AccountID FROM tblclients 
        INNER JOIN tblaccountbalances ON tblaccountbalances.AccountID = tblclients.AccountID AND tblaccountbalances.PlantID = tblclients.PlantID 
        WHERE tblclients.PlantID = 3 AND tblaccountbalances.FY = "21" AND tblclients.SubActGroupID="60001004" AND active = 1
        ORDER BY tblclients.AccountID ASC';
        
        $result = $conn->query($sql);
        
        //while($row = $result->fetch_assoc()) {
            /*echo $row["AccountID"] .'-'.$row["BAL1"];
            echo "<br>";*/
            
            foreach ($month_array as $value) {
                
                if ( $value < 3 ) {
                        $fy = date('y') - 1;
                        $new_FY = '20'.$fy;
                    }else {
                        $fy = date('y');
                        $new_FY = '20'.$fy;
                    }
                //$new_FY = 2022;
                $last_date =  cal_days_in_month(CAL_GREGORIAN, $value, $new_FY); 
                $from_date = $new_FY.'-'.$value.'-01 00:00:00';
                $to_date = $new_FY.'-'.$value.'-'.$last_date.' 23:59:59';
                $cSQL = 'SELECT Sum(Amount) AS crAmt FROM `tblaccountledger` WHERE TType = "C" AND FY = "'.$FY.'" AND PlantID = 3 AND AccountID = "'.$AccountID.'" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
                $CR = $conn->query($cSQL);
                $CR_r = $CR->fetch_array();
                
                if($CR_r['crAmt']){
                    $CR = $CR_r['crAmt'];
                }else{
                    $CR = 0;
                }
                
                $dSQL = 'SELECT Sum(Amount) AS drAmt FROM `tblaccountledger` WHERE TType = "D" AND FY = "'.$FY.'" AND PlantID = 3 AND AccountID = "'.$AccountID.'" AND Transdate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
                $DR = $conn->query($dSQL);
                $DR_r = $DR->fetch_assoc();
                
                if($DR_r['drAmt']){
                    $DR = $DR_r['drAmt'];
                }else{
                    $DR = 0;
                }
                $bal = $DR - $CR;
                
                if($value == "1"){
                       $m = 11; 
                    }if($value == "2"){
                       $m = 12; 
                    }if($value == "3"){
                       $m = 13; 
                    }if($value == "4"){
                       $m = 2; 
                    }if($value == "5"){
                       $m = 3; 
                    }if($value == "6"){
                       $m = 4; 
                    }if($value == "7"){
                       $m = 5; 
                    }if($value == "8"){
                       $m = 6; 
                    }if($value == "9"){
                       $m = 7; 
                    }if($value == "10"){
                       $m = 8; 
                    }if($value == "11"){
                       $m = 9; 
                    }if($value == "12"){
                       $m = 10; 
                    }
                    $mm = "BAL".$m;
                $sql2 = "UPDATE tblaccountbalances SET ".$mm." ='".$bal."' WHERE FY ='$FY' AND PlantID = 3 AND AccountID = '".$AccountID."'";
                $conn->query($sql2);
            //}
            
        }
}else{
    echo 'Enter AccountID';
}


?>