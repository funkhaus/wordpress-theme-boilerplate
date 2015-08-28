var store = {
	ajaxURL: site_vars.ajaxURL,
    init: function() {

		store.formatFilterMenu();
		store.addToCart();
		store.removeFromCart();

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

				// explode and clean this href
				var href = jQuery(this).attr('href');
				href = href.split('/').filter(function(el){ return el.length; });

				var newFilter = href[(href.length - 1)] + '+' + currentFilter;

				// skip if this is a current item
				if ( jQuery(this).closest('li').hasClass('current-menu-item') ){

					// split newly built filter
					var pieces = newFilter.split('+');

					// remove this element from filter
					pieces = pieces.filter(function(piece){
						return piece !== href[(href.length - 1)];
					});

					// put filter back together
					newFilter = pieces.join('+');
				}

				// set term as last array element
				href[(href.length - 1)] = newFilter;

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

			})
			.always(function(){

				jQuery('#sidecart').removeClass('loading');
				
			});

	},

	addToCart: function(){

		jQuery(document).on('click', 'button.add-to-cart:not(.loading button)', function(e){
			e.preventDefault();

			var $el = jQuery(this);
			var $product = $el.closest('.product');
			var url = $el.data('url');

			$product.addClass('loading');
			
			jQuery.get(url)
				.success(function(body){
					store.refreshCart();
				})
				.always(function(){
					$product.removeClass('loading');
				});
			
		});

	},

	removeFromCart: function(){

		jQuery(document).on('click', 'button.remove-from-cart:not(.loading button)', function(e){
			e.preventDefault();

			var url = jQuery(this).data('url');
			
			jQuery.get(url)
				.success(function(body){
					store.refreshCart();
				});

		});

	}

};
jQuery(document).ready(function($){

	store.init();
	jQuery(window).resize(function(){
		store.onResize();
	});

});