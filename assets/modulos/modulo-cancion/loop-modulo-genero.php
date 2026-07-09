<?php
if (!isset($genero_slug) || empty($genero_slug)) return;

$args = array(
    'post_type'      => 'canciones',
    'posts_per_page' => 12,
    'orderby'        => 'rand',   // 🔀 Orden aleatorio
    'tax_query'      => array(
        array(
            'taxonomy' => 'genero_cancion',
            'field'    => 'slug',
            'terms'    => $genero_slug,
        ),
    ),
);
$query = new WP_Query($args);

if ($query->have_posts()) :
?>
    <div class="bd-carousel-wrap">
        <div class="bd-carousel-track" data-page="0">
            <?php while ($query->have_posts()) : $query->the_post();
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = 'https://via.placeholder.com/300x300?text=No+Image';
                $artista_obj = get_field('artista');
                $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
                $duracion_seg = get_field('duracion');
                $duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';
                $url_cancion = get_field('url_cancion');
            ?>
                <a href="<?php the_permalink(); ?>" class="bd-card" data-index="<?php echo $query->current_post; ?>">
                    <div class="bd-card-thumb-wrap">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="bd-card-thumb">
                        <?php if ($duracion) : ?>
                            <span class="bd-card-duration" style="position:absolute; bottom:10px; left:10px; background:rgba(0,0,0,0.7); padding:2px 8px; border-radius:4px; font-size:0.75rem; color:#fff;"><?php echo esc_html($duracion); ?></span>
                        <?php endif; ?>
                        <button class="bd-play-btn" data-url="<?php echo esc_url($url_cancion); ?>" data-permalink="<?php the_permalink(); ?>">
                            <i class="bi bi-play-fill"></i>
                        </button>
                    </div>
                    <p class="bd-card-title"><?php the_title(); ?></p>
                    <p class="bd-card-sub"><?php echo esc_html($artista_nombre); ?></p>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
<?php
endif;
wp_reset_postdata();
?>