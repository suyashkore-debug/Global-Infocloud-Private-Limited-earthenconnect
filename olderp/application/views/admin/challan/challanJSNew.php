
<script>
    $('#challan_route').on('change', function() {
        var id = $(this).val();
        //alert(roleid);
        var url = "<?php echo base_url(); ?>admin/challan/get_order_by_routeNew";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        beforeSend: function () {
               
                            $('#searchh2').css('display','block');
                            $('.showtable').css('display','none');
                            
                         },
                        complete: function () {
                                                
                            $('.showtable').css('display','');
                            $('#searchh2').css('display','none');
                         },
                        success: function(data) {
                           $("#txtchalanvalue1").val("0.00");
                           $("#txtchalanvalue").val("0.00");
                           $("#txtCases1").val("0");
                           $("#txtCases").val("0");
                           $("#txtCrates1").val("0");
                           $("#txtCrates").val("0");
                           $("#rate_changeID").val("0");
                           $("#new_record").val(" ");
                            $(".updateRate_btn").css("display","none");
                            $(".save_challan").css("display","");
                            $(".showtable").html(data);
                            //alert(data);
                            
                        }
                    });
      });
    $('.InvoicePrint').on('click', function() {
        var ChallanID = $('#ChallanID').val();
        var url = "<?php echo base_url(); ?>admin/challan/GetTaxableTransaction";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {ChallanID: ChallanID},
            dataType:'json',
            success: function(data) {
               
                var Link = '<?php echo admin_url(); ?>challan/pdf/'+ChallanID+'?output_type=I';
                var NotMAtch = 0;
                for(var count = 0; count < data.length; count++)
                {
                    if(data[count].PlantID !== "3"){
                        if(data[count].gstno == null || data[count].gstno == ''){
                        
                        }else{
                            if(data[count].irn == null && data[count].BillAmt > 0){
                                NotMAtch++;
                            }
                        }
                    }
                }
                if(NotMAtch == 0){
                    //alert(NotMAtch)
                    window.open(Link,'_blank');
                }else{
                    //alert(NotMAtch)
                    alert('Please create E-invoice for all GST registered parties...');
                }
            }
        })
    })
  $('.updateRate_btn').on('click', function() {
        var RCHID = $("#new_record").val();
        //alert(roleid);
        var url = "<?php echo base_url(); ?>admin/challan/update_rate";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {RCHID: RCHID},
                        dataType:'json',
                        
                        success: function(data) {
                           //alert("rate updated");
                           if(data == true){
                               location.reload(true);
                           }
                           
                        }
                    });
      });
      
  $('#challan_vehicle').on('change', function() {
        var id = $(this).val();
        
        /*var len = $('#challan_data tr')[0].cells.length;
            $('#challan_data tbody').find('tr').each(function (k, v) {
                $(this).find('td').each(function (k, v) {
                    $(this).find("input[type=checkbox]").prop('checked', false); // Unchecks it
                    
                })
                $(this).removeClass("bg-an");
            })*/
        if(id == "TV"){
            $("#custom_vehicle_number").css("display","");
            //remove it
                    //$("#vahicle_capacity1").removeAttr("disabled");
                    $(".chldr").css("display","none");
                    $("#capacity_div").hide();
                    
        }else{
            $("#custom_vehicle_number").css("display","none");
            //add disabled
                    //$("#vahicle_capacity1").attr('disabled', 'disabled');
                    $(".chldr").css("display","");
                    $("#capacity_div").show();
        }
        //alert(id);
        var url = "<?php echo base_url(); ?>admin/challan/get_vehicle_detail";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                            if(data){
                                $("#vahicle_capacity").val(data["VehicleCapacity"]);
                                $("#vahicle_capacity1").val(data["VehicleCapacity"]);
                            }else{
                                $("#vahicle_capacity").val(" ");
                                $("#vahicle_capacity1").val(" ");
                            }
                        }
                    });
      });
  
  
        
    $('.number1').on('blur', function() {
        var NestId = $(".number1").val();
        var url = admin_url + 'challan/list_challan/' + NestId;
        window.location.href = url;
     });
     
    
        
    /*$('#challan_form').on('submit', function() {
        var chlval = $("#txtchalanvalue").val();
        if(chlval == '0.00' || chlval == '0'){
            alert("please select atleast one order");
            return false
        }else{
            return true
        }
        
     });*/
     
    $('#challan_form').on('submit', function() {
        var chlval = $("#txtchalanvalue").val();
        var ORDChecked = $("input[type=checkbox]:checked").val();
        if(ORDChecked == "undefined"){
            alert("please select atleast one order");
            return false;
        }else{
            return true;
        }
        /*alert(aa);
        return false*/
        if(chlval == '0.00' || chlval == '0'){
            alert("Challan value must be grater than 0");
            return false
        }else{
            return true
        }
    });
    
        
  $(document).on('click', '.chk', function () {
            currentRows = $(this).closest("tr");
            //alert(aa)
            var Vehicle = $('#challan_vehicle').val();
            var MaxCreditLimit = $('#MaxCreditLimit').val();
            var OBal = currentRows.find("input[name=Balance]").val();
            var MaxCrdAmt = currentRows.find("input[name=MaxCrdAmt]").val();
            var FBillAmt = currentRows.find("input[name=FBilAmt]").val();
            //alert(FBillAmt);
            if(OBal < 0 || MaxCreditLimit == "N" || MaxCrdAmt == "0.00"){
                if(Math.abs(OBal) >= FBillAmt || MaxCreditLimit == "N" || MaxCrdAmt == "0.00"){
                    //alert('Bill Amt Valid');   
                    
                    /*if(Vehicle == ""){
                        alert('please select vehicle first..');
                        currentRows.find("input[type=checkbox]").prop('checked', false); // Unchecks it
                    }else{
                        var status = ChallanValues();
                    if(status == false){
                        currentRows.find("input[type=checkbox]").prop('checked', false); // Unchecks it
                        alert('Vehicle Capacity Overlloaded please select anather vehicle..');
                    }else{*/
                    
                    ChallanValues();
                    var aa = currentRows.find("input[type=checkbox]:checked").val();
                    var ab = currentRows.find("input[name=rate_change]").val();
                    var orderID = currentRows.find("input[name=OrderID]").val();
                    
                    //alert(orderID);
                    if (aa){
                        currentRows.addClass("bg-an");
                        $(".transaction-submit").removeAttr("disabled");
                        //$('#submit').prop("disabled", false);
                        if(ab == "Y"){
                            
                            var new_rec = $('#new_record').val();
                            new_rec = new_rec +","+ orderID
                            $('#new_record').val(new_rec);
                            
                            var rate_changeID = $("#rate_changeID").val();
                            var new_cont = parseInt(rate_changeID) + 1;
                            $("#rate_changeID").val(new_cont);
                        }
                        updateRate();
                    }else {
                        currentRows.removeClass("bg-an");
                        if(ab == "Y"){
                            var new_rec = $('#new_record').val();
                            $new_item_code = ','+orderID;
                            new_rec = new_rec.replace($new_item_code, " ");
                            $('#new_record').val(new_rec);
                            
                            var rate_changeID = $("#rate_changeID").val();
                            var new_cont = parseInt(rate_changeID) - 1;
                            $("#rate_changeID").val(new_cont);
                        }
                        updateRate();
                    }
                    /*}
                    }*/
                }else{
                    alert('Max credit limit exceeds');
                    currentRows.find("input[type=checkbox]").prop('checked', false); // Unchecks it
                }
            }else{
                alert('Max credit limit exceeds');
                currentRows.find("input[type=checkbox]").prop('checked', false); // Unchecks it
            }
        });
        
        function ChallanValues() {
            var x = document.getElementById("challan_data").rows[0].cells.length;
            var challanTotal = 0, cratetotal = 0, casetotal = 0, tcsamt = 0;
            var a = x - 10;
            var b = x - 1;
            var c = x - 11;
            var d = x;

            $('#challan_data tbody input[type=checkbox]:checked').each(function (i, row) {
                var row = $(this).closest("tr")[0];
                
                //row.addClass("bg-primary")
                $(row).find('td').each(function (index, r) {
                    if (index == a) {
                        //Case values
                        var h = r.innerText;
                        casetotal += isNaN(parseFloat(h)) ? 0 : parseFloat(h);
                        //console.log(row);
                    }
                    if (index == b) {
                        //challan values
                        var h = r.innerText;
                        challanTotal += isNaN(parseFloat(h)) ? 0 : parseFloat(h);
                        //console.log(h);
                    }
                    if (index == c) {
                        //crate values
                        var h = r.innerText;
                        cratetotal += isNaN(parseFloat(h)) ? 0 : parseFloat(h);
                        
                    }
                    if (index == d) {
                        //crate values
                        var h = r.innerText;
                        tcsamt += isNaN(parseFloat(h)) ? 0 : parseFloat(h);
                        //console.log(h);
                    }
                })
            });
            $('#txtCrates').val(cratetotal);
                $('#txtCases').val(casetotal);
                $('#txtCrates1').val(cratetotal);
                $('#txtCases1').val(casetotal);
                $('#txtchalanvalue').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                $('#txtchalanvalue1').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                return true;
            /*var VCapacity = $("#vahicle_capacity").val();
            var Vehicle = $('#challan_vehicle').val();
            if(Vehicle == "TV"){
                $('#txtCrates').val(cratetotal);
                $('#txtCases').val(casetotal);
                $('#txtCrates1').val(cratetotal);
                $('#txtCases1').val(casetotal);
                $('#txtchalanvalue').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                $('#txtchalanvalue1').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                return true;
            }else{
                if(VCapacity == ""){
                $('#txtCrates').val(cratetotal);
                $('#txtCases').val(casetotal);
                $('#txtCrates1').val(cratetotal);
                $('#txtCases1').val(casetotal);
                $('#txtchalanvalue').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                $('#txtchalanvalue1').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                return true;
            }else if(VCapacity >= cratetotal){
                $('#txtCrates').val(cratetotal);
                $('#txtCases').val(casetotal);
                $('#txtCrates1').val(cratetotal);
                $('#txtCases1').val(casetotal);
                $('#txtchalanvalue').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                $('#txtchalanvalue1').val(Number.parseFloat(challanTotal + tcsamt).toFixed(2));
                return true;
            }else if(VCapacity < cratetotal){
                return false;
            }
            }*/
        }
        
        function total(val,oldvl) {
            
            var name = val.name;
            var NewName = 'org'+name; 
            var oldVal = val.value;
             $('#challan_data tbody').find('tr').each(function (k, v) {
                
                 $(this).find('td').each(function (k, v) {
                    var hdval = $(this).find('input[id="qtyhidden"]').val();
                    if (hdval != undefined) {
                        var qty = $(this).find('input[type="text"]').val();
                        var name = $(this).find('input[type="text"]').attr("name");
                        var ItemID = GETItemID(name); 
                        var Stock = GETStock(hdval); 
                        var pq = pkg(hdval);
                        //alert(Stock);
                        if(qty == '0' || qty == '0.00'){
                            
                        }else{
                            if(parseFloat(Stock) < qty){
                                $(this).find('input[type="text"]').css({ 'font- weight': 'bold', 'color': 'darkorange' });//'border': '1px solid red',
                            }
                        }
                        
                       /* var url = "<?php echo base_url(); ?>admin/challan/GetStockDetails";
                        jQuery.ajax({
                            type: 'POST',
                            url:url,
                            data: {qty: qty,ItemID:ItemID,pq:pq},
                            dataType:'json',
                            success: function(data) {
                                if(data == false){
                                    alert('Stock qty not available..');
                                    $('qty_ORD22300010_ZRT2').css({ 'font- weight': 'bold', 'color': 'red' });//'border': '1px solid red',
                                }else{
                                    alert('Stock qty available..');
                                }
                            }
                        });*/
                    }
                 });
             });
            GetRightTotal(name,oldVal,NewName);
            GetBottomTotal();
            ChallanValues();
        }
        function updateRate() {
            var rate_changeID_val = $("#rate_changeID").val();
            if(rate_changeID_val == "0"){
                $(".updateRate_btn").css("display","none");
                $(".save_challan").css("display","");
            }else{
                $(".updateRate_btn").css("display","");
                $(".save_challan").css("display","none");
            }
        }
        
        function GetRightTotal(name,oldVal,NewName) {
            var tcper = 0;
            var len = $('#challan_data tr')[0].cells.length;
            var CHLCrate = 0.00;
            var CHLCases = 0.00;
            var CHLAmt = 0.00;
            $('#challan_data tbody').find('tr').each(function (k, v) {
            
                var totcase = 0; var totcrate = 0; tq = 0; var totorder = 0; var tcase = 0; var tcrate = 0; var totordsale = 0; var tcsper = 0;
                var IGSTAmtSum = 0; var CGSTAmtSum = 0; var SGSTAmtSum = 0;
                $(this).find('td').each(function (k, v) {
                    var hdval = $(this).find('input[id="qtyhidden"]').val();
                    if (hdval != undefined) {
                        var cscr = CSCR(hdval);
                        var r = rate(hdval);
                        var pq = pkg(hdval);
                        var g = gst(hdval);
                        var state = stateval(hdval);
                        

                        if (cscr == 'CS') {
                            var cqty = $(this).find('input[type="text"]').val();
                            tcase = isNaN(parseFloat(cqty)) ? 0 : parseFloat(cqty);
                            totcase += tcase;
                            CHLCases += tcase;
                        }
                        else if (cscr == 'CR') {
                            var crqty = $(this).find('input[type="text"]').val();
                            tcrate = isNaN(parseFloat(crqty)) ? 0 : parseFloat(crqty);
                            totcrate += tcrate;
                            CHLCrate += tcrate;
                        }
                        var qty = $(this).find('input[type="text"]').val();
                        var saleAmt = qty * pq * r;
                        totordsale = parseFloat(totordsale) + parseFloat(saleAmt);
                        
                        if(state == "UP"){
                            var CGSTPer = g /2;
                            var CGSTAmt = (saleAmt / 100) * parseFloat(CGSTPer);
                            var SGSTAmt = (saleAmt / 100) * parseFloat(CGSTPer);
                            SGSTAmtSum = parseFloat(SGSTAmtSum) + parseFloat(SGSTAmt);
                            CGSTAmtSum = parseFloat(CGSTAmtSum) + parseFloat(CGSTAmt);
                            var BillAmt = parseFloat(saleAmt) + parseFloat(CGSTAmt) + parseFloat(SGSTAmt);
                        }else{
                            var IGSTAmt = (saleAmt / 100) * parseFloat(g);
                            IGSTAmtSum = parseFloat(IGSTAmtSum) + parseFloat(IGSTAmt);
                            var BillAmt = parseFloat(saleAmt) + parseFloat(IGSTAmt);
                        }
                        totorder = parseFloat(totorder) + parseFloat(BillAmt);
                    }
                });
                
                
                
                //alert(totcase);
                //alert(totorder);
                tcsper = parseFloat($(this).find('td').eq(5).find('input[type="hidden"]').val());
                if(tcsper=="0.00"){
                    tcsAmt = 0.00;
                }else{
                    var tcsAmt = (parseFloat(totorder) / 100) * tcsper;
                }
                
                var finalBillAmt = parseFloat(totorder) +  parseFloat(tcsAmt);
               CHLAmt += finalBillAmt;
               var aa = $(this).find("input[type=checkbox]:checked").val();
               var FBillAmt = $(this).find('td:last-child').find('input[name="FBilAmt"]').val();
                var OBal = $(this).find('td').eq(1).find('input[name="Balance"]').val();
                //alert(OBal);
                //alert(finalBillAmt);
                var MaxCreditLimit = $('#MaxCreditLimit').val();
                var MaxCrdAmt = $(this).find("input[name=MaxCrdAmt]").val();
                if(OBal < 0 || MaxCreditLimit == "N" || MaxCrdAmt == "0.00"){
                    if(Math.abs(OBal) >= finalBillAmt || MaxCreditLimit == "N" || MaxCrdAmt == "0.00"){
                        
                        $('input[name="LastValue"]').val(oldVal);
                        $('input[name="'+NewName+'"]').val(oldVal);
                        //alert('Bill Amt Valid11');   
                        $(this).find('td').eq(len - 2).html(parseFloat(tcsAmt).toFixed(2));
                        $(this).find('td').eq(len - 11).html(totcrate);
                        $(this).find('td').eq(len - 10).html(totcase);
                        $(this).find('td').eq(len - 9).html(parseFloat(totorder).toFixed(2));
                        $(this).find('td').eq(len - 8).html(parseFloat(totordsale).toFixed(2));
                        $(this).find('td').eq(len - 4).html(parseFloat(IGSTAmtSum).toFixed(2));
                        $(this).find('td').eq(len - 5).html(parseFloat(SGSTAmtSum).toFixed(2));
                        $(this).find('td').eq(len - 6).html(parseFloat(CGSTAmtSum).toFixed(2));
        
                       //$(this).find('td:last-child').html(Number(parseFloat(totordsale * tcsper / 100).toFixed(2)));
                       var htmls = Number(parseFloat(finalBillAmt).toFixed(2))+'<input type="hidden" name="FBilAmt" id="FBilAmt" value="'+finalBillAmt+'">';
                       $(this).find('td:last-child').html(htmls);
                       $(this).find('td:last-child').addClass('textright');
                    }else{
                        if(aa){
                            alert('Max credit limit exceeds');
                            var preVal = $('input[name="'+NewName+'"]').val();
                            $('input[name="'+name+'"]').val(preVal);
                        }
                    }
                }else{
                        if(aa){
                            alert('Max credit limit exceeds');
                            var preVal = $('input[name="'+NewName+'"]').val();
                            $('input[name="'+name+'"]').val(preVal);
                        }
                }
            });
            
            $('#txtchalanvalue1').val(parseFloat(CHLAmt).toFixed(2));
            $('#txtchalanvalue').val(parseFloat(CHLAmt).toFixed(2));
            
            $('#txtCases1').val(parseFloat(CHLCases).toFixed(2));
            $('#txtCases').val(parseFloat(CHLCases).toFixed(2));
            
            $('#txtCrates1').val(parseFloat(CHLCrate).toFixed(2));
            $('#txtCrates').val(parseFloat(CHLCrate).toFixed(2));
        }
        
        function GetBottomTotal() {
            var x = document.getElementById("challan_data").rows[0].cells.length;
            var totalRow = '';
            for (var index = 7; index < x; index++) {
                var total = 0;
                $('#challan_data tbody tr').each(function () {
                    //if (index == x - 1 || index == x - 2 || index == x - 3 || index == x - 4) {
                    if (index <= x - 1 && index >= x - 11) {
                        total += isNaN(parseFloat($('td', this).eq(index).html())) ? 0 : parseFloat($('td', this).eq(index).html());
                    }
                    else {
                        total += isNaN(parseFloat($('td', this).eq(index).find('input[type="text"]').val())) ? 0 : parseFloat($('td', this).eq(index).find('input[type="text"]').val());
                    }
                });
                totalRow += '<td style="text-align:right">' + parseFloat(total).toFixed(2) + '</td>';
                $('#challan_data tbody input[type="text"]').on('change', function () {
                    $(this).css({ 'font- weight': 'bold', 'color': 'blue' });//'border': '1px solid red',
                })
            }
            $('#challan_data tfoot tr').remove();
            $('#challan_data tfoot').append('<tr><td>Total</td><td></td><td></td><td></td><td></td><td></td><td></td>' + totalRow + '</tr>');
        }
        
        function rate(str) {
            var s = str.split('_');
            var q = parseFloat(s[2]);
            q = isNaN(q) ? 0 : q.toFixed(2);
            return q;
        }
        function CSCR(str) {
            var s = str.split('_');
            var q = s[4];
            //q = isNaN(q) ? 0 : q;
            return q;
        }
        function pkg(str) {
            var s = str.split('_');
            var q = parseFloat(s[1]);
            q = isNaN(q) ? 0 : q.toFixed(2);
            return q;
        }
        function GETItemID(str) {
            var s = str.split('_');
            var q = s[2];
            //q = isNaN(q) ? 0 : q.toFixed(2);
            return q;
        }
        function gst(str) {
            var s = str.split('_');
            var q = parseFloat(s[3]);
            q = isNaN(q) ? 0 : q.toFixed(2);
            return q;
        }
        function stateval(str) {
            var s = str.split('_');
            var q = s[5];
            return q;
        }
        function GETStock(str) {
            var s = str.split('_');
            var q = s[6];
            return q;
        }

        
</script>