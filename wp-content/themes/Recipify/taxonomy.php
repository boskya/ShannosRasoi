<?php get_header(); ?>

    <div id="content" class="clearfix">
        
        <div id="main" class="full-width clearfix" role="main">

        	<h3>Explore <?php single_cat_title(); ?> Recipes</h3>

	      	<?php global $post;
	      	 global $wp_query;
	      	
			// $paged = (get_query_var("paged")) ? get_query_var("paged") : 1;
			 //$args_extra=array(
			//	"paged"=>$paged, //Pulls the paged function into the query
				//"post_type" => 'recipe',
			//	"posts_per_page" => 3, //Limits the amount of posts on each page
			//);

			//$args = array_merge( $wp_query->query_vars, $args_extra);

			//get only post types of recipes
			
			$args = array_merge( $wp_query->query_vars, array("posts_per_page" => 3));
			query_posts( $args ); 
			
			$current_category = get_the_category();
			$current_category_parent ="";

			if ($current_category)
			{
				$current_category_parent= $current_category[0]->category_parent;
				if(!$current_category_parent)
				{
					$current_category_parent = $current_category[0]->cat_ID;
				}

			}




	$args = array(
	'type'                     => 'recipe',
	'child_of'                 => $current_category_parent,
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );

    $categories = get_categories( $args ); 
	?>

<?php 

foreach ($categories as $category) {
 	//echo $category->cat_name;
  }

$categories ?>
        	<?php if(have_posts()) :?>	


        		<div class="recipe-collection">
	       		<?php while ( have_posts() ) : the_post(); ?>
       				<div class="recipe-collection-item">      					
					  <?php $final_image = get_post_meta($post->ID, 'recipe_final_image', true); 
						if ($final_image)
						{
							$final_image_src = wp_get_attachment_image_src($final_image,'full');
					 	?>
						 	<a href="<?php echo get_permalink( $id ); ?>"><img itemprop="photo" src="<?php echo $final_image_src[0]; ?>" alt="" /></a>
						<?php } ?>

						<div class="title"><?php the_title(); ?></div>
					</div>	
				<?php endwhile; // end of the loop. ?>
				<?php recipify_content_nav("recipe-nav") ?>
					</div>
			 <?php else: ?>

			 <!-- The very first "if" tested to see if there were any Posts to -->
 			 <!-- display.  This "else" part tells what do if there weren't any. -->
 			<p>Sorry, no recipes matched your criteria.</p>

 <!-- REALLY stop The Loop. -->
 	<?php endif; ?>
		

		<?php wp_reset_query(); ?>
        </div> <!-- end #main -->


    </div> <!-- end #content -->
        
<?php get_footer(); ?>