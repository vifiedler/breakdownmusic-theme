<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$temp = $wp_query;
$args = array(
    'post_type' => 'canciones',
    'order' => 'RAND',
    'paged' => $paged,
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'genero_cancion',
            'field' => 'slug',
            'terms' => 'destacados',
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