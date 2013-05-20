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
add_action( 'parse_query',array($recipify,'changept' ));
add_filter('pre_get_posts',array($recipify,'searchFilter'));



/* set up custom Recipe type */
require_once( trailingslashit(TEMPLATEPATH) . 'recipify_post.php');
$recipify_post = new Recipify_post();
add_action('init', array($recipify_post,'setup_post'));
add_action('admin_init', array($recipify_post,'setup_scripts'));


/**
 * Display navigation to next/previous pages when applicable
 */
function recipify_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'recipify' ) . '</span> Previous' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', 'Next <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'recipify' ) . '</span>' ); ?>

		<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search()  || is_category()) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-next"><?php next_posts_link( __( 'NEXT', 'recipify' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-previous"><?php previous_posts_link( __( 'PREVIOUS', 'recipify' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}


class Recipify {

	/*
		Intialize the theme using the Hybrid core framework
	*/
	function setup(){	

		// add hybrid defaults

		$prefix = hybrid_get_prefix();
		
		add_theme_support( 'hybrid-core-menus', array( 'primary'));
		add_theme_support( 'hybrid-core-template-hierarchy' );
		add_theme_support( 'loop-pagination' );

		add_theme_support( 'custom-background', array( 'default-color' => 'f2f2f2' ) );
		add_theme_support( 'cleaner-gallery' );
	
		
		add_action( 'wp_enqueue_scripts', array($this,'recipify_scripts') );
		$this->initialize_custom_header();
		register_sidebar(array('name' => 'recipify sidebar'));
		
	}

	/*
	*  Ensures recipe shows up in category pages and sets default number of posts per page to 6
	*/
	function changept() {
		if( is_category() && !is_admin() )
		{
			set_query_var( 'post_type', array( 'recipe' ) );
			set_query_var('posts_per_page','6');
		}
		return;
	}

	/*
		Change search filter to recipe types
	*/
	function searchFilter($query) {
  	 
	   	 if ($query->is_search) {
	   	     $query->set('post_type', array('recipe'));
	   	     $query->set('posts_per_page','6');
   		 };
    	return $query;
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