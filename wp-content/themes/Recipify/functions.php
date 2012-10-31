<?php
/**
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 */

/** Sets up custom recipe type **/
/*-------------------------------------------------------------------------------------------*/
/* shanno_recipeWithImage Post Type */
/*-------------------------------------------------------------------------------------------*/

/* Load the hybrid core theme framework */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

$recipify = new Recipify();
add_action( 'after_setup_theme', array($recipify, 'setup'), 10 );

class Recipify {
	
	/*
		Intialize the theme using the Hybrid core framework
	*/
	function setup()
	{
		
		$prefix = hybrid_get_prefix();
				
		add_theme_support( 'hybrid-core-menus', array( 'primary'));
		add_theme_support( 'hybrid-core-seo' );
		add_theme_support( 'hybrid-core-template-hierarchy' );
		add_theme_support( 'custom-background', array( 'default-color' => 'f2f2f2' ) );
		add_theme_support( 'cleaner-gallery' );
		
		
		$this->initialize_custom_header();
		$this->create_post_type();
		
		
		$this->load_stylesheet();				
	}
	
	function initialize_custom_header() {
	$args = array(
				'default-image' => get_template_directory_uri() . 'images/header.jpg',
				'width'			=> 300,
				'uploads'		=> true,
				'flex-width'	=> true
			);
			add_theme_support('custom-header',$args);

	}	
	
	function create_post_type() {
		$labels = array(
		    'name' => __('RecipesWithImage'),
		  
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
		); 
		register_post_type('recipeWithImage',$args);		
	}
	
	/*
	 Add support of stylesheet
	*/
	function load_stylesheet()
	{
		if ( !is_admin() ) { 
		    wp_register_style(
		        'recipify-style',
		       trailingslashit( get_template_directory_uri() ). '/style.css',
		        false,
		        0.1
		    );
		}
		wp_enqueue_style( 'recipify-style' );

	}

	/*
	  add support for custom background
	*/
	function custom_background()
	{
		// custom backgrounds
			$recipify_custom_background = array(
				// Background color default
				'default-color' => 'f2f2f2',
				// Background image default
				'default-image' => get_template_directory_uri() . '/images/bg.png',
				'wp-head-callback' => '_custom_background_cb'
			);
			add_theme_support('custom-background', $recipify_custom_background );		
	}
	
}
?>



		
		