<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package breakdownmusic-theme
 */
$artista_obj = get_field('artista');
$album_obj = get_field('album');
$duracion_seg = get_field('duracion');
$url_cancion = get_field('url_cancion');
$descripcion = get_the_content();

// Formatear duración
$duracion = $duracion_seg ? sprintf('%02d:%02d', floor($duracion_seg / 60), $duracion_seg % 60) : '';

// Obtener nombres e IDs
$artista_nombre = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->post_title : 'Artista desconocido';
$artista_id = is_array($artista_obj) && !empty($artista_obj) ? $artista_obj[0]->ID : 0;
$album_titulo = is_array($album_obj) && !empty($album_obj) ? $album_obj[0]->post_title : 'Álbum desconocido';
$album_id = is_array($album_obj) && !empty($album_obj) ? $album_obj[0]->ID : 0;

// Portada
$img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$img_url && $album_id) {
	$img_url = get_the_post_thumbnail_url($album_id, 'full');
}
if (!$img_url)
	$img_url = 'https://via.placeholder.com/600x600?text=No+Image';

// Año del álbum (opcional)
$anio_album = $album_id ? get_field('anio_lanzamiento', $album_id) : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="bd-single-layout">

		<!-- Columna izquierda -->
		<div class="bd-single-info">
			<!-- Breadcrumb: artista -->
			<?php if ($artista_id): ?>
				<a href="<?php echo get_permalink($artista_id); ?>" class="bd-single-breadcrumb">
					<i class="bi bi-music-note-beamed"></i>
					<span id="bd-single-artista"><?php echo esc_html($artista_nombre); ?></span>
				</a>
			<?php else: ?>
				<span class="bd-single-breadcrumb">
					<i class="bi bi-music-note-beamed"></i>
					<span id="bd-single-artista"><?php echo esc_html($artista_nombre); ?></span>
				</span>
			<?php endif; ?>

			<!-- Portada -->
			<div class="bd-single-cover">
				<img id="bd-single-cover-img" src="<?php echo esc_url($img_url); ?>"
					alt="<?php echo esc_attr(get_the_title()); ?>">
			</div>

			<!-- Título -->
			<h1 class="bd-single-title" id="bd-single-titulo"><?php the_title(); ?></h1>

			<!-- Meta -->
			<p class="bd-single-meta-line">
				<i class="bi bi-person-fill"></i>
				<?php if ($artista_id): ?>
					<a href="<?php echo get_permalink($artista_id); ?>"><?php echo esc_html($artista_nombre); ?></a>
				<?php else: ?>
					<?php echo esc_html($artista_nombre); ?>
				<?php endif; ?>
			</p>
			<p class="bd-single-meta-line">
				<i class="bi bi-disc-fill"></i>
				<?php if ($album_id): ?>
					<a href="<?php echo get_permalink($album_id); ?>"><?php echo esc_html($album_titulo); ?></a>
				<?php else: ?>
					<?php echo esc_html($album_titulo); ?>
				<?php endif; ?>
				<?php if ($anio_album): ?>
					<span class="text-muted">• <?php echo esc_html($anio_album); ?></span>
				<?php endif; ?>
			</p>
			<p class="bd-single-meta-line">
				<i class="bi bi-clock-fill"></i> <?php echo esc_html($duracion); ?>
			</p>

			<!-- Descripción -->
			<?php if ($descripcion): ?>
				<p class="bd-single-desc"><?php echo wp_kses_post($descripcion); ?></p>
			<?php endif; ?>

			<!-- Acciones -->
			<div class="bd-single-actions">
				<button class="bd-action-btn" title="Descargar"><i class="bi bi-download"></i></button>
				<button class="bd-action-btn" id="bd-save-btn" title="Guardar"><i
						class="bi bi-bookmark-fill"></i></button>
				<button class="bd-action-btn bd-play-main" id="bd-play-main" title="Reproducir"
					data-url="<?php echo esc_url($url_cancion); ?>">
					<i class="bi bi-play-fill"></i>
				</button>
				<button class="bd-action-btn" title="Compartir"><i class="bi bi-share-fill"></i></button>
				<button class="bd-action-btn" title="Más opciones"><i class="bi bi-three-dots"></i></button>
			</div>
		</div>

		<!-- Columna derecha: canciones del mismo artista -->
		<div class="bd-single-tracklist" id="bd-tracklist">
			<h3 class="h5 fw-bold mb-3">Canciones de <?php echo esc_html($artista_nombre); ?></h3>
			<?php
			$artista_id_loop = $artista_id;
			include get_template_directory() . '/assets/modulos/modulo-cancion/loop-mp-relacionadas-artista.php';
			?>
		</div>

	</div>
</article><!-- #post-<?php the_ID(); ?> -->