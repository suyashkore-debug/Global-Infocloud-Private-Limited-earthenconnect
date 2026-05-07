<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .load_data          { overflow: auto;max-height: 55vh;width:70%;position:relative;top: 0px; }
.load_data thead th { position: sticky; top: 0; z-index: 1; }
.load_data tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              <div class="_buttons">
                
                
                <div class="row">
                   <?php
                        $fy = $this->session->userdata('finacial_year');
                        $selected_company = $this->session->userdata('root_company');
                        $fy_new  = $fy + 1;
                        $lastdate_date = '20'.$fy_new.'-03-31';
                        $firstdate_date = '20'.$fy_new.'-04-01';
                        $curr_date = date('Y-m-d');
                        $curr_date_new    = new DateTime($curr_date);
                        $last_date_yr = new DateTime($lastdate_date);
                        if($last_date_yr < $curr_date_new){
                            $to_date = '31/03/20'.$fy_new;
                            $from_date = '01/03/20'.$fy_new;
                        }else{
                            $from_date = "01/".date('m')."/".date('Y');
                            $to_date = date('d/m/Y');
                        }
                ?>
                        <div class="col-md-2">
                            <input type="hidden" id="PlantID" name="PlantID"  value="<?php echo $selected_company; ?>" >
                            <div class="form-group" app-field-wrapper="from_date">
                                <label for="from_date" class="control-label from_date_text">From Date</label>
                                <div class="input-group date">
                                    <input type="text" id="from_date" name="from_date" class="form-control datepicker" value="<?php echo $from_date; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                       <i class="fa fa-calendar calendar-icon"></i>
                                     </div>
                                </div>
                             </div>
                           
                        </div>
                        <div class="col-md-2">
                            <?php  //$to_date = date('d/m/Y');?>
                            <div class="form-group" app-field-wrapper="to_date">
                                <label for="to_date" class="control-label to_date_text">To Date</label>
                                <div class="input-group date">
                                    <input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo $to_date; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar calendar-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        </div>
                         <div class="row">
                        <div class="custom_button" style="padding-top: 20px;">
                        <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
                    </div>
                
                    <div class="custom_button">
                        <button class="btn btn-default pull-left mleft5 " href="javascript:void(0);"    onclick="printPage();" style="font-size:12px;">Print</button>
                    </div>
                    <div class="custom_button">
                        &nbsp;&nbsp;<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="GSTR1_report" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
                                <!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Print</span></a>-->
                    </div>
                       
                    </div> 
                 
                
        
                <!--<div class="col-md-10">
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>  -->  
             </div>
              <div class="clearfix"></div>
            
        
            <div class="fixTableHead load_data">
              
            </div>
            <span id="searchh" style="display:none;"> please wait data Loading...</span>
            <span id="searchh2" style="display:none;"> please wait exporting data...</span>
            </div>
        <div class="panel-body mtop10">
            <div class="row col-md-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#B2B" class="B2B">B2B</a></li>
                    <li><a data-toggle="tab" href="#B2CL" class="B2CL">B2CL</a></li>
                    <li><a data-toggle="tab" href="#B2CS" class="B2CS" >B2CS</a></li>
                    <li><a data-toggle="tab" href="#CDNR" class="CDNR" >CDNR</a></li>
                    <li><a data-toggle="tab" href="#CDNUR" class="CDNUR" >CDNUR</a></li>
                    <li><a data-toggle="tab" href="#EXP" class="EXP" >EXP</a></li>
                    <li><a data-toggle="tab" href="#AT" class="AT" >AT</a></li>
                    <li><a data-toggle="tab" href="#ADJ" class="ADJ" >ADJ</a></li>
                    <li><a data-toggle="tab" href="#test2" class="test2" >EXEMP</a></li>
                    <li><a data-toggle="tab" href="#HSN" class="HSN" >HSN</a></li>
                    <li><a data-toggle="tab" href="#test" class="test" >DOCS</a></li>
                </ul>
                
                <div class="" id="test" >
                    <div class="row">
                        <div class="col-md-8">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered test_tbl" id="test_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>NatureofDocument</th>
                                        <th>SrNoFrom</th>
                                        <th>SrNoTo</th>
                                        <th>TotalNumber</th>
                                        <th>Cancelled</th>
                                    </tr>
                                </thead>
                                <tbody id="DOCS_tbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="B2B" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered B2B_tbl" id="B2B_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>GSTIN</th>
                                        <th>InvNumber</th>
                                        <th>InvDate</th>
                                        <th>InvValue</th>
                                        <th>PlaceOfSupply</th>
                                        <th>RevCharge</th>
                                        <th>InvoiceType</th>
                                        <th>E-comGSTIN</th>
                                        <th>GSTRate%</th>
                                        <th>TaxableValue</th>
                                        <th>CessAmount</th>
                                    </tr>
                                </thead>
                                <tbody id="B2B_tbltbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="B2CL" >
                    <div class="row">
                        <div class="col-md-8">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered B2CL_tbl" id="B2CL_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo.</th>
                                        <th>InvNumber</th>
                                        <th>InvDate</th>
                                        <th>InvAmt</th>
                                        <th>PlaceOfSupply</th>
                                        <th>Rate</th>
                                        <th>TaxableValue</th>
                                        <th>CessAmount</th>
                                        <th>E-comGSTIN</th>
                                    </tr>
                                </thead>
                                <tbody id="B2CL_tbltbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="" id="B2CS" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered B2CS_tbl" id="B2CS_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo.</th>
                                        <th>InvType</th>
                                        <th>PlaceOfSupply</th>
                                        <th>Rate</th>
                                        <th>TaxableValue</th>
                                        <th>CessAmount</th>
                                        <th>E-comGSTIN</th>
                                    </tr>
                                </thead>
                                <tbody id="B2CS_tbltbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="CDNR" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered CDNR_tbl" id="CDNR_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo.</th>
                                        <th>GSTINUINofRecipient</th>
                                        <th>InvoiceAdvanceReceiptNumber</th>
                                        <th>InvoiceAdvanceReceiptDate</th>
                                        <th>NoteRefundVoucherNumber</th>
                                        <th>NoteRefundVoucherDate</th>
                                        <th>DocumentType</th>
                                        <th>ReasonForIssuingdicument</th>
                                        <th>PlaceOfSupply</th>
                                        <th>NoteRefundVoucherValue</th>
                                        <th>Rate</th>
                                        <th>TaxableValue</th>
                                        <th>CessAmt</th>
                                        <th>PreGst</th>
                                        <th>ReceiverName</th>
                                    </tr>
                                </thead>
                                <tbody id="CDNR_tbltbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="CDNUR" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered CDNUR_tbl" id="CDNUR_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo.</th>
                                        <th>GSTINUINofRecipient</th>
                                        <th>InvoiceAdvanceReceiptNumber</th>
                                        <th>InvoiceAdvanceReceiptDate</th>
                                        <th>NoteRefundVoucherNumber</th>
                                        <th>NoteRefundVoucherDate</th>
                                        <th>DocumentType</th>
                                        <th>ReasonForIssuingdicument</th>
                                        <th>PlaceOfSupply</th>
                                        <th>NoteRefundVoucherValue</th>
                                        <th>Rate</th>
                                        <th>TaxableValue</th>
                                        <th>CessAmt</th>
                                        <th>PreGst</th>
                                        <th>ReceiverName</th>
                                    </tr>
                                </thead>
                                <tbody id="CDNUR_tbltbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="EXP" >
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                </div>
                
                <div class="" id="AT" >
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                </div>
                
                <div class="" id="ADJ" >
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                </div>
                
                <div class="" id="test2" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered test2_tbl" id="test2_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>Description</th>
                                        <th>NilRatedSupplies</th>
                                        <th>Exempted</th>
                                        <th>NonGSTSupplies</th>
                                    </tr>
                                </thead>
                                <tbody id="EXEMP_tbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="" id="HSN" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fixed_header1" id="fixed_header1">
                            <table class="table table-striped table-bordered HSN_tbl" id="HSN_tbl" width="100%">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>HSN</th>
                                        <th>Description</th>
                                        <th>UQC</th>
                                        <th>TotalQty</th>
                                        <th>TotalValue</th>
                                        <th>TaxableValue</th>
                                        <th>IntegratedTax</th>
                                        <th>CentralTax</th>
                                        <th>State/UTTax</th>
                                        <th>CessAmount</th>
                                        <th>GST%</th>
                                    </tr>
                                </thead>
                                <tbody id="HSN_tbody">
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
            </div>
        </div>
    
              
          </div>
