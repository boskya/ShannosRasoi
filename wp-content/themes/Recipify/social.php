<?php
/**
 * Template for display the social share bar
 */
?>

<div class="socialFooterWrap">
	<div class="social-links">
		<div class="socialClose"></div>
		<div class="fb">
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<script type="text/javascript">
				document.write('<fb:like layout="button_count" show_faces="false" width="100"></fb:like>')
			</script></div>

		<div class="googplus">
		<g:plusone size="medium"></g:plusone></div>
		
		<div class="stumble">
			<script src="http://www.stumbleupon.com/hostedbadge.php?s=2"></script>
		</div>
	
		<div class="tweet">
			<a href="http://twitter.com/share" data-url="<?php the_permalink(); ?>" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
		</div>
</div>