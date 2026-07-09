<?php
$artista_nombre = get_the_title();
$artista_id = get_the_ID();
$biografia = get_field('biografia');
$img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$img_url) $img_url = 'https://via.placeholder.com/600x600?text=No+Image';
?>

<div class="bd-artist-hero" style="background-image: url('<?php echo esc_url($img_url); ?>');">
    <div class="bd-artist-hero-overlay">
        <h1 class="bd-artist-name"><?php the_title(); ?></h1>
        <p class="bd-artist-audience">Artista</p>
        <?php if ($biografia) : ?>
            <p class="bd-artist-bio" id="bd-artist-bio"><?php echo wp_kses_post($biografia); ?></p>
            <button class="bd-artist-bio-toggle" id="bd-artist-bio-toggle">Ver más</button>
        <?php endif; ?>
        <div class="bd-artist-actions">
            <button class="bd-btn-pill"><i class="bi bi-shuffle"></i> Aleatorio</button>
            <button class="bd-btn-pill"><i class="bi bi-broadcast"></i> Mix</button>
            <button class="bd-btn-subscribe" id="bd-subscribe-btn">Suscribirse</button>
        </div>
    </div>
</div>

<!-- Álbumes del artista -->
<section class="bd-section">
    <div class="bd-section-head">
        <h2 class="bd-section-title">Álbumes</h2>
    </div>
    <?php include get_template_directory() . '/assets/modulos/modulo-artista/loop-mp-albumes-del-artista.php'; ?>
</section>