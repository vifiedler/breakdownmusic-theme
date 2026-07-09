/**
 * js-bdmusic.js - Con reproductor GLOBAL y navegación AJAX
 */
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
    // 2. CERRAR BÚSQUEDA AL HACER CLICK FUERA
    // ============================================================
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.bd-search-form').length) {
            $('#bd-search-results').hide();
        }
    });

    // ============================================================
    // 3. REPRODUCTOR DE YOUTUBE (GLOBAL)
    // ============================================================
    var player;
    var playerReady = false;
    var currentVideoId = '';
    var currentTitle = '';
    var currentArtist = '';
    var currentThumb = '';
    var isPlaying = false;
    var progressInterval = null;
    var isDragging = false;

    // Elementos DOM
    var playerBar = $('#bd-player-bar');
    var playerThumb = $('#bd-player-thumb');
    var playerTitle = $('#bd-player-title');
    var playerArtist = $('#bd-player-artist');
    var playerPlayBtn = $('#bd-player-play');
    var playerToggle = $('#bd-player-toggle');
    var progressBar = $('#bd-player-progress-bar');
    var progressWrap = $('#bd-player-progress');
    var timeCurrent = $('#bd-player-time-current');
    var timeTotal = $('#bd-player-time-total');

    // Funciones auxiliares
    function bdExtractVideoId(url) {
        if (!url) return null;
        var match = url.match(/(?:v=|\/)([a-zA-Z0-9_-]{11})(?:[&?]|$)/);
        return match ? match[1] : null;
    }

    function bdFormatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

    // YouTube API
    function bdInitYouTubePlayer() {
        if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            return;
        }
        bdCreatePlayer();
    }

    window.onYouTubeIframeAPIReady = function () {
        bdCreatePlayer();
    };

    function bdCreatePlayer() {
        if (player) return;
        player = new YT.Player('bd-youtube-player', {
            height: '1',
            width: '1',
            videoId: '',
            playerVars: {
                autoplay: 0,
                controls: 0,
                disablekb: 1,
                fs: 0,
                rel: 0,
                modestbranding: 1
            },
            events: {
                'onReady': bdOnPlayerReady,
                'onStateChange': bdOnPlayerStateChange,
                'onError': function (e) { console.warn('YouTube error:', e.data); }
            }
        });
    }

    function bdOnPlayerReady() {
        playerReady = true;
        console.log('YouTube Player ready');
        if (currentVideoId) {
            playerPlayBtn.prop('disabled', false);
            if (isPlaying) {
                player.playVideo();
            }
        }
    }

    function bdOnPlayerStateChange(event) {
        var state = event.data;
        if (state === YT.PlayerState.PLAYING) {
            isPlaying = true;
            playerPlayBtn.html('<i class="bi bi-pause-fill"></i>');
            playerPlayBtn.prop('disabled', false);
            playerBar.removeClass('empty');
            if (progressInterval) clearInterval(progressInterval);
            progressInterval = setInterval(bdUpdateProgress, 500);
        } else if (state === YT.PlayerState.PAUSED || state === YT.PlayerState.ENDED) {
            isPlaying = false;
            playerPlayBtn.html('<i class="bi bi-play-fill"></i>');
            playerPlayBtn.prop('disabled', false);
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
            if (state === YT.PlayerState.ENDED) {
                progressBar.css('width', '100%');
                var dur = player.getDuration();
                if (dur) timeCurrent.text(bdFormatTime(dur));
            }
        }
    }

    function bdUpdateProgress() {
        if (!player || !playerReady || isDragging) return;
        try {
            var current = player.getCurrentTime();
            var duration = player.getDuration();
            if (duration > 0) {
                var percent = (current / duration) * 100;
                progressBar.css('width', percent + '%');
                timeCurrent.text(bdFormatTime(current));
                timeTotal.text(bdFormatTime(duration));
            }
        } catch (e) { /* ignore */ }
    }

    // Función principal para cargar y reproducir
    function bdPlaySong(videoId, title, artist, thumb) {
        if (!videoId) return;

        if (currentVideoId === videoId) {
            if (player && playerReady) {
                var state = player.getPlayerState();
                if (state === YT.PlayerState.PLAYING) {
                    player.pauseVideo();
                } else {
                    player.playVideo();
                }
            }
            return;
        }

        currentVideoId = videoId;
        currentTitle = title || 'Canción';
        currentArtist = artist || 'Artista';
        currentThumb = thumb || '';

        playerTitle.text(currentTitle);
        playerArtist.text(currentArtist);
        if (currentThumb) {
            playerThumb.attr('src', currentThumb);
        } else {
            playerThumb.attr('src', 'https://img.youtube.com/vi/' + videoId + '/default.jpg');
        }

        playerBar.addClass('visible').removeClass('empty');
        playerBar.removeClass('minimized');
        playerPlayBtn.prop('disabled', true);

        if (player && playerReady) {
            player.loadVideoById(videoId);
            playerPlayBtn.prop('disabled', false);
        } else {
            if (!player) bdInitYouTubePlayer();
            var checkReady = setInterval(function () {
                if (playerReady) {
                    clearInterval(checkReady);
                    player.loadVideoById(videoId);
                    playerPlayBtn.prop('disabled', false);
                }
            }, 200);
        }
    }

    // ============================================================
    // 4. EVENTOS DE REPRODUCCIÓN (GLOBALES)
    // ============================================================

    // Botón PLAY en cards del home y otros
    $(document).on('click', '.bd-play-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var url = $btn.data('url');
        if (!url) return;
        var videoId = bdExtractVideoId(url);
        if (!videoId) {
            alert('URL de YouTube no válida');
            return;
        }
        var $card = $btn.closest('.bd-card');
        var title = $card.find('.bd-card-title').text().trim() || 'Canción';
        var artist = $card.find('.bd-card-sub').text().trim() || 'Artista';
        var thumb = $card.find('.bd-card-thumb').attr('src') || '';
        if (!player) bdInitYouTubePlayer();
        bdPlaySong(videoId, title, artist, thumb);
        $btn.css('transform', 'scale(0.8)');
        setTimeout(function () { $btn.css('transform', 'scale(1)'); }, 200);
    });

    // Botón PLAY en tracks relacionados (loop-mp-relacionadas-artista.php)
    $(document).on('click', '.bd-play-btn-track', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var url = $btn.data('url');
        if (!url) return;
        var videoId = bdExtractVideoId(url);
        if (!videoId) {
            alert('URL de YouTube no válida');
            return;
        }
        var $row = $btn.closest('.bd-track-row');
        var title = $row.find('.bd-track-title a').text().trim() || 'Canción';
        var artist = $row.find('.bd-track-sub').text().trim() || 'Artista';
        var thumb = $btn.data('thumb') || ''; // 🔥 Usar data-thumb del botón
        if (!player) bdInitYouTubePlayer();
        bdPlaySong(videoId, title, artist, thumb);
        $btn.css('transform', 'scale(0.8)');
        setTimeout(function () { $btn.css('transform', 'scale(1)'); }, 200);
    });

    // Botón PLAY principal del single (#bd-play-main)
    $(document).on('click', '#bd-play-main', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var url = $btn.data('url');
        if (!url) return;
        var videoId = bdExtractVideoId(url);
        if (!videoId) {
            alert('URL de YouTube no válida');
            return;
        }
        var title = $('#bd-single-titulo').text().trim() || 'Canción';
        var artist = $('#bd-single-artista').text().trim() || 'Artista';
        var thumb = $('.bd-single-cover img').attr('src') || '';
        if (!player) bdInitYouTubePlayer();
        bdPlaySong(videoId, title, artist, thumb);
        $btn.css('transform', 'scale(0.9)');
        setTimeout(function () { $btn.css('transform', 'scale(1)'); }, 200);
    });

    // Botón play/pause del reproductor inferior
    $('#bd-player-play').on('click', function () {
        if (!player || !playerReady) {
            console.warn('Reproductor no listo');
            return;
        }
        var state = player.getPlayerState();
        if (state === YT.PlayerState.PLAYING) {
            player.pauseVideo();
        } else if (state === YT.PlayerState.PAUSED || state === YT.PlayerState.ENDED || state === -1) {
            player.playVideo();
        }
    });

    // Barra de progreso (arrastrar)
    progressWrap.on('mousedown', function (e) {
        if (!player || !playerReady) return;
        isDragging = true;
        var rect = this.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var percent = Math.max(0, Math.min(1, x / rect.width));
        var duration = player.getDuration();
        if (duration > 0) {
            var seekTime = percent * duration;
            player.seekTo(seekTime, true);
            progressBar.css('width', percent * 100 + '%');
            timeCurrent.text(bdFormatTime(seekTime));
        }
        $(document).on('mousemove.bdProgress', function (e2) {
            var rect2 = progressWrap[0].getBoundingClientRect();
            var x2 = e2.clientX - rect2.left;
            var percent2 = Math.max(0, Math.min(1, x2 / rect2.width));
            progressBar.css('width', percent2 * 100 + '%');
            if (player && playerReady) {
                var dur = player.getDuration();
                if (dur > 0) {
                    timeCurrent.text(bdFormatTime(percent2 * dur));
                }
            }
        });
        $(document).on('mouseup.bdProgress', function () {
            isDragging = false;
            $(document).off('mousemove.bdProgress');
            $(document).off('mouseup.bdProgress');
            bdUpdateProgress();
        });
    });

    // Minimizar/Expandir
    playerToggle.on('click', function () {
        playerBar.toggleClass('minimized');
        var icon = playerBar.hasClass('minimized') ? 'bi-chevron-up' : 'bi-chevron-down';
        $(this).find('i').removeClass('bi-chevron-down bi-chevron-up').addClass(icon);
    });

    // Inicializar reproductor
    bdInitYouTubePlayer();

    // Limpiar intervalo al salir
    $(window).on('beforeunload', function () {
        if (progressInterval) clearInterval(progressInterval);
    });

    // ============================================================
    // 5. CARRUSEL (solo si existe)
    // ============================================================
    if ($('.bd-carousel-track').length) {

        if (typeof anime !== 'undefined') {
            anime({
                targets: '.bd-card',
                opacity: [0, 1],
                translateY: [8, 0],
                duration: 200,
                easing: 'easeOutQuad'
            });
        } else {
            $('.bd-card').addClass('fade-in-up');
        }

        const BD_CARDS_PER_PAGE = 3;

        function bdActualizarBotones($section) {
            const $wrap = $section.find('.bd-carousel-wrap');
            const $track = $wrap.find('.bd-carousel-track');
            const $cards = $track.find('.bd-card');
            const totalPages = Math.ceil($cards.length / BD_CARDS_PER_PAGE);
            const page = parseInt($track.data('page')) || 0;

            $section.find('.bd-carousel-prev').prop('disabled', page <= 0);
            $section.find('.bd-carousel-next').prop('disabled', page >= totalPages - 1);
        }

        function bdIrAPagina($section, nuevaPagina) {
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

        $('.bd-section').each(function () {
            bdActualizarBotones($(this));
        });

        window.bdReinitCarousel = function () {
            if ($('.bd-carousel-track').length) {
                $('.bd-section').each(function () {
                    bdActualizarBotones($(this));
                });
                if (typeof anime !== 'undefined') {
                    anime({
                        targets: '.bd-card',
                        opacity: [0, 1],
                        translateY: [8, 0],
                        duration: 200,
                        easing: 'easeOutQuad'
                    });
                }
            }
        };
    }

    // ============================================================
    // 6. NAVEGACIÓN AJAX
    // ============================================================
    $(document).on('click', 'a:not([target="_blank"]):not([href^="http"]):not([href^="#"])', function (e) {
        var $link = $(this);
        var href = $link.attr('href');
        if (!href || href.indexOf('wp-admin') !== -1 || href.indexOf('wp-login') !== -1) return;
        if ($link.hasClass('no-ajax')) return;
        if (href.match(/\.(pdf|zip|doc|docx|jpg|png|gif|mp3|mp4)$/i)) return;

        e.preventDefault();
        bdLoadPage(href);
    });

    function bdLoadPage(url) {
        $('#bd-content').html('<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            success: function (html) {
                var $html = $(html);
                var newContent = $html.find('#bd-content').html();
                if (newContent) {
                    $('#bd-content').html(newContent);
                } else {
                    var primaryContent = $html.find('#primary').html();
                    if (primaryContent) {
                        $('#bd-content').html(primaryContent);
                    } else {
                        var bodyContent = $html.find('main').html();
                        if (bodyContent) $('#bd-content').html(bodyContent);
                    }
                }

                var newTitle = $html.filter('title').text();
                if (newTitle) document.title = newTitle;

                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', url);
                }

                if (typeof window.bdReinitCarousel === 'function') {
                    window.bdReinitCarousel();
                }

                window.scrollTo(0, 0);
            },
            error: function () {
                window.location.href = url;
            }
        });
    }

    $(window).on('popstate', function (e) {
        var url = location.href;
        bdLoadPage(url);
    });

    // Función para obtener datos de una canción por ID usando el endpoint REST
    function bdGetSongData(songId, callback) {
        $.ajax({
            url: '/wp-json/breakdown/v1/song/' + songId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (callback) callback(data);
            },
            error: function () {
                console.warn('Error al obtener datos de la canción');
                if (callback) callback(null);
            }
        });
    }

    // ============================================================
    // SCROLL INFINITO PARA CANCIONES DEL MISMO GÉNERO (single de álbum)
    // ============================================================
    var genreContainer = $('#bd-genre-songs-container');
    if (genreContainer.length) {
        var genreSlug = genreContainer.data('genre');
        var albumId = genreContainer.data('album-id');
        var currentPage = parseInt(genreContainer.data('page')) || 1;
        var totalPages = parseInt(genreContainer.data('total-pages')) || 0;
        var loading = false;
        var endReached = false;

        var $list = $('#bd-genre-songs-list');
        var $loader = $('#bd-genre-loader');
        var $end = $('#bd-genre-end');

        // Función para cargar una página
        function bdLoadGenreSongs(page) {
            if (loading || endReached) return;
            loading = true;
            $loader.show();

            if (!genreSlug) {
                console.warn('Genre slug no definido');
                $loader.hide();
                loading = false;
                return;
            }

            // 🔥 Usar bd_ajax.rest_url
            var apiUrl = bd_ajax.rest_url + 'songs-by-genre/' + genreSlug;
            $.ajax({
                url: apiUrl,
                method: 'GET',
                data: {
                    page: page,
                    per_page: 10,
                    exclude_album: albumId
                },
                success: function (response) {
                    if (response.songs && response.songs.length > 0) {
                        // Renderizar las canciones como elementos HTML
                        var $items = $();
                        response.songs.forEach(function (song) {
                            var duration = song.duration ? String(Math.floor(song.duration / 60)).padStart(2, '0') + ':' + String(song.duration % 60).padStart(2, '0') : '';
                            var $item = $(`
                            <div class="bd-song-card d-flex align-items-center gap-3 p-2 rounded-3" style="cursor:pointer;">
                                <div class="bd-song-thumb-wrap position-relative flex-shrink-0" style="width:52px;height:52px;">
                                    <img src="${song.thumbnail}" alt="${song.title}" class="w-100 h-100 rounded" style="object-fit:cover;">
                                    <button class="bd-play-btn-track position-absolute top-50 start-50 translate-middle bg-danger border-0 rounded-circle d-flex align-items-center justify-content-center" 
                                            data-url="${song.url}" 
                                            data-thumb="${song.thumbnail}" 
                                            style="width:32px;height:32px;opacity:0;transition:opacity 0.2s;">
                                        <i class="bi bi-play-fill text-white" style="font-size:0.9rem;"></i>
                                    </button>
                                </div>
                                <div class="bd-song-info flex-grow-1 min-width-0">
                                    <p class="bd-song-title fw-semibold text-truncate mb-0">
                                        <a href="${song.permalink}" class="text-white text-decoration-none">${song.title}</a>
                                    </p>
                                    <p class="bd-song-sub text-secondary text-truncate small mb-0">${song.artist}</p>
                                </div>
                                <span class="bd-song-duration text-secondary small">${duration}</span>
                            </div>
                        `);
                            $items = $items.add($item);
                        });

                        // Agregar al DOM
                        $list.append($items);

                        // Animación con anime.js
                        if (typeof anime !== 'undefined') {
                            anime({
                                targets: $items.toArray(),
                                opacity: [0, 1],
                                translateY: [12, 0],
                                delay: anime.stagger(40),
                                duration: 350,
                                easing: 'easeOutQuad'
                            });
                        } else {
                            $items.css('opacity', 1);
                        }

                        // Actualizar estado de paginación
                        totalPages = response.total_pages;
                        currentPage = page;
                        genreContainer.data('page', currentPage);
                        genreContainer.data('total-pages', totalPages);

                        // Si es la última página, mostrar mensaje de fin
                        if (currentPage >= totalPages) {
                            endReached = true;
                            $end.show();
                        }
                    } else {
                        endReached = true;
                        $end.show();
                    }
                    $loader.hide();
                    loading = false;
                },
                error: function () {
                    $loader.hide();
                    loading = false;
                    console.warn('Error al cargar canciones del género');
                }
            });
        }

        // Cargar primera página
        bdLoadGenreSongs(1);

        // Scroll infinito: detectar cuando el usuario llega al final del contenedor
        $(window).on('scroll', function () {
            if (endReached || loading) return;
            var containerBottom = genreContainer.offset().top + genreContainer.outerHeight();
            var windowBottom = $(window).scrollTop() + $(window).height();
            // Cargar cuando el usuario haya scrolleado hasta el 80% del contenedor
            if (windowBottom > containerBottom - 200) {
                bdLoadGenreSongs(currentPage + 1);
            }
        });
    }
});