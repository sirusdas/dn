/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

                            function myFunction() {
                                var x2 = document.getElementById("frame2_model").value;
                                var x3 = document.getElementById("frame3_model").value;
                                var x4 = document.getElementById("frame4_model").value;
                                var x5 = document.getElementById("frame5_model").value;


                                //var y2 = document.getElementById("lens2_r").value;
                                //var y3 = document.getElementById("lens3_r").value;
                               // var y4 = document.getElementById("lens4_r").value;
                               // var y5 = document.getElementById("lens5_r").value;

                                //var z2 = document.getElementById("lens2_l").value;
                               // var z3 = document.getElementById("lens3_l").value;
                               // var z4 = document.getElementById("lens4_l").value;
                                //var z5 = document.getElementById("lens5_l").value;

                                if(x2!=""){  jQuery(".acc_f2").show(); } else{  jQuery(".acc_f2").hide(); }
                                if(x3!=""){  jQuery(".acc_f3").show(); } else{  jQuery(".acc_f3").hide(); }
                                if(x4!=""){  jQuery(".acc_f4").show(); } else{  jQuery(".acc_f4").hide(); }
                                if(x5!=""){ jQuery(".acc_f5").show(); } else{  jQuery(".acc_f5").hide(); }

                                   // if(y2!=null && z2!=null ){ $(".acc_l2").show(); } else{ $(".acc_l2").hide(); }
                                  //  if(y3!=null && z3!=null ){ $(".acc_l3").show(); } else{ $(".acc_l3").hide(); }
                                   // if(y4!=null && z4!=null ){ $(".acc_l4").show(); } else{ $(".acc_l4").hide(); }
                                   // if(y5!=null && z5!=null ){ $(".acc_l5").show(); } else{ $(".acc_l5").hide(); }







                            }


jQuery(function($){
    
    $(".frame_t2").hide();
    $(".frame_t3").hide();
    $(".frame_t4").hide();
    $(".frame_t5").hide();
    
    $(".lens_t2").hide();
    $(".lens_t3").hide();
    $(".lens_t4").hide();
    $(".lens_t5").hide();
    
    $(".acc_f2").hide();
    $(".acc_f3").hide();
    $(".acc_f4").hide();
    $(".acc_f5").hide();
    
    //$(".acc_l2").hide();
   // $(".acc_l3").hide();
    //$(".acc_l4").hide();
   // $(".acc_l5").hide();
    
    
    /*
    $(".frame2").click(function(){
        $(".frame_t2").toggle();
    });
    $(".frame3").click(function(){
        $(".frame_t3").toggle();
    });
    $(".frame4").click(function(){
        $(".frame_t4").toggle();
    });
    $(".frame5").click(function(){
        $(".frame_t5").toggle();
    });
   
    
    
    $(".lens2").click(function(){
        $(".lens_t2").toggle();
    });
    $(".lens3").click(function(){
        $(".lens_t3").toggle();
    });
    $(".lens4").click(function(){
        $(".lens_t4").toggle();
    });
    $(".lens5").click(function(){
        $(".lens_t5").toggle();
    });
     */
   
    
//});

jQuery(document).ready(function() {
    jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});

/*
function myFunctionFrame() {
var x = document.getElementById("nofFrame").value;
    if (x == "1") 
    {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").hide();
            jQuery(".frame_t3").hide();
            jQuery(".frame_t4").hide();
            jQuery(".frame_t5").hide();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").hide();
            jQuery(".lens_t3").hide();
            jQuery(".lens_t4").hide();
            jQuery(".lens_t5").hide();
    }else if (x == "2")
        {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").show();
            jQuery(".frame_t3").hide();
            jQuery(".frame_t4").hide();
            jQuery(".frame_t5").hide();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").show();
            jQuery(".lens_t3").hide();
            jQuery(".lens_t4").hide();
            jQuery(".lens_t5").hide();
        }
      else if (x == "3")
        {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").show();
            jQuery(".frame_t3").show();
            jQuery(".frame_t4").hide();
            jQuery(".frame_t5").hide();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").show();
            jQuery(".lens_t3").show();
            jQuery(".lens_t4").hide();
            jQuery(".lens_t5").hide();
        }
      else if(x == "4")
        {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").show();
            jQuery(".frame_t3").show();
            jQuery(".frame_t4").show();
            jQuery(".frame_t5").hide();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").show();
            jQuery(".lens_t3").show();
            jQuery(".lens_t4").show();
            jQuery(".lens_t5").hide();
        }
      else if (x == "5")
        {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").show();
            jQuery(".frame_t3").show();
            jQuery(".frame_t4").show();
            jQuery(".frame_t5").show();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").show();
            jQuery(".lens_t3").show();
            jQuery(".lens_t4").show();
            jQuery(".lens_t5").show();
        }
    else if (x == "")
        {
            jQuery(".frame_t1").show();
            jQuery(".frame_t2").hide();
            jQuery(".frame_t3").hide();
            jQuery(".frame_t4").hide();
            jQuery(".frame_t5").hide();
            jQuery(".lens_t1").show();
            jQuery(".lens_t2").hide();
            jQuery(".lens_t3").hide();
            jQuery(".lens_t4").hide();
            jQuery(".lens_t5").hide();
        }
    
    }
    
    /*
     * Printing Module
     */
    
    function printDiv(divName) {
        //alert("working");
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

/*
 * Lets get the status using java script
 * 
 */

function getStatus(){
    alert("i was called");
    var http = new XMLHttpRequest();
    var url = "../wp-admin/admin.php?page=manage_inventory_items";
    var params = "inventory_search=&inventory_sort_by=c_email&inventory_category_id=2&inventory_filter=Go";
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                    alert(http.responseText);
            }
    }
    http.send(params);
    
}

function calCustData(){
                                var f0 = document.getElementById("f_sp0").value;
                                var f1 = document.getElementById("f_sp1").value;
                                var f2 = document.getElementById("f_sp2").value;
                                var f3 = document.getElementById("f_sp3").value;
                                var f4 = document.getElementById("f_sp4").value;
                                
                                var l0 = document.getElementById("l_sp0").value;
                                var l1 = document.getElementById("l_sp1").value;
                                var l2 = document.getElementById("l_sp2").value;
                                var l3 = document.getElementById("l_sp3").value;
                                var l4 = document.getElementById("l_sp4").value;
                                
                                var o = document.getElementById("others").value;
                                var adj = document.getElementById("adj").value;
                                
                                
    document.getElementById("total").value = o*1 + (f0*1 + l0*1) + (f1*1 + l1*1) + (f2*1 + l2*1)+ (f3*1 + l3*1) + (f4*1 + l4*1) - adj*1;
    document.getElementById("bal").value = document.getElementById("total").value - document.getElementById("adv").value ;
    
}