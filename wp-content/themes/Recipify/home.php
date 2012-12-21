<?php get_header(); 
/**
 * Template Name: Homepage
 * Description: Template for the homepage
 */

// Template for the home page
query_posts("post_type=recipe&&meta_key=_jsFeaturedPost&meta_value=yes"); 
?>
  <div id="content" class="clearfix">
        <div id="main" class="with-sidebar clearfix" role="main">
		
		<div id="slides" >
<?php 
	
	$thumb_array = array();
	$index = 0;
	while ( have_posts() ) : the_post();
		$class = "slide-thumb";
		$final_image = get_post_meta($post->ID, 'recipe_final_image', true); 
		$final_image_src = wp_get_attachment_image_src($final_image,'fulll')[0]; 
		$title = get_the_title();

		if ($index == 0)
		{
			//put this in in the main big window
		?>
			<div class="slide-main">
				<a href="<?php echo get_permalink( $id ); ?>">
					<img itemprop="photo" src="<?php echo $final_image_src ?>" alt="" />
					<div class="title"><?php echo $title ?></div>
				</a>
			</div>
			<div class="slide-thumbs-container">
				<div class="slide-thumbs">
				</div>
			</div>
		<?php 	
		}
		$thumb_array[$index]["imagesrc"] = $final_image_src;
		$thumb_array[$index]["url"] = get_permalink( $id ); 
		$thumb_array[$index]["recipeTitle"] = $title;


		$index++;
	endwhile; // end of the loop. ?>
</div>
</div>
</div>
<?php wp_reset_query();

?>
<script>
(function($)
{
	function loadSlideThumbs(slidesString)
	{
		var slides = jQuery.parseJSON(slidesString);
		var slideContainer = jQuery("#slides div.slide-thumbs");
		jQuery(slides).each(function(index, slide){
			var newDiv = jQuery('<div class="slide-thumb" />');
			var link = jQuery("<img src='' alt=''/>");
			$(link).attr('src', slide.imagesrc);
			$(link).attr('href', slide.url);
			$(newDiv).append(link);
			$(slideContainer).append(newDiv);
			$(newDiv).click(function(e)
			{
					// replace the main div with thumb image
				var mainArea = $(".slide-main");
				mainArea.find('img').attr('src',slide.imagesrc);
				mainArea.find('a').attr('href',slide.url);
				mainArea.find('div.title').text(slide.recipeTitle);
			});
		});
	}
	loadSlideThumbs('<?php echo json_encode($thumb_array) ?>');
	$(".slide-thumbs").mousemove(function(e)
	{
		var h = $(this).height();
        var offset = $($(this)).offset();
        var position = (e.pageY-offset.top)/$(this).height();
        var scrollTopPosition;

        if (position < 0.5)
        {
        	scrollTopPosition = '-=30px';
        }
        else
        {
        	scrollTopPosition = '+=30px';
        }

		$(this).stop().animate({ scrollTop: scrollTopPosition });
	});

})(jQuery);
</script>
<?php get_footer(); ?>