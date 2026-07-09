<?php
if (!isset($genero_slug) || empty($genero_slug))
    return;
$genero_term = get_term_by('slug', $genero_slug, 'genero_cancion');
if (!$genero_term)
    return;

$temp = $wp_query;
$args = array(
    'post_type' => 'canciones',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'genero_cancion',
            'field' => 'slug',
            'terms' => $genero_slug,
        ),
    ),
);
$wp_query = new WP_Query($args);
if ($wp_query->have_posts()):
    ?>
    
    <?php
endif;
wp_reset_query();
$wp_query = $temp;
?>