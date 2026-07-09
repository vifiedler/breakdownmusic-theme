<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main id="primary" class="site-main">

    <div class="container-fluid">

        <!-- Mood Pills (opcional, lo dejo como referencia) -->
        <div class="bd-mood-pills">
            <button class="bd-pill active">Entrenar</button>
            <button class="bd-pill">Energizarme</button>
            <button class="bd-pill">Relajarme</button>
            <button class="bd-pill">Fiesta</button>
            <button class="bd-pill">Concentración</button>
            <button class="bd-pill">Dormir</button>
        </div>

        <?php
        // ============================================================
        // 1. SECCIÓN: ESCUCHADO DE NUEVO (destacados)
        // ============================================================
        $destacados_term = get_term_by('slug', 'destacadas', 'genero_cancion');
        if ($destacados_term && !is_wp_error($destacados_term)) :
            $term_link = get_term_link($destacados_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <h2 class="bd-section-title">Destacados</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'destacadas';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-destacados.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // ============================================================
        // 2. SECCIÓN: METALCORE
        // ============================================================
        $metalcore_term = get_term_by('slug', 'metalcore', 'genero_cancion');
        if ($metalcore_term && !is_wp_error($metalcore_term)) :
            $term_link = get_term_link($metalcore_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <div>
                        <span class="bd-section-eyebrow">Género</span>
                        <h2 class="bd-section-title">Metalcore</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'metalcore';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // ============================================================
        // 3. SECCIÓN: THRASH METAL
        // ============================================================
        $thrash_term = get_term_by('slug', 'thrash-metal', 'genero_cancion');
        if ($thrash_term && !is_wp_error($thrash_term)) :
            $term_link = get_term_link($thrash_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <div>
                        <span class="bd-section-eyebrow">Género</span>
                        <h2 class="bd-section-title">Thrash Metal</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'thrash-metal';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // ============================================================
        // 4. SECCIÓN: HEAVY METAL
        // ============================================================
        $heavy_term = get_term_by('slug', 'heavy-metal', 'genero_cancion');
        if ($heavy_term && !is_wp_error($heavy_term)) :
            $term_link = get_term_link($heavy_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <div>
                        <span class="bd-section-eyebrow">Género</span>
                        <h2 class="bd-section-title">Heavy Metal</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'heavy-metal';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // ============================================================
        // 5. SECCIÓN: HARD ROCK
        // ============================================================
        $hardrock_term = get_term_by('slug', 'hard-rock', 'genero_cancion');
        if ($hardrock_term && !is_wp_error($hardrock_term)) :
            $term_link = get_term_link($hardrock_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <div>
                        <span class="bd-section-eyebrow">Género</span>
                        <h2 class="bd-section-title">Hard Rock</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'hard-rock';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // ============================================================
        // 6. SECCIÓN: GRUNGE
        // ============================================================
        $grunge_term = get_term_by('slug', 'grunge', 'genero_cancion');
        if ($grunge_term && !is_wp_error($grunge_term)) :
            $term_link = get_term_link($grunge_term);
        ?>
            <section class="bd-section">
                <div class="bd-section-head">
                    <div>
                        <span class="bd-section-eyebrow">Género</span>
                        <h2 class="bd-section-title">Grunge</h2>
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
                <div class="bd-carousel-wrap">
                    <?php
                    $genero_slug = 'grunge';
                    include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                    ?>
                </div>
            </section>
        <?php endif; ?>

    </div><!-- .container-fluid -->

</main><!-- #main -->

<?php get_footer(); ?>