<script>
    $('#TransID').on('focus',function(){
            $('#TransID').val('');
           $('#ChallanID').val('');
           $('#RouteID').val('');
           $('#RouteName').val('');
           $('#RouteKm').val('');
           $('#VehicleID').val('');
           $('#DriverID').val('');
           $('#DriverName').val('');
           $('#LoaderID').val('');
           $('#LoaderName').val('');
           $('#SalesManID').val('');
           $('#SalesManName').val('');
           $('#TotalCrates').val('');
           $('#TotalCases').val('');
           $('#ChallanValue').val('');
           $('#vehCapacity').val('');
       
     });
</script>
<script>
$(document).ready(function(){
    $("#TransID").focus();
    
    
    $("#TransID").dblclick(function(){
        
      $('#transfer-modal').modal('show');
      $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
    })
    
    $("#search_data").click(function(){  
        
        var from_date = $('#from_date1').val();
        var to_date = $('#to_date1').val();
     
     $.ajax({
      url:"<?php echo admin_url(); ?>einvoice/sales_details_model",
      dataType:"html",
      method:"POST",
      data:{from_date:from_date,to_date:to_date},
      beforeSend: function () {
        
        $('#searchh2').css('display','block');
        $('.table_sales_list tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_sales_list tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
         $('#table_sales_list tbody').html(data);
            $('.get_challan_id').on('click',function(){ 
                challan_id = $(this).attr("data-id");
                salesID = $(this).attr("data-name");
                
                myFunction(challan_id,salesID);
                myFunction_table_details(salesID);
                $('#transfer-modal').modal('hide');
                
            });
        }
     });
   });
   
    $('.get_challan_id').on('click',function(){ 
        challan_id = $(this).attr("data-id");
        salesID = $(this).attr("data-name");
        
        myFunction(challan_id,salesID);
        myFunction_table_details(salesID);
        $('#transfer-modal').modal('hide');
        
    });
            
   function myFunction(challan_id,salesID) { 
    $.ajax({
      url:"<?php echo admin_url(); ?>einvoice/unique_challan_details",
      dataType:"JSON",
      method:"POST",
      data:{challan_id:challan_id},
     
      success:function(data){
           $('#TransID').val(salesID);
           $('#TransID2').val(salesID);
           $('#ChallanID').val(data.ChallanID);
           $('#RouteID').val(data.RouteID);
           $('#RouteName').val(data.name);
           $('#RouteKm').val(data.KM);
           $('#VehicleID').val(data.VehicleID);
           $('#DriverID').val(data.DriverID);
           $('#DriverName').val(data.driver_fn+' '+data.driver_ln);
           $('#LoaderID').val(data.LoaderID);
           $('#LoaderName').val(data.loader_fn+' '+data.loader_ln);
           $('#SalesManID').val(data.SalesmanID);
           $('#SalesManName').val(data.Salesman_fn+' '+data.Salesman_ln);
           $('#TotalCrates').val(data.Crates);
           $('#TotalCases').val(data.Cases);
           $('#ChallanValue').val(data.ChallanAmt);
           $('#vehCapacity').val(data.VehicleCapacity);
           
        }
     });
    }
    
    function myFunction_table_details(salesID) {
    $.ajax({
      url:"<?php echo admin_url(); ?>einvoice/ganerate_json_table",
      dataType:"JSON",
      method:"POST",
      data:{salesID:salesID},
     
      success:function(response){
          if(response.html == "Selected party not GST registerd"){
              //alert('heello');
              $('input[name="create_json"]').attr('disabled','disabled');
               $('input[name="process_excel"]').attr('disabled','disabled');
          }else{
              $('input[name="create_json"]').removeAttr('disabled');
              $('input[name="process_excel"]').removeAttr('disabled');
          }
        //var decodedString = atob(response.Qrcode);
        if(!empty(response.Qrcode)){
                alert('Process excel already ganerated');
               $('input[name="create_json"]').attr('disabled','disabled');
               $('input[name="process_excel"]').attr('disabled','disabled');
          }
        $("#json_table_div").html(response.html);
        }
     });
    }
    
    /*$("#JSON_res_form").on('submit',(function(e) {  
        
        var SaleID = $('#TransID').val();
        var FILE = $('#res_file').val();
        //alert(SaleID);
     
     $.ajax({
      url:"<?php echo admin_url(); ?>einvoice/process_excel",
      dataType:"html",
      method:"POST",
      data: new FormData(this),
      beforeSend: function () {
        $('#searchh2').css('display','block');
      },
      complete: function () {
        $('#searchh2').css('display','none');
      },
      success:function(data){
         $('#showmsg').html(data);
            
        }
     });
   }));*/

});
</script>
<script>
   $(function(){
     appValidateForm($('form'),{TransID:'required'});
   });
</script>