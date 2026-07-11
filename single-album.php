<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package breakdownmusic-theme
 */

get_header();
?>

<main id="bd-content" class="site-main">
	<?php
	while (have_posts()) :
		the_post();
		get_template_part('template-parts/content-single', 'album');
	endwhile;
	?>
</main>

<?php
get_footer();