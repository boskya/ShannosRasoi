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
		$final_image_src = wp_get_attachment_image_src($final_image,'full');
		$final_image_src = $final_image_src[0];
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
	endwhile; // end of the loop. 
	wp_reset_query();

	?>

	</div>
</div>

	<aside id='profile' class="widget">

	<h2 class="widget-title">About me</h2>
	<?php
		$attachments = get_children( array(
                'post_parent' => $post->ID,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC',
                'orderby' => 'menu_order'
            ) );

	    /**
    	 * Loop through each attachment
    	 */
    	foreach ( $attachments as $id  => $attachment ) :
    	?><?php	
        	$title = esc_html( $attachment->post_title, 1 );
        	if ($title == 'ShannoProfile')
        	{
	        	$img = wp_get_attachment_image_src( $id, "medium" );
	        	?>
	        		<div class="row-fluid">
	        			<div class="span12">
			 	    		<img src="<?php echo esc_url( $img[0] ) ?>" alt="<?php echo esc_attr( $title ) ?>" />
			 	    	</div>
	 	    		</div>
	 	    	
	 	    		<div class="row-fluid">
	 	    			<div class="span12">
	 	    				<p><em>Hi, I am Shanno</em> from  Udaipur India, now settled in USA. I learnt the art of vegetarian cooking with the guidance of my mother and inspiration from my father. 
								I cherished the desire to share my skills and recipes of delicious Indian and Thai dishes with you all. I 
								appreciate the efforts and support of my family, especially my daughters for the development of this 
								site. Your suggestions to improve the recipes and the site are always welcome. 								
								<a href="#" id="profile_hide">Read More</a>
</p>
	 	    				<p id="profile_hidden" style="display:none">I am from a vegetarian family of Udaipur, Rajasthan. Growing up, my father used to make special items and give ideas for new dishes which my mother cooked to his liking. Being the eldest daughter, I started helping mom and slowly developed a deep interest in cooking with the encouragement of my father.  After my marriage I moved to Ahmedabad, Gujurat, then Bangkok Thailand and finally settled in Maryland, U.S.A. My cooking is influenced by my stay in various cultures including Rajasthani, Gujurati, Sindhi, Punjabi, South Indian and Thai.</p>

						</div>
					</div>			
	 	    	<?php
	         break;
	        }
    	endforeach;
    	?>


		<aside>
</div>
<div id="connected_container">
	    <div id="connected">
		<ul>
			<li><a href="mailto:shanno.atlani@gmail.com" class="mail"></a>
			<li><a href="http://www.twitter.com/shanno" class="twitter"></a>

			<li><a href="http://www.facebook.com/shanno.atlani" class="facebook"></a>
			</ul>
		</div>
</div>

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
        	scrollTopPosition = '-=40px';
        }
        else
        {
        	scrollTopPosition = '+=40px';
        }

		$(this).stop().animate({ scrollTop: scrollTopPosition });
	});

})(jQuery);
</script>
<?php get_footer(); ?>