# Funkhaus Programming Style Guide

This is the Funkhaus programming style guide and WordPress template theme.

The purpose of this style guide is to provide guidance on building WordPress sites in the style Funkhaus has developed over the years. The aim is to make code readable, easy for fresh eyes to understand, and to standardize the many ways things can be done in WordPress.

## Table of Contents
1. **Setting Up**
   - [Theme Setup](#theme-setup)
   - [Store Considerations](#store-considerations)
   - [JavaScript Setup](#javascript-setup)
   - [CSS Setup](#css-setup)
   - [Markup Guidelines](#markup-guidelines)
   - [Plugins](#plugins)
   - [Template Routing](#template-routing)
   - [Image Sizes](#image-sizes)
   - [Open Graph Tags](#open-graph-tags)
1. **Front-End Guidelines**
   - [Whitespace](#whitespace)
   - [Z-Index](#z-index)
   - [SVGs](#svgs)
   - [Mobile & Responsive](#mobile--responsive)
1. **Working with Wordpress**
   - [Custom Functions](#custom-functions)
   - [Enqueue Scripts](#enqueue-scripts)
   - [Menus](#menus)
   - [Metaboxes](#metaboxes)
   - [Loops](#loops)
   - [Admin & Login Pages](#admin-login)
1. **Common Elements**
   - [Vimeo](#vimeo)
   - [Galleries](#galleries)
   - [Slideshows](#slideshows)
   - [Contact Pages](#contact-pages)
   - [Advanced Pagination](#advanced-pagination)
   - [GeoIP Detect](#geoip-detect)
1. **Testing**
   - [Basic Checklist](#basic-checklist)
   - [Code Checklist](#code-checklist)
1. **[To Do List](#to-do-list)**


___


## Setting Up

Below are some general guidelines to follow while you get started:

### Theme Setup

The theme directory name should be something short and indicative of the client's name, with the year the theme was built. For example, `bmw2015`, or `prettybird2015`. We do it this way so we can quickly tell how old a code base is, and easily build a new theme years later, and not worry about local caching of files.

Generally, the structure of your theme directory will look like this:

```
clientname2015
	/fonts
	/images
		logo.svg
	/css
		breakpoints.css
		admin.css
		login.css
	/js
		clientname2015.js
	style.css
	index.php
	header.php
	footer.php
```

#### File Naming

Any components that will be used on multiple pages should be abstracted into a separate file prefixed with the word "part." Common examples might be:
- `part-newsletter-signup.php`
- `part-slideshow.php`

All page template files should be prefixed with the word `template-`. Common examples:
- `template-director-grid.php`
- `template-video-detail.php`

Icons and image assets should be named describing what they are, not what they represent:

**Good:**

* `icon-envelope.svg`
* `icon-arrow-left.svg`

**Bad:**

* `icon-email.svg`
* `icon-previous.svg`

___

### Store Considerations

By default, the template contains extra boilerplate structure to account for Woocommerce pages and functionality. Every time a new site is started, make sure you determine whether or not the store/Woocommerce components will be needed. If you won't be needing them (most sites), follow these steps:

1. Edit [functions.php](/template/functions.php) and remove these lines:
```php
/*
 * Check if functions-store file exists, if so include it
 */
	if ( $store_funcs = locate_template('store/functions-store.php') ) {
		include( $store_funcs );
	}
```
2. Completely remove these two directories out of the template:

	- [/store](/template/store)
	- [/woocommerce](/template/woocommerce)

___

### JavaScript Setup

We put all the main JavaScript for the site in one file, named just like the theme: `bmw2015.js`, or `prettybrid2015.js`, etc.

To avoid any namespace issues and keep things simple, all the main logic of the site gets wrapped in one large object. The main js file on the site might look something like this in its most stripped-down form:
```javascript
var client2015 = {
    init: function() {
        client2015.firstFunction();
		client2015.secondFunction();
		client2015.thirdFunction();
    },
    firstFunction: function(){
		
    },
	secondFunction: function(){
		
    },
	thirdFunction: function(){

    }
};
jQuery(document).ready(function($){
    client2015.init();
});
```

This also allows you to turn off entire sections of the code, or change the run order, which makes debugging easier. It has the added benefit of providing a clear way to safely set global values:
```javascript
var client2015 = {
	homeURL: client2015_vars.homeURL,
	themeURL: client2015_vars.themeURL,
	winHeight: null,
	winwidth: null,	
    init: function() {
		// Size things
		client2015.setWinSize();
    },
    
    setWinSize: function({
	    // Set window size
	    client2015.winHeight = jQuery(window).height();
	    client2015.winWidth = jQuery(window).width();
    }
};
jQuery(document).ready(function($){
    client2015.init();
    jQuery(window).resize(function(){
    	client2015.onResize();
    });
});
```
In this example we're setting globals brought in from Wordpress (via [wp_localize_script](https://codex.wordpress.org/Function_Reference/wp_localize_script)) and setting them at the top. We're also setting the window dimensions globally every time the browser is resized. Be sure to set any global values at the top of the script in order to make it obvious that they exist.

Have a look through the [main JS file](template/js/site.js) in the template for a more in-depth example of how this js structure can be expanded.


___

### CSS Setup

Class names should all be lower case, with hyphens as spaces. So use `work-grid`, not `WorkGrid` or `work_grid`.

ID's should be used very sparingly, and the mostly for top level elements. Some acceptable examples are `#menu`, `#sidebar` or `#overlay`.

When defining styles try to keep things as simple as possible. Overcomplicated css definitetions are [more taxing on the browser](https://developers.google.com/speed/docs/best-practices/rendering#UseEfficientCSSSelectors) and make it more difficult to fix problems down the road. So for example:

```css
/* This is good */
.work-grid .title {
	font-size: 120%;
}

/* This is bad */
#container #content.work-grid .block .title {
	font-size: 120%;
}
```

#### Preprocessors and Resets
When it comes to CSS, our current philosophy is to avoid using any frameworks, preprocessors, or resets of any kind. We have a very minimal amount of boilerplate code to start with in our [stylesheet](/template/style.css) and it allows us to keep our CSS files very slim.

In general when a site is finished the stylesheet should be between 800 and 1500 lines. It's acceptable to be a little outside of this range depending on the size of the site, but if you find your stylesheet is well above this range then you are most likely doing something wrong.

#### CSS naming conventions

We like to use a semantic approach to CSS up to a certain point. The idea is for you to be able to read the CSS and get some idea of what the HTML would look like. In most cases we avoid making extremely general classes, doing things like `.three-col`, `.blue_font`, or `.largeText` is bad. We'd rather things be intuitive and easy to read when going through the stylesheet.

Here are some base style names we commonly use: 
* `.block`
* `.section`
* `.grid`
* `.detail`
* `.title`
* `.credit`
* `.meta`
* `.browse`
* `.component`

We use variations of these to describe different parts of the site (i.e. `.director-grid` or `.director-detail`).


```css
/* Good */
.work-block {
	display: inline-block;
}
.work-block .title {
	font-size: 120%;
	color: red;
	margin-bottom: 10px;
	text-transform: uppercase;
	text-align: center;
}

/* Bad */
.uppercase {
	text-transform: uppercase;	
}
.red {
	color: red;	
}
.center-align {
	text-align: center;
}
.work-block .title {
	margin-bottom: 10px;
}
.column_1 {
	
}
.priority-2 {
	
}
.largeText {
	
}
```

There are a few class names that should *always* be used in certain situations:

* `.entry`
  - every time you use **the_content()** it should be wrapped in an element with this class.
* `.grid` or `.grid-X` where **X** is the type of grid being used.
  - any time you are displaying a loop of posts or pages, wrap the loop in this class
* `.block`
  - Use this for individual elements within a grid.
* `#content.template-name` where **template-name** describes what template is being used.
  - Every template should have all the main content in a `#content` div
  - Every `#content` div should have a class name like `.contact` or `.video-detail`, describing what page template is being used.

#### Style Sheet Struture

Our preferred approach with CSS is to structure it similar to the sites' visual structure. So things that appear at the top of the browser window, should be higher in the CSS document. This makes it faster to find a section of code, based on the visual hierarchy of the site.

This also applies to individual elements too, so when defining any elements try to keep the visual hierarchy in mind. For example:

**Example Markup**

```html
<div id="content" class="work-detail">
	<div class="media-player">
		<iframe>
	</div>
	<div class="meta">
		<h2 class="title">Steven Spielberg</h2>
		<h3 class="credit">Director</h3>
	</div>
	<div class="entry">
		Text in here.
	</div>
</div>
```

**Example CSS**

```css
#content.work-detail {
	margin: auto;
	max-width: 1100px;
}
.media-player {
	height: 500px;
}
.meta {
	margin: auto;
	max-width: 800px;
}
.entry {
	margin-top: 50px;
}
```

**Animations**

If possible, try to group transitions/animation definitions into one area at the bottom. These are common definitions and it helps to standardize their application on elements. It's quite common for a feedback note to be "make all hover states faster", so having all the transitions in one place makes that a very simple change.

Don't do this:
```css
.page .grid-block img {
    width: 100%;
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.grid-block a {
	display: inline-block;
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
```

Do this:
```css
/*
 * Animations
 */
	.page .grid-block img,
	.grid-block a {
	    -webkit-transition: all 0.3s ease;
	    transition: all 0.3s ease;
	}
```

Check out [the stylesheet in the template](template/style.css) folder to see a more in-depth example of how the stylesheet should be laid out.

___

### Markup Guidelines
All HTML should be concise, semantic, and use as few wrapping elements as possible. Here are a few strict guidelines we follow for specific tags:
- `H1`: Should almost never be used, H1 is reserved for the site title only.
- `H2`: This should be the page title for each page, i.e. "contact" or "Directors."
- `H3`: Should work well as a secondary headline on any page with body copy. In the design phase an H3 style will be mocked up inside a blog post.
- `div`: Should be the main building block of the site.
- `address`: Any street address areas should be wrapped in this tag.
- `HTML5 tags`: We don't use these very often, but feel free to use them in a way that makes sense.

The overall page structure should be fairly minimal. Use a `#container` div to wrap the page **or** a `#wrapper` div. There is no need to have both in most situations. Here is some simplified pseudo-HTML that would represent how any given page should be setup:

```html
<html>
<head>
	<script/meta/link tags go here>
</head>
<body>
	<div id="container">
		<div id="header">
			A top menu and logo would usually go in here.
		</div>

		<div id="content" class="template-name">

			<div id="post-48">
				<h2>Post title here</h2>
				<div class="entry">
					Post content in here
				</div>				
			</div>

		</div>
		<div id="footer">
			Put this outside of container if sticky footer is needed.
		</div>
	</div>
</body>
</html>
```

#### Sticky Footers

It's very common for our sites to require a sticky footer (a footer that remains attached to the bottom of the document regardless of the content length.) Here is a basic template for achieving that, we do not include it in the template by default.

__Markup:__
```html
<html>
<body>
	<div id="container">
    	<div id="content"></div>
	</div>
	<div id="footer"></div>
</body>
</html>
```

__CSS:__
```css
html, body {
	height: 100%;
	padding: 0;
	margin: 0;
}
#container {
	min-height: 100%;
}
#content {
	padding-bottom: 100px; /* height of footer */    
}
#footer {
	margin-top: -100px; /* negative value of footer height */
	height: 100px;
}
```

___

### Plugins

We take an extremely conservative approach to using Wordpress and js plugins. Generally the rule is if you can build it yourself relatively easily, then you don't need a plugin for it. This helps keep the code reliable and prevents situations where the site might stop working altogether. It also minimizes the learning curve for any fresh eyes looking at the code.

The most common Wordpress plugin to avoid is "Advanced Custom Fields." It's such a big plugin, and it quickly becomes a critical part of a site. If it breaks or stops being supported, the site breaks. We've never had a situation that can't be solved by a combination of page hierachy and custom metaboxes.

Here's a list of common plugins that we *do* use:

1. [Simple Page Ordering](https://wordpress.org/plugins/simple-page-ordering/)
1. [Cycle2](http://jquery.malsup.com/cycle2/api/) (Don't use HTML data attributes, use it as jQuery('.slides').cycle() )
1. [CaroFredSel](http://docs.dev7studios.com/caroufredsel-old/) (Although Cycle2 is better in most circumstances)
1. [Vimeo jQuery API](https://github.com/jrue/Vimeo-jQuery-API)
1. [FitVids](https://github.com/davatron5000/FitVids.js)
1. [Velocity](http://julian.com/research/velocity/)

___

### Template Routing

The way Funkhaus does template selection is a little different than most. Normally you'd rely on [custom page templates](https://codex.wordpress.org/Page_Templates#Custom_Page_Template), but that gets very repetitive for the user when building a page-heavy site.

We use parent/child relationships to conditionally determine what template should be used, and we add all the logic into `page.php`. Here is a simplified example of what that file might look like:

```php
global $post;
switch (true) {
    case is_page('work') :
        get_template_part('template-work');
        break;

    case has_children($post->ID) :
		// If has child pages
        get_template_part('template-work-grid');
        break;

    default:
        get_template_part('index');
        break;
}
```

If you are building a theme that has several variations of single.php, or category.php, you should use a similar technique for them too.

___

### Image sizes

When handeling images in WordPress, you'll generally need to define a set of sizes in functions.php and then another set under Settings > Media.

In functions.php we will generally define sizes as "grid-thumb," "work-thumb," "video-thumb," etc.

```
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
add_image_size( 'grid-thumb', 566, 250, true ); // Medium thumbnail size used on work girds
add_image_size( 'fullscreen', 1920, 1080, true ); // Large image used on homepage slideshows etc.
add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used for Facebook shares
```

As for the sizes in Settings > Media, we set the width for both "medium" and "large" to be the maximum content width for the site. The height of "medium" will be a [16:9 ratio](http://size43.com/jqueryVideoTool.html) of that width, and the height of "large" will be unlimited (so set it to 0). Thumbnails we generally set to something small and square, like 250px X 250px.

Assuming that the max-width of .entry is 800px, you'd have the following media settings in the backend:

```
Thumbnail size
Width: 250px | Height: 250px | Crop: True

Medium size
Max Width: 800px | Max Height: 450px

Large size
Max Width: 800px | Max Height: 0
```

And then be sure to set the `$content_width` global, so that WordPress knows how to size oEmbeds. You'd do that in functions.php like so:

```
function set_content_width() {
	global $content_width;
	if ( is_single() ) {
		$content_width = 800;
	} else {
		$content_width = 1100;
	}
}
add_action( 'template_redirect', 'set_content_width' );
```

___

### Open Graph Tags

The boilerplate theme template has a file called `part-facebook-tags.php` that generates them for you mostly. It relies on the `social-preview` image size being generated in `functions.php`.

___

## Front-End Guidelines
Below are some general guidelines for formatting your code in a way that keeps it easily usable for our team. By far the most important thing when it comes to code etiquette is commenting. **Comment everything,** even if it seems obvious to you it needs to be commented. This is especially true for everything in both the main javascript file and the functions.php file.

### Whitespace

Whitespace in code is very important, but there are a wide variety of approaches people take. This is how we like it to look, in an effort to make code consistent and readable.

___

#### CSS
All CSS should have 4-space tabs and everything should be on new lines. Whenever making a list of selectors be sure to have a line-break between each one. Selectors should be spaced with hyphens, like `.video-detail` or `.video-grid`.

```css
/*
 * Section heading
 */
	.class-name {
		font-size: 100%;
	}


/*
 * Another section heading
 */	
	.another-class-name {
		font-size: 100%;
		text-transform: uppercase;
	}
	.one-more-class-name {
		font-size: 120%;
		text-transform: none;
	}
```

#### JavaScript
All javascript should also have 4-space tabs. New line for everything. Extremely liberal use of comments. Try to group similar functions in one comment. Variables should be camel cased like `initMenu` or `initVideoDetail`.

```javascript
// Cache image
var $svgs = jQuery('img.svg');

// Convert linked SVG to embedded SVG's
$svgs.each(function(){
	
	// Cache things
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');
	
	// Get SVG XML from the server
    jQuery.get(imgURL, function(data) {
			        
        // Get the SVG tag, ignore the rest
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID and classes to the new SVG
        if(typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        if(typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass+' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Replace image with new SVG
        $img.replaceWith($svg);
    });

});
```

#### PHP
4-space tabs. Everything indented. New lines for anything opening or closing. New line for major code separations. Variables should use underscores like `$attachment_id`.

```html
<div>

	<?php
		// Argument arrays declared on multple lines, properly spaced.
	    $args = array(
	        'post_type'        => 'page',
	    	'orderby'          => 'menu_order',
	    	'posts_per_page'   => -1,
	    	'post_parent'      => $post->ID,
	    	'order'            => 'ASC'
	    );
	    $posts = get_posts($args);		
	?>	
	<?php foreach($posts as $post) : setup_postdata($post); ?>
		<p>
			Four space tabs, opening and closing tags (including <?php ?>) on new lines.
		</p>
	<?php endforeach; ?>

</div>
```

For when inside function.php for example. 4 space tabs. New lines for everything. Single quotes for parameters. New lines for opening and closing of PHP (if required). Use [output buffering](http://stackoverflow.com/a/4402045/503546) for anything major that needs to be returned, rather than concatenating a string (so never do `$output .= '<div class="example">Something</div>'`).

```php
/*
 * Add custom metabox to the new/edit page
 */
    function custom2015_add_metaboxes(){
        add_meta_box('custom_media_meta', 'Media Meta', 'custom_media_meta', 'page', 'normal', 'low');     
    }
    add_action('add_meta_boxes', 'custom2015_add_metaboxes');    

    // Media meta box
    function custom_media_meta() {
        global $post;

        ?>
        	<div class="custom-meta">
				<label for="video-url">Enter the video URL for this page:</label>            
				<input id="video-url" class="short" title="This is needed for all video pages" name="_custom_video_url" type="text" value="<?php echo $post->_custom_video_url; ?>">
				<br/>				
        	</div>
        <?php
    }
```

In PHP, we try to use long form where possible, to aid in readability.

```php
// This is good
if( $foo ) {
	return true;
}

// This is bad
if($foo) return true;

if($foo) 
	return true;
```

Extending this further, here is an example of what **not** to do:

```php
<?php

	foreach ( $images as $image ) : $count++;
		$class = 'thumb';
		if ( $total === 1 ) continue; // skip if only child
		if ( $count == 1 ) $class .= ' active';

			echo wp_get_attachment_image($image->ID, 'store-gallery-thumb', false, array( 'class' => $class, 'data-image-id' => $image->ID ));

	endforeach; ?>
```

And here is that same code, but in a way more readable format and debuggable format:

```php
// This is a correct example of the above code
<?php
	foreach ( $images as $image ) {
		
		// Set vars
		$count++;
		$class = 'thumb';
		
		// Skip if only child	
		if ( $total === 1 ) {
			continue; 
		}
		
		// If on the first item, set class
		if ( $count == 1 ) {
			$class = 'active';
		} 
		
		// Set args
		$args = array( 
			'class' 		=> $class,
			'data-image-id' => $image->ID 
		);
		
		// Show image
		echo wp_get_attachment_image($image->ID, 'store-gallery-thumb', false, $args);

	};
?>
```

For reasons of portability and consistency, we always use the default opening and closing tags in PHP:

```
// This is good:
<?php ?>

// This is bad
<?= ?>
<? ?>
<% %>
```
___

### Z-Index

When using `z-index` in CSS, we like to increment values in series of 10. So the first default z-index would be 0, then the next layer would be 10, and then 20 and so on.

If you have a floating header or footer, setting it to 100 or 110 is generally a good idea. Setting an overlay to 200 is accetable too.

If you use Cycle2, you'll notice that it uses 0-100 for it's z-indexing of slides. You can set the base level containing elment to be a low z-index to counter this.

If you find yourself setting z-index to 300 or above, then you should probably reconsider what you're doing.

___

### SVGs

You should never need to use a sprite or PNGs again! We simply use SVG's as an image, and use a few lines of jQuery to load in the SVG XML, as documented here.

If you use Adobe Illustrator to save out the SVG, be sure to not include the PDF preview in the SVG. It will increase the file size of the SVG by 10x or more.

First you include an SVG as an IMG tag:

```html
<img class="svg " src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">            
```

Then you'd place this code in your JS file. It's easy to modify this to work with a callback, once all SVG's are replaced.

```javascript
// Set total and counter 
var $svgs = jQuery('img.svg');

// Convert linked SVG to embedded SVG's
$svgs.each(function(){
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');

    jQuery.get(imgURL, function(data) {
			        
        // Get the SVG tag, ignore the rest
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID to the new SVG
        if(typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if(typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass+' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Replace image with new SVG
        $img.replaceWith($svg);
    });

});

```

Sometimes you'll want to add SVG to content that has been enetered in the by the user. A common example is for an email icon next to an person's name, or a map pin icon next to a link to an address. This can be done like below 

```javascript
if( jQuery('#content').hasClass('contact') ) {
    
    // Load email icons
    jQuery('a.email').each(function(){
        jQuery(this).prepend('<img class="svg" src="'+prettybird2015.themeURL+'/images/icon-email.svg" /> ');
    });

    // Load map icons
    jQuery('a.map').each(function(){
        jQuery(this).prepend('<img class="svg" src="'+prettybird2015.themeURL+'/images/icon-map.svg" /> ');
    });

}
```

Be sure to see the section on JavaScript and Enqueue Scripts to see where `prettybird2015.themeURL` comes from.

___

### Mobile & Responsive

For mobile styling we use a combination of user-agent detects and CSS media-query breakpoints.

#### Mobile Detects
By default in the [functions file](template/functions.php) there is a mobile test using [wp_is_mobile()](https://codex.wordpress.org/Function_Reference/wp_is_mobile) that adds a class to the body accordingly. If the user is viewing on mobile then the class **.is-mobile** will be added to the body and if not then the class **.not-mobile** will be added. This class is particularly useful in javascript to avoid running heavy animations on mobile. Here is a basic example:

```javascript
initParallax: function(){

	if ( jQuery('body').hasClass('not-mobile') ) {
		// run code to initialize parallax effect
	}

}
```

#### Break Points
All mobile-related CSS should be included in the [mobile.css](template/css/mobile.css) stylesheet within the template. The breakpoints will be somewhat specific to each site and design, but in general here is a good starting point. [We have prepared a working example for you here](http://labs.funkhausdesign.com/examples/responsive/index.html).

```css
 /* Smaller than an iPad portrait (so all phones) */
@media (max-width: 767px) {
    
}

/* Up to a tablet (includes iPads in landscape) */
@media (min-width: 768px) and (max-width: 1024px) {
    
}

/* Larger than a tablet, but smaller than a big screen (basicaly this is a MacBook Air) */
@media (min-width: 1025px) and (max-width: 1199px) {

}            

/* This is basically a decent laptop, up to an average external display */
@media (min-width: 1200px) and (max-width: 1799px) {

}               

/* Large external display and larger */
@media (min-width: 1800px) {

}            

```

Using this as a framework, you can then set the min-width of the site to be 350px and set your viewport width like this in the [head](template/header.php):
```html
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
```

___

## Working with Wordpress
In this section we'll focus on Wordpress-specific features and the way we approach them:

### Custom Functions

There are a few custom utility functions that we add to every site to help make things easier:

`is_tree($post_id)`: This function checks if global **$post** is within the ancestral tree of a specified page or post.

* There is 1 required parameter of this function, the ID of the target post

Example:
```php
$work = get_page_by_path('work');
if ( is_tree($work->ID) ) {
	echo 'This page is an ancestor of "Work"';
}
```


`has_children($post_id, $post_type)`: This function checks if the specified post has any child pages beneath it

* The first parameter is the ID of the post to test, and is optional. If no ID is supplied the global $post will be used.
* The second parameter is the post type, and is optional. This defaults to 'page' and should only be used for hierarchical custom post types when needed.
* This function is often used in conjunction with **is_tree()** for template routing

Example:
```php
$work = get_page_by_path('work');

if ( is_tree($work->ID) && has_children() ) {
	get_template_part('template-work-grid');
} else {
	get_template_part('template-work-detail');
}
```


`get_split_title($post_id)`: This function splits a title into parts and wraps each in a `<span>` tag.

* There is 1 required parameter of this function, the ID of the target post
* By default the delimiter by which to split the title is ` - `

Example:
```php
// assuming the post title is "Client name - Spot title"
echo get_split_title($post->ID);

// this will echo "<span class="line-1">Client name</span><span class="line-2">Spot title</span>"
```


`previous_project_link($html, $exclude)`: This function will return an html `<a>` tag that links to the previous page according to menu_order

* The first parameter is required and is the html to put into the a tag. Most of the time this will just be 'Previous Project' or something similar.
* The second parameter is optional and is an array of page IDs to exclude

Example:
```php
<div class="prev-next">
	<?php echo previous_project_link('Previous Project'); ?>
	<?php echo next_project_link('Next Project'); ?>
</div>
```

`next_project_link($html, $exclude)`: This function is exactly the same as **previous_project_link()**, but links to the next page rather than the previous.

Check out the [functions file in the template](template/functions.php) to get a better understanding of how each of these functions work.

___

### Enqueue Scripts

The stylesheet should normally be added in header.php like so:
```html
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v1.0" />
```

Each js script should be properly enqueued using `wp_enqueue_scripts`. For example, in functions.php you'd do something like this: 

```php
function custom_scripts() {
	wp_register_script('prettybird2015', get_template_directory_uri() . '/js/site.js', 'jquery', '1.0');
	wp_register_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', 'jquery', '2.1.5');
	wp_register_script('froogaloop2', 'http://a.vimeocdn.com/js/froogaloop2.min.js', 'jquery', '1.0');

	wp_enqueue_script('jquery');
	wp_enqueue_script('cycle2');
	wp_enqueue_script('froogaloop2');
	wp_enqueue_script('prettybird2015');

	// Setup JS variables in scripts. This can be used to pass in data from PHP to JavaScript. It is not always needed.
	wp_localize_script('prettybird2015', 'prettybird2015_vars', 
		array(
			'themeURL' => get_template_directory_uri(),
			'homeURL'  => home_url()
		)
	);
}
add_action('wp_enqueue_scripts', 'custom_scripts', 10);
```

Some additional notes:

* Be sure to set version numbers and dependencies. 
* Each `wp_register_script` and `wp_enqueue_script` should be on a new line.
* Avoid using any 3rd part CDNs unless it's a major web project (like Vimeo or Goggle Maps.)
* Be sure to check what scripts ship with WordPress (Masonry and jQuery are good examples).

If you need to call multple style sheets, you can register and enqueue them in a simalar way:

```php
function custom_styles() {

	if( wp_is_mobile() ) {
		wp_register_style('prettybird2015-mobile', get_template_directory_uri() . '/css/mobile.css');

		wp_enqueue_style('prettybird2015-mobile');

	}
}
add_action('wp_enqueue_scripts', 'custom_styles', 10);
```
___

### Menus

We try to use WordPress Menu's where possible. We only use generated menus based on page ordering when things like thumbnails are needed. So you'll generally use a `wp_nav_menu` in header.php of every site.

```php
    // Turn on menus in functions.php
    register_nav_menus(
    	array(
    	  'main_menu' => 'Main Menu',
    	)
	);
	
	// Then display them like so (in header.php generally):
	$args = array(
	    'container'         => 'false',
	    'menu'              => 'Main Menu',
	    'menu_id'           => 'main-menu', // You can change this depending on the context
	    'menu_class'        => 'main-menu menu' // Change the first class, but generally keep 'menu' always.
	);
	wp_nav_menu($args);
```

___

### Metaboxes

The most common thing you'll generally need is to save a video URL (usually Vimeo) to a page. THe best way to do that is via a custom field metabox. THe below code shows how to build a basic one, and then save the value. 

```php
/*
 * Add custom metabox to the new/edit page
 *
 * By using an underscore before the 'key' (eg: _customkey), WordPress will hide that custom field from the user in the default Custom Field metaboxe UI.
 *
 */
    function custom2015_add_metaboxes(){
        add_meta_box("custom_media_meta", "Media Meta", "custom_media_meta", "page", "normal", "low");     
    }
	add_action("add_meta_boxes", "custom2015_add_metaboxes");    
	
    // Build media metabox
    function custom_media_meta() {
        global $post;

        ?>
        	<div class="custom-meta">
				<label for="video-url">Enter the video URL for this page:</label>            
				<input id="video-url" class="short" title="This is needed for all video pages" name="_custom_video_url" type="text" value="<?php echo $post->_custom_video_url; ?>">
				<br/>				

        	</div>

        <?php
    }
    

/*
 * Save the metabox vaule
 */
    function custom2015_save_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( isset($_POST["_custom_video_url"]) ) {
	        update_post_meta($post_id, "_custom_video_url", $_POST["_custom_video_url"]);
        }

    }
    add_action('save_post', 'custom2015_save_metabox');
    
```

Sometimes you'll see examples on the internet which save mutlple vlaues in one key. This should eb avoided, as it becomes very hard to query for that parameter. For example:

```
// This is bad. Don't save mutlple values to one metakey.
<div class="custom-meta">
	<label for="video-url">Enter the video URL for this page:</label>            
	<input id="video-url" class="short" title="This is needed for all video pages" name="_custom_video[url]" type="text" value="<?php echo $post->_custom_video['url']; ?>">
	<br/>			
	
	<label for="video-credit">Enter credit information for this page:</label>            
	<input id="video-credit" class="short" title="This is needed for all video pages" name="_custom_video[credit]" type="text" value="<?php echo $post->_custom_video['credit']; ?>">
	<br/>					

</div>
```

___

### Loops
Here are some basic best-practices for writing loops in PHP.

#### Default Loop
When displaying a page with one loop, the default Wordpress patten is preferred:

```php
// Default loop. Good.
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		Content in here
	</div>

<?php endwhile; ?>
<?php endif; ?>
```

#### Secondary Loops
Often you'll need to do a secondary loop (eg. when displaying a grid of pages). You should *never* need to use `query_posts()` to accomplish this, instead use `get_posts()` and a foreach. The example below uses a `setup_postdata()`, which is often not needed, but shown to be equivalent to the above example.

```php
// get_posts loop. Good.
<?php
	// Get all children of this page
    $args = array(
        'post_type'        => 'page',
    	'orderby'          => 'menu_order',
    	'posts_per_page'   => -1,
    	'post_parent'      => $post->ID,
    	'order'            => 'ASC'
    );
    $posts = get_posts($args);
?>
<?php foreach($posts as $post) : setup_postdata($post); ?>
  
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		Content in here
	</div>

<?php endforeach; ?>     	
```

#### Paginated Loops
Sometimes you'll need to use WP_Query if you need pagination on a loop (with custom post types, or a related episodes grid). This is the preferred way to do that:

```php
<?php
	// Get paginated Episodes
	$args = array(
	    'post_type'        	=> array('episodes'),
		'posts_per_page'   	=> 12,
		'post_parent'	   	=> $post->post_parent,
		'order'			   	=> 'DESC',
		'orderby'		   	=> 'date',
		'exclude'		   	=> $post->ID,
		'paged'				=> max( 1, get_query_var('paged') )
	);
	$episodes_query = new WP_Query($args);
?>	
<?php if( $episodes_query->have_posts() ) : ?>
	<div id="content" class="episodes-grid">

		<?php while ( $episodes_query->have_posts() ) : $episodes_query->the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				Content in here
			</div>

		<?php endwhile; ?>
	
	</div>

	<?php build_pagination_links($episodes_query); ?>

<?php endif; ?>
```

Then in functions.php you'd define the `build_pagination_links` like this:

```php
/**
 * Return pagination links
 *
 * @param OBJ A WP_Query object
 */
	function build_pagination_links($wp_query = false){

		// Fallback to the default query
		if( empty($wp_query) ) {
			global $wp_query;
		}
		
		?>
			<div class="pagination-links">
				<?php
					// Setup vars used as per Codex
					$big = 999999999; // need an unlikely integer
				
					$args = array(
						'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' 	=> '?paged=%#%',
						'current' 	=> max( 1, get_query_var('paged') ),
						'total' 	=> $wp_query->max_num_pages,
						'prev_text' => 'Prev',
						'next_text' => 'Next'
					);
					echo paginate_links($args);
				?>
			</div>
		<?
	}
```

#### Nested Loops

Oftentimes you'll need to build out a case study type page, with a video player, and then various content sections down the page. Tradionally this will be done with a plugin like Advanced Custom Fields, but it's very easy to do that without need to use ACF or any plugins, which is what we prefer. So here is how we like to work with nested loops.

You'd build out a page structure in WordPress like below, as well as use [WordPress Post Formats](https://codex.wordpress.org/Post_Formats) to allow the user to pick what each page/section should look like.

```
work
	/case-study-1
		/videos
			/video-1
			/video-2
		/gallery
		/description

	/case-study-2
		/videos
			/video-1
			/video-2
		/gallery
		/description
```

Using the method discribed in the "Template Routing" section above, you put the following code in your case study template (probably called `template-case-study.php`. This code then loops through all the child pages of the case study, and uses template parts for each section type.


```PHP
<div id="content" class="case-study">

	<?php
        //Get all child pages of this page
        $args = array(
            'post_type'        => 'page',
        	'orderby'          => 'menu_order',
        	'posts_per_page'   => -1,
        	'post_parent'      => $post->ID,
        	'order'            => 'ASC'
        );
        $sections = get_posts( $args );

		// loop through each section
		foreach( $sections as $post ) { 

			// Setup post data for each section
			setup_postdata($post);

			// What sort of section is this?
			switch (true) {    			
				case has_post_format('video') :
					// Is a video section
					get_template_part('part-section-video');
		        	break;

				case has_post_format('gallery') :
					// Is a video section
					get_template_part('part-section-gallery');
		        	break;

				default :
					// Fallback to a text section
					get_template_part('part-section-text');
		        	break;						
			}

		}
		wp_reset_postdata();
	?>

</div>
```

Then inside each template part, you'd display a video player with thumbnails perhaps, or the gallery section, etc. For example, here is what `part-section-text.php` might look like:

```php
<div class="section text-section">
	<div class="entry">
		<?php the_content(); ?>
		<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
	</div>
</div>
```

___

### Admin & Login Pages

For every site we build, the Wordpress login page gets styled to match the front-end. This is a very simple process, and is partially built into the template. Once the template theme is installed you'll notice that the login page changes to a blank white page with no Wordpress logo and a greenish login button. 

To customize this, just open the [login.css](/template/css/login.css) file within the /css folder in the template. This is where all of the login page styling is done. Near the top of the file you'll see this style being applied:

```css
/*
 * Login Banner Image
 */
    #login h1 a { 
    	background: url(../images/backend/login-banner.png) no-repeat 9px 0;
    	margin: 0; 
    	background-size: auto;
    	width: auto;
    }
```

Upload a .png image of the company logo into the theme folder under /images/backend and name it "login-banner", it should now show up on the login page above the form.

* _Note that this image is usually included in the production kit._

After that, change the colors of the background, the input labels, and the submit button to match the primary colors of the site. All of these elements can be found pre-defined in the login.css stylesheet. Feel free to add more styling to the file as you see fit, anything defined in login.css will only be called on the login page.

___

## Common Elements
In this section we'll focus on a few elements that go into almost every site we build and the ways we approach them.

### Vimeo

We use Vimeo Pro for most websites we build. Make sure cleint has locked down thier account to exclude videos from Vimeo, and domain locked them tot he websites they want.

The [Vimeo jQuery API](https://github.com/jrue/Vimeo-jQuery-API) has made working with Vimeo much easier now. Best practice is to use that plugin now. Note that it includes the Froogaloop2 script files for you, so no need to enqueue them too.

There is also an example of using the manual [Froogaloop2 method here](http://labs.funkhausdesign.com/examples/vimeo/froogaloop2-api-basics.html).

When attempting to display a Vimeo video, you'll generally be using a URL from a saved metabox. You should use the [wp_oembed_get](http://codex.wordpress.org/Function_Reference/wp_oembed_get) function to do this, like so:

```
	<?php 
		// Show Vimeo video
		$args = array(
			'width' => 1280
		);
		echo wp_oembed_get($post->_custom_video_url, $args);
	?>
```

#### Scaling Video

There are many ways to scale Vimeo iframes. I've prepared [examples of many fo them here](http://labs.funkhausdesign.com/examples/video-sizes/index.html).

___

### Galleries

Using galleries effectively in WordPress requires the following in your functions.php file:

```
/*
* Enqueue Custom Gallery
   */
   function custom_gallery($atts) {
  	if ( !is_admin() ) {
  		include('part-gallery.php');
  	}
  	return $output;
   }
  add_shortcode('gallery', 'custom_gallery');
```	

And then in `part-gallery.php` you'd have code that looks something like this:


```php
<?php
	/*
	 * Build out gallery
	 */
		global $post;
		
		// This turns the atts into $vars
		extract(shortcode_atts(array(
			'order'   => 'ASC',
			'ids'     => '',
		), $atts));
		
		// Set default gallery args
		$args = array(
			'post_type'       => 'attachment',
			'post_mime_type'  => 'image',		
			'posts_per_page'  => -1,
			'post_status'     => 'inherit',
			'orderby' 		  => 'menu_order',
			'order'           => 'ASC',		
			'post_parent'     => $post->post_parent,
			'post__in'        => null
	    );
		
		// Should we get all images attached to the page, or just those specified?
	    if( !empty($ids) ) {
		    $args['post__in'] 		= explode(',', $ids);
		    $args['post_parent'] 	= null;
		    $args['orderby'] 		= 'post__in';
	    }
	
		// Get images
		$attachments = get_posts($args);
		$total = count($attachments);	
	
	    // Return false if no images attached
	    if($total == 0) {
	        return false;
	    }
	    
		// Start building the output. Use output buffering for speed!
		ob_start();
?>

	<div class="gallery">

		<div class="gallery-controls">
			<div class="browse prev">
				<img src="<?php echo get_template_directory_uri(); ?>/images/arrow-left.svg" class="svg arrow arrow-left" />
			</div>
			
			<div class="browse next">
				<img src="<?php echo get_template_directory_uri(); ?>/images/arrow-right.svg" class="svg arrow arrow-right" />
			</div>
		</div>

		<?php foreach ($attachments as $attachment) : ?>

		    <?php 
		        // Get image background URL
		        $attachmentData = wp_get_attachment_image_src( $attachment->ID, 'gallery-stage');
		        $imageURL = $attachmentData[0];
		    ?>
			
            <div class="gallery-item gallery-image cover" style="background-image: url('<?php echo $imageURL; ?>');">
            
				<?php echo wp_get_attachment_image( $attachment->ID, 'gallery-stage'); ?>
				
				<div class="caption">
					<?php echo $attachment->post_excerpt; ?>
				</div>
			</div>

		<?php endforeach; ?>

	</div>

<?php
	$output = ob_get_clean();
	return $output;
?>
```

___

### Slideshows

In general there are two main types of slideshows that we commonly have to build:

1. #### Single Slide:
Single slideshows are those that scroll only one element at a time. The most common occurrences of these are fullscreen slideshows for home pages, and image slideshows for post galleries. We use [Cycle2 for jQuery](http://jquery.malsup.com/cycle2/api/) to build all single slides. Here is an example of how a fullscreen slideshow might be built using cycle:

	__Markup:__
	```html
	<div class="slideshow">
		<div class="slide"></div>
		<div class="slide"></div>
		<div class="slide"></div>
	</div>
	```
	
	__CSS:__
	```css
		.slideshow {
			position: relative;
			overflow: hidden;
		}
		.slide {
			float: left;
		}
	```
	
	__Javascript:__
	```javascript
	jQuery('.slideshow').cycle({
		fx: 'scrollHorz',
		slides: '> .slide',
		speed: 1000
	});
	```
	
	To make the slideshow fullscreen, you would need to manually size the height within your resize handler:
	```javascript
	jQuery(window).resize(function(){
	
		jQuery('.slideshow').height( jQuery(window).height() );
	
	});
	```

	Occasionally you will need to make slideshows with transitions that are outside the scope of the normal cycle2 api. To do this you'll need to write a custom cycle transition plugin, using the [transition API](http://jquery.malsup.com/cycle2/api/advanced.php#transition).

2. #### Multiple Slide:
Multiple slideshows are those that scroll several elements at a time. The most common occurrences of these are thumbnail trays below video detail pages. To make multi-slide slideshows we use [carouFredSel](https://github.com/gilbitron/carouFredSel), here's a very basic example of what a thumbnail tray might look like:

	__Markup:__
	```html
	<div class="video-thumb-tray">
	
		<div class="slider">
			<a class="video-thumb" href="#linkToSpot">
				<img src="#thumbnail" class="thumbnail" />
			</a>
			<a class="video-thumb active" href="#linkToSpot">
				<img src="#thumbnail" class="thumbnail" />
			</a>
			<a class="video-thumb" href="#linkToSpot">
				<img src="#thumbnail" class="thumbnail" />
			</a>
		</div>
	
	</div>
	```
	
	__CSS:__
	```css
		.video-thumb-tray {
			position: absolute;
			overflow: hidden;
			margin: 0 60px;
			bottom: 0;
			right: 0;
			left: 0;
		}
		.video-thumb {
			display: inline-block;
			float: left;
		}
	```
	
	__Javascript:__
	```javascript
	if ( jQuery('.video-thumb-tray').length ) {
	
		jQuery('.slider').carouFredSel({
			circular: false,
			infinite: false,
			width: '100%',
			align: 'center',
			height: 100,
			items: {
				height: 100,
				width: 200,
				start: jQuery('.slider .active')
			},
			scroll: {
				duration: 400,
				items: 5
			},
			auto: false,
			prev: {
				key: 'left'
			},
			next: {
				key: 'right'
			},
			swipe: {
				onTouch: true,
				items: 1
			}
		});
	
	}
	```
	
	You can check out the full documentation for carouFredSel [here](http://docs.dev7studios.com/jquery-plugins/caroufredsel).

___

### Contact Pages

Social links (to Facebook etc) can just be hardcoded. Clients won't change these ever, so don't worry about build custom options pages for these things.

Contact pages are generally the most time consuming parts to build. We haven't come up with a great solution, but the one we have works and should be considered the standard unless you can pitch us something better.

Often a contact page will consist of a nested loop of pages (see the section above on loops). So the page structure in WordPress might look like this:

```
contact
	/locations
	/representation
	/staff
```

Whether its a nested loop, or just the single 'contact' page, we generally pre-populate the pages with a UL/LI list, and use a combination of H, A and/or SPAN tags (or HTML5 address et. al. tags).

So your HTML in the content editor might look like this:

```
<ul>
	<li>
		<a class="map" href="https://www.google.com/maps/place/example" target="_blank">Los Angeles</a>

		<address>
		1855 Industrial St.
		Los Angeles, CA 90013
		</address>		

		+12 345 678 9101
	</li>
	<li>
		<a class="email" href="mailto:example@funkhaus.us">Drew Baker</a>	
		<span class="job">Managing Director</span>

		<a class="email" href="mailto:example@funkhaus.us">Kim Darwin</a>	
		<span class="job">Business Affairs Manager</span>
	</li>
	<li>
		<a class="email" href="mailto:example@funkhaus.us">George Meeker</a>	
		<span class="job">Executive Producer / Partner</span>

		<a class="email" href="mailto:example@funkhaus.us">Sharon Morov</a>	
		<span class="job">Bidding Producer</span>
	</li>
	<li>
		<a class="email" href="mailto:example@funkhaus.us">Tara Riddell</a>	
		<span class="job">Executive Producer<br>
		Australia</span>
		
		<a class="email" href="mailto:example@funkhaus.us">Alex Johnson</a>
		<span class="job">Staff Coordinator</span>		
	</li>
</ul>
```

Then you'd use CSS to position each LI. Depending on the design, setting the LI's to be `display:inline-block` is handy, or set the UL to a FlexBox. [This playground helps with that](http://the-echoplex.net/flexyboxes/?fixed-height=on&display=flex&flex-direction=row&flex-wrap=wrap&justify-content=space-between&align-items=flex-start&align-content=center&order[]=0&flex-grow[]=0&flex-shrink[]=1&flex-basis[]=auto&align-self[]=auto&order[]=0&flex-grow[]=0&flex-shrink[]=1&flex-basis[]=auto&align-self[]=auto&order[]=0&flex-grow[]=0&flex-shrink[]=1&flex-basis[]=auto&align-self[]=auto).

You can then use SVG insertion (see the section in this document) to add in email or map icons.

When use the UL/LI approach, it's very important to disable the rich editor in WordPress for the contact page. You can do that with the below code:

```
/*
 * Disable Rich Editor on certain pages
 */
    function disabled_rich_editor($allow_rich_editor) {
	    global $post;
		
		// Disable rich editor on contact page
	    if($post->post_name == 'contact') {
		    return false;		    
	    }
	    return $allow_rich_editor;
    }	
    add_filter('user_can_richedit', 'disabled_rich_editor');
```

___

### Advanced Pagination

Occasionally a site will require some advanced navigation abilities. The most common example is a video detail page needing to have links to the previous and next detail pages. There is no built-in way to easily achieve this in Wordpress, so we've built our own (included in [functions.php](/template/functions.php#L320).)

To put these functions to use, just call `get_previous_page()` or `get_next_page()`, you can use them like this:
```php
<?php
$nextID = get_next_page(null, false);

if ( $nextID ) : ?>

	<h3><?php echo get_title($nextID); ?></h3>
	<?php wp_get_attachment_image( $nextID ); ?>

<?php endif: ?>

```

Each function has two option parameters:

1. __exclude:__ _(array)_ An array of post IDs to exclude. default: null
1. __loop:__ _(boolean)_ Specifies whether or not the query should loop. If set to true and you are currently viewing the last page, using `get_next_page()` will loop and return the first page.

#### Advanced Back Functionality
Sometimes there will be a need to provide users with a link on a single post to go back to the index that they came from. The difficult part of this is knowing where the user came from when there are multiple categories within the blog. Consider a blog with posts laid out like this:

__Main Blog:__

* Post #1
* Post #2
* Post #3

__Category A:__

* Post #1
* Post #3

__Category B:__

* Post #1
* Post #2

If a user clicks into post #1 and then clicks a link to go back to the main index, that may need to be a link to Category A, Category B, or the main blog page. The only way to know which page to link back to is by keeping track of which page the user came from. Here is a basic example of some code that might allow you to do that:

```php
	// start session if necessary
	function start_session() {
		if(!session_id()) {
			session_start();       
		}
	}
	add_action('init', 'start_session', 1);	

	// Update Cookie trail whenever viewing archive page
	function update_cookie_trail() {
		if ( is_archive() ) {
			$_SESSION['ref_category'] = get_query_var('cat');
		}
	}
	add_action('wp', 'update_cookie_trail');

	// Generate 'back' permalink based on cookie
	function get_index_permalink() {
		$ref_cat = $_SESSION['ref_category'];

		// fallback to the default blog category
		if ( ! $ref_cat ) $ref_cat = get_option('default_category');

		// return permalink for category
		return get_category_link($ref_url);
	}
```

If you place this code in the [theme functions file](/template/functions.php) it will set a cookie to track where the user came from each time a user views an archive page. Then you can safely use `get_index_permalink()` on a single post to get the URL where a back link should lead to.

___

### GeoIP Detect

Many of our clients have offices in several countries and require basic geolocation detection to serve up their website accordingly. There are a myriad of ways to achieve this, but our approach is to reference a database of IP addresses to estimate the user's location and then set a cookie to keep track.

The service we use for this purpose can be found [here](https://www.maxmind.com/en/geoip2-services-and-databases). There are two main PHP APIs provided by Max Mind, we choose to use the [legacy API](https://github.com/maxmind/geoip-api-php) because we're more familiar with it, but you may decide the [GeoIP2 API](http://maxmind.github.io/GeoIP2-php/) suits you better.

Here are some general steps to get you started, these examples are done using the legacy API:

1. Setup a general function to get any user's region based on IP:
```php
function get_geoip_region() {

	// This is the IP database, get country by IP
	include("GeoIP/geoip.inc");	
	$ip = $_SERVER['REMOTE_ADDR'];	

	$db = geoip_open( get_template_directory() . "/GeoIP/GeoIP-VERSION.dat", GEOIP_STANDARD );
	$country_code = strtolower( geoip_country_code_by_addr($db, $ip) );
	geoip_close($db);

	// Include country arrays
	include("functions/country-codes.php");
	
	// Uppercase code
	$country_code = strtoupper($country_code);

	if( in_array( $country_code, $europe) || in_array( $country_code, $africa) ) {
		// User is in Europe
		$region = 'uk';

	} elseif( in_array( $country_code, $asia) ) {
		// User is in Asia
		$region = 'asia';
		
	} else {
		// User is anywhere else, assume USA
		$region = 'usa';
	}

	return $region;
}
```
1. Next you might have a function to attempt to retrieve a user's region from a cookie, and fallback to the auto GeoIP method:
```php
function get_user_region(){
	// Get cookie's value
	if( isset($_COOKIE['region']) ) {
		return $_COOKIE['region'];

	// no cookie? detect automatically
	} else {
		// Save region cookie
		$region = get_geoip_region();
		setcookie('region', $region, time() + (86400 * 365)); // expires in 1 year
		return $region;
	}
}
```
1. Now you can use these two functions to direct a user as needed. You may for example have a function hooked to the "init" action that redirects a user based on their region:
```php
function redirect_by_region(){
	if( is_front_page() ){
		$region = get_user_region();

		wp_redirect( get_region_url($region) );
		exit();
    }
}
add_action( 'init', 'redirect_by_region' );
```

In this example `get_region_url()` would be a custom function that returns an internal permalink URL based on the region provided (i.e. 'usa', 'uk', or 'asia').

___

## Testing

Please be sure to pass all checklists before work is presented to Funkhaus. If a site can pass all these tests, then it is ready. Our desire is to push testing down to the vendors where possible, so that they may learn how we like things. We stole this concept from [Kelly Johnson](http://www.lockheedmartin.com/us/aeronautics/skunkworks/14rules.html), and last we checked he was good at building things.

All vendors are **required to QA and test their own code before presenting it to Funkhaus**.

The below checklists include examples(along with solutions), taken from real life sites submitted to us.

### Basic Checklist

The basic checklist is designed so that a non-tech person could do them. At Funkhaus, we just have our Project Manager run through these on our own projects. It is the basic level of QA testing, and should be completed before anything is sent to us for review.

1. Does it pass validation?
1. Are `overflow` scroll bars visible, or show up on resize when they shouldn't?
1. Does the site layout break at min-height or min-width on resize?
1. If there is a video thumb tray, does it break if you rapidly move your mouse on and off it?
1. Do the videos scale, and maintain aspect ratio, and don’t scale above 1280px wide?
1. Are the footers sticky? Should they be?
1. Does the category/news pages have pagination or infinite scroll built out?
1. If the design calls for a floating header on scroll, does it float correctly?
1. Does it look consistent in Chrome, Safari and Firefox? What about iOS on iPhones and iPads?  Android?

#### Does it pass validation?

Run the major parts of the site through the validator at https://validator.w3.org/nu/. 

It's fine to get non-critical errors/warnings like `An img element must have an alt attribute, except under certain conditions.` or the ones thrown by old attributes on the default Vimeo iframe embeds, like `The frameborder attribute on the iframe element is obsolete`. Another common one is it complaining about `&` symbols in Google Map URLs, those are fine to ignore. But anything else should be fixed, especially `Stray end tag` errors.

#### Are `overflow` scroll bars visible, or do they show up on resize when they shouldn't?

A common mistake we see is setting a fixed height on a `DIV`, and then on another browser scroll bars will be visible on that `DIV` due to variations in font sizing or line heights. This is most common with footers.

Make sure that as you resize the browser, scroll bars only appear on the right hand side of the screen on `body`.

### Code Checklist

The code checklist is what the Funkhaus tech team will be checking against. We will be looking at code, and judging it against the conventions laid out in this style guide.

1. Is the CSS too specific, or using `!important`?
1. Are you being consistent with CamelCase (for JS) vs Hyphen (for CSS) vs underscores (for PHP) [as detailed above](#whitespace)?
1. Where applicable, is page `menu_order` respected on grids and homepage?
1. Are CSS z-indexes well structured, and logical [as detailed above](https://github.com/funkhaus/style-guide#z-index)?
1. Is you code indenting and spacing consistent and readable, as per style the guide?
1. Make sure no clear fixes are used
1. Is your PHP well commented?
1. Make sure database I/O techniques avoid things like `$wpdb`, and that use the correct WordPress functions.
1. Are HTML wrapping elements kept to a minimum, and HTML structure is clean as possible?

#### Is the CSS too specific, or using `!important`?

We see CSS code like this a lot:

```
Bad:

body.UK_colours .contact-page .contact-bottom input {
   background-color: #12142D;    
}


Good:

.uk-colours .contact-bottom input {
   background-color: #12142D;    
}

```

And never use `!important` in CSS. If you need it, you've cut corners.


#### Make sure no clear fixes are used

A common issue we see is developers not understanding when to use a float and a clearfix, and when not too. In almost all cases, setting the containing DIV to `overflow:hidden` means you don't need to use a clearfix, and thus makes your code way cleaner, with less markup. A lot of the time (but not always), using a `display:inline-block` is a better move that using a `float` too.

___

### To Do List

1. Spelling and grammar check
1. Add more documentation of common bugs and testing procedures.