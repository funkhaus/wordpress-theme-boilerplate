var store = {
	ajaxURL: site_vars.ajaxURL,
    init: function() {

		store.formatFilterMenu();

	},

	onResize: function(){
		
	},

	formatFilterMenu: function(){

		// explode URL, clean
		var currentURL = window.location.pathname.split( '/' );
		currentURL = currentURL.filter(function(el){ return el.length; });

		// if currently in a category filter...
		if ( currentURL.indexOf('product-category') > -1 ) {

			// get the current filter
			var currentFilter = currentURL[(currentURL.length - 1)];

			currentFilter.split('+').forEach(function(term){

				jQuery('.filter-menu .sub-menu a[href$="' + term + '/"]').closest('li').addClass('current-menu-item');

			});

			// loop through filter menu
			jQuery('.filter-menu .sub-menu a').each(function(){

				// skip if this is a current item
				if ( jQuery(this).closest('li').hasClass('current-menu-item') ){
					return;
				}

				// explode and clean this href
				var href = jQuery(this).attr('href');
				href = href.split('/').filter(function(el){ return el.length; });

				// set term as last array element
				href[(href.length - 1)] = href[(href.length - 1)] + '+' + currentFilter;

				// remove protocol from array
				href = href.filter(function(el){
					return el !== 'http:' && el !== 'https:';
				});

				// rebuild URL
				var newHref = 'http://' + href.join('/');

				// add new URL to link
				jQuery(this).attr('href', newHref);

			});

		}

		//console.log(currentURL);

	},

	refreshCart: function(){

		jQuery('#sidecart').addClass('loading');

		jQuery.get(store.ajaxURL, { action:  'get_minicart'})
			.success(function(body){

				jQuery('#sidecart').replaceWith( jQuery(body) );

			});

	}

};
jQuery(document).ready(function($){

	store.init();
	jQuery(window).resize(function(){
		store.onResize();
	});

});