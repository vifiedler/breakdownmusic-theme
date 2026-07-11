<?php
/**
 * AJAX handlers for Breakdown Music
 */

// ============================================================
// AJAX para búsqueda en vivo
// ============================================================
function bd_ajax_search() {
    check_ajax_referer('bd_ajax_nonce', 'nonce');

    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    if (empty($search) || strlen($search) < 2) {
        wp_send_json_success([]);
    }

    $args = array(
        'post_type'      => array('canciones', 'artista', 'album', 'post', 'page'),
        's'              => $search,
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'orderby'        => 'relevance',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);
    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_type = get_post_type_object(get_post_type());
            $results[] = array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'type'      => $post_type ? $post_type->labels->singular_name : get_post_type(),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}
add_action('wp_ajax_bd_ajax_search', 'bd_ajax_search');
add_action('wp_ajax_nopriv_bd_ajax_search', 'bd_ajax_search');