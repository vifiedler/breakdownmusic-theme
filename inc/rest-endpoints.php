<?php
/**
 * REST API endpoints para datos de música
 */

// Endpoint: /breakdown/v1/song/{id}
add_action('rest_api_init', function () {
    register_rest_route('breakdown/v1', '/song/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'bd_get_song_data',
        'permission_callback' => '__return_true',
    ));
});

function bd_get_song_data($data)
{
    $id = $data['id'];
    $post = get_post($id);
    if (!$post || $post->post_type !== 'canciones') {
        return new WP_Error('not_found', 'Canción no encontrada', array('status' => 404));
    }

    $artista_obj = get_field('artista', $id);
    $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
    $img_url = get_the_post_thumbnail_url($id, 'medium');
    if (!$img_url)
        $img_url = 'https://via.placeholder.com/300x300?text=No+Image';

    return array(
        'id' => $id,
        'title' => get_the_title($id),
        'artist' => $artista_nombre,
        'thumbnail' => $img_url,
        'url' => get_field('url_cancion', $id),
        'permalink' => get_permalink($id),
    );
}

// Endpoint: /breakdown/v1/songs-by-artist/{id}
add_action('rest_api_init', function () {
    register_rest_route('breakdown/v1', '/songs-by-artist/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'bd_get_songs_by_artist',
        'permission_callback' => '__return_true',
    ));
});

function bd_get_songs_by_artist($data)
{
    $artist_id = $data['id'];
    $posts = get_posts(array(
        'post_type' => 'canciones',
        'posts_per_page' => 10,
        'orderby' => 'rand',
        'meta_query' => array(
            array(
                'key' => 'artista',
                'value' => '"' . $artist_id . '"',
                'compare' => 'LIKE',
            ),
        ),
        'post__not_in' => array(get_the_ID()),
    ));
    $songs = array();
    foreach ($posts as $post) {
        $songs[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'permalink' => get_permalink($post->ID),
        );
    }
    return $songs;
}

// Endpoint: /breakdown/v1/songs-by-genre/{slug}
add_action('rest_api_init', function () {
    register_rest_route('breakdown/v1', '/songs-by-genre/(?P<slug>[a-zA-Z0-9-_]+)', array(
        'methods' => 'GET',
        'callback' => 'bd_get_songs_by_genre',
        'permission_callback' => '__return_true',
    ));
});

function bd_get_songs_by_genre($data)
{
    $slug = $data['slug'];
    $page = isset($data['page']) ? intval($data['page']) : 1;
    $per_page = isset($data['per_page']) ? intval($data['per_page']) : 10;
    $exclude_album = isset($data['exclude_album']) ? intval($data['exclude_album']) : 0;

    $exclude_ids = array();
    if ($exclude_album) {
        $album_songs = get_posts(array(
            'post_type' => 'canciones',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'album',
                    'value' => '"' . $exclude_album . '"',
                    'compare' => 'LIKE',
                ),
            ),
        ));
        $exclude_ids = $album_songs;
    }

    $args = array(
        'post_type' => 'canciones',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'tax_query' => array(
            array(
                'taxonomy' => 'genero_cancion',
                'field' => 'slug',
                'terms' => $slug,
            ),
        ),
        'post__not_in' => $exclude_ids,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $songs = array();
    foreach ($query->posts as $post) {
        $artista_obj = get_field('artista', $post->ID);
        $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
        $thumb = get_the_post_thumbnail_url($post->ID, 'medium');
        if (!$thumb)
            $thumb = 'https://via.placeholder.com/300x300?text=No+Image';
        $songs[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'artist' => $artista_nombre,
            'thumbnail' => $thumb,
            'url' => get_field('url_cancion', $post->ID),
            'permalink' => get_permalink($post->ID),
            'duration' => get_field('duracion', $post->ID),
        );
    }

    return array(
        'songs' => $songs,
        'total' => $query->found_posts,
        'page' => $page,
        'total_pages' => $query->max_num_pages,
    );
}

// Endpoint: /breakdown/v1/all-songs
add_action('rest_api_init', function () {
    register_rest_route('breakdown/v1', '/all-songs', array(
        'methods' => 'GET',
        'callback' => 'bd_get_all_songs',
        'permission_callback' => '__return_true',
    ));
});

function bd_get_all_songs($data)
{
    $page = isset($data['page']) ? intval($data['page']) : 1;
    $per_page = isset($data['per_page']) ? intval($data['per_page']) : 12;
    $orderby = isset($data['orderby']) ? sanitize_text_field($data['orderby']) : 'title';
    $order = isset($data['order']) ? sanitize_text_field($data['order']) : 'ASC';

    $args = array(
        'post_type' => 'canciones',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => $orderby,
        'order' => $order,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);
    $songs = array();

    foreach ($query->posts as $post) {
        $artista_obj = get_field('artista', $post->ID);
        $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
        $thumb = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        if (!$thumb)
            $thumb = 'https://via.placeholder.com/150x150?text=No+Image';
        $songs[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'artist' => $artista_nombre,
            'thumbnail' => $thumb,
            'url' => get_field('url_cancion', $post->ID),
            'permalink' => get_permalink($post->ID),
            'duration' => get_field('duracion', $post->ID),
        );
    }

    return array(
        'songs' => $songs,
        'total' => $query->found_posts,
        'page' => $page,
        'total_pages' => $query->max_num_pages,
    );
}
/**
 * Endpoint REST para obtener el HTML del single de una canción.
 * GET /wp-json/breakdown/v1/song-content/{id}
 */
add_action('rest_api_init', function () {
    register_rest_route('breakdown/v1', '/song-content/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'bd_get_song_content',
        'permission_callback' => '__return_true'
    ));
});

function bd_get_song_content($data)
{
    $post_id = absint($data['id']);
    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'canciones') {
        return new WP_Error('no_song', 'Canción no encontrada', array('status' => 404));
    }

    // Configurar el contexto global para que get_template_part funcione
    global $post;
    $post = get_post($post_id);
    setup_postdata($post);

    ob_start();
    // Cargar el template part que contiene el layout del single
    get_template_part('template-parts/content-single', 'canciones');
    $html = ob_get_clean();

    wp_reset_postdata();

    return array('html' => $html);
}