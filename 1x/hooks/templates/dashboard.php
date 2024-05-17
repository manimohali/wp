<?php
/**
 * Template Name: Dashboard
 * @package dashboard
 * @since dashboard 1.0
 */

?>
<?php get_header(); ?>

<div class="row row-large">
	<div class="large-9 col">
		<?php the_content(); ?>
	</div>

	<div class="large-3 col">
		<?php get_sidebar(); ?>
	</div>
</div>


<?php get_footer(); ?>