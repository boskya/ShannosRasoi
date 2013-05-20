<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Recipify
 */

get_header(); ?>

<div id="content" class="clearfix">
        
    <div id="main" class="full-width clearfix" role="main">

        <h3>Search Results for <?php echo get_search_query() ?> </h3>

		<?php if ( have_posts() ) : ?>

       		<div class="recipe-collection">


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

     			<div class="recipe-collection-item">     
				  <?php $final_image = get_post_meta($post->ID, 'recipe_final_image', true); 
						if ($final_image)
						{
							$final_image_src = wp_get_attachment_image_src($final_image,'large');
					 	?>
						 	<a href="<?php echo get_permalink( $id ); ?>"><img itemprop="photo" src="<?php echo $final_image_src[0]; ?>" alt="" /></a>
						<?php } ?>

						<div class="title"><?php the_title(); ?></div>
				</div>	
			<?php endwhile; ?>


			<?php recipify_content_nav("recipe-nav") ?>
		</div>

		<?php else : ?>

			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentytwelve' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>