</div>
</div>
</div>
</div>
</div>

<style>
 /* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
th     { background: #50607b;color: #fff !important; }

.fixed_header1 { overflow: auto;max-height: 50vh;width:100%;position:relative;top: 0px; }
.fixed_header1 thead th { position: sticky; top: 0; z-index: 1; }
.fixed_header1 tbody th { position: sticky; left: 0; }
</style>
<?php init_tail(); ?>
<!--new update -->
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var PlantID = $("#PlantID").val();
	    if(PlantID == "3"){
	        $.ajax({
              url:"<?php echo admin_url(); ?>E_filling/GSTR1ReportsNew",
              dataType:"JSON",
              method:"POST",
              /*cache: false,
              async: true,*/
              data:{from_date:from_date, to_date:to_date},
              beforeSend: function () {
                       
                $('#searchh').css('display','block');
                $('.load_data').css('display','none');
                
             },
              complete: function () {
                                    
                $('.load_data').css('display','');
                $('#searchh').css('display','none');
             },
            success:function(data){
                  
                $('#B2CL_tbltbody').html(data.B2CL);
                $('#B2B_tbltbody').html(data.B2B);
                $('#B2CS_tbltbody').html(data.B2CS);
                $('#CDNR_tbltbody').html(data.CDNR);
                $('#CDNUR_tbltbody').html(data.CDNUR);
                $('#EXEMP_tbody').html(data.EXEMP);
                $('#HSN_tbody').html(data.HSN);
                $('#DOCS_tbody').html(data.DOCS);
              }
            });
	    }else{
	        alert("Something went wrong, please try again later.");
	    }
	    
	    
 });
 
    $('#B2B').show();
    $('#B2CL').hide(); 
    $('#B2CS').hide();
    $('#CDNR').hide();
    $('#CDNUR').hide();
    $('#EXP').hide();
    $('#AT').hide();
    $('#ADJ').hide();
    $('#test2').hide();
    $('#HSN').hide();
    $('#test').hide();

    $(".test").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').show();
    })
    $(".B2B").click(function(){
        $('#B2B').show();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    $(".B2CL").click(function(){
        $('#B2B').hide();
        $('#B2CL').show(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    
    $(".B2CS").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').show();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    
    $(".CDNR").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').show();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    $(".CDNUR").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').show();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    $(".EXP").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').show();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    
    $(".AT").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').show();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').hide();
        $('#test').hide();
    })
    $(".ADJ").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').show();
        $('#test2').hide();
        $('#HSN').hide();
        $('#Docs').hide();
        $('#test').hide();
    })
    $(".test2").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').show();
        $('#HSN').hide();
        $('#test').hide();
    })
    $(".HSN").click(function(){
        $('#B2B').hide();
        $('#B2CL').hide(); 
        $('#B2CS').hide();
        $('#CDNR').hide();
        $('#CDNUR').hide();
        $('#EXP').hide();
        $('#AT').hide();
        $('#ADJ').hide();
        $('#test2').hide();
        $('#HSN').show();
        $('#test').hide();
    })
    
    
});

 $("#caexcel").click(function(){
 var from_date = $("#from_date").val();
 var to_date = $("#to_date").val();
	  
    $.ajax({
        url:"<?php echo admin_url(); ?>E_filling/export_GSTR1_report",
        method:"POST",
        data:{from_date:from_date,to_date:to_date},
        beforeSend: function () {
            $('#searchh2').css('display','block');
        },
        complete: function () {
            $('#searchh2').css('display','none');    
        },
        success:function(data){
            response = JSON.parse(data);
            window.location.href = response.site_url+response.filename;
        }
    });
});


function printPage(){
    var html_filter_name =    $('.report_for').html();
    // $('.print_hide').show();
    //      var from_date = $("#from_date").val();
	   // var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">GST Sale report</td>';
         heading_data += '</tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
         heading_data += '</tr>';
         
         heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
   
    };
</script>


<script>
$(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    
    
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y > fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
    
    $('#from_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    $('#to_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
});
</script> 


