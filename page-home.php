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
 * @package breakdownmusic-theme
 */

get_header();
?>

<main class="bd-main">

	<div class="bd-mood-pills">
		<button class="bd-pill active">Entrenar</button>
		<button class="bd-pill">Energizarme</button>
		<button class="bd-pill">Relajarme</button>
		<button class="bd-pill">Fiesta</button>
		<button class="bd-pill">Concentración</button>
		<button class="bd-pill">Dormir</button>
	</div>

	<!-- Sección: Escuchado de nuevo (destacados) -->
	<section class="bd-section">
		<div class="bd-section-head">
			<h2 class="bd-section-title">Escuchado de nuevo</h2>
			<div class="bd-section-controls">
				<a href="archive.html" class="bd-more-btn">Ver más</a>
				<div class="bd-carousel-controls">
					<button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
					<button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
				</div>
			</div>
		</div>
		<div class="bd-carousel-wrap">
			<div class="bd-carousel-track" data-genero="destacados"></div>
		</div>
	</section>

	<!-- Sección por género: Metalcore -->
	<section class="bd-section">
		<div class="bd-section-head">
			<div>
				<span class="bd-section-eyebrow">Género</span>
				<h2 class="bd-section-title">Metalcore</h2>
			</div>
			<div class="bd-section-controls">
				<a href="archive.html" class="bd-more-btn">Ver más</a>
				<div class="bd-carousel-controls">
					<button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
					<button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
				</div>
			</div>
		</div>
		<div class="bd-carousel-wrap">
			<div class="bd-carousel-track" data-genero="metalcore"></div>
		</div>
	</section>

	<!-- Sección por género: Deathcore -->
	<section class="bd-section">
		<div class="bd-section-head">
			<div>
				<span class="bd-section-eyebrow">Género</span>
				<h2 class="bd-section-title">Deathcore</h2>
			</div>
			<div class="bd-section-controls">
				<a href="archive.html" class="bd-more-btn">Ver más</a>
				<div class="bd-carousel-controls">
					<button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
					<button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
				</div>
			</div>
		</div>
		<div class="bd-carousel-wrap">
			<div class="bd-carousel-track" data-genero="deathcore"></div>
		</div>
	</section>

	<!-- Sección por género: Nu Metal -->
	<section class="bd-section">
		<div class="bd-section-head">
			<div>
				<span class="bd-section-eyebrow">Género</span>
				<h2 class="bd-section-title">Nu Metal</h2>
			</div>
			<div class="bd-section-controls">
				<a href="archive.html" class="bd-more-btn">Ver más</a>
				<div class="bd-carousel-controls">
					<button class="bd-carousel-btn bd-carousel-prev"><i class="bi bi-chevron-left"></i></button>
					<button class="bd-carousel-btn bd-carousel-next"><i class="bi bi-chevron-right"></i></button>
				</div>
			</div>
		</div>
		<div class="bd-carousel-wrap">
			<div class="bd-carousel-track" data-genero="nu-metal"></div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
