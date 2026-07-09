<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main id="primary" class="site-main">

    <div class="container-fluid">

        <!-- Mood Pills -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bd-mood-pills">
                    <button class="bd-pill active">Entrenar</button>
                    <button class="bd-pill">Energizarme</button>
                    <button class="bd-pill">Relajarme</button>
                    <button class="bd-pill">Fiesta</button>
                    <button class="bd-pill">Concentración</button>
                    <button class="bd-pill">Dormir</button>
                </div>
            </div>
        </div>

        <?php
        // 🔥 UNA SOLA CONSULTA: obtener TODAS las canciones publicadas
        $all_songs = get_posts(array(
            'post_type'      => 'canciones',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'rand', // aleatorio global
            'fields'         => 'ids',  // solo IDs para ahorrar memoria
        ));

        if (empty($all_songs)) {
            echo '<p>No hay canciones disponibles.</p>';
            get_footer();
            return;
        }

        // Obtener los términos de género
        $terms = get_terms(array(
            'taxonomy'   => 'genero_cancion',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));

        if (empty($terms) || is_wp_error($terms)) {
            echo '<p>No hay géneros configurados.</p>';
            get_footer();
            return;
        }

        // 🔥 Agrupar IDs por género en PHP (sin consultas adicionales)
        $songs_by_genre = array();
        foreach ($terms as $term) {
            $songs_by_genre[$term->slug] = array();
        }

        // Obtener las relaciones de taxonomía de todas las canciones (una sola consulta)
        $term_relationships = wp_get_object_terms($all_songs, 'genero_cancion', array('fields' => 'all_with_object_id'));

        // Agrupar: para cada canción, asignarla a su género
        foreach ($term_relationships as $rel) {
            if (isset($songs_by_genre[$rel->slug])) {
                $songs_by_genre[$rel->slug][] = $rel->object_id;
            }
        }

        // Ahora, para cada género, obtener los datos completos de las canciones (solo las que pertenecen)
        // PERO: haremos un solo query por género con post__in, que es rápido
        // Para evitar múltiples queries, podemos obtener todos los posts de una vez con post__in = todos los IDs
        // y luego filtrar en PHP, pero eso ya lo tenemos: $all_songs tiene todos los IDs.
        // Lo que haremos es: para cada género, obtener los posts completos de sus IDs.
        // Pero como son pocos IDs por género, podemos hacer un solo get_posts con post__in = todos los IDs
        // y luego en PHP agrupar por género. Eso es UNA SOLA CONSULTA extra.
        // Ya tenemos $all_songs que son los IDs. Ahora obtenemos los posts completos.
        $all_posts = get_posts(array(
            'post_type'      => 'canciones',
            'post__in'       => $all_songs,
            'posts_per_page' => -1,
            'orderby'        => 'post__in', // mantener el orden aleatorio
        ));

        // Indexar los posts por ID para acceso rápido
        $posts_by_id = array();
        foreach ($all_posts as $post) {
            $posts_by_id[$post->ID] = $post;
        }

        // Función para renderizar el carrusel de un género dado su slug y array de IDs
        function render_carousel($genre_slug, $post_ids, $posts_by_id) {
            if (empty($post_ids)) return;
            // Tomar solo los primeros 12 (aleatorio ya está aplicado globalmente)
            $ids = array_slice($post_ids, 0, 12);
            if (empty($ids)) return;
            ?>
            <div class="bd-carousel-wrap">
                <div class="bd-carousel-track" data-page="0">
                    <?php foreach ($ids as $id) :
                        $post = $posts_by_id[$id];
                        if (!$post) continue;
                        setup_postdata($post);
                        $img_url = get_the_post_thumbnail_url($id, 'medium');
                        if (!$img_url) $img_url = 'https://via.placeholder.com/300x300?text=No+Image';
                        $artista_obj = get_field('artista', $id);
                        $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
                        $duracion_seg = get_field('duracion', $id);
                        $duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';
                        $url_cancion = get_field('url_cancion', $id);
                    ?>
                        <div class="bd-card">
                            <a href="<?php echo get_permalink($id); ?>" class="d-block text-decoration-none">
                                <div class="bd-card-thumb-wrap">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title($id)); ?>" class="bd-card-thumb" loading="lazy">
                                    <button class="bd-play-btn" data-id="<?php echo $id; ?>" data-url="<?php echo esc_url($url_cancion); ?>" data-permalink="<?php echo get_permalink($id); ?>">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                    <?php if ($duracion) : ?>
                                        <span class="bd-card-duration"><?php echo esc_html($duracion); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="bd-card-body">
                                <h5 class="bd-card-title">
                                    <a href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?></a>
                                </h5>
                                <p class="bd-card-artist"><?php echo esc_html($artista_nombre); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        }

        // Mostrar destacados primero
        if (isset($songs_by_genre['destacados']) && !empty($songs_by_genre['destacados'])) {
            $term_link = get_term_link('destacados', 'genero_cancion');
            ?>
            <section class="row mb-5 bd-section">
                <div class="col-12">
                    <div class="bd-section-head">
                        <h2 class="bd-section-title">Escuchado de nuevo</h2>
                        <div class="bd-section-controls">
                            <?php if (!is_wp_error($term_link)) : ?>
                                <a href="<?php echo esc_url($term_link); ?>" class="bd-more-btn">Ver más</a>
                            <?php endif; ?>
                            <div class="bd-carousel-controls">
                                <button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
                                <button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php render_carousel('destacados', $songs_by_genre['destacados'], $posts_by_id); ?>
                </div>
            </section>
            <?php
            unset($songs_by_genre['destacados']); // para no mostrarlo de nuevo
        }

        // Mostrar el resto de géneros
        foreach ($terms as $term) {
            if ($term->slug === 'destacados') continue;
            if (empty($songs_by_genre[$term->slug])) continue;
            $term_link = get_term_link($term);
            ?>
            <section class="row mb-5 bd-section">
                <div class="col-12">
                    <div class="bd-section-head">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title"><?php echo esc_html($term->name); ?></h2>
                        </div>
                        <div class="bd-section-controls">
                            <?php if (!is_wp_error($term_link)) : ?>
                                <a href="<?php echo esc_url($term_link); ?>" class="bd-more-btn">Ver más</a>
                            <?php endif; ?>
                            <div class="bd-carousel-controls">
                                <button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
                                <button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php render_carousel($term->slug, $songs_by_genre[$term->slug], $posts_by_id); ?>
                </div>
            </section>
            <?php
        }
        ?>

    </div>

</main>

<?php get_footer(); ?>