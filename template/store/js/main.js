var store = {
	ajaxURL: siteVars.ajaxURL,
	categoryBaseURL: siteVars.categoryBaseURL,	
    init: function() {

		store.formatFilterMenu();
		store.addToCart();
		store.removeFromCart();

	},

	onResize: function(){
		
	},

/*
 * 	This function formats a filter menu so that active elements are represented
 * 	and replaces each menu items href so it filters properly when clicked
 */
	formatFilterMenu: function(){
        
        // Get current active terms from window.location
        var currentTerms = store.getCurrentTerms();

        // Get menu items to treat as reset buttons, or skip them altogether
        var $resetItems = jQuery('.filter-menu .reset-item]');
        var $skipItems = jQuery('.filter-menu .skip-item');
		        
        // Add data attributes and 
        $filterItems = jQuery('.filter-menu a[href^="'+store.categoryBaseURL+'"]').not('.reset-item > a, .skip-item > a');
        $filterItems.each(function(){
    		var $link = jQuery(this);
    		var $menuItem = jQuery(this).closest('li');
    		
    		// Parse menu items terms into array
    		var href = store.getCurrentTerms($link.attr('href'));
                
            // Get the link's last term (so to avoid nesting), and set it as data attribute.
            var newTerm = href.pop();
            var newTermIndex = currentTerms.indexOf(newTerm); 
            
            // Account for toggeling off an active filter            
            if( newTermIndex > -1  ) {
                // Remove new term from href
                var terms = store.removeTerm(newTerm);
            } else {
                // Add new term to href (replace trialing slashes before adding term)
                var terms = store.addTerm(newTerm);
            }
            
            // Add class if menu item term is currently present in URL
            if( jQuery.inArray( newTerm, currentTerms ) > -1 ) {
                $menuItem.addClass('current-menu-item');
            }
            
            // Add new URL and class to menu item
            var url = store.buildFilterUrl(terms);
            $link.attr( 'href', url);               
            $menuItem.attr('data-filter', newTerm).addClass('filter-item');            
        });

        // Make sure no reset-item has an active class, if a sibling is active
        $resetItems.each(function(){
            $resetItem = jQuery(this);
            
            // Check if a sibling is active
            if( $resetItem.siblings('.current-menu-item').length ) {
                $resetItem.removeClass('current-menu-item');
            } else {
                // If no sibling, then mark the item active (as it is the "All" item
                $resetItem.addClass('current-menu-item');                
            }

            // Make each reset item's href removes all siblings terms, but leave other terms in it            
            var currentTerms = store.getCurrentTerms();
            var siblingsTerms = store.getSiblingsTerms($resetItem);
            var diffTerms = store.arraySubtract(currentTerms, siblingsTerms);            
            
            // Build URL
            var url = store.buildFilterUrl(diffTerms);
            $resetItem.find('a').attr('href', url);
        });


	},
	
	// Given a URL, it parses out the term1+term2 part of the URL always. Returns an array of those terms.
	getCurrentTerms: function(url){
		url = url || window.location.href;
		
		// Get just the terms part of URL
        url = url.replace(store.categoryBaseURL, '')
        
        // Are we splitting by a / or a + (to work on a fresh reload)?
		var delim = '/';
		if( url.indexOf('+') > -1 ) {
    		delim = '+';
    		
            // Replace all slashes
            url = url.replace(/\//g, '');
		}
        
		// Build array of all current terms
		var currentTerms = url.split(delim).filter(function(el){ return el.length; });    	
		return currentTerms;
	},
	
	// Given a filter menu item, it returns all the terms for it's siblings
	getSiblingsTerms: function($menuItem){
    	var terms = [];
    	$menuItem.siblings('.filter-item').each(function(){
        	terms.push(jQuery(this).attr('data-filter'));
    	});
    	
    	return terms;
	},
	
	// This function builds out a URL/term1+term2
	buildFilterUrl: function(terms, baseURL){
    	baseURL = baseURL || store.categoryBaseURL;
    	terms = terms || store.getCurrentTerms();
        
        // Make sure terms are unique
        terms = store.arrayUnique(terms);
        
        // Make sure we arn't adding too many slashes to end of URL, so we remove it just to be sure, then add it back.
        baseURL = baseURL.replace(/\/+$/, '');
        return baseURL + '/' + terms.join('+');
	},	
	
	// Helper function to add an element to a array
	addTerm: function(add, stack){
    	var array = stack || store.getCurrentTerms();
    	array.push(add);
    	return array;
	},

	// Helper function to remove an element to a array
	removeTerm: function(remove, stack){
    	var array = stack || store.getCurrentTerms();
        
        var index = array.indexOf(remove);
        if (index > -1) {
            array.splice(index, 1);
        }
        return array;
	},

	// Helper function to subtract one array from another, and return the result
	arraySubtract: function(stack, remove){
        var array = stack.filter( function(el) {
          return remove.indexOf( el ) < 0;
        }); 
        return array;	
	},

	// Helper function to make sure an array never contains the same stirng twice
	arrayUnique: function(stack){
        var array = stack.filter(function(item, pos, self) {
            return self.indexOf(item) == pos;
        });
        return array;
	},

    // Call this whenever you want to refresh the sidecart
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
    
    // Binds the click event on an add to cart button.
	addToCart: function(){

		// click any add to cart <button>, unless loading
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

    // Binds the click event on a remove from cart button.
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