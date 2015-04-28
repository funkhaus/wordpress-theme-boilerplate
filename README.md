# Funkhaus Programming Style Guide

This is the [Funkhaus](http://funkhaus.us) programming style guide and WordPress template theme.

The purpose of this style guide is to provide guidance on building WordPress sites in the style [Funkhaus](http://funkhaus.us) has developed over the years. The aim is to make code readable,  very easy for fresh eyes to understand, and standardize the many ways things can be done in WordPress.

## Table of Contents
1. [Theme Setup](#theme-setup)
1. [JavaScript Setup](#javascript-setup)
1. [Plugins](#plugins)
1. [CSS](#css)
1. [Template Routing](#template-routing)
1. [Whitespace](#whitespace)
1. [Enqueue Scripts](#enqueue-scripts)
1. [Loops](#loops)
1. [SVGs](#svgs)
1. [Menus](#menus)
1. [Metaboxes](#metaboxes)
1. [Vimeo](#vimeo)
1. [Galleries](#galleries)
1. [Image Sizes](#images-sizes)
1. [Slideshows](#slideshows)
1. [Z-Index](#z-index)
1. [Contact Pages](#contact-pages)
1. [Break Points](#break-points)
1. [Open Graph Tags](#open-graph-tags)
1. [Admin & Login Pages](#admin--login)
1. [Mobile & Responsive](#mobile--responsive)
1. [To Do List](#to-do-list)

___

## Theme Setup

The theme directory name should be something short and indicative of the client's name, with the year the theme was built. For example, `bmw2015`, or `prettybird2015`. We do it this way so we can quickly tell how old a code base is, and easily build a new theme years later, and not worry about local caching of files.

Your directory structure would generally look something like this:

```
clientname2015
	/fonts
	/images
		logo.svg
	/css
		mobile.css
		admin.css
		login.css
	/js
		clientname2015.js
	style.css
	index.php
	header.php
	footer.php
```

A good basic HTML structure that we use for all our themes. Obviously things will need to be changed on a a case by case basis, but if you find yourself deviating form this basic structure significantly, it might be worth thinking about simplifying things.

```html
<html>
<head>
	<!-- The HEAD would contain any of the useful things you need. For example, the stylesheet: -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v1.0" />	

	<!-- Or some meta tags -->    
    <?php if( is_front_page() ) : ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>
    
    <!-- Often you'll need Typekit: -->
	<script type="text/javascript" src="//use.typekit.net/xyl8ynd.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>    
    
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>

	<div id="menu">
		If using a Hamburger menu, this is where it would go generally. This allows you to translateX the position of #container if needed, or easily have the menu slide over the top of all content.
	</div>

	<div id="container">
		<div id="header">
			A top menu and logo would generally go in here. 
		</div>
	
		<div id="content" class="work-grid">
			The class of content will change, depending on the template being viewed. 
			
			The class should be a top level, short descriptive word for what that path is showing. Some common examples:
			* work-grid
			* work-detail
			* contact
			* about
			* category
			* single

			<div id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
				Then put all content inside of a DIV like this. If you are inside of a .grid, you'll generally need a .block class added too, then loop this element (and then you'd normally change this <DIV> to an <A> tag and add a href="<?php the_permalink(); ?>".)
				
				<div class="entry">
					Whenever you are displaying user generated content, try to put it inside .entry.
					<?php the_content(); ?>
					<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
				</div>				
				
			</div>

		</div>
	
		<div id="footer">
			Sometimes the footer will need to be outside of #container, if a sticky footer is required. Otherwise put it inside #container.
		</div>
	</div>

	<?php wp_footer();?>
</body>
</html>

```

___

## JavaScript Setup

The main JavaScript file for the theme should be named similar to the theme directory, like `bmw2015.js`, or `prettybrid2015.js`. 

You should avoid anonymous functions clogging the namespace, the best way to do this is to include everything in an object. This also allows you to turn off entire sections of the code, or change the run order, which makes debugging easier.

```javascript
var prettybrid2015 = {
    init: function() {
		// SVG things
        prettybrid2015.initSVG();
        		
		// Size things
		prettybrid2015.sizeSections();
		
		// Init things
		prettybrid2015.initFitVids();
    },
    
    onResize: function(){
		// Do these things on window resize
		prettybrid2015.sizeSections();
    },
    
    initSVG: function(){
	    // If needed, include SVG code from here:
	    // http://stackoverflow.com/questions/11978995/how-to-change-color-of-svg-image-using-css-jquery-svg-image-replacement/11978996#11978996.
    },
    
    sizeSections: function(){
	    // You might need to dynamically size things depending on browser height/width. 
	    // This is less needed now that we have VH and VW units in CSS, but sometimes it works better this way, specially when doing 100VH on mobile.
    },
    
    initFitVids: function(){
	    // You'd include code here to init the FitVids plugin
    }
};
jQuery(document).ready(function($){
    
    prettybrid2015.init();
    jQuery(window).resize(function(){
	    prettybrid2015.onResize();
    });
});

```

If you are using globals, or need to pass in data form PHP (using `wp_localize_script`), be sure to declare them all at the beginning of the JS file. This way it's easy to see what is being used, and we remove the risk of conflicts.

```
var prettybird2015 = {
	homeURL: prettybird2015_vars.homeURL,
	themeURL: prettybird2015_vars.themeURL,
	winHeight: null,
	winwidth: null,	
    init: function() {
		// Size things
		prettybird2015.setWinSize();
    },
    
    setWinSize: function({
	    // Set window size
	    prettybird2015.winHeight = jQuery(window).height();
	    prettybird2015.winWidth = jQuery(window).width();
    }
}
```

___


## Plugins

We try not to use plugins (both WordPress and jQuery), and when we do it's as a last resort to save on a large amount of effort. Using less plugins helps with code relaibility, and avoid situations where the site might stop working, if the plugin stops working. It also reducers th elearning curve for new developers look at the code.

The most common one to avoid is Advanced Custom Fields. It's such a big plugin, and it quickly becomes a critical part of a website. If it breaks or stops working, the site breaks. We have never had a situation that can't be solved by page herachy and a custom metabox.

Here is a list of common plugins that we do use.


1. [Simple Page Ordering](https://wordpress.org/plugins/simple-page-ordering/)
1. [Cycle2](http://jquery.malsup.com/cycle2/api/) (Don't use HTML data attributes, use it as jQuery('.slides').cycle() )
1. [CaroFredSel](http://docs.dev7studios.com/caroufredsel-old/) (Although Cycle2 is better in most circumstances)
1. [Vimeo jQuery API](https://github.com/jrue/Vimeo-jQuery-API)
1. [FitVids](https://github.com/davatron5000/FitVids.js)
1. [Velocity](http://julian.com/research/velocity/)

___

## CSS

Class names should all be lower case, with hyphens as spaces. So use `work-grid`, not `WorkGrid` or `work_grid`.

ID's should be used very sparingly, and the mostly for top level elements. Some acceptable examples are for elements commons elements like `#menu`, `#sidebar` or `#overlay`.

When defining styles, try to use classes, and keep things as least specific as needed. For example:

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


### CSS naming conventions

We like to use a semantic approach to CSS, but up until a point. The idea is for you to be able to read the CSS, and get some idea of what the HTML would look like, but still be flexible enough. Using classes like `three-col` and `blue_font` or `largeText` is bad.

This goes a little against the trend of completely semantic CSS, but the fact is all these site we build aren't large enough to warrant in depth semantic names and intricate grid systems. We'd rather things be easy to read, and somewhat intuitive when going through the CSS. 

We like to use `.block`, `.section`, `.grid`, `.detail`, `.title`, '.credit`. `.meta`, `.browse`, `.component` variations.


```ccs
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
	text-transform: uppercase;		
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


### Style Sheet Struture

Our preferred approach with CSS is to structure it similar to the sites visual structure. So things that appear at the top of the browser window, should be higher in the CSS document. This makes it faster for us to find a section of code, based on the visual hierarchy of the site. 

This also applies to individual elements too, so when defining things that might be inside a `.block` for example, try to keep the visual hierarchy in mind. For example:

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

 

If possible, try to group transitions/animation definitions into one area at the bottom. These are common definitions, and it helps to standardize their application on elements. It's quiet common for a feedback note to be "make all hover states faster", so combing through code looking for all transitions is hard. 

```css
/*
Theme Name: Client Name 2015
Theme URI: http://www.example.com/
Description: A theme for WordPress.
Author: Dave Funkhouser, Drew Baker, Funkhaus
Author URI: http://www.funkhaus.us
Version: 1.0

Fonts:
    font-family: Helvetica, Arial, sans-serif;
    	font-weight: 400;     
    	font-weight: 500;
    	
    font-family: 'franklin-gothic-cond', sans-serif;
    	font-weight: 500;
    	font-weight: 700;    	

Colors:
    Black: #222222;
    Blue: #??????;
    Pink: #??????;

/*-------------------------------------------------------------- */

/*
 * Fonts
 */
	@import url('fonts/fonts.css');

/*
 * Globals 
 */
	body { 
	    font-family: Helvetica, Arial, sans-serif; 
	    font-size: 11px; 
	    color: #333;
	}
    h1,h2,h3,h4,h5,h6 {
        margin: 0;
        padding: 0;
        line-height: 1;
        font-weight: normal;
    }
    ::selection {
        color: #ebebe3;
        background: #222;
    }
    ::-moz-selection {
        color: #ebebe3;
        background: #222;
    }	


/* 
 * Links 
 */
	a { 
	    color: #333; 
	    text-decoration: none;
	    outline: none;	    
	}
	a:hover { 
	    color: #666;
	    text-decoration: underline;
	}
	a img {
	   border: none;
	}
	

/* 
 * Page Structure 
 */
	#container {
		
	}
	#header {
		
	}
	#content {
		
	}
	#footer {
		
	}


/* 
 * Menus
 */


 
/* 
 * Header
 */ 



/* 
 * Home
 */



/* 
 * Work Grid
 */

 

 /* 
 * Work Detail
 */ 



/* 
 * Category
 */ 



/* 
 * Single (Blog detail)
 */ 


 
/* 
 * Footer
 */ 



/*
 * Animations
 */
    /* Color */
    a {
    	transition: color 0.4s;
    }    
    
    /* Opacity */
    .browse {
		transition: opacity 0.4s;
    }

    /* Everything */
    svg path {
		transition: 0.4s;
    }

```

___

## Template Routing

The way Funkhaus does template selection is a little different than most. Normally you'd use a [custom page template](https://codex.wordpress.org/Page_Templates#Custom_Page_Template), but that gets very repetitive for the user when building a site with lots of pages.

So we use a parent/child relationship conditional, or a few other novel ways to determine the correct template to use. Here are a few common ways to do that in page.php:

```php
global $post;
switch (true) {
    case is_page('work') :
		// Redirect to first child page.
		$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
        $firstchild = $pagekids[0];
        if( $firstchild ) {
            wp_redirect(get_permalink($firstchild->ID), 301);        
            exit;
        } else {
	        get_template_part('index');
        }
        break;

    case has_children($post->ID) : // has_children() is a custom function we use in functions.php. It tests if a given page has children.
    	// If has child pages
        get_template_part('template-work-grid');
        break;
        
    case is_tree(5) : // is_tree() is a custom function we use in functions.php. It tests if the current $post is in a given tree.
    	// If a page id is explicitly set, be sure to comment what the slug should be. 
    	// Is in the "About" tree.
        get_template_part('template-work-grid');
        break;
        
    case !empty($post->_custom_video_url) : 
    	// Page has a video URL metabox value saved, so use the work detail template. 
        get_template_part('template-work-detail');
        break;	        

    default:
        get_template_part('index');
        break;
}
```

If you are building a theme that has several variations of single.php, or category.php, you should use a similar technique for them too.

Here are the functions for `has_children()` and `is_tree`:

```php
/*
 * Custom conditional function. Used to get the parent and all it's child.
 */
    function is_tree($post_id) {
    	global $post;
    	
    	$ancestors = get_post_ancestors($post);
    	
    	if( is_page() && (is_page($post_id) || $post->post_parent == $post_id || in_array($post_id, $ancestors)) ) {
    		return true;
    	} else {
    		return false;
    	}
    }



/*
 * Custom conditional function. Used to test if current page has children.
 */
    function has_children($post_id = false, $post_type = 'page') {
    	// Defaults
    	if( !$post_id ) {
	    	global $post;
	    	$post_id = $post->ID;
    	}

    	// Check if the post/page has a child (this should be wrapped in a transient cache function one day)
        $args = array(
        	'post_parent'		=> $post_id,
        	'post_type'			=> $post_type,
        	'posts_per_page'	=> 1
        );
        $children = get_posts($args);
		
        if( count( $children ) !== 0 ) { 
			// Has Children
	        return true; 
	    } else { 
		    // No children
		    return false; 
		}
    }
```

___

## Whitespace

Whitespace in code is very important, but there is a wide variety of approaches people take. This is how we like it to look, in an effort to make code consistent and readable.


### PHP
4 space tabs. Everything indented. New lines for anything opening or closing. New line for major code separations. Always use <?php (not <? or <?=),

```
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

### PHP
When inside function.php for example. 4 space tabs. New lines for everything. Single quotes for parameters. New lines for opening and closing of PHP (if required). Use [output buffering](http://stackoverflow.com/a/4402045/503546) for anything major that needs to be returned, rather than concatenating a string (so never do `$output .= '<div class="example">Something</div>'`).

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

In PHP, we try to use long form wher epossible, to aid in reability.

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

Extending this example further, here is an example of what not to do:

```php
// A more complicated example of what not to do
<?=

	foreach ( $images as $image ) : $count++;
		$class = 'thumb';
		if ( $total === 1 ) continue; // skip if only child
		if ( $count == 1 ) $class .= ' active';

			echo wp_get_attachment_image($image->ID, 'store-gallery-thumb', false, array( 'class' => $class, 'data-image-id' => $image->ID ));

	endforeach; ?>
```

And here is that same code, but in a way more readable format and debuggable format:

```	
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


### CSS
4 space tabs. New lines for everything. Double lines between sections.

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

### JavaScript
4 space tabs. New line for everything. Liberal use of comments. Try to group similar functions in one comment.
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


___

## Enqueue Scripts

You should include style.css in your header.php file like so:

```html
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v1.0" />
```

And then use `wp_enqueue_scripts` to load in each required JS file as need. For example, in functions.php you'd do something like below. 

Be sure to set version numbers and dependencies. Each `wp_register_script` and `wp_enqueue_script` should be on a new line. We do this so it is easy to turn off scripts for debugging. Unless it is a major web project (like Vimeo or Goggle Maps) avoid using 3rd party CDNs.

Be sure to check what scripts ship by default with WordPress (Masnory and jQuery are good examples).


```php
/*
 * Enqueue Custom Scripts
 */
    function custom_scripts() {
        wp_register_script('prettybird2015', get_template_directory_uri() . '/js/site.js', 'jquery', '1.0');
        wp_register_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', 'jquery', '2.1.5');
		wp_register_script('froogaloop2', 'http://a.vimeocdn.com/js/froogaloop2.min.js', 'jquery', '1.0');
      
        wp_enqueue_script('jquery');
        wp_enqueue_script('cycle2');
        wp_enqueue_script('froogaloop2');
        wp_enqueue_script('prettybird2015');

        // Setup JS variables in scripts. This can be used ot pass in data from PHP to JavaScript. It is not always needed.
		wp_localize_script('prettybird2015', 'prettybird2015_vars', 
			array(
				'themeURL' => get_template_directory_uri(),
				'homeURL'  => home_url()
			)
		);
    }
    add_action('wp_enqueue_scripts', 'custom_scripts', 10);
```

If you need to multple style sheets, you can register and enqueue them in a simalar way:


```php
/*
 * Enqueue Custom Styles
 */    
    function custom_styles() {
	
		if( wp_is_mobile() ) {
			wp_register_style('prettybird2015-mobile', get_template_directory_uri() . '/css/mobile.css');			
	    	wp_enqueue_style('prettybird2015-mobile');			
		}

    }
	add_action('wp_enqueue_scripts', 'custom_styles', 10);	
```

___

## Loops
### Default Loop
One of the foundations of WordPress is loops.

When displaying a page with one loop, the default patten is preferred:

```php
// Default loop. Good.
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>            
    
        <div class="entry">
            <?php the_content(); ?>
			<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
        </div>
        
	</div>

<?php endwhile; ?>
<?php endif; ?>
```

### Secondary Loops
Often you'll need to do a secondary loop (eg. when displaying a grid of pages). You should never need to use `query_posts()`.  Using `get_posts()` as a foreach is the preferred way. The example below uses a `setup_postdata()`, which is often not needed, but shown to be equivalent to the above example.

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
    
        <div class="entry">
            <?php the_content(); ?>
			<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
        </div>

	</div>    	

<?php endforeach; ?>     	
```

### Paginated Loops
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

		    <div id="post-<?php the_ID(); ?>" <?php post_class('block episode-block'); ?>>            
		    
		        <div class="entry">
		            <?php the_content(); ?>
					<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
		        </div>
		        
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

### Nested Loops

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

Then inside each template part, you'd display a video player with thumbnails perhaps, or the gallery section, etc. For xample, here is what `part-section-text.php` might look like:

```php
<div class="section text-section">
	<div class="entry">
		<?php the_content(); ?>
		<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
	</div>
</div>
```

___

## SVGs

You should never need to use a sprite or PNG again! We simplay use SVG's as an image, and use a few lines of jQuery to load in the SVG XML, as documented here.

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

## Menus

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

## Metaboxes

The most common thing you'll generally need is to save a vidoe URL (usually Vimeo) to a page. THe best way to do that is via a custom field metabox. THe below code shows how to build a basic one, and then save the value. 

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

## Vimeo

We use Vimeo Pro for most websites we build. Make sure cleint has locked down thier account to exclude videos from Vimeo, and domain locked them tot he websites they want.

The [Vimeo jQuery API](https://github.com/jrue/Vimeo-jQuery-API) has made working with Vimeo much easier now. Best practice is to use that plugin now. Note that it includes the Froogaloop2 script files for you, so no need to enqueue them too.

There is also an example of using the manual [Froogaloop2 method here](http://labs.funkhausdesign.com/examples/vimeo/froogaloop2-api-basics.html).

### Scaling Video

There are many ways to scale Vimeo iframes. I've prepared [examples of many fo them here](http://labs.funkhausdesign.com/examples/video-sizes/index.html).

___

## Galleries

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

			<div class="gallery-item gallery-image cover" style="background-image: url('<?php echo wp_get_attachment_image( $attachment->ID, 'gallery-stage'); ?>');">
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

## Image sizes

When handerling images in WordPress, you'll generally need to define them in functions.php and then another set under Settings > Media.

In functions.php, we will generally define things as, grid-thumb, or work-thumb, video-thumb, etc.

```
/*
 * Enable post thumbnail support
 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
	add_image_size( 'grid-thumb', 566, 250, true ); // Medium thumbnail size used on work girds
	add_image_size( 'fullscreen', 1920, 1080, true ); // Large image used on homepage slideshows etc.
    add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used for Facebook shares
    
```

As for the sizes in Settings > Media, we like to set the width for "medium" and "large" to be the maximum content width for the site. And the height of "medium" to be a [16:9 ratio](http://size43.com/jqueryVideoTool.html) of that width, and the height of "large" to be unlimited (so set it to 0). Thumbnails we generally set to the somthing small and square, like 250px X 250px.

So if the max-width of .entry is 800px, you'd have the following settings:

```
Thumbnail size
Max-Width: 250px	Max-Height: 250px	Crop: True

Medium size
Max-Width: 800px	Max-Height: 450px

Large size
Max-Width: 800px	Max-Height: 0
```

And then be sure to set the `$content_width` global, so that WordPress knows how to size oEmbeds. You'd do that in functions.php like so:

```
/*
 * Set global content width
 */
	function set_content_width() {
		global $content_width;
		if ( is_single() ) {
			$content_width = 720;		
		} else {
			$content_width = 720;
		}
	}
	add_action( 'template_redirect', 'set_content_width' );
```

___

## Slideshows

@TODO

When used on a home page, don't worry about lazy loading or infinite scroll. Limit query to display 10 slides at max.

___

## Z-Index

When using `z-index` in CSS, we like to go in series of 10. So the first default z-index would be 0, then the next layer would be 10, and then 20 and so on.

If you have a floating header or footer, setting it to 100 or 110 is generally a good idea. Setting an overlay to 200 is accetable too.

If you use Cycle2, you'll notice that it uses 0-100 for it's z-indexing of slides. You can set the base level containing elment to be a low z-index to counter this.

If you find yourself setting z-index to 300 or above, then you should probably reconsider what you are doing. You shouldn't need to set that high of a z-index for a normal website.

___

## Contact Pages

Social links (to Facebook etc) can just be hardcoded. Clients won't change these ever, so don't worry about build custom options pages for these things.

Contact pages are generally the most time consuming parts to build. We haven't come up with a great solution, but the one we have works and should be considered the standard unless you can pitch us something better.

Often a contact page will consist of a nested loop of pages (see the section above on loops). So the page strucutre in WordPress might look like this:

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

____

## Break Points

@TODO

___

## Open Graph Tags

@TODO

___

## Admin & Login Pages

@TODO

___

## Mobile & Responsive

@TODO

## To Do List

1. Replace all @TODO in this document with correct examples
1. Advanced pagination (next_page() and how to build correct back buttons using sessions)
1. How to use pre_get_posts for advanced queries (like search results)