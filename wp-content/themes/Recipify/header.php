<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title('|', true, 'left'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="container">
<?php do_action( 'before' ); ?>
	<header id="banner" role="banner">
		 <a href="<?php echo home_url( '/' ); ?>" rel="home" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<img src="<?php header_image(); ?>" width="300" alt="<?php bloginfo( 'name' ); ?>" />
		</a>
			<h2 id="site-tagline"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

		<nav id="access" role="navigation">
		 	<div class="skip-link screen-reader-text">
		        <a href="#content" title="<?php esc_attr_e( 'Skip to content', 'recipify' ); ?>">
		        <?php _e( 'Skip to content', 'recipify' ); ?></a>
		    </div>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'menu', 'fallback_cb' => '') ); ?> 
			<?php get_search_form(); ?>
		</nav><!-- #access -->

      
	</header>
