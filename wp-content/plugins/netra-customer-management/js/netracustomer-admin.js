jQuery(function($) {
    jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
    $c=1;
    	$('#vendor_name, #vendor_address').on("keyup", actionTo);
	function actionTo() {
		   if($('#vendor_name').val().length > 0 && $('#vendor_address').val().length > 0) {
			  $('#addMore').prop("disabled", false);
		   }else {
			  $('#addMore').prop("disabled", true);
		   }
	}
    //my code 

$("#addMore").click(function(){
    $c=$c+1;
        $("#newTable").append("<form id='form"+$c+"' method='post' > " +
    "<table class='form-table"+$c+"'>"+
				"<tbody>"+
									"<tr>"+
						"<th>Product Name</th>"+
						"<td><input value='' class='regular-text onfocused' id='p_name"+$c+"' name='p_name"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Model No</th>"+
						"<td><input value='' class='regular-text onfocused' id='p_model_no"+$c+"' name='p_model_no"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Quantity</th>"+
						"<td><input value='' class='regular-text' id='p_qty"+$c+"' name='p_qty"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Rate</th>"+
						"<td><input value='' class='regular-text' id='p_rate"+$c+"'  name='p_rate"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Total</th>"+
						"<td><input value='' class='regular-text' id='p_total"+$c+"' name='p_total"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Advance</th>"+
						"<td><input value='' class='regular-text' id='p_adv"+$c+"' name='p_adv"+$c+"'></td>"+
					"</tr>"+                                                                        
						"<th>Balance</th>"+
						"<td><input value='' class='regular-text' id='p_bal"+$c+"' oninput='calProData2()' name='p_bal"+$c+"'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Due Date</th>"+
						"<td><input value='' class='MyDate' name='p_duedate"+$c+"' id='dp1458535185551'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Speification</th>"+
						"<td><div class='wp-core-ui wp-editor-wrap tmce-active' id='wp-description-wrap'><link media='all' type='text/css' href='http://localhost:8080/wordpress/wp-includes/css/editor.min.css?ver=4.1.10' id='editor-buttons-css' rel='stylesheet'>"+
"<div class='wp-editor-tools hide-if-no-js' id='wp-description-editor-tools'><div class='wp-editor-tabs'><button onclick='switchEditors.switchto(this);' class='wp-switch-editor switch-tmce' id='description-tmce' type='button'>Visual</button>"+
"<button onclick='switchEditors.switchto(this);' class='wp-switch-editor switch-html' id='description-html' type='button'>Text</button>"+
"</div>"+
"</div>"+
"<div class='wp-editor-container' id='wp-description-editor-container'><div id='qt_description_toolbar' class='quicktags-toolbar'><input type='button' value='b' class='ed_button button button-small' id='qt_description_strong'><input type='button' value='i' class='ed_button button button-small' id='qt_description_em'><input type='button' value='link' class='ed_button button button-small' id='qt_description_link'><input type='button' value='b-quote' class='ed_button button button-small' id='qt_description_block'><input type='button' value='del' class='ed_button button button-small' id='qt_description_del'><input type='button' value='ins' class='ed_button button button-small' id='qt_description_ins'><input type='button' value='img' class='ed_button button button-small' id='qt_description_img'><input type='button' value='ul' class='ed_button button button-small' id='qt_description_ul'><input type='button' value='ol' class='ed_button button button-small' id='qt_description_ol'><input type='button' value='li' class='ed_button button button-small' id='qt_description_li'><input type='button' value='code' class='ed_button button button-small' id='qt_description_code'><input type='button' value='more' class='ed_button button button-small' id='qt_description_more'><input type='button' value='close tags' title='Close all open tags' class='ed_button button button-small' id='qt_description_close'></div><div role='application' tabindex='-1' hidefocus='1' class='mce-tinymce mce-container mce-panel' id='mceu_36' style='visibility: hidden; border-width: 1px;'><div class='mce-container-body mce-stack-layout' id='mceu_36-body'><div role='group' tabindex='-1' hidefocus='1' class='mce-toolbar-grp mce-container mce-panel mce-first mce-stack-layout-item' id='mceu_37'><div class='mce-container-body mce-stack-layout' id='mceu_37-body'><div role='toolbar' class='mce-container mce-toolbar mce-first mce-stack-layout-item' id='mceu_38'><div class='mce-container-body mce-flow-layout' id='mceu_38-body'><div class='mce-container mce-first mce-last mce-flow-layout-item mce-btn-group' id='mceu_39' role='group'><div id='mceu_39-body'><div aria-labelledby='mceu_9' tabindex='-1' class='mce-widget mce-btn mce-first' id='mceu_9' role='button' aria-label='Bold'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-bold'></i></button></div><div aria-labelledby='mceu_10' tabindex='-1' class='mce-widget mce-btn' id='mceu_10' role='button' aria-label='Italic'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-italic'></i></button></div><div aria-labelledby='mceu_11' tabindex='-1' class='mce-widget mce-btn' id='mceu_11' role='button' aria-label='Strikethrough'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-strikethrough'></i></button></div><div aria-labelledby='mceu_12' tabindex='-1' class='mce-widget mce-btn' id='mceu_12' role='button' aria-label='Bullet list'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-bullist'></i></button></div><div aria-labelledby='mceu_13' tabindex='-1' class='mce-widget mce-btn' id='mceu_13' role='button' aria-label='Numbered list'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-numlist'></i></button></div><div aria-labelledby='mceu_14' tabindex='-1' class='mce-widget mce-btn' id='mceu_14' role='button' aria-label='Blockquote'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-blockquote'></i></button></div><div aria-labelledby='mceu_15' tabindex='-1' class='mce-widget mce-btn' id='mceu_15' role='button' aria-label='Horizontal line'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-hr'></i></button></div><div aria-labelledby='mceu_16' tabindex='-1' class='mce-widget mce-btn' id='mceu_16' role='button' aria-label='Align left'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-alignleft'></i></button></div><div aria-labelledby='mceu_17' tabindex='-1' class='mce-widget mce-btn' id='mceu_17' role='button' aria-label='Align center'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-aligncenter'></i></button></div><div aria-labelledby='mceu_18' tabindex='-1' class='mce-widget mce-btn' id='mceu_18' role='button' aria-label='Align right'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-alignright'></i></button></div><div aria-labelledby='mceu_19' tabindex='-1' class='mce-widget mce-btn mce-disabled' id='mceu_19' role='button' aria-label='Insert/edit link' aria-disabled='true' aria-pressed='null'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-link'></i></button></div><div aria-labelledby='mceu_20' tabindex='-1' class='mce-widget mce-btn mce-disabled' id='mceu_20' role='button' aria-label='Remove link' aria-disabled='true' aria-pressed='null'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-unlink'></i></button></div><div aria-labelledby='mceu_21' tabindex='-1' class='mce-widget mce-btn' id='mceu_21' role='button' aria-label='Insert Read More tag'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-wp_more'></i></button></div><div aria-labelledby='mceu_22' tabindex='-1' class='mce-widget mce-btn' id='mceu_22' role='button' aria-label='Fullscreen'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-fullscreen'></i></button></div><div aria-labelledby='mceu_23' tabindex='-1' class='mce-widget mce-btn mce-last mce-active' id='mceu_23' role='button' aria-label='Toolbar Toggle' aria-pressed='true'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-wp_adv'></i></button></div></div></div></div></div><div role='toolbar' class='mce-container mce-toolbar mce-last mce-stack-layout-item' id='mceu_40'><div class='mce-container-body mce-flow-layout' id='mceu_40-body'><div class='mce-container mce-first mce-last mce-flow-layout-item mce-btn-group' id='mceu_41' role='group'><div id='mceu_41-body'><div aria-labelledby='mceu_24' tabindex='-1' class='mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox mce-first' id='mceu_24' role='button' aria-haspopup='true'><button tabindex='-1' type='button' role='presentation' id='mceu_24-open'><span>Paragraph</span> <i class='mce-caret'></i></button></div><div aria-labelledby='mceu_25' tabindex='-1' class='mce-widget mce-btn' id='mceu_25' role='button' aria-label='Underline'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-underline'></i></button></div><div aria-labelledby='mceu_26' tabindex='-1' class='mce-widget mce-btn' id='mceu_26' role='button' aria-label='Justify'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-alignjustify'></i></button></div><div aria-haspopup='true' tabindex='-1' role='button' class='mce-widget mce-btn mce-colorbutton' id='mceu_27' aria-label='Text color'><button tabindex='-1' type='button' hidefocus='1' role='presentation'><i class='mce-ico mce-i-forecolor'></i><span class='mce-preview' id='mceu_27-preview'></span></button><button tabindex='-1' hidefocus='1' class='mce-open' type='button'> <i class='mce-caret'></i></button></div><div aria-labelledby='mceu_28' tabindex='-1' class='mce-widget mce-btn' id='mceu_28' role='button' aria-pressed='false' aria-label='Paste as text'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-pastetext'></i></button></div><div aria-labelledby='mceu_29' tabindex='-1' class='mce-widget mce-btn' id='mceu_29' role='button' aria-label='Clear formatting'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-removeformat'></i></button></div><div aria-labelledby='mceu_30' tabindex='-1' class='mce-widget mce-btn' id='mceu_30' role='button' aria-label='Special character'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-charmap'></i></button></div><div aria-labelledby='mceu_31' tabindex='-1' class='mce-widget mce-btn' id='mceu_31' role='button' aria-label='Decrease indent'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-outdent'></i></button></div><div aria-labelledby='mceu_32' tabindex='-1' class='mce-widget mce-btn' id='mceu_32' role='button' aria-label='Increase indent' aria-disabled='false'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-indent'></i></button></div><div aria-labelledby='mceu_33' tabindex='-1' class='mce-widget mce-btn mce-disabled' id='mceu_33' role='button' aria-label='Undo' aria-disabled='true'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-undo'></i></button></div><div aria-labelledby='mceu_34' tabindex='-1' class='mce-widget mce-btn mce-disabled' id='mceu_34' role='button' aria-label='Redo' aria-disabled='true'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-redo'></i></button></div><div aria-labelledby='mceu_35' tabindex='-1' class='mce-widget mce-btn mce-last' id='mceu_35' role='button' aria-label='Keyboard Shortcuts'><button tabindex='-1' type='button' role='presentation'><i class='mce-ico mce-i-wp_help'></i></button></div></div></div></div></div></div></div><div role='group' tabindex='-1' hidefocus='1' class='mce-edit-area mce-container mce-panel mce-stack-layout-item' id='mceu_42' style='border-width: 1px 0px 0px;'><iframe frameborder='0' id='description_ifr' allowtransparency='true' title='Rich Text Area. Press Alt-Shift-H for help' style='width: 100%; height: 100px; display: block;' src='javascript:&quot;&quot;'></iframe></div><div role='group' tabindex='-1' hidefocus='1' class='mce-statusbar mce-container mce-panel mce-last mce-stack-layout-item' id='mceu_43' style='border-width: 1px 0px 0px;'><div class='mce-container-body mce-flow-layout' id='mceu_43-body'><div class='mce-path mce-first mce-flow-layout-item' id='mceu_44'><div aria-level='0' id='mceu_44-0' tabindex='-1' data-index='0' class='mce-path-item mce-last' role='button'>p</div></div><div class='mce-last mce-flow-layout-item mce-resizehandle' id='mceu_45'><i class='mce-ico mce-i-resize'></i></div></div></div></div></div><textarea id='description' name='p_details' cols='40' autocomplete='off' rows='20' class='wp-editor-area' style='display: none;' aria-hidden='true'></textarea></div>"+
"</div>"+

"</td>"+
					"</tr>"+
								"<tr class='images'>"+
					"<th>Images					</th><td>"+
						"<div class='mediasortable media-container ui-sortable' data-type='image'><div data-count='0' class='imagewrapper mediawrap ui-sortable-handle'><div id='inventory-div-0' class='imagecontainer'></div><a class='netracustomer-upload' id='inventory-link-0' data-count='0' href='media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=673'>Add New Image</a><input type='hidden' id='inventory-field-0' value='' name='image[0]'></div><input type='hidden' id='imagesort' value='0,' name='imagesort'></div>					</td>"+
				"</tr>"+
									"<tr class='media'>"+
						"<th>Media						</th><td>"+
							"<div class='mediasortable media-container ui-sortable' data-type='media'><input type='hidden' id='mediasort' value='' name='mediasort'></div><a class='button netracustomer-upload' id='inventory-link-0' data-count='0' href='media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=673'>Add Media</a>						</td>"+
					"</tr>"+
							"</tbody></table>"+
                        "<p class='submit'>"+
				
				
					"<input type='submit' id='abc"+$c+"'  name='save' class='button button-primary' value='Save' />"+
				
			"</p>"+
                        

                            "<script>"+
                         //getting the vendor name
                               "  jQuery( '#vendor_name"+$c+"' ).autocomplete({"+
                                            "source: function( request, response ) {"+
                                              "jQuery.getJSON( 'http://localhost:8080/wordpress/wp-content/plugins/netra-customer-management/search/searchVendor.php', {"+
                                                "term: jQuery('#vendor_name"+$c+"').val()"+
                                              "}, response );"+
                                            "}"+
                                 "});"+
                              /*  jQuery( '#skills' ).autocomplete({
                                 source: '<?php //echo jQueryfile; ?>'
                                }); */
                                "jQuery( '#p_name"+$c+"' ).autocomplete({"+
                                            "source: function( request, response ) {"+
                                              "jQuery.getJSON( 'http://localhost:8080/wordpress/wp-content/plugins/netra-customer-management/search/searchBrand.php', {"+
                                                "term: jQuery('#p_name"+$c+"').val(),"+
                                                "model: jQuery('#p_model_no"+$c+"').val()"+
                                              "}, response );"+
                                            "}"+
                                 "});"+
                                 
                                  "jQuery( '#p_model_no"+$c+"' ).autocomplete({"+
                                            "source: function( request, response ) {"+
                                              "jQuery.getJSON( 'http://localhost:8080/wordpress/wp-content/plugins/netra-customer-management/search/search.php', {"+
                                                "term: jQuery('#p_model_no"+$c+"').val(),"+
                                                "f_brand: jQuery('#p_name"+$c+"').val()"+
                                              "}, response );"+
                                            "}"+
                                    "});"+
                                    
                                    //json for filling as per retrival of data
                                   
                                     "jQuery('.onfocused').focusout(function(){"+
                                        "var data;"+
                                          "data= 'model='+jQuery('#p_model_no"+$c+"').val()+'&brand='+jQuery('#p_name"+$c+"').val();"+

                                       "jQuery.ajax({"+
                                         "url: 'http://localhost:8080/wordpress/wp-content/plugins/netra-customer-management/search/searchallstock.php',  "+
                                         "type: 'POST',"+
                                         "dataType: 'json',"+
                                         "data: data,"+
                                         "success: function(data) {"+
                                             "if(data!=''){"+
                                                "if(jQuery('#p_model_no"+$c+"').val(data[1])!=''){ jQuery('#p_model_no"+$c+"').val(data[1]); }"+
                                                "if(jQuery('#p_name"+$c+"').val(data[0])!=''){  jQuery('#p_name"+$c+"').val(data[0]); }"+
                                            "}"+
                                         "}"+
                                       "});"+
                                       "return false;"+
                                     "});"+
                                     
                                     //same code but to autofill Vendor Address
                                   
                                     "jQuery('.onfocus').focusout(function(){"+
                                        "var data;"+
                                          "data= 'vname='+jQuery('#vendor_name"+$c+"').val();"+

                                       "jQuery.ajax({"+
                                         "url: 'http://localhost:8080/wordpress/wp-content/plugins/netra-customer-management/search/searchVendorAddress.php',  "+
                                         "type: 'POST',"+
                                         "dataType: 'json',"+
                                         "data: data,"+
                                         "success: function(data) {"+
                                             "if(data!=''){"+
                                                "if(jQuery('#vendor_address"+$c+"').val(data[0])!=''){  jQuery('#vendor_address"+$c+"').val(data[0]); }"+
                                            "}"+
                                         "}"+
                                       "});"+
                                       "return false;"+
                                     "});"+
                                     
                                         "jQuery('.MyDate').datepicker({"+
                                                "dateFormat : 'yy-mm-dd'"+
                                          "});"+
                                          
                                          "jQuery('#p_rate"+$c+"').focusout(function(){  jQuery('#p_total"+$c+"').val(jQuery('#p_rate"+$c+"').val() * jQuery('#p_qty"+$c+"').val()); });"+
                                          "jQuery('#p_qty"+$c+"').focusout(function(){  jQuery('#p_total"+$c+"').val(jQuery('#p_rate"+$c+"').val() * jQuery('#p_qty"+$c+"').val()); });"+
                                          "jQuery('#p_adv"+$c+"').focusout(function(){  jQuery('#p_bal"+$c+"').val(jQuery('#p_total"+$c+"').val() - jQuery('#p_adv"+$c+"').val()); });"+ 
                            "</script>"+
                            
                            "<script type='text/javascript'>"+
                                "function calProData(){"+
                                                "var p_rate = document.getElementById('p_rate"+$c+"').value;"+
                                                "var p_qty = document.getElementById('p_qty"+$c+"').value; "+                           

                                "document.getElementById('p_total"+$c+"').value = (p_rate*1)*(p_qty*1);"+
                                "document.getElementById('p_bal"+$c+"').value = document.getElementById('p_total"+$c+"').value - document.getElementById('p_adv"+$c+"').value ;"+

                                               "}"+                    
                            "</script>" 
                    
                                                        );                                          
    });
    

    
    
    // process the form
$('#form'+$c).submit(function(event) {
        alert("i was called");
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
			'i_no'                  : $('input[name=invoice_no]' ).val(),
			'v_name'                 : $('input[name=vendor_name]' ).val(),
                        'pdate'                       : $('input[name=pdate]' ).val(),
			'v_address'              : $('input[name=vendor_address]' ).val(),
			'p_name'                      : $('input[name=p_name'+$c+']' ).val(),
                        'p_model_no'                  : $('input[name=p_model_no'+$c+']').val(),
                        'p_qty'                       : $('input[name=p_qty'+$c+']').val(),
                        'p_rate'                      : $('input[name=p_rate'+$c+']').val(),
                        'p_total'                     : $('input[name=p_total'+$c+']').val(),
                        'p_adv'                       : $('input[name=p_adv'+$c+']').val(),                        
                        'p_bal'                       : $('input[name=p_bal'+$c+']').val(),
                        'p_duedate'                   : $('input[name=p_duedate'+$c+']').val(),
                        'p_details'                   : $('input[name=p_details'+$c+']').val(),
			'category_id'                 : $('input[name=category_id]' ).val()                     
        };

       // process the form
$.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : 'process.php', // the url where we want to POST
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
    
    
	/**
	 * All delete links get a confirmation alert
	 */
	$('.inventorywrap a.delete').click(
		function() {
			var prompt = netracustomer.delete_prompt;
			var name = $(this).attr("data-name");
			if (typeof name != "undefined" && name != "") {
				prompt+= ' ' + netracustomer.delete_named + '"' + name + '"' + netracustomer.prompt_qm;
			} else {
				prompt+= ' ' + netracustomer.delete_general + netracustomer.prompt_qm;
			}
			return confirm(prompt);
		}
	);
	
	$(".inventory_wrap form.filter select").change(
			function() {
				this.form.submit();
			}
	);
	
	/**
	 * Light visual feedback on the "is used" setting for Manage Labels screen
	 */
	$('input.is_used').change(
			function() {
				toggleIsUsed(this);
			}
	).each(
			function() {
				toggleIsUsed(this);
			}
	);
	
	function toggleIsUsed(el) {
		if ($(el).is(":checked")) {
			$(el).closest("tr").removeClass("not_used");
		} else {
			$(el).closest("tr").addClass("not_used");
		}
	}

	$('a.ncm_show_debug').click(
		function() {
			$(this).next().slideDown();
		}
	);
	
	/**
	 * Initialize the class for inventory upload
	 */
	NETRACustomerUpload.init();
});

/**
 * The image / media upload class
 */
var NETRACustomerUpload = {};

NETRACustomerUpload = (function() {
	var $;
	var container;
	var type;
	var word;
	var formfield;
	var custom_media = true;
	var button_update;
	
	
	function inventoryUpload(el) {
		// Using the count value as unique identifier
		formfield = $(el).attr('data-count');
		custom_media = true;
		var _orig_send_attachment = wp.media.editor.send.attachment;
		wp.media.editor.send.attachment = function(props, attachment) {
			if ( custom_media ) {
				// $(container).find('#inventory-field-' + formfield).val(attachment.url);
				formfield = renderUpload(formfield, attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
			clearInterval(button_update);
		}
		 
		wp.media.editor.open(1);
		
		button_update = setInterval(function() {
 			 $('div.media-modal a.media-button-insert').html('Use ' + word + '');} 
 		,300);
 		return false;
	}

	/* function to load the image url into the correct input box */
	function renderUpload(field, src) {
		if (type == 'image') {
			return renderImage(field, src);
		} else {
			return renderMedia(field, src);
		}
	}
	
	function renderImage(field, src) {
		if (container.find("img#inventory-image-" + field).length <= 0) {
			container.find("div#inventory-div-" + field).prepend('<img id="inventory-image-' + field + '" src="" />');
			container.find("div#inventory-div-" + field).prepend('<a class="delete" href="javascript:void(0);">X</a>');
			container.find('div#inventory-div').attr('data-count', field);
		}
		container.find("img#inventory-image-" + field).attr("src", src);
		container.find("input#inventory-field-" + field).val(src);
		container.find("a#inventory-link-" + field).html("Change " + word);	
		return ensureNewImage();
	}
	
	function renderMedia(field, src) {
		// Always add new, so just get count here
		field = (container.find('div.mediacontainer').length);
		container.append('<div class="mediacontainer mediawrap" id="inventory-media-' + field + '" data-count="' + field + '"></div>');
		var html = '<p><label>' + netracustomer.title_label + ':</label><input type="text" class="widefat" name="media_title[' + field + ']" value="" /></p>'; 
		html+= '<p class="media_url"><label>' + netracustomer.url_label + '</label><span>' + src + '</span></p><a href="javascript:void(0);" data-count="' + field + '" class="delete">X</a>';
		html+= '<input type="hidden" name="media[' + field + ']" value="' + src + '" />';
		container.find('div#inventory-media-' + field).html(html);
		updateOrder();
		return field;
	}
	
	function ensureNewImage() {
		var empty = 0;
		var count = 0;
		var retval = 0;
		container.find("div.imagecontainer").each(
			function() {
				count++;
				if ($(this).find("img").length <=0) {
					empty = 1;
				}
			}
		);
		if ( ! empty) {
			var td = container.find("div.imagecontainer").parents("div.mediasortable");
			var html = '<div class="imagewrapper mediawrap" data-count="' + count  + '">';
			html+= '<div class="imagecontainer" id="inventory-div-' + count + '"></div>';
			html+= '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' + count + '" id="inventory-link-' + count + '" class="netracustomer-upload">Add New Image</a>';
			html+= '<input type="hidden" name="image[' + count + ']" value="" id="inventory-field-' + count + '" />';
			html+= '</div>';
			td.append(html);
			/* $('#inventory-link-' + count).click(function() {
				inventoryUpload($(this));
				 return false;
			}); */
			retval = count;
		}
		updateOrder();
		return retval;
	}

	function removeMedia(el) {
		console.log('here');
		container = $(el).closest('.mediawrap');
		container.addClass('media-deleted');
		type = $(el).closest('td').find('.media-container').attr('data-type');
		console.log(type);
		container.fadeOut(500, function() {
			$(this).remove();
			if (type == 'image') {
				ensureNewImage();
			} else {
				updateOrder();
			}
		});
	}
	
	function updateOrder() {
		$('.mediasortable').each(function() {
			var str = "";
			var count = 0;
			var otype = $(this).attr('data-type');
			console.log(otype);
			$(this).find(".mediawrap").each(function() {
				count++;
				str+= $(this).attr("data-count") + ",";
			});
			jQuery("input#" + otype + "sort").val(str);
			if (count > 1) {
				if ($(this).next(".sortnotice").length <= 0) {
					word = ($(this).attr("data-type") == 'media') ? netracustomer.media_label : netracustomer.image_label;
					$(this).after('<p class="sortnotice">Drag and drop ' + word + ' to change sort order</p>');
				}
			}
		});
	}

	
	return {
		init: function() {
			$ = jQuery;
			// Media upload functionality. Use live method, because add / edit can be created dynamically
			$(document).on('click', '.netracustomer-upload', function() {
				// Set the container element to ensure actions take place within container
				container = $(this).closest('td').find('.media-container');
				// Set the type.  media or image
				type = container.attr("data-type");
				word = (type == 'media') ? netracustomer.media_label : netracustomer.image_label;
				inventoryUpload($(this));
				return false;
			});
			
			$(document).on('click', '.media-container .delete', function() {
				removeMedia($(this));
			});
			
			if ($("div.mediasortable").length > 0) {
				$("div.mediasortable").sortable({
					items: '.mediawrap',
					helper: 'clone',
					forcePlaceholderSize: true,
					stop: function() {
						updateOrder();
					}
				});
				updateOrder();
			}
		}		
	}       
	
})();

/**
 * Utility function for getting query parameters.
 * @param q
 * @returns
 */
function $_GET(q) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split("=");
		if (pair[0] == q) {
			return unescape(pair[1]);
		}
	}
	return false;
}

     function my()
   {
      alert('Hello');
      var head= document.getElementsByTagName('head')[0];
      var script= document.createElement('script');
      script.type= 'text/javascript';
      script.src= '../wp-content/plugins/netra-customer-management/js/my.js';
      head.appendChild(script);
   }

