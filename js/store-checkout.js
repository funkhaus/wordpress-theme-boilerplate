var storeCheckout = {
	ajaxURL: site_vars.ajaxURL,
	init: function() {

		storeCheckout.controlViews();
		storeCheckout.controlBillFields();

	},

	onResize: function(){
		
	},

	controlViews: function(){

		jQuery(document).on('click', '.checkout-controls .switch', function(e){
			e.preventDefault();

			// get target phase
			var phase = jQuery(this).data('phase');

			// strip active classes
			jQuery('.checkout-controls .switch').removeClass('active');

			// set active class
			jQuery(this).addClass('active');

			// hide all views
			jQuery('form.checkout .view').hide();

			// show the target view
			jQuery('form.checkout .view[data-phase="' + phase + '"]').show();

		});

		jQuery('.checkout-controls .switch').first().click();

	},

	controlBillFields: function(){

		var toggleFields = function(){

			var checked = jQuery('#bill-to-different-address-checkbox').get(0).checked;

			if ( checked ){

				jQuery('.bill-fields').slideDown();

			} else {

				jQuery('.bill-fields').slideUp();

			}

		}

		// listen for checkbox toggle
		jQuery('#bill-to-different-address-checkbox').change(function(){

			toggleFields();

		});
		toggleFields();

	}

};
jQuery(document).ready(function($){

	storeCheckout.init();
	jQuery(window).resize(function(){
		storeCheckout.onResize();
	});

});