<?php
/**
 * The template for displaying all songs archive (Todas las canciones)
 *
 * @package breakdownmusic-theme
 */

get_header();
?>

<main id="bd-content" class="site-main">
    <div class="container-fluid py-4">

        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="bd-archive-title">Todas las canciones</h1>
            </div>
        </div>

        <!-- Contenedor para las canciones (scroll infinito) -->
        <div id="bd-all-songs-container" data-page="1" data-total-pages="0" data-orderby="title" data-order="ASC">
            <h2 class="d-none">Nuestra biblioteca</h2>
            <div id="bd-all-songs-list">
                <!-- Se llenará via AJAX como lista -->
            </div>
            <div id="bd-all-songs-loader" class="text-center py-3" style="display:none;">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
            <div id="bd-all-songs-end" class="text-center text-muted small py-3" style="display:none;">
                No hay más canciones.
            </div>
        </div>
    </div>
</main>

<?php
get_sidebar();
get_footer();