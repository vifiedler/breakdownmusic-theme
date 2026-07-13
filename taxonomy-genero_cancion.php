<?php
/**
 * The template for displaying genre (taxonomy) pages
 *
 * @package breakdownmusic-theme
 */

$current_term = get_queried_object();
get_header();
?>

<main id="bd-content" class="site-main">
    <div class="container-fluid py-4">

        <!-- Título del género -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="bd-archive-title"><?php echo esc_html($current_term->name); ?></h1>
                <?php if ($current_term->description): ?>
                <p class="bd-archive-description"><?php echo esc_html($current_term->description); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contenedor para las canciones (scroll infinito) -->
        <div id="bd-genre-songs-container" data-genre="<?php echo esc_attr($current_term->slug); ?>" data-page="1"
            data-total-pages="0" data-orderby="title" data-order="ASC">
            <h2 class="d-none">Más canciones</h2>
            <div id="bd-genre-songs-list">
                <!-- Se llenará via AJAX -->
            </div>
            <div id="bd-genre-loader" class="text-center py-3" style="display:none;">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
            <div id="bd-genre-end" class="text-center text-muted small py-3" style="display:none;">
                No hay más canciones de este género.
            </div>
        </div>

    </div>
</main>

<?php
get_sidebar();
get_footer();