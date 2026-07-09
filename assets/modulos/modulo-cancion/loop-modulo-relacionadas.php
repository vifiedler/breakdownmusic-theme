<?php
/**
 * Loop para canciones relacionadas (mismo álbum)
 * Espera que la variable $album_id_loop esté definida
 */
if (!isset($album_id_loop) || empty($album_id_loop)) {
    echo '<p class="text-muted">No hay canciones relacionadas.</p>';
    return;
}

$args = array(
    'post_type' => 'canciones',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
        array(
            'key' => 'album', // Nombre del campo ACF/SCF
            'value' => '"' . $album_id_loop . '"', // Buscar el ID del álbum
            'compare' => 'LIKE',
        ),
    ),
    'post__not_in' => array(get_the_ID()), // Excluir la canción actual
);
$query = new WP_Query($args);

if ($query->have_posts()):
    $i = 1;
    while ($query->have_posts()):
        $query->the_post();
        $duracion_seg = get_field('duracion');
        $duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';
        $artista_obj = get_field('artista');
        $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : '';
        ?>
        <div class="bd-track-row" data-id="<?php the_ID(); ?>">
            <span class="bd-track-num"><?php echo $i; ?></span>
            <div class="bd-track-info">
                <p class="bd-track-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </p>
                <p class="bd-track-sub">
                    <?php echo esc_html($artista_nombre); ?>
                </p>
            </div>
            <button class="bd-track-like" data-id="<?php the_ID(); ?>">
                <i class="bi bi-hand-thumbs-up"></i>
            </button>
            <span class="bd-track-duration"><?php echo esc_html($duracion); ?></span>
        </div>
        <?php
        $i++;
    endwhile;
    wp_reset_postdata();
else:
    echo '<p class="text-muted">No hay más canciones de este álbum.</p>';
endif;
?>