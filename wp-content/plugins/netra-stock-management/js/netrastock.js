
jQuery(function($) {

	$("div.inventory .inventory_images .inventory_thumbs .inv_lightbox").click(

			function() {

				var full = $(this).attr("data-full");

				var lrg = $(this).find("img").attr("data-large");

				var med = $(this).find("img").attr("data-medium");

				var src = lrg;

				if (typeof src == "undefined") {

					src = $(this).find("img").attr("src");

				}

				$(this).parents(".inventory_images").find(".inventory_main a").attr("data-full", full);

				$(this).parents(".inventory_images").find(".inventory_main a img").attr("data-large", lrg);

				$(this).parents(".inventory_images").find(".inventory_main img").attr("src", src);

			}

	);

	

	$(".inv_sort_form select").change(

			function() {

				this.form.submit();

			}

	);

	

	$("div.inventory .inventory_main .inv_lightbox").click(

			function() {

				var src = $(this).find("img").attr("data-large");

				if (typeof src == "undefined" || !src) {

					return;

				}

				$("#inventory_lightwrap img").attr("src", src);

				$("#inventory_blur, #inventory_lightwrap").fadeIn();

				$("#inventory_lightbox").css("width", "10px");

				setTimeout(function() {

					var w = $("#inventory_lightwrap img").width();

					if (w > ($(window).width() - 80)) {

						w = $(window).width() - 80;

						$("#inventory_lightwrap img").css("width", w + "px");

					}

					$("#inventory_lightbox").animate({

						width: w + 'px'

					});

				}, 100);

			}

	);

});



function invHideLightbox() {

	jQuery("#inventory_blur, #inventory_lightwrap").fadeOut();

	jQuery("#inventory_lightwrap img").css("width", "auto");


jQuery(function($) {
	
	$('form[name="netracustomer_filter"]').on('change', 'select', function() {
		$(this).closest('form').submit();
	}).find('span.sort input').hide();
	
	$("div.inventory .inventory_images .inventory_thumbs .inv_lightbox").click(
			function() {
				var full = $(this).attr("data-full");
				var lrg = $(this).find("img").attr("data-large");
				var med = $(this).find("img").attr("data-medium");
				var src = lrg;
				if (typeof src == "undefined") {
					src = $(this).find("img").attr("src");
				}
				$(this).parents(".inventory_images").find(".inventory_main a").attr("data-full", full);
				$(this).parents(".inventory_images").find(".inventory_main a img").attr("data-large", lrg);
				$(this).parents(".inventory_images").find(".inventory_main img").attr("src", src);
			}
	);
	
	$("div.inventory .inventory_main .inv_lightbox").click(
			function() {
				var src = $(this).find("img").attr("data-large");
				if (typeof src == "undefined" || !src) {
					return;
				}
				$("#inventory_lightwrap img").attr("src", src);
				$("#inventory_blur, #inventory_lightwrap").fadeIn();
				$("#inventory_lightbox").css("width", "10px");
				setTimeout(function() {
					var w = $("#inventory_lightwrap img").width();
					if (w > ($(window).width() - 80)) {
						w = $(window).width() - 80;
						$("#inventory_lightwrap img").css("width", w + "px");
					}
					$("#inventory_lightbox").animate({
						width: w + 'px'
					});
				}, 100);
			}
	);

//my code 

$("#addMore").click(function(){
        $("#newTable").html("<table class='form-table'>"+
				"<tbody><tr>"+
					"<th><label for='invoice_no'>Invoice No</label></th>"+
					"<td><input value='' class='regular-text' name='invoice_no'></td>"+
				"</tr>"+                              
				"<tr>"+
					"<th><label for='category_id'>Category</label></th>"+
					"<td><select name='category_id'>"+
"<option value=''>Select Category</option>"+
"<option value='1'>Public</option>"+
"</select>"+
"</td>"+
				"</tr>"+
                                					"<tr>"+
						"<th>Vendor Name</th>"+
						"<td><input value='' class='regular-text' name='vendor_name'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Date</th>"+
						"<td><input value='' class='MyDate hasDatepicker' name='pdate' id='dp1458535185550'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Vendor Address</th>"+
						"<td><input value='' class='regular-text' name='vendor_address'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Product Name</th>"+
						"<td><input value='' class='regular-text' name='p_name'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Model No</th>"+
						"<td><input value='' class='regular-text' name='p_model_no'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Quantity</th>"+
						"<td><input value='' class='regular-text' name='p_qty'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Rate</th>"+
						"<td><input value='' class='regular-text' name='p_rate'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Total</th>"+
						"<td><input value='' class='regular-text' name='p_total'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Balance</th>"+
						"<td><input value='' class='regular-text' name='p_bal'></td>"+
					"</tr>"+
									"<tr>"+
						"<th>Due Date</th>"+
						"<td><input value='' class='MyDate hasDatepicker' name='p_duedate' id='dp1458535185551'></td>"+
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
							"</tbody></table>");
    });

});

function invHideLightbox() {
	jQuery("#inventory_blur, #inventory_lightwrap").fadeOut();
	jQuery("#inventory_lightwrap img").css("width", "auto");
}
}


