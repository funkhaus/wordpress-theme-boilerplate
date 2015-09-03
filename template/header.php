<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0," />
    <?php if( is_front_page() ) : ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>
    
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v1.0" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" />
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/icon-touch.png"/> 
    
    <!--Make Microsoft Internet Explorer behave like a standards-compliant browser. http://code.google.com/p/ie7-js/-->
    <!--[if lt IE 9]>
        <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
    
    <?php get_template_part('part-facebook-tags'); ?>
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>

    <?php	
        // Include side cart 
        /*
    	if ( !is_checkout() ) {
    		woocommerce_mini_cart();
    	}
        */
    ?>

	<div id="container">

	    <div id="header">
	    	<?php if ( is_front_page() ) : ?>
	        <div id="tagline"><p><?php bloginfo('description'); ?></p></div>
	        <?php endif;?>

			<?php 
				$menuArgs = array(
				    'container'         => 'false',
				    'menu'              => 'Main Menu',
				    'menu_id'           => 'main-menu',
				    'menu_class'        => 'main-menu menu'
				);
				wp_nav_menu($menuArgs); 
	        ?>

	        <a id="logo" href="<?php bloginfo('url'); ?>">
	            <img class="svg " src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">            
	        </a>
	        
	        <?php //get_template_part('store/part-store-controls'); ?>	        

	    </div>
