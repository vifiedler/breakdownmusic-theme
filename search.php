<?php
/**
 * The template for displaying search results pages
 *
 * @package breakdownmusic-theme
 */

get_header();
?>

<main id="bd-content" class="site-main">
    <div class="container-fluid py-4">
        <h1 class="d-none">Página de búsqueda</h1>
        <!-- Título de búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="bd-search-title h2 fw-bold">
                    <?php
                    printf(
                        /* translators: %s: search query */
                        esc_html__('Resultados de búsqueda para: "%s"', 'breakdownmusic-theme'),
                        '<span class="text-danger">' . get_search_query() . '</span>'
                    );
                    ?>
                </h2>
            </div>
        </div>

        <?php if (have_posts()): ?>
            <!-- Grid de resultados -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php while (have_posts()):
                    the_post(); ?>
                    <div class="col">
                        <?php
                        // Usar el mismo template que en archive
                        get_template_part('template-parts/content', 'search-card');
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Paginación -->
            <div class="row mt-4">
                <div class="col-12">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '<i class="bi bi-chevron-left"></i>',
                        'next_text' => '<i class="bi bi-chevron-right"></i>',
                        'class' => 'pagination justify-content-center',
                    ));
                    ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Sin resultados -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="bd-search-empty">
                        <i class="bi bi-music-note-beamed display-1 text-secondary mb-3 d-block"></i>
                        <h2 class="h4"><?php esc_html_e('No se encontraron resultados', 'breakdownmusic-theme'); ?></h2>
                        <p class="text-muted">
                            <?php esc_html_e('Intenta con otras palabras clave o revisa la ortografía.', 'breakdownmusic-theme'); ?>
                        </p>
                        <a href="<?php echo home_url('/'); ?>" class="btn btn-outline-light mt-2">
                            <i class="bi bi-house-fill"></i>
                            <?php esc_html_e('Volver al inicio', 'breakdownmusic-theme'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Alerta via JS -->
            <script>
                (function () {
                    var query = '<?php echo esc_js(get_search_query()); ?>';
                    alert('Lo sentimos, "' + query + '" no se encuentra disponible.');
                })();
            </script>
        <?php endif; ?>

    </div>
</main>

<?php
get_sidebar();
get_footer();