jQuery(function($) {
	/**
	 * All delete links get a confirmation alert
	 */
	$('.inventorywrap a.delete').click(
		function() {
			var prompt = wpinventory.delete_prompt;
			var name = $(this).attr("data-name");
			if (typeof name != "undefined" && name != "") {
				prompt+= ' ' + wpinventory.delete_named + '"' + name + '"' + wpinventory.prompt_qm;
			} else {
				prompt+= ' ' + wpinventory.delete_general + wpinventory.prompt_qm;
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

	$('a.wpim_show_debug').click(
		function() {
			$(this).next().slideDown();
		}
	);
	
	/**
	 * Initialize the class for inventory upload
	 */
	WPInventoryUpload.init();
});

/**
 * The image / media upload class
 */
var WPInventoryUpload = {};

WPInventoryUpload = (function() {
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
		var html = '<p><label>' + wpinventory.title_label + ':</label><input type="text" class="widefat" name="media_title[' + field + ']" value="" /></p>'; 
		html+= '<p class="media_url"><label>' + wpinventory.url_label + '</label><span>' + src + '</span></p><a href="javascript:void(0);" data-count="' + field + '" class="delete">X</a>';
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
			html+= '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' + count + '" id="inventory-link-' + count + '" class="wpinventory-upload">Add New Image</a>';
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
					word = ($(this).attr("data-type") == 'media') ? wpinventory.media_label : wpinventory.image_label;
					$(this).after('<p class="sortnotice">Drag and drop ' + word + ' to change sort order</p>');
				}
			}
		});
	}

	
	return {
		init: function() {
			$ = jQuery;
			// Media upload functionality. Use live method, because add / edit can be created dynamically
			$(document).on('click', '.wpinventory-upload', function() {
				// Set the container element to ensure actions take place within container
				container = $(this).closest('td').find('.media-container');
				// Set the type.  media or image
				type = container.attr("data-type");
				word = (type == 'media') ? wpinventory.media_label : wpinventory.image_label;
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

