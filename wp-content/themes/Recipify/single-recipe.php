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
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php get_footer(); ?>