var store = {
	ajaxURL: siteVars.ajaxURL,
    init: function() {

		store.formatFilterMenu();
		store.addToCart();
		store.removeFromCart();

	},

	onResize: function(){
		
	},

/*
 * 	This function reformats the filter menu
 * 	so that active elements are represented
 * 	and replaces each menu link href so it
 * 	filters properly when clicked
 */
	formatFilterMenu: function(){

		// explode URL, clean
		var currentURL = window.location.pathname.split( '/' );
		    currentURL = currentURL.filter(function(el){ return el.length; });

		// if currently in a category filter...
		if ( currentURL.indexOf('product-category') > -1 ) {

			// get the current filter
			var currentFilter = currentURL[(currentURL.length - 1)];

			// split filter by term
			currentFilter.split('+').forEach(function(term){

				// add active classes to each term within the URL
				// ( By default only the first term will receive an active class )
				jQuery('.filter-menu .sub-menu a[href$="' + term + '/"]').closest('li').addClass('current-menu-item');

			});

			// loop through filter menu
			jQuery('.filter-menu .sub-menu a').each(function(){

				// explode and clean this href
				var href = jQuery(this).attr('href');
				href = href.split('/').filter(function(el){ return el.length; });

				// Append the current filter onto this href 
				var newFilter = href[(href.length - 1)] + '+' + currentFilter;

				// if this menu item is a 'current' item...
				if ( jQuery(this).closest('li').hasClass('current-menu-item') ){

					// split newly built filter into terms
					var pieces = newFilter.split('+');

					// remove this term
					pieces = pieces.filter(function(piece){
						return piece !== href[(href.length - 1)];
					});

					// put filter back together,
					// now clicking active links will 'unfilter' that term
					newFilter = pieces.join('+');
				}

				// set term as last element in URL array
				href[(href.length - 1)] = newFilter;

				// concat secondary slash after protocol
				href = href.map(function(el){
					if ( el == 'http:' || el == 'https:' ) return el + '/';
					return el;
				});

				// rebuild URL and set as href
				jQuery(this).attr('href', href.join('/'));

			});

		}

	},

/*
 *	Call this whenever you want to rebuild the cart
 */
	refreshCart: function(){

		// add loading class to cart
		jQuery('#sidecart').addClass('loading');

		// run ajax to get mini-cart html
		jQuery.get(store.ajaxURL, { 
    		    action:  'get_minicart'
            })
			.success(function(data){

				// switch out mini-cart
				jQuery('#sidecart').replaceWith( jQuery(data) );
				
				// Update cart count
				var cartCount = jQuery(data).data('cart-count');
				jQuery('#cart-count').text(cartCount);

			})
			.always(function(){

				// remove loading class
				jQuery('#sidecart').removeClass('loading');

			});

	},

	addToCart: function(){

		// click any add to cart button, unless loading
		jQuery(document).on('click', 'button.add-to-cart:not(.loading button)', function(e){
			e.preventDefault();

			// cache elements, get url
			var $el = jQuery(this);
			var $product = $el.closest('.product');
			var url = $el.data('url');

			// add loading class
			$product.addClass('loading');

			// run ajax to add product
			jQuery.get(url)
				.success(function(data){

					// rebuild cart
					store.refreshCart();

				})
				.always(function(){

					// remove loading
					$product.removeClass('loading');

				});

		});

	},

	removeFromCart: function(){

		// click a remove from cart button, unless loading...
		jQuery(document).on('click', 'button.remove-from-cart:not(.loading button)', function(e){
			e.preventDefault();

			// get removal URL
			var url = jQuery(this).data('url');

			// make request
			jQuery.get(url)
				.success(function(body){

					// rebuild cart
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