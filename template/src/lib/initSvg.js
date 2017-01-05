var $ = require('jquery');

module.exports = {
    run: function(prop){

        // init global cache object and assign local var
        var cache = this.svgCache = this.svgCache || {};

        // Set total and counter
        var $svgs = $('img.svg');
        var total = $svgs.length;
        var count = 0;

        // If no SVGs on page, fire callback event
        if ( total === count ) $(document).trigger('svgsLoaded', [count]);

        // define function to replace single svg
        var replaceSVG = function( data ){

            // get img and attributes
            var $img = $(this),
                attributes = $img.prop("attributes");

			// Increment counter
			count++;

            // Clone the SVG tag, ignore the rest
            var $svg = $(data).find('svg').clone();

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Loop through IMG attributes and add them to SVG
            $.each(attributes, function() {
                $svg.attr(this.name, this.value);
            });

            // Replace image with new SVG
            $img.replaceWith($svg);

			// If this is the last svg, fire callback event
			if ( total === count ) $(document).trigger('svgsLoaded', [count]);

        }

        // loop all svgs
        $svgs.each(function(){

            // get URL from this SVG
	        var imgURL = $(this).attr('src');

            // if not cached, make new AJAX request
            if ( ! cache[imgURL] ){
                cache[imgURL] = $.get(imgURL).promise();
            }

            // when we have SVG data, replace img with data
            cache[imgURL].done( replaceSVG.bind(this) );

		});

	}
}
