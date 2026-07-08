(function ($) {

    /* ===== Carruseles ===== */
    const SCROLL_AMOUNT = 680;

    $('.bd-carousel-next').on('click', function () {
        const $track = $(this).siblings('.bd-carousel-track');
        $track.animate({ scrollLeft: $track.scrollLeft() + SCROLL_AMOUNT }, 350);
    });

    $('.bd-carousel-prev').on('click', function () {
        const $track = $(this).siblings('.bd-carousel-track');
        $track.animate({ scrollLeft: $track.scrollLeft() - SCROLL_AMOUNT }, 350);
    });

    /* ===== Single: reproducción ===== */
    const $playBtn = $('#bd-play-btn');
    const $pauseBtn = $('#bd-pause-btn');
    const $videoContainer = $('#bd-single-video');
    const $overlay = $('#bd-overlay');
    let $iframe = null;
    let isPaused = false;
    let pauseTimer = null;
    let hoverTimer = null;

    // Envía comandos al iframe de YouTube vía postMessage
    function sendCommand(cmd) {
        if (!$iframe?.length) return;
        $iframe[0].contentWindow.postMessage(
            JSON.stringify({ event: 'command', func: cmd, args: '' }), '*'
        );
    }

    // Gestión del timer de ocultamiento del botón pausa
    function clearPauseTimer() {
        clearTimeout(pauseTimer);
        pauseTimer = null;
    }

    function startPauseTimer() {
        clearPauseTimer();
        pauseTimer = setTimeout(() => {
            if (!$pauseBtn.data('hovering')) $pauseBtn.hide();
        }, 10000);
    }

    // Mostrar / ocultar botón pausa
    function showPause() {
        if (!isPaused && $videoContainer.is(':visible')) {
            $pauseBtn.show();
            startPauseTimer();
        }
    }

    function hidePause() {
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(() => {
            if (!$pauseBtn.data('hovering') && !isPaused) $pauseBtn.hide();
        }, 3000);
    }

    // Inicializa el iframe y fuerza autoplay (solo la primera vez)
    function initIframe() {
        if ($iframe) return;
        $iframe = $('#video-container').find('iframe');
        const src = $iframe.attr('src') ?? '';
        if (src && !src.includes('autoplay=1')) {
            const sep = src.includes('?') ? '&' : '?';
            $iframe.attr('src', src + sep + 'autoplay=1');
        }
    }

    /* Botón Reproducir */
    $playBtn.on('click', function () {
        $videoContainer.show();
        initIframe();

        if (isPaused) {
            sendCommand('playVideo');
            isPaused = false;
        } else {
            $overlay.addClass('video-active');
        }

        $playBtn.hide();
        $pauseBtn.show();
        startPauseTimer();
    });

    /* Botón Pausa */
    $pauseBtn.on('click', function () {
        sendCommand('pauseVideo');
        isPaused = true;
        clearPauseTimer();
        $pauseBtn.hide();
        $playBtn.show();
    });

    /* Hover sobre botón pausa */
    $pauseBtn
        .on('mouseenter', function () {
            $(this).data('hovering', true);
            clearPauseTimer();
        })
        .on('mouseleave', function () {
            $(this).data('hovering', false);
            if (!isPaused) startPauseTimer();
        });

    /* Hover sobre overlay */
    $overlay
        .on('mouseenter', function () {
            if ($videoContainer.is(':visible') && !isPaused) showPause();
        })
        .on('mouseleave', function () {
            if ($videoContainer.is(':visible') && !isPaused) hidePause();
        });

})(jQuery);