var site = {
    //homeURL: siteVars.homeURL,
    //themeURL: siteVars.themeURL,
    init: function() {

        // SVG things
        //site.replaceSVGs();
        //site.initSVG();

        // Size things

        // Init things

    },

    onResize: function(){

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

    }

};
jQuery(document).ready(function($){

    site.init();
    jQuery(window).resize(function(){
        site.onResize();
    });
});
