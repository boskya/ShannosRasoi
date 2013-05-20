<?php
/**
 * Template Name: Recipe Page
*/
get_header();
?>
   <div id="content" class="clearfix">
        <div id="main" class="with-sidebar clearfix" role="main">
			<?php get_template_part('loop','single-recipe'); ?>
			
        </div> <!-- end #main -->
		<?php get_sidebar(); ?>

	</div> <!-- end #content -->
<?php get_footer(); ?>