
    
    /*
     * Printing Module
     */
    
   /* function printDiv(divName) {
        //alert("working");documentElement.
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}*/

function printDiv(divName) {
    var DocumentContainer = document.getElementById(divName);
    var WindowObject = window.open('', 'PrintWindow', 'width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
    var strHtml = "<html><head><link rel='stylesheet' type='text/css' href='../plugins/netra-stock-management/css/test.css'><style>\n\
	#spec_bill{width: 630px;border: 1px solid black;padding: 0px 10px;}\n\
        td{border-bottom: 1px solid #1b1b1b;}\n\
        table{margin-bottom:15px;width:100%;}\n\
	table, th, td{padding:5px;}\n\
        table.spec_bill2{border-collapse: collapse;}\n\
        .shop_address{width:100%;border-bottom: 1px solid;}\n\
        .shop_address .wrapper{width:80%;margin:0 auto;}\n\
        .shop_address .wrapper .shop_name{width:80%;display:inline-block;}\n\
        .shop_name h2,.shop_name h3{text-align:center;}\n\
	.shop_logo{width:20%;height:88px;background:red;float: right;margin-top: 10px;display:inline-block;}\n\
	#cust_receipt{width: 496px;border: 1px solid black;padding: 0px 10px;}\n\
	#cust_receipt p{text-align: center;}\n\
        </style></head><body><div id='spec_bill'>" + DocumentContainer.innerHTML + "</div></body></html>";
    WindowObject.document.writeln(strHtml);
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();
    }

/*
 * Lets get the status using java script
 * 
 */

function getStatus(){
    //alert("i was called");
    var http = new XMLHttpRequest();
    var url = "../wp-admin/admin.php?page=orders";
    var params = "inventory_search=&inventory_sort_by=c_email&inventory_category_id=2&inventory_filter=Go";
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                    //alert(http.responseText);
                    document.body.innerHTML =http.responseText;
            }
    }
    http.send(params);
    
}


function setView(view){
    //alert("i was called");
    var http = new XMLHttpRequest();
    var url = "../wp-admin/admin.php?page=manage_store";
    var params = "view="+view;
    http.open("POST", url, true);

    //Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                    //alert(http.responseText);
                    document.body.innerHTML =http.responseText;
            }
    }
    http.send(params);
    
}






/*

function printDiv() {
    var DocumentContainer = document.getElementById('invc');
    var WindowObject = window.open('', 'PrintWindow', 'width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
    var strHtml = "<html>\n<head>\n <link rel=\"stylesheet\" type=\"text/css\" href=\"test.css\">\n</head><body><div style=\"testStyle\">\n" + DocumentContainer.innerHTML + "\n</div>\n</body>\n</html>";
    WindowObject.document.writeln(strHtml);
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();

}
*/