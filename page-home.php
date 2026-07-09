<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main id="primary" class="site-main">

    <div class="container-fluid">

        <!-- Mood Pills (opcional) -->
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
        $terms = get_terms(array(
            'taxonomy'   => 'genero_cancion',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));

        if (!empty($terms) && !is_wp_error($terms)) :

            $destacados = null;
            $generos = array();
            foreach ($terms as $term) {
                if ($term->slug === 'destacados') {
                    $destacados = $term;
                } else {
                    $generos[] = $term;
                }
            }

            if ($destacados) :
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
                        <?php
                        $genero_slug = 'destacados';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </section>
        <?php
            endif;

            foreach ($generos as $term) :
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
                        <?php
                        $genero_slug = $term->slug;
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </section>
        <?php
            endforeach;
        endif;
        ?>

    </div>

</main>

<?php get_footer(); ?>