<!DOCTYPE html>
	<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
	<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
	<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width" />
<title><?php wp_title('|', true, 'left'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link ref="stylesheet" href="library/css/bootstrap-responsive.min.css" />
<?php wp_enqueue_script("jquery"); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!--[if lt IE 7]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div id="container">
<?php do_action( 'before' ); ?>
	<header id="banner" role="banner">

		 <a href="<?php echo home_url( '/' ); ?>" rel="home" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<img src="<?php header_image(); ?>" id="headerimg" alt="<?php bloginfo( 'name' ); ?>" />
					<h2 id="site-tagline"><?php bloginfo( 'description' ); ?></h2>

		</a>
				<nav id="search" role="search">
			<?php get_search_form(); ?>

		</nav>


		<nav id="access" role="navigation" class="clearfix">

		 	<div class="skip-link screen-reader-text">
		        <a href="#content" title="<?php esc_attr_e( 'Skip to content', 'recipify' ); ?>">
		        <?php _e( 'Skip to content', 'recipify' ); ?></a>
		    </div>
			
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'menu', 'fallback_cb' => '') ); ?> 

		</nav><!-- #access -->

	</header>
