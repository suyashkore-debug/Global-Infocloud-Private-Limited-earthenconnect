<style>
    table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }
</style>
<table class="table table-striped table-bordered" id="data_table" width="100%">
          <thead>
            <tr>
			   <th style="width:12%">Item ID</th>
                <th style="width:30%">Item Description</th>
                <th style="width:12%">Item Category</th>
                <th style="width:5%">Quantity</th>
                <th style="width:5%">Unit</th>
                <th style="width:20%">Comment</th>
                <th style="width:10%">Child BOM Number</th>
            </tr>
          </thead>
		    <tbody id="tbody">
			<?php
				$i = 1;
			foreach ($RMItemDetails as $row1) 
			{		
			 ?>  
                <tr>  
                    <td><?php echo $row1["item_id"];?></td>
				    <td><?php echo $row1["item_name"];?></td>
				    <td><?php echo $row1["ItemSubGroup"];?></td>
				    <td style="text-align:center;"><?php echo $row1["req_qty"];?></td>
				    <td style="text-align:center;"><?php echo $row1["unit"];?></td>
				    <td><?php echo $row1["Item_comments"];?></td>
				    <td><?php echo $row1["child_bom"];?></td>
                </tr>
			<?php
			$i++;
				  }
			?> 
				
            </tbody>
	</table>