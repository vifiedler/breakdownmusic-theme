<?php
// 🔥 Cache para destacados
$transient_key = 'bd_home_destacados_' . date('Y-m-d-H');
$cached_ids = get_transient($transient_key);

if (false === $cached_ids) {
    $args = array(
        'post_type' => 'canciones',
        'posts_per_page' => 12,
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'genero_cancion',
                'field' => 'slug',
                'terms' => 'destacados',
            ),
        ),
        'fields' => 'ids',
    );
    $query = new WP_Query($args);
    $cached_ids = $query->posts;
    set_transient($transient_key, $cached_ids, HOUR_IN_SECONDS);
}

if (empty($cached_ids))
    return;

$args = array(
    'post_type' => 'canciones',
    'post__in' => $cached_ids,
    'orderby' => 'post__in',
    'posts_per_page' => -1,
);
$query = new WP_Query($args);

if ($query->have_posts()):
    ?>
    <div class="bd-carousel-wrap">
        <div class="bd-carousel-track" data-page="0">
            <?php while ($query->have_posts()):
                $query->the_post();
                // ... (mismo HTML que en el loop genérico)
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