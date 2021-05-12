jQuery(document).ready(function() {

	jQuery('.add-data-field-button').hide();
	jQuery('.cdq-card .row').last().find('.add-data-field-button').show();


	setTimeout(
		function() 
		{
			jQuery('.cdq-notices').hide();
		}, 2500);


	jQuery('.data-type-field').each(function(){

		if (jQuery(this).val()=='text') {

			jQuery(this).parent().parent().find('.data-search-field').prop('disabled',false);

		}else{

			jQuery(this).parent().parent().find('.data-search-field').prop('checked',false);
			jQuery(this).parent().parent().find('.data-search-field').prop('disabled',true);

		}
	});
	

});

function addDataField(){

	jQuery('.cdq-card').append(jQuery('.copyfield').html());
	jQuery('.add-data-field-button').hide();
	jQuery('.cdq-card .row').last().find('.add-data-field-button').show();

	jQuery('.data-type-field').change(function(){

		if (jQuery(this).val()=='text') {

			jQuery(this).parent().parent().find('.data-search-field').prop('disabled',false);

		}else{

			jQuery(this).parent().parent().find('.data-search-field').prop('checked',false);
			jQuery(this).parent().parent().find('.data-search-field').prop('disabled',true);

		}
	});

}

function removeDataField(e){

	jQuery(e).parent().parent().remove();
	jQuery('.cdq-card .row').last().find('.add-data-field-button').show();

}

jQuery('#all-cdq-list').change(function(){

	if (jQuery('#all-cdq-list').prop('checked') == true) {
		jQuery("tbody .cdq-list-checkbox").prop('checked',true);
	}else{
		jQuery("tbody .cdq-list-checkbox").prop('checked',false);
	}

});


jQuery('.data-type-field').change(function(){

	if (jQuery(this).val()=='text') {

		jQuery(this).parent().parent().find('.data-search-field').prop('disabled',false);

	}else{

		jQuery(this).parent().parent().find('.data-search-field').prop('checked',false);
		jQuery(this).parent().parent().find('.data-search-field').prop('disabled',true);

	}
});



