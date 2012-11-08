<?php
/**
 * @package Recipify
 * @subpackage Functions
 * @version 0.1
 * @author Bosky Atlani
 * @link http://boskyatlani.me
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Load the hybrid core theme framework */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

/* set up the recipify theme */
$recipify = new Recipify();
add_action( 'after_setup_theme', array($recipify, 'setup'), 10 );

/* set up custom Recipe type */
require_once( trailingslashit(TEMPLATEPATH) . 'recipify_post.php');
$recipify_post = new Recipify_post();
add_action('init', array($recipify_post,'setup_scripts'));
add_action('init', array($recipify_post,'setup'));



class Recipify {

	/*
		Intialize the theme using the Hybrid core framework
	*/
	function setup(){	

		$prefix = hybrid_get_prefix();
		
		add_theme_support( 'hybrid-core-menus', array( 'primary'));
		add_theme_support( 'hybrid-core-template-hierarchy' );
		add_theme_support( 'custom-background', array( 'default-color' => 'f2f2f2' ) );
		add_theme_support( 'cleaner-gallery' );
		
		add_action( 'wp_enqueue_scripts', array($this,'recipify_scripts') );
		$this->initialize_custom_header();
		
		
	}

	/*
	* Adds support for custom header
	*/
	function initialize_custom_header() {
	$args = array(
				'default-image' => get_template_directory_uri() . 'images/header.jpg',
				'width'			=> 300,
				'uploads'		=> true,
				'flex-width'	=> true
			);
			add_theme_support('custom-header',$args);

	}	
	
	

	/*
	 Load style sheets and scripts needed for recipify theme
	*/
	function recipify_scripts() {
		if ( !is_admin() ) { 
		    wp_register_style(
		        'recipify-style',
		       trailingslashit( get_template_directory_uri() ). 'style.css',
		        false,
		        0.1
		    );
			wp_enqueue_style( 'recipify-style' );
		}
	}

	/*
	  add support for custom background
	*/
	function custom_background() {
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