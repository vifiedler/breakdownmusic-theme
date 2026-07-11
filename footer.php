<?php
/**
 * The template for displaying the footer
 *
 * @package breakdownmusic-theme
 */
?>

<!-- ============================================================
	 FOOTER TRADICIONAL (zonas de widgets)
	 ============================================================ -->
<?php include get_template_directory() . '/assets/templates/footers/template-footer.php'; ?>
<!-- ============================================================
	 REPRODUCTOR FIJO INFERIOR (siempre visible)
	 ============================================================ -->
<div id="bd-player-bar" class="bd-player-bar empty">
	<div class="bd-player-container d-flex align-items-center gap-3">
		<!-- Info canción -->
		<div class="d-flex align-items-center gap-2 flex-shrink-0" style="min-width:180px;">
			<img id="bd-player-thumb" src="" alt="Portada" class="bd-player-thumb rounded">
			<div class="d-flex flex-column overflow-hidden">
				<span id="bd-player-title" class="text-truncate fw-semibold" style="font-size:0.85rem;">
					Selecciona una canción
				</span>
				<span id="bd-player-artist" class="text-truncate text-secondary" style="font-size:0.75rem;">
					Artista
				</span>
			</div>
		</div>

		<!-- Controles -->
		<div class="d-flex align-items-center gap-2 flex-shrink-0">
			<button id="bd-player-play" class="btn btn-link text-white p-0" disabled style="font-size:1.5rem;">
				<i class="bi bi-play-fill"></i>
			</button>
		</div>

		<!-- Barra de progreso -->
		<div class="d-flex align-items-center gap-2 flex-grow-1">
			<span id="bd-player-time-current" class="text-secondary"
				style="font-size:0.7rem;min-width:40px;">0:00</span>
			<div id="bd-player-progress" class="flex-grow-1"
				style="height:4px;background:#333;border-radius:2px;cursor:pointer;position:relative;">
				<div id="bd-player-progress-bar" class="bg-danger"
					style="height:100%;width:0%;border-radius:2px;transition:width 0.1s linear;"></div>
			</div>
			<span id="bd-player-time-total" class="text-secondary" style="font-size:0.7rem;min-width:40px;">0:00</span>
		</div>

		<!-- Botón minimizar -->
		<button id="bd-player-toggle" class="btn btn-link text-secondary p-0" style="font-size:1.2rem;">
			<i class="bi bi-chevron-up"></i>
		</button>
	</div>
</div>

<!-- Contenedor oculto para YouTube (necesario para el reproductor) -->
<div id="bd-youtube-player" style="position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px;"></div>
<!-- Modal para vista expandida de canción (carga AJAX) -->
<div class="modal fade" id="bdSongModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen"> <!-- usa modal-fullscreen para ocupar toda la pantalla -->
		<div class="modal-content bg-dark text-white border-0">
			<div class="modal-header border-0">
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
					aria-label="Cerrar"></button>
			</div>
			<div class="modal-body" id="bd-song-modal-body">
				<!-- El contenido del single se inyecta aquí vía AJAX -->
			</div>
		</div>
	</div>
</div>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>