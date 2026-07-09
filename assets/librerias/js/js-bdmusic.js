/**
 * js-bdmusic.js
 * Archivo JavaScript limpio y optimizado para Breakdown Music
 * Solo incluye funcionalidades esenciales: sidebar, carrusel y botón de play.
 * No usa datos estáticos ni sobrescribe contenido generado por PHP.
 */

/*
$(function () {

    // ============================================================
    // 1. SIDEBAR TOGGLE
    // ============================================================
    $('#bd-burger').on('click', function () {
        if (window.innerWidth <= 768) {
            $('#bd-sidebar').toggleClass('open');
        } else {
            $('#bd-sidebar').toggleClass('collapsed');
        }
    });

    // ============================================================
    // 2. CERRAR RESULTADOS DE BÚSQUEDA AL HACER CLICK FUERA
    // ============================================================
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.bd-search-form').length) {
            $('#bd-search-results').hide();
        }
    });

    // ============================================================
    // 3. HOME: CARRUSEL DE CANCIONES
    // ============================================================
    if ($('.bd-carousel-track').length) {

        // --- 3.1. Animación de entrada (solo si anime.js está cargado) ---
        if (typeof anime !== 'undefined') {
            anime({
                targets: '.bd-card',
                opacity: [0, 1],
                translateY: [8, 0],
                duration: 200,
                easing: 'easeOutQuad'
            });
        } else {
            // Fallback: añadir clase CSS para animación
            $('.bd-card').addClass('fade-in-up');
        }

        // --- 3.2. Lógica de navegación ---
        const BD_CARDS_PER_PAGE = 3;

        /**
         * Actualiza el estado de los botones prev/next de una sección.
         * @param {jQuery} $section - Elemento .bd-section que contiene el carrusel.
         */
        /*function bdActualizarBotones($section) {
            const $wrap = $section.find('.bd-carousel-wrap');
            const $track = $wrap.find('.bd-carousel-track');
            const $cards = $track.find('.bd-card');
            const totalPages = Math.ceil($cards.length / BD_CARDS_PER_PAGE);
            const page = parseInt($track.data('page')) || 0;

            $section.find('.bd-carousel-prev').prop('disabled', page <= 0);
            $section.find('.bd-carousel-next').prop('disabled', page >= totalPages - 1);
        }*/

        /**
         * Desplaza el carrusel a una página específica.
         * @param {jQuery} $section - Elemento .bd-section.
         * @param {number} nuevaPagina - Índice de página (0‑based).
         */
       /* function bdIrAPagina($section, nuevaPagina) {
            const $wrap = $section.find('.bd-carousel-wrap');
            const $track = $wrap.find('.bd-carousel-track');
            const $cards = $track.find('.bd-card');
            if (!$cards.length) return;

            const totalPages = Math.ceil($cards.length / BD_CARDS_PER_PAGE);
            nuevaPagina = Math.max(0, Math.min(nuevaPagina, totalPages - 1));

            const targetIndex = nuevaPagina * BD_CARDS_PER_PAGE;
            const $targetCard = $cards.eq(targetIndex);
            if (!$targetCard.length) return;

            const destino = $targetCard.position().left + $track.scrollLeft();

            $track.animate({ scrollLeft: destino }, 400);
            $track.data('page', nuevaPagina);
            bdActualizarBotones($section);
        }

        // --- 3.3. Eventos para los botones prev/next ---
        $('.bd-section-head .bd-carousel-prev').on('click', function () {
            const $section = $(this).closest('.bd-section');
            const $track = $section.find('.bd-carousel-track');
            const page = parseInt($track.data('page')) || 0;
            bdIrAPagina($section, page - 1);
        });

        $('.bd-section-head .bd-carousel-next').on('click', function () {
            const $section = $(this).closest('.bd-section');
            const $track = $section.find('.bd-carousel-track');
            const page = parseInt($track.data('page')) || 0;
            bdIrAPagina($section, page + 1);
        });

        // --- 3.4. Inicializar estado de botones en cada sección ---
        $('.bd-section').each(function () {
            bdActualizarBotones($(this));
        });

        // --- 3.5. Botón de reproducción (play) ---
        $(document).on('click', '.bd-play-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const url = $btn.data('url');
            const permalink = $btn.data('permalink');

            if (url) {
                // Abrir la URL de la canción en una nueva pestaña
                window.open(url, '_blank');
            } else if (permalink) {
                // Si no hay URL, redirigir a la página single
                window.location.href = permalink;
            }

            // Animación simple del botón (sin depender de anime.js)
            $btn.css('transform', 'scale(0.8)');
            setTimeout(function () {
                $btn.css('transform', 'scale(1)');
            }, 200);
        });
    }

    // ============================================================
    // FIN DEL ARCHIVO
    // ============================================================
});*/
