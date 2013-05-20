<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

 	<!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->
		<!-- Recipe using microdata -->
 		<article class="recipe" itemscope itemtype="http://schema.org/Recipe">
			<div>
			  <?php $final_image = get_post_meta($post->ID, 'recipe_final_image', true); 
				if ($final_image)
				{
					$final_image_src = wp_get_attachment_image_src($final_image,'full');
			 	?>			 	
				<div class="photo">
					<img itemprop="photo" src="<?php echo $final_image_src[0]; ?>" alt="" />
				</div>
				<?php } ?>
				<div class="description-block"> 
					<h2 itemprop="name" class="title"><?php the_title(); ?></h2>							
					<span class="posted">
						Posted by: 
						<span itemprop="author">
							<?php the_author_posts_link(); ?>
						</span>
							on 
						<time datetime="<?php the_time('F jS, Y') ?>"  itemprop="published">	<?php the_time('F jS, Y') ?></time>
						
						</span>
					</span>
					<div itemprop="summary" class="description"><?php echo get_post_meta($post->ID, 'recipe_description', true); ?></div>
				</div>
			</div>
						
			<section class="ingredients">
			<h3> Ingredients </h3>	
			<?php $ingredients = json_decode(get_post_meta($post->ID, "recipe-ingredients", true), true);
				if ($ingredients) 
				{
			?>		
						<ul>
							<?php foreach($ingredients as $ingredient)
							{ 
								$ingredient_name = $ingredient['name'];
								$ingredient_quantity = $ingredient['quantity'];
								$ingredient_notes = $ingredient['notes'];
							?>
								<li itemprop="ingredient" itemscope itemtype="http://data-vocabulary.org/RecipeIngredient">
									<span itemprop="amount"><?php echo $ingredient_quantity ?></span>
									<span itemprop="name"><?php echo $ingredient_name ?></span>
									<span><?php echo $ingredient_notes ?></span>
							   </li>

						<?php	}?>
						</ul>
			<?php		
				}
			?>
			</section>
			
			<section class="instructions">
			 <h3>Instructions</h3>
				 <?php $instructions =  json_decode(get_post_meta($post->ID, "recipe-directions-table", true), true); ?>
			  <?php if ($instructions)
				{
			  ?>
					<div itemprop="instructions" class="instructions-content">
					<?php foreach($instructions as $index=>$instruction) 
					{
						$direction_step = $instruction['step'];
						$direction_photo = $instruction['photo'];
					
					?>
						<div class="instruction clearfix">
							<h4> Step <?php echo $index + 1 ?></h4>
                            <div class="instruction-step"><?php echo $direction_step ?></div>
                            <?php if ($direction_photo)
                            {
                                $direction_photo_src = wp_get_attachment_image_src($direction_photo,'medium');
                                ?>
                            <div class="instruction-photo">
                                <img src="<?php echo $direction_photo_src[0]; ?>" />
                            </div>
    						<?php } ?>
                        </div>
					<?php } ?>
					</div>
				<?php } ?>
			</section>
 		</article>

 <!-- Display a comma separated list of the Post's Categories. -->
 <p class="postmetadata">Posted in <?php the_category(', '); ?></p>
 <!-- closes the first div box -->

 <!-- Stop The Loop (but note the "else:" - see next line). -->
 <?php endwhile; ?>
 <?php else: ?>

 <!-- The very first "if" tested to see if there were any Posts to -->
 <!-- display.  This "else" part tells what do if there weren't any. -->
 <p>Sorry, no posts matched your criteria.</p>

 <!-- REALLY stop The Loop. -->
 <?php endif; ?>