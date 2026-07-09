<?php
if (!isset($genero_slug) || empty($genero_slug))
    return;

$genero_term = get_term_by('slug', $genero_slug, 'genero_cancion');
if (!$genero_term)
    return;

// 🔥 Caché: guardar el resultado del query por 1 hora
$transient_key = 'bd_home_' . $genero_slug . '_' . date('Y-m-d-H');
$cached_ids = get_transient($transient_key);

if (false === $cached_ids) {
    // Si no hay caché, hacer el query
    $args = array(
        'post_type' => 'canciones',
        'posts_per_page' => 12, // 🔥 LÍMITE: solo 12 canciones por género
        'orderby' => 'rand', // Orden aleatorio eficiente
        'tax_query' => array(
            array(
                'taxonomy' => 'genero_cancion',
                'field' => 'slug',
                'terms' => $genero_slug,
            ),
        ),
        'fields' => 'ids', // Solo obtener IDs para ahorrar memoria
    );
    $query = new WP_Query($args);
    $cached_ids = $query->posts;
    set_transient($transient_key, $cached_ids, HOUR_IN_SECONDS);
}

// Si no hay IDs, salir
if (empty($cached_ids))
    return;

// Segundo query: obtener los posts completos con los IDs cacheados
$args = array(
    'post_type' => 'canciones',
    'post__in' => $cached_ids,
    'orderby' => 'post__in', // Mantener el orden aleatorio del cache
    'posts_per_page' => -1,
);
$query = new WP_Query($args);

if ($query->have_posts()):
    ?>
    <div class="bd-carousel-wrap">
        <div class="bd-carousel-track" data-page="0">
            <?php while ($query->have_posts()):
                $query->the_post();
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url)
                    $img_url = 'https://via.placeholder.com/300x300?text=No+Image';
                $artista_obj = get_field('artista');
                $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
                $duracion_seg = get_field('duracion');
                $duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';
                $url_cancion = get_field('url_cancion');
                ?>
                <div class="bd-card">
                    <a href="<?php the_permalink(); ?>" class="d-block text-decoration-none">
                        <div class="bd-card-thumb-wrap">
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
                                class="bd-card-thumb" loading="lazy">
                            <button class="bd-play-btn" data-id="<?php the_ID(); ?>"
                                data-url="<?php echo esc_url($url_cancion); ?>" data-permalink="<?php the_permalink(); ?>">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <?php if ($duracion): ?>
                                <span class="bd-card-duration"><?php echo esc_html($duracion); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="bd-card-body">
                        <h5 class="bd-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <p class="bd-card-artist"><?php echo esc_html($artista_nombre); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
endif;
wp_reset_postdata();
?>