<?php
/**
 * Template Name: FAQ
 *
 * @package breakdownmusic-theme
 */

get_header();
?>

<main id="bd-content" class="container-fluid py-4">
    <?php
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content-page', 'faq' );
    endwhile;
    ?>
</main>

<?php
get_sidebar();
get_footer();