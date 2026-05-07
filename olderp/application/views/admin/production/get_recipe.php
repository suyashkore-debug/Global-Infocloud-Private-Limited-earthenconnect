
<table class="table table-striped table-bordered" id="data_table" width="100%">
          <thead>
            <tr>
			   <!--<th>Id</th>-->
               <th>Item Code</th>
               <th>Item Name</th>
               <th>StockQty</th>
               <th>Req. Qty</th>
               <th>Measured In</th>
            </tr>
          </thead>		  
 	
		    <tbody id="tbody">
			<?php
			
				$i = 1;
			foreach ($result2 as $row1) {		
			 $qt=$row1['req_qty'] * $batch_qty; 
			        $PQty = 0;
                    $PRQty = 0;
                    $IQty = 0;
                    $PRDQty = 0;
                    $SQty = 0;
                    $SRTQty = 0;
                    $AQty = 0;
                    $GOQty = 0;
                    $GIQty = 0;
                    
                    foreach ($ItemStocks as $stock) {
                        if($stock['ItemID']==$row1['item_id']){
                            if($stock['TType'] == 'P'){
                                $PQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'N'){
                                $PRQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'A'){
                                $IQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'B'){
                                $PRDQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
                                $SQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
                                $SRTQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
                                $AQty += $stock['BilledQty'];
                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
                                $GIQty = $stock['BilledQty'];
                            }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
                                $GOQty = $stock['BilledQty'];
                            }
                        }
                    }
                    $stockQty = $row1['OQty'] + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty- $GOQty + $GIQty;
                    if($stockQty < $qt){
                        $style = 'border-color: red;color:red';
                    }else{
                        $style = '';
                    }
			 ?>  
               <tr>  
			   <input type="hidden" name="count_of_rec" value="<?php echo count($result2); ?>">
			   
               <input type="hidden" name="rec_detid[]<?php echo $i;?>" value="<?php echo $row1['id']; ?>" class="form-control" style="width: 60px;border-radius: 2px;height: 30px;" readonly>
			   
               <td><input type="text" name="item_id[]<?php echo $i;?>" value="<?php echo $row1['item_id']; ?>" class="form-control" style="width: 60px;border-radius: 2px;height: 30px;" readonly></td>
								
               <td><input type="text" name="item_name[]<?php echo $i;?>" value="<?php echo $row1['item_name'];?>" class="form-control" style="width: 300px;border-radius: 2px;height: 30px;" readonly></td>
				
				<td>
				    <input type="text" name="pro_stock_qty[]<?php echo $i;?>" value="<?php echo number_format($stockQty, 2, '.', ''); ?>" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;" readonly>
				    <input type="hidden" name="Oqty[]<?php echo $i;?>" value="<?php echo $row1['OQty']; ?>" readonly>
				</td>
				
               <td><input type="text" name="pro_req_qty[]<?php echo $i;?>" value="<?php echo $qt; ?>" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;<?php echo $style; ?>" readonly>
			   <input type="hidden" name="req_qty[]<?php echo $i;?>" value="<?php echo $row1['req_qty']; ?>" readonly>
			   </td> 
								 
               <td><input type="text" name="unit[]<?php echo $i;?>" value="<?php echo $row1['unit']; ?>" class="form-control" style="width: 260px;border-radius: 2px;height: 30px;" readonly></td>
                                
            </tr>
			<?php
			$i++;
				  }
			?> 
				
            </tbody>
	</table>