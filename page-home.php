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
        // Obtener todos los géneros
        $terms = get_terms(array(
            'taxonomy'   => 'genero_cancion',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));

        if (!empty($terms) && !is_wp_error($terms)) :

            // Separar destacados
            $destacados = null;
            $generos = array();
            foreach ($terms as $term) {
                if ($term->slug === 'destacados') {
                    $destacados = $term;
                } else {
                    $generos[] = $term;
                }
            }

            // Obtener los slugs de todos los géneros para la consulta
            $slugs = array();
            if ($destacados) $slugs[] = $destacados->slug;
            foreach ($generos as $g) $slugs[] = $g->slug;

            if (!empty($slugs)) :

                // 🔥 UNA SOLA CONSULTA para TODAS las canciones de TODOS los géneros
                $args = array(
                    'post_type'      => 'canciones',
                    'posts_per_page' => 18, // 12 por género * 6 géneros = 72 máx, pero limitamos a 18 para no sobrecargar
                    'orderby'        => 'rand',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'genero_cancion',
                            'field'    => 'slug',
                            'terms'    => $slugs,
                        ),
                    ),
                );
                $query = new WP_Query($args);
                $all_posts = $query->posts;

                // Agrupar posts por género en memoria
                $posts_por_genero = array();
                foreach ($all_posts as $post) {
                    $terms_of_post = wp_get_post_terms($post->ID, 'genero_cancion', array('fields' => 'slugs'));
                    foreach ($terms_of_post as $slug) {
                        if (in_array($slug, $slugs)) {
                            $posts_por_genero[$slug][] = $post;
                            break; // Solo asignar a un género (el primero)
                        }
                    }
                }

                // --- Función para renderizar un carrusel ---
                function render_carousel($posts, $show_duration = true) {
                    if (empty($posts)) return;
                    ?>
                    <div class="bd-carousel-wrap">
                        <div class="bd-carousel-track" data-page="0">
                            <?php foreach ($posts as $post) : setup_postdata($post);
                                $img_url = get_the_post_thumbnail_url($post->ID, 'medium');
                                if (!$img_url) $img_url = 'https://via.placeholder.com/300x300?text=No+Image';
                                $artista_obj = get_field('artista', $post->ID);
                                $artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
                                $duracion_seg = get_field('duracion', $post->ID);
                                $duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';
                                $url_cancion = get_field('url_cancion', $post->ID);
                            ?>
                                <div class="bd-card">
                                    <a href="<?php echo get_permalink($post->ID); ?>" class="d-block text-decoration-none">
                                        <div class="bd-card-thumb-wrap">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($post->post_title); ?>" class="bd-card-thumb" loading="lazy">
                                            <button class="bd-play-btn" 
                                                    data-id="<?php echo $post->ID; ?>" 
                                                    data-url="<?php echo esc_url($url_cancion); ?>"
                                                    data-permalink="<?php echo get_permalink($post->ID); ?>">
                                                <i class="bi bi-play-fill"></i>
                                            </button>
                                            <?php if ($duracion && $show_duration) : ?>
                                                <span class="bd-card-duration"><?php echo esc_html($duracion); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="bd-card-body">
                                        <h5 class="bd-card-title">
                                            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
                                        </h5>
                                        <p class="bd-card-artist"><?php echo esc_html($artista_nombre); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php
                }

                // --- Mostrar destacados ---
                if ($destacados && !empty($posts_por_genero[$destacados->slug])) :
                    $term_link = get_term_link($destacados);
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
                            <?php render_carousel($posts_por_genero[$destacados->slug]); ?>
                        </div>
                    </section>
                <?php
                endif;

                // --- Mostrar el resto de géneros ---
                foreach ($generos as $term) :
                    if (empty($posts_por_genero[$term->slug])) continue;
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
                            <?php render_carousel($posts_por_genero[$term->slug]); ?>
                        </div>
                    </section>
                <?php
                endforeach;

            endif;
        endif;
        ?>

    </div>

</main>

<?php get_footer(); ?>