<?php
/**
 * Template Name: Home
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package nota4-template
 */

get_header();
?>

<main id="bd-content" class="mt-3">
    <h1 class="d-none">Breakdown Music</h1>
    <div class="container-fluid">
        <?php
        $destacados_term = get_term_by('slug', 'destacadas', 'genero_cancion');
        if ($destacados_term && !is_wp_error($destacados_term)):
            $term_link = get_term_link($destacados_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <h2 class="bd-section-title">Destacados</h2>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'destacadas';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-destacados.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
<!-- ============================================================
     ARTISTAS (todos)
     ============================================================ -->
<section class="bd-section row mb-5">
    <div class="col-12">
        <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="bd-section-eyebrow">Artistas</span>
                <h2 class="bd-section-title">Todos los artistas</h2>
            </div>
            <div class="bd-section-controls d-flex align-items-center gap-2">
                <a href="<?php echo get_post_type_archive_link('artista'); ?>" class="bd-more-btn btn btn-outline-light btn-sm">Ver todos</a>
                <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="bd-carousel-wrap">
            <?php include get_template_directory() . '/assets/modulos/modulo-artista/loop-modulo-artistas.php'; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     ÁLBUMES (todos)
     ============================================================ -->
<section class="bd-section row mb-5">
    <div class="col-12">
        <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="bd-section-eyebrow">Álbumes</span>
                <h2 class="bd-section-title">Todos los álbumes</h2>
            </div>
            <div class="bd-section-controls d-flex align-items-center gap-2">
                <a href="<?php echo get_post_type_archive_link('album'); ?>" class="bd-more-btn btn btn-outline-light btn-sm">Ver todos</a>
                <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="bd-carousel-wrap">
            <?php include get_template_directory() . '/assets/modulos/modulo-album/loop-modulo-albumes.php'; ?>
        </div>
    </div>
</section>
        <?php
        $metalcore_term = get_term_by('slug', 'metalcore', 'genero_cancion');
        if ($metalcore_term && !is_wp_error($metalcore_term)):
            $term_link = get_term_link($metalcore_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title">Metalcore</h2>
                        </div>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'metalcore';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
        $thrash_term = get_term_by('slug', 'thrash-metal', 'genero_cancion');
        if ($thrash_term && !is_wp_error($thrash_term)):
            $term_link = get_term_link($thrash_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title">Thrash Metal</h2>
                        </div>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'thrash-metal';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
        $heavy_term = get_term_by('slug', 'heavy-metal', 'genero_cancion');
        if ($heavy_term && !is_wp_error($heavy_term)):
            $term_link = get_term_link($heavy_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title">Heavy Metal</h2>
                        </div>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'heavy-metal';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
        $hardrock_term = get_term_by('slug', 'hard-rock', 'genero_cancion');
        if ($hardrock_term && !is_wp_error($hardrock_term)):
            $term_link = get_term_link($hardrock_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title">Hard Rock</h2>
                        </div>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'hard-rock';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
        $grunge_term = get_term_by('slug', 'grunge', 'genero_cancion');
        if ($grunge_term && !is_wp_error($grunge_term)):
            $term_link = get_term_link($grunge_term);
            ?>
            <section class="bd-section row mb-5">
                <div class="col-12">
                    <div class="bd-section-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="bd-section-eyebrow">Género</span>
                            <h2 class="bd-section-title">Grunge</h2>
                        </div>
                        <div class="bd-section-controls d-flex align-items-center gap-2">
                            <?php if (!is_wp_error($term_link)): ?>
                                <a href="<?php echo esc_url($term_link); ?>"
                                    class="bd-more-btn btn btn-outline-light btn-sm">Ver más</a>
                            <?php endif; ?>
                            <button class="bd-carousel-prev btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="bd-carousel-next btn btn-outline-light btn-sm rounded-circle"
                                style="width:36px;height:36px;">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bd-carousel-wrap">
                        <?php
                        $genero_slug = 'grunge';
                        include get_template_directory() . '/assets/modulos/modulo-cancion/loop-modulo-genero.php';
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    </div><!-- .container-fluid -->
</main><!-- #main -->

<?php
get_footer(); ?>