$(function () {

    /* ============================================
       SIDEBAR TOGGLE
       ============================================ */
    $('#bd-burger').on('click', function () {
        if (window.innerWidth <= 768) {
            $('#bd-sidebar').toggleClass('open');
        } else {
            $('#bd-sidebar').toggleClass('collapsed');
        }
    });

    /* ============================================
       CERRAR RESULTADOS DE BÚSQUEDA
       ============================================ */
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.bd-search-form').length) {
            $('#bd-search-results').hide();
        }
    });

    /* ============================================
       BÚSQUEDA: ENTER en el topbar
       ============================================ */
    $('#bd-search-input').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const val = $(this).val().trim();
            if (val) window.location.href = 'search.html?q=' + encodeURIComponent(val);
        }
    });

    /* ============================================
       HOME: CARRUSEL DE CANCIONES
       ============================================ */
    if ($('.bd-carousel-track').length) {

        // Animación rápida (casi instantánea)
        anime({
            targets: '.bd-card',
            opacity: [0, 1],
            translateY: [8, 0],
            duration: 200,
            easing: 'easeOutQuad'
        });

        /* --- Lógica de navegación del carrusel --- */
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

        // Solo los botones dentro de .bd-section-head
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

        // Inicializar estado de botones en cada sección
        $('.bd-section').each(function () {
            bdActualizarBotones($(this));
        });

        // Botón de reproducción (abrir URL en nueva pestaña)
        $(document).on('click', '.bd-play-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(this);
            const url = $btn.data('url');
            if (url) {
                window.open(url, '_blank');
            } else {
                // Si no hay URL, redirigir a la página single
                const permalink = $btn.data('permalink');
                if (permalink) {
                    window.location.href = permalink;
                }
            }
            // Animación del botón
            anime({
                targets: $btn[0],
                scale: [1, 0.8, 1],
                duration: 300,
                easing: 'easeOutElastic(1, .6)'
            });
        });
    }
    /* ============================================
       SINGLE (single.html) — requiere #bd-tracklist
       ============================================ */
    if ($('#bd-tracklist').length) {

        const bdAlbum = {
            artista: "Bring Me The Horizon",
            titulo: "POST HUMAN: NeX GEn",
            cover: "https://picsum.photos/seed/posthuman/600",
            tipo: "Álbum",
            anio: 2024,
            cancionesTotal: 16,
            duracionTotal: "55 minutos",
            descripcion: "POST HUMAN: NeX GEn es el séptimo álbum de estudio de la banda británica Bring Me The Horizon, continuación de la saga POST HUMAN iniciada en 2020."
        };

        const bdTracks = [
            { num: 1, titulo: "[ost] dreamseeker", plays: "1.8m", duracion: "0:20", liked: false },
            { num: 2, titulo: "YOUtopia", plays: "17m", duracion: "4:03", liked: false },
            { num: 3, titulo: "Kool-Aid", plays: "49m", duracion: "3:49", liked: true },
            { num: 4, titulo: "Top 10 staTues tHat criEd blOOd", plays: "26m", duracion: "4:01", liked: false },
            { num: 5, titulo: "liMOusIne (feat. AURORA)", plays: "18m", duracion: "4:12", liked: true },
            { num: 6, titulo: "DArkSide", plays: "45m", duracion: "2:46", liked: true },
            { num: 7, titulo: "a bulleT w/ my namE On (feat. Underoath)", plays: "11m", duracion: "4:21", liked: true },
            { num: 8, titulo: "[ost] (spi)ritual", plays: "2.5m", duracion: "1:55", liked: false },
            { num: 9, titulo: "n/A", plays: "18m", duracion: "3:21", liked: false },
            { num: 10, titulo: "LosT", plays: "9.2m", duracion: "3:26", liked: false }
        ];

        $('#bd-single-artista').text(bdAlbum.artista);
        $('#bd-single-cover-img').attr('src', bdAlbum.cover).attr('alt', bdAlbum.titulo);
        $('#bd-single-titulo').text(bdAlbum.titulo);
        $('#bd-single-meta').text(`${bdAlbum.tipo} • ${bdAlbum.anio}`);
        $('#bd-single-meta2').text(`${bdAlbum.cancionesTotal} canciones • ${bdAlbum.duracionTotal}`);
        $('#bd-single-desc').text(bdAlbum.descripcion);

        function bdRenderTrack(t) {
            return `
            <div class="bd-track-row" data-num="${t.num}">
                <span class="bd-track-num">${t.num}</span>
                <div class="bd-track-info">
                    <p class="bd-track-title">${t.titulo}</p>
                    <p class="bd-track-sub">${t.plays} reproducciones</p>
                </div>
                <button class="bd-track-like ${t.liked ? 'active' : ''}" data-num="${t.num}">
                    <i class="bi ${t.liked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'}"></i>
                </button>
                <span class="bd-track-duration">${t.duracion}</span>
            </div>`;
        }

        $('#bd-tracklist').html(bdTracks.map(bdRenderTrack).join(''));

        anime({
            targets: '.bd-track-row',
            opacity: [0, 1],
            translateX: [12, 0],
            delay: anime.stagger(35),
            duration: 400,
            easing: 'easeOutQuad'
        });

        $(document).on('click', '.bd-track-like', function (e) {
            e.stopPropagation();
            const $btn = $(this);
            $btn.toggleClass('active');
            const isActive = $btn.hasClass('active');
            $btn.find('i')
                .toggleClass('bi-hand-thumbs-up', !isActive)
                .toggleClass('bi-hand-thumbs-up-fill', isActive);

            anime({
                targets: $btn[0],
                scale: [1, 1.3, 1],
                duration: 280,
                easing: 'easeOutElastic(1, .6)'
            });
        });

        $(document).on('click', '.bd-track-row', function () {
            console.log('Reproduciendo track num:', $(this).data('num'));
        });

        $('#bd-play-main').on('click', function () {
            const $icon = $(this).find('i');
            const isPlaying = $icon.hasClass('bi-pause-fill');
            $icon.toggleClass('bi-play-fill', isPlaying).toggleClass('bi-pause-fill', !isPlaying);
        });

        $('#bd-save-btn').on('click', function () {
            const $btn = $(this);
            const isSaved = $btn.hasClass('active');
            if (isSaved) {
                if (window.confirm('¿Quitar este álbum de tu biblioteca?')) {
                    $btn.removeClass('active');
                }
            } else {
                $btn.addClass('active');
            }
        });
    }

    /* ============================================
       ARCHIVE (archive.html) — requiere #bd-song-grid
       ============================================ */
    if ($('#bd-song-grid').length) {

        /* En WordPress esto sería:
           fetch(`/wp-json/wp/v2/videos?genero_videos=metalcore&page=${pagina}&per_page=6`)
           Simulamos la misma paginación con un array local + delay. */
        const bdSongs = [
            { titulo: "Nothing Else Matters", artista: "Metallica", views: "180m", img: "https://picsum.photos/seed/nem/100" },
            { titulo: "Bark at the Moon", artista: "Ozzy Osbourne", views: "45m", img: "https://picsum.photos/seed/bark/100" },
            { titulo: "Walk", artista: "Pantera", views: "123m", img: "https://picsum.photos/seed/walk/100" },
            { titulo: "Toxicity", artista: "System Of A Down", views: "339m", img: "https://picsum.photos/seed/toxicity/100" },
            { titulo: "Sonne", artista: "Rammstein", views: "191m", img: "https://picsum.photos/seed/sonne/100" },
            { titulo: "Killing In The Name", artista: "Rage Against The Machine", views: "210m", img: "https://picsum.photos/seed/killing/100" },
            { titulo: "Paranoid", artista: "Black Sabbath", views: "107m", img: "https://picsum.photos/seed/paranoid/100" },
            { titulo: "Rollin'", artista: "Limp Bizkit", views: "143m", img: "https://picsum.photos/seed/rollin/100" },
            { titulo: "Bodies", artista: "Drowning Pool", views: "156m", img: "https://picsum.photos/seed/bodies/100" },
            { titulo: "Faint", artista: "Linkin Park", views: "111m", img: "https://picsum.photos/seed/faint/100" },
            { titulo: "Psychosocial", artista: "Slipknot", views: "117m", img: "https://picsum.photos/seed/psycho/100" },
            { titulo: "The Trooper", artista: "Iron Maiden", views: "89m", img: "https://picsum.photos/seed/trooper/100" },
            { titulo: "Freak On a Leash", artista: "Korn", views: "144m", img: "https://picsum.photos/seed/freak/100" },
            { titulo: "One", artista: "Metallica", views: "76m", img: "https://picsum.photos/seed/one/100" },
            { titulo: "Dehumanized", artista: "Bring Me The Horizon", views: "914k", img: "https://picsum.photos/seed/dehumanized/100" }
        ];

        const BD_SONGS_PER_LOAD = 6;
        let bdSongsLoaded = 0;
        let bdCargando = false;

        function bdRenderSongCard(s) {
            return `
            <a href="single.html" class="bd-song-card">
                <div class="bd-song-thumb-wrap">
                    <img src="${s.img}" alt="${s.titulo}">
                    <i class="bi bi-play-fill"></i>
                </div>
                <div class="bd-song-info">
                    <p class="bd-song-title">${s.titulo}</p>
                    <p class="bd-song-sub">${s.artista} • ${s.views} vistas</p>
                </div>
            </a>`;
        }

        /* Simula la llamada GET (con delay), como si fuera $.get() al REST API */
        function bdCargarSiguienteTanda() {
            if (bdCargando || bdSongsLoaded >= bdSongs.length) return;
            bdCargando = true;
            $('#bd-song-loader').show();

            setTimeout(function () {
                const tanda = bdSongs.slice(bdSongsLoaded, bdSongsLoaded + BD_SONGS_PER_LOAD);
                const $nuevos = $(tanda.map(bdRenderSongCard).join(''));

                $('#bd-song-grid').append($nuevos);
                bdSongsLoaded += tanda.length;

                anime({
                    targets: $nuevos.toArray(),
                    opacity: [0, 1],
                    translateY: [12, 0],
                    delay: anime.stagger(30),
                    duration: 350,
                    easing: 'easeOutQuad'
                });

                $('#bd-song-loader').hide();
                bdCargando = false;

                if (bdSongsLoaded >= bdSongs.length) {
                    $('#bd-song-end').show();
                }
            }, 500);
        }

        /* Detecta cercanía al fondo de la página (BOM: window.scrollY / innerHeight) */
        $(window).on('scroll', function () {
            const cercaDelFondo = (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 300);
            if (cercaDelFondo) bdCargarSiguienteTanda();
        });

        /* Carga inicial */
        bdCargarSiguienteTanda();
    }


    /* ============================================
       SEARCH (search.html) — requiere #bd-search-results-list
       ============================================ */
    if ($('#bd-search-results-list').length) {

        /* Lee el parámetro ?q= de la URL (BOM: window.location.search) */
        const bdParams = new URLSearchParams(window.location.search);
        const bdQuery = bdParams.get('q');
        if (bdQuery) {
            $('#bd-search-page-input').val(bdQuery);
        }

        /* En WordPress esto sería:
           fetch(`/wp-json/wp/v2/videos?search=${query}`)
           Simulamos el resultado ya resuelto para maquetar la vista. */
        const bdSearchResults = [
            { titulo: "Best Pop Punk Songs of All Time", sub: "Playlist • Redlist - Top Sounds • 2.5m vistas", img: "https://picsum.photos/seed/bestpoppunk/100", redonda: false },
            { titulo: "'00s Pop-Punk", sub: "Playlist • Breakdown • 86 canciones", img: "https://picsum.photos/seed/00pop/100", redonda: false },
            { titulo: "Thnks fr th Mmrs", sub: "Canción • Fall Out Boy • 429m plays", img: "https://picsum.photos/seed/thnks/100", redonda: false },
            { titulo: "What's My Age Again?", sub: "Canción • blink-182 • 280m plays", img: "https://picsum.photos/seed/whatsmyage/100", redonda: false },
            { titulo: "blink-182", sub: "Artista • 13.9m oyentes mensuales", img: "https://picsum.photos/seed/blink182/100", redonda: true },
            { titulo: "Green Day", sub: "Artista • 32.8m oyentes mensuales", img: "https://picsum.photos/seed/greenday/100", redonda: true }
        ];

        function bdRenderSearchRow(r) {
            return `
            <a href="single.html" class="bd-search-result-row">
                <img src="${r.img}" alt="${r.titulo}" class="bd-search-result-thumb ${r.redonda ? 'bd-round' : ''}">
                <div class="bd-search-result-info">
                    <p class="bd-search-result-title">${r.titulo}</p>
                    <p class="bd-search-result-sub">${r.sub}</p>
                </div>
            </a>`;
        }

        $('#bd-search-results-list').html(bdSearchResults.map(bdRenderSearchRow).join(''));

        anime({
            targets: '.bd-search-result-row',
            opacity: [0, 1],
            translateY: [10, 0],
            delay: anime.stagger(30),
            duration: 350,
            easing: 'easeOutQuad'
        });

        /* Tabs (DOM: toggleClass) */
        $('.bd-search-tab').on('click', function () {
            $('.bd-search-tab').removeClass('active');
            $(this).addClass('active');
        });

        /* Filtros por chip */
        $('.bd-search-filter').on('click', function () {
            $('.bd-search-filter').removeClass('active');
            $(this).addClass('active');
        });

        /* Mostrar/ocultar botón de limpiar según contenido del input (BOM/DOM) */
        function bdToggleClearBtn() {
            const tieneTexto = $('#bd-search-page-input').val().trim().length > 0;
            $('#bd-search-page-clear').toggle(tieneTexto);
        }
        bdToggleClearBtn();

        $('#bd-search-page-input').on('input', bdToggleClearBtn);

        $('#bd-search-page-clear').on('click', function () {
            $('#bd-search-page-input').val('').focus();
            bdToggleClearBtn();
        });
    }


    /* ============================================
       TERMS (terms.html) — requiere #bd-faq-list
       ============================================ */
    if ($('#bd-faq-list').length) {

        /* Acordeón manual con jQuery (slideToggle), sin depender del collapse de Bootstrap */
        $('.bd-faq-question').on('click', function () {
            const $item = $(this).closest('.bd-faq-item');
            const $answer = $item.find('.bd-faq-answer');
            const yaAbierto = $item.hasClass('open');

            /* Cierra cualquier otro ítem abierto (solo uno a la vez) */
            $('.bd-faq-item.open').not($item).each(function () {
                $(this).removeClass('open').find('.bd-faq-answer').slideUp(220);
            });

            $item.toggleClass('open', !yaAbierto);
            $answer.slideToggle(220);
        });
    }


    /* ============================================
       LIBRARY (library.html) — requiere #bd-library-grid
       ============================================ */
    if ($('#bd-library-grid').length) {

        const BD_LIBRARY_PER_LOAD = 8;
        let bdLibraryLoaded = 0;
        let bdLibraryCargando = false;

        /* Simula GET paginado. En WordPress: /wp-json/wp/v2/videos?page=N&per_page=8 */
        function bdCargarSiguienteTandaLibrary() {
            if (bdLibraryCargando || bdLibraryLoaded >= bdCatalogo.length) return;
            bdLibraryCargando = true;
            $('#bd-library-loader').show();

            setTimeout(function () {
                const tanda = bdCatalogo.slice(bdLibraryLoaded, bdLibraryLoaded + BD_LIBRARY_PER_LOAD);
                const $nuevos = $(tanda.map((item, i) => bdRenderCard(item, bdLibraryLoaded + i)).join(''));

                $('#bd-library-grid').append($nuevos);
                bdLibraryLoaded += tanda.length;

                anime({
                    targets: $nuevos.toArray(),
                    opacity: [0, 1],
                    translateY: [12, 0],
                    delay: anime.stagger(30),
                    duration: 350,
                    easing: 'easeOutQuad'
                });

                $('#bd-library-loader').hide();
                bdLibraryCargando = false;

                if (bdLibraryLoaded >= bdCatalogo.length) {
                    $('#bd-library-end').show();
                }
            }, 500);
        }

        $(window).on('scroll', function () {
            const cercaDelFondo = (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 300);
            if (cercaDelFondo) bdCargarSiguienteTandaLibrary();
        });

        bdCargarSiguienteTandaLibrary();
    }


    /* ============================================
       ARTIST (artist.html) — requiere #bd-artist-page
       ============================================ */
    if ($('#bd-artist-page').length) {

        const bdArtista = {
            nombre: "Metallica",
            audiencia: "58.1m oyentes mensuales",
            bio: "Formada en 1981 por el vocalista y guitarrista James Hetfield y el baterista Lars Ulrich, junto al guitarrista Kirk Hammett y el bajista Robert Trujillo, Metallica se ha convertido en una de las bandas más influyentes e importantes del metal, con más de 125 millones de discos vendidos en todo el mundo.",
            hero: "https://picsum.photos/seed/metallicahero/1400/500"
        };

        const bdTopSongs = [
            { titulo: "Nothing Else Matters", plays: "2.3bn", img: "https://picsum.photos/seed/nem2/100", liked: false },
            { titulo: "Enter Sandman", plays: "1.2bn", img: "https://picsum.photos/seed/entersandman/100", liked: true },
            { titulo: "The Unforgiven", plays: "939m", img: "https://picsum.photos/seed/unforgiven/100", liked: false },
            { titulo: "Master of Puppets", plays: "890m", img: "https://picsum.photos/seed/mop/100", liked: true },
            { titulo: "One", plays: "760m", img: "https://picsum.photos/seed/one2/100", liked: false }
        ];

        const bdArtistAlbums = [
            { id: 101, titulo: "72 Seasons", artista: "Metallica", img: "https://picsum.photos/seed/72seasons/400" },
            { id: 102, titulo: "Hardwired... To Self-Destruct", artista: "Metallica", img: "https://picsum.photos/seed/hardwired/400" },
            { id: 103, titulo: "Death Magnetic", artista: "Metallica", img: "https://picsum.photos/seed/deathmagnetic/400" },
            { id: 104, titulo: "The Metallica Blacklist", artista: "Metallica", img: "https://picsum.photos/seed/blacklist/400" },
            { id: 105, titulo: "ReLoad", artista: "Metallica", img: "https://picsum.photos/seed/reload/400" }
        ];

        const bdArtistSingles = [
            { id: 201, titulo: "Lux Æterna", artista: "Metallica", img: "https://picsum.photos/seed/luxaeterna/400" },
            { id: 202, titulo: "Screaming Suicide", artista: "Metallica", img: "https://picsum.photos/seed/screamingsuicide/400" },
            { id: 203, titulo: "If Darkness Had a Son", artista: "Metallica", img: "https://picsum.photos/seed/darkness/400" }
        ];

        /* Hero */
        $('#bd-artist-hero').css('background-image', `url(${bdArtista.hero})`);
        $('#bd-artist-name').text(bdArtista.nombre);
        $('#bd-artist-audience').text(bdArtista.audiencia);
        $('#bd-artist-bio').text(bdArtista.bio);

        /* Ver más / Ver menos en la bio (DOM: toggleClass) */
        $('#bd-artist-bio-toggle').on('click', function () {
            const $bio = $('#bd-artist-bio');
            $bio.toggleClass('expanded');
            $(this).text($bio.hasClass('expanded') ? 'Menos' : 'Más');
        });

        /* Top songs */
        function bdRenderArtistSong(s, i) {
            return `
            <div class="bd-artist-song-row">
                <span class="bd-artist-song-num">${i + 1}</span>
                <img src="${s.img}" alt="${s.titulo}" class="bd-artist-song-thumb">
                <div class="bd-artist-song-info">
                    <p class="bd-artist-song-title">${s.titulo}</p>
                    <p class="bd-artist-song-sub">${bdArtista.nombre}</p>
                </div>
                <span class="bd-artist-song-plays">${s.plays} plays</span>
                <button class="bd-artist-song-like ${s.liked ? 'active' : ''}">
                    <i class="bi ${s.liked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'}"></i>
                </button>
            </div>`;
        }
        $('#bd-artist-songs').html(bdTopSongs.map(bdRenderArtistSong).join(''));

        $(document).on('click', '.bd-artist-song-like', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');
            const isActive = $(this).hasClass('active');
            $(this).find('i')
                .toggleClass('bi-hand-thumbs-up', !isActive)
                .toggleClass('bi-hand-thumbs-up-fill', isActive);
        });

        /* Álbumes y Singles/EPs — reutiliza bdRenderCard compartido */
        $('#bd-artist-albums').html(bdArtistAlbums.map(bdRenderCard).join(''));
        $('#bd-artist-singles').html(bdArtistSingles.map(bdRenderCard).join(''));
        $('#bd-artist-albums, #bd-artist-singles').attr('data-page', 0);

        anime({
            targets: ['#bd-artist-songs .bd-artist-song-row', '#bd-artist-albums .bd-card', '#bd-artist-singles .bd-card'],
            opacity: [0, 1],
            translateY: [12, 0],
            delay: anime.stagger(30),
            duration: 400,
            easing: 'easeOutQuad'
        });

        /* Suscribirse (BOM: confirm antes de darse de baja) */
        $('#bd-subscribe-btn').on('click', function () {
            const $btn = $(this);
            const isSubscribed = $btn.hasClass('active');
            if (isSubscribed) {
                if (window.confirm('¿Dejar de seguir a ' + bdArtista.nombre + '?')) {
                    $btn.removeClass('active').html('Suscribirse · <span id="bd-subscribe-count">12.5m</span>');
                }
            } else {
                $btn.addClass('active').text('Suscrito ✓');
            }
        });
    }

});