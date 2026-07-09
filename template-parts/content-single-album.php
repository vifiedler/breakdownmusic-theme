<?php
/**
 * Template part for displaying album single
 */
$artista_obj = get_field('artista');
$artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
$artista_id = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->ID : 0;
$anio = get_field('anio_lanzamiento');
$descripcion = get_field('descripcion');
$img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$img_url)
	$img_url = 'https://via.placeholder.com/600x600?text=No+Image';

// Obtener el género de las canciones del álbum (usamos el de la primera canción)
$album_songs = get_posts(array(
	'post_type' => 'canciones',
	'posts_per_page' => 1,
	'meta_query' => array(
		array(
			'key' => 'album',
			'value' => '"' . get_the_ID() . '"',
			'compare' => 'LIKE',
		),
	),
));
$genero_slug = '';
$genero_nombre = '';
if (!empty($album_songs)) {
	$terms = wp_get_post_terms($album_songs[0]->ID, 'genero_cancion');
	if (!empty($terms) && !is_wp_error($terms)) {
		$genero_slug = $terms[0]->slug;
		$genero_nombre = $terms[0]->name;
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="bd-single-layout">
		<!-- Columna izquierda: info del álbum -->
		<div class="bd-single-info">
			<!-- Breadcrumb: artista -->
			<?php if ($artista_id): ?>
				<a href="<?php echo get_permalink($artista_id); ?>" class="bd-single-breadcrumb">
					<i class="bi bi-music-note-beamed"></i>
					<span><?php echo esc_html($artista_nombre); ?></span>
				</a>
			<?php else: ?>
				<span class="bd-single-breadcrumb">
					<i class="bi bi-music-note-beamed"></i>
					<span><?php echo esc_html($artista_nombre); ?></span>
				</span>
			<?php endif; ?>

			<div class="bd-single-cover">
				<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
			</div>

			<h1 class="bd-single-title"><?php the_title(); ?></h1>
			<p class="bd-single-meta-line">
				<i class="bi bi-person-fill"></i>
				<?php if ($artista_id): ?>
					<a href="<?php echo get_permalink($artista_id); ?>"><?php echo esc_html($artista_nombre); ?></a>
				<?php else: ?>
					<?php echo esc_html($artista_nombre); ?>
				<?php endif; ?>
			</p>
			<?php if ($anio): ?>
				<p class="bd-single-meta-line"><i class="bi bi-calendar-fill"></i> <?php echo esc_html($anio); ?></p>
			<?php endif; ?>

			<?php if ($descripcion): ?>
				<p class="bd-single-desc"><?php echo wp_kses_post($descripcion); ?></p>
			<?php endif; ?>

			<div class="bd-single-actions">
				<button class="bd-action-btn" title="Descargar"><i class="bi bi-download"></i></button>
				<button class="bd-action-btn" id="bd-save-btn" title="Guardar"><i
						class="bi bi-bookmark-fill"></i></button>
				<button class="bd-action-btn" title="Compartir"><i class="bi bi-share-fill"></i></button>
				<button class="bd-action-btn" title="Más opciones"><i class="bi bi-three-dots"></i></button>
			</div>
		</div>

		<!-- Columna derecha: canciones del álbum + género -->
		<div class="bd-single-tracklist">
			<h3 class="h5 fw-bold mb-3">Canciones del álbum</h3>
			<?php
			$album_id_loop = get_the_ID();
			include get_template_directory() . '/assets/modulos/modulo-album/loop-mp-canciones-del-album.php';
			?>

			<!-- ============================================ -->
			<!-- NUEVA SECCIÓN: Otras canciones del género     -->
			<!-- ============================================ -->
			<?php if ($genero_slug): ?>
				<hr class="my-4 border-secondary">
				<h3 class="h5 fw-bold mb-3">Otras canciones de <?php echo esc_html($genero_nombre); ?></h3>
				<div id="bd-genre-songs-container" data-genre="<?php echo esc_attr($genero_slug); ?>"
					data-album-id="<?php the_ID(); ?>" data-page="1" data-total-pages="0">
					<div id="bd-genre-songs-list" class="bd-song-grid">
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
			<?php endif; ?>
		</div>
	</div>
</article>