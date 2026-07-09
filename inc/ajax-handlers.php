<?php
/**
 * AJAX handlers para navegación sin recarga
 */

function bd_ajax_load_page() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if ($post_id) {
        $post = get_post($post_id);
        if ($post) {
            setup_postdata($post);
            ob_start();
            if (is_singular('canciones')) {
                get_template_part('template-parts/content', 'canciones');
            } elseif (is_singular('artista')) {
                get_template_part('template-parts/content', 'artista');
            } elseif (is_singular('album')) {
                get_template_part('template-parts/content', 'album');
            } elseif (is_page()) {
                get_template_part('template-parts/content', 'page');
            } else {
                the_content();
            }
            wp_reset_postdata();
            $html = ob_get_clean();
            wp_send_json_success(['html' => $html, 'title' => get_the_title($post_id)]);
        }
    } elseif ($url) {
        $html = bd_fetch_page_content($url);
        if ($html) {
            wp_send_json_success(['html' => $html, 'title' => '']);
        }
    }
    wp_send_json_error('No se pudo cargar el contenido.');
}
add_action('wp_ajax_bd_load_page', 'bd_ajax_load_page');
add_action('wp_ajax_nopriv_bd_load_page', 'bd_ajax_load_page');

function bd_fetch_page_content($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return false;
    $body = wp_remote_retrieve_body($response);
    preg_match('/<main id="primary"[^>]*>(.*?)<\/main>/s', $body, $matches);
    return isset($matches[1]) ? $matches[1] : false;
}

function bd_enqueue_ajax_scripts() {
    wp_enqueue_script('bd-ajax', get_template_directory_uri() . '/assets/librerias/js/bd-ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('bd-ajax', 'bd_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'rest_url' => rest_url('breakdown/v1/'), // <-- Agregamos esto para usar en JS
        'nonce' => wp_create_nonce('bd_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'bd_enqueue_ajax_scripts');