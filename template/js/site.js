var site = {
    //homeURL: siteVars.homeURL,
    //themeURL: siteVars.themeURL,
    winHeight : 0,
    winWidth : 0,
    resizeTimer : null,
    // Maximum times per second to call onResize
    resizeFPS : 60,
    // Scroll throttle
    scrollTimer : null,
    scrollFPS : 60,

    init: function() {

        site.winHeight = jQuery(window).height();
        site.winWidth = jQuery(window).width();

        // SVG things
        //site.replaceSVGs();
        //site.initSVG();

        // Padding things
        //site.createImageContainers();

        // Size things

        // Init things

    },

    onResize: function(){
        site.winHeight = jQuery(window).height();
        site.winWidth = jQuery(window).width();
    },

    onScroll: function(){

    },

    initSVG: function(prop){

        // init global cache object and assign local var
        var cache = this.svgCache = this.svgCache || {};

        // Set total and counter
        var $svgs = jQuery('img.svg');
        var total = $svgs.length;
        var count = 0;

        // If no SVGs on page, fire callback event
        if ( total === count ) jQuery(document).trigger('svgsLoaded', [count]);

        // define function to replace single svg
        var replaceSVG = function( data ){

            // get img and attributes
            var $img = jQuery(this),
                attributes = $img.prop("attributes");

			// Increment counter
			count++;

            // Clone the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg').clone();

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Loop through IMG attributes and add them to SVG
            jQuery.each(attributes, function() {
                $svg.attr(this.name, this.value);
            });

            // Replace image with new SVG
            $img.replaceWith($svg);

			// If this is the last svg, fire callback event
			if ( total === count ) jQuery(document).trigger('svgsLoaded', [count]);

        }

        // loop all svgs
        $svgs.each(function(){

            // get URL from this SVG
	        var imgURL = jQuery(this).attr('src');

            // if not cached, make new AJAX request
            if ( ! cache[imgURL] ){
                cache[imgURL] = jQuery.get(imgURL).promise();
            }

            // when we have SVG data, replace img with data
            cache[imgURL].done( replaceSVG.bind(this) );

		});

	},

    replaceSVGs: function(){

        if( jQuery('#content').hasClass('contact') ) {

            // Load email icons
            jQuery('a.email').each(function(){
                jQuery(this).prepend('<img class="svg" src="'+site.themeURL+'/images/icon-email.svg" /> ');
            });

            // Load map icons
            jQuery('a.map').each(function(){
                jQuery(this).prepend('<img class="svg" src="'+site.themeURL+'/images/icon-map.svg" /> ');
            });
        }

    },

    createImageContainers: function(){
        // Create responsive image containers for all entry images.
        // Images will take up the full size of their containers, so to style wrapped images,
        //  make sure to style `.image-wrap` instead of `img`
        jQuery('.entry > p > img').each(function() {
            // Ignore images marked as .no-img-wrap
            if ( ! jQuery(this).hasClass('no-img-wrap') ) {
                // Get image aspect ratio
                var ratio = (jQuery(this).attr('height') / jQuery(this).attr('width')) * 100;
                jQuery(this).
                    // Unwrap from <p> tags
                    unwrap()
                    // Wrap in appropriate divs
                    .wrap('<div class="image-wrap"><div class="responsive-padding"></div></div>')
                    // Image will take up the full size of its container
                    .css({
                        'height': 'auto',
                        'width' : '100%'
                    })
                    // Set up padding for image
                    .closest('.responsive-padding')
                    .css({
                        'padding-bottom': ratio + '%',
                        'height': 0
                    });
            }
        });
    }

};
jQuery(document).ready(function($){

    site.init();
    jQuery(window).resize(function(){
        clearTimeout(site.resizeTimer);
        site.resizeTimer = setTimeout(site.onResize, (1 / site.resizeFPS) * 1000);
    });
    jQuery(window).scroll(function(){
        clearTimeout(site.scrollTimer);
        site.scrollTimer = setTimeout(site.onResize, (1 / site.scrollFPS) * 1000)
    })
});
