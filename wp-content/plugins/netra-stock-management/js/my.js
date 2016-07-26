
jQuery(function($) {

//$c=2;
	alert("my.js Loaded.. .. . ...");
	
	/* Allow only numbers and backspace in text-field*/
	$('.num').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9 && key.charCode != 46) return false;
    });
	
	 jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
	
	
	/*$("#vendor_name"+$c).on("keyup", actionTo);
	function actionTo() {
		if($('#vendor_name'+$c).val().length ) {
			  $('#addMore').prop("disabled", false);
		   }else {
			  $('#addMore').prop("disabled", true);
		   }
	}*/

$('#addMore').prop("disabled", true);
$("#abc"+$c).prop('disabled', false);

$("#form"+$c).submit(function(event) {
        alert("i was called");
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
						'i_no'                  : $('input[name=invoice_no'+$c+']' ).val(),
						'v_name'                 : $('input[name=vendor_name'+$c+']' ).val(),
                        'pdate'                       : $('input[name=pdate'+$c+']' ).val(),
						'v_address'              : $('input[name=vendor_address'+$c+']' ).val(),
						'p_name'                      : $('input[name=p_name'+$c+']' ).val(),
                        'p_model_no'                  : $('input[name=p_model_no'+$c+']').val(),
                        'p_qty'                       : $('input[name=p_qty'+$c+']').val(),
                        'p_rate'                      : $('input[name=p_rate'+$c+']').val(),
                        'p_total'                     : $('input[name=p_total'+$c+']').val(),
                        'p_bal'                       : $('input[name=p_bal'+$c+']').val(),
                        'p_duedate'                   : $('input[name=p_duedate'+$c+']').val(),
                        'p_details'                   : $('input[name=p_details'+$c+']').val(),
						'category_id'                 : $('#category_id'+$c).val()                     
        };
		
alert($('input[name=invoice_no'+$c+']' ).val());
alert("Customer Details Added Successfully");
$("#abc"+$c).prop("disabled", true);


$('#addMore').prop("disabled", false);

       // process the form
$.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : '../wp-content/plugins/netra-customer-management/includes/netracustomer.saveproduct.class.php', // the url where we want to POST
    data        : formData, // our data object
    dataType    : 'json' // what type of data do we expect back from the server
})
    // using the done promise callback
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {
            
            // handle errors for name ---------------
            if (data.errors.name) {
                $('#name-group').addClass('has-error'); // add the error class to show red input
                $('#name-group').append('<div class="help-block">' + data.errors.name + '</div>'); // add the actual error message under our input
            }

            // handle errors for email ---------------
            if (data.errors.email) {
                $('#email-group').addClass('has-error'); // add the error class to show red input
                $('#email-group').append('<div class="help-block">' + data.errors.email + '</div>'); // add the actual error message under our input
            }

            // handle errors for superhero alias ---------------
            if (data.errors.superheroAlias) {
                $('#superhero-group').addClass('has-error'); // add the error class to show red input
                $('#superhero-group').append('<div class="help-block">' + data.errors.superheroAlias + '</div>'); // add the actual error message under our input
            }

        } else {

            // ALL GOOD! just show the success message!
            $('form').append('<div class="alert alert-success">' + data.message.Name + '</div>');

            // usually after form submission, you'll want to redirect
            // window.location = '/thank-you'; // redirect a user to another page
            alert('success'); // for now we'll just alert the user

        }

    });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });   
	
		
    });

