$(function () {

    /* ============================================
       COMPARTIDO EN TODAS LAS PÁGINAS
       ============================================ */

    /* Sidebar toggle (BOM/DOM) */
    $('#bd-burger').on('click', function () {
        if (window.innerWidth <= 768) {
            $('#bd-sidebar').toggleClass('open');
        } else {
            $('#bd-sidebar').toggleClass('collapsed');
        }
    });

    /* Cerrar resultados de búsqueda al hacer click afuera (si existen) */
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.bd-search-form').length) {
            $('#bd-search-results').hide();
        }
    });

    /* Enter en el buscador del topbar navega a la página de resultados */
    $('#bd-search-input').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const val = $(this).val().trim();
            if (val) window.location.href = 'search.html?q=' + encodeURIComponent(val);
        }
    });

    /* Catálogo compartido (home + biblioteca). En WordPress: /wp-json/wp/v2/videos */
    const bdCatalogo = [
        { id: 1, titulo: "Bring Me The Horizon", artista: "POST HUMAN: NeX GEn", genero: "metalcore", img: "https://picsum.photos/seed/bmth/400" },
        { id: 2, titulo: "Architects", artista: "The Sky, The Earth & All Between", genero: "metalcore", img: "https://picsum.photos/seed/architects/400" },
        { id: 3, titulo: "Sleep Token", artista: "Take Me Back To Eden", genero: "metalcore", img: "https://picsum.photos/seed/sleeptoken/400" },
        { id: 4, titulo: "Bad Omens", artista: "THE DEATH OF PEACE OF MIND", genero: "metalcore", img: "https://picsum.photos/seed/badomens/400" },
        { id: 5, titulo: "Ice Nine Kills", artista: "The Silver Scream", genero: "deathcore", img: "https://picsum.photos/seed/incocore/400" },
        { id: 6, titulo: "Lorna Shore", artista: "Pain Remains", genero: "deathcore", img: "https://picsum.photos/seed/lorna/400" },
        { id: 7, titulo: "Whitechapel", artista: "Kin", genero: "deathcore", img: "https://picsum.photos/seed/whitechapel/400" },
        { id: 8, titulo: "Spiritbox", artista: "Eternal Blue", genero: "nu-metal", img: "https://picsum.photos/seed/spiritbox/400" },
        { id: 9, titulo: "Underoath", artista: "Voyeurist", genero: "nu-metal", img: "https://picsum.photos/seed/underoath/400" },
        { id: 10, titulo: "Sleep Token", artista: "Take Me Back To Eden", genero: "destacados", img: "https://picsum.photos/seed/sleeptoken2/400" },
        { id: 11, titulo: "Architects", artista: "For Those That Wish To Exist", genero: "destacados", img: "https://picsum.photos/seed/architects2/400" },
        { id: 12, titulo: "Korn", artista: "Requiem", genero: "nu-metal", img: "https://picsum.photos/seed/korn/400" },
        { id: 13, titulo: "Slipknot", artista: "The End, So Far", genero: "nu-metal", img: "https://picsum.photos/seed/slipknot/400" },
        { id: 14, titulo: "Whitechapel", artista: "Kin", genero: "deathcore", img: "https://picsum.photos/seed/whitechapel2/400" },
        { id: 15, titulo: "Knocked Loose", artista: "You Won't Go Before You're Supposed To", genero: "hardcore", img: "https://picsum.photos/seed/knockedloose/400" },
        { id: 16, titulo: "Turnstile", artista: "Glow On", genero: "hardcore", img: "https://picsum.photos/seed/turnstile/400" },
        { id: 17, titulo: "Neck Deep", artista: "All Distortions Are Intentional", genero: "pop-punk", img: "https://picsum.photos/seed/neckdeep/400" },
        { id: 18, titulo: "State Champs", artista: "Living Proof", genero: "pop-punk", img: "https://picsum.photos/seed/statechamps/400" },
        { id: 19, titulo: "Currents", artista: "The Death We Seek", genero: "metalcore", img: "https://picsum.photos/seed/currents/400" },
        { id: 20, titulo: "Fit For A King", artista: "The Hell We Create", genero: "metalcore", img: "https://picsum.photos/seed/fitforaking/400" }
    ];

    function bdRenderCard(item, index) {
        return `
        <a href="single.html?id=${item.id}" class="bd-card" data-index="${index}">
            <div class="bd-card-thumb-wrap">
                <img src="${item.img}" alt="${item.titulo}" class="bd-card-thumb">
                <button class="bd-play-btn" data-id="${item.id}"><i class="bi bi-play-fill"></i></button>
            </div>
            <p class="bd-card-title">${item.titulo}</p>
            <p class="bd-card-sub">${item.artista}</p>
        </a>`;
    }


    /* ============================================
       HOME (index.html) — requiere .bd-carousel-track
       ============================================ */
    if ($('.bd-carousel-track').length) {

        $('.bd-carousel-track').each(function () {
            const genero = $(this).data('genero');
            const items = bdCatalogo.filter(c => c.genero === genero);
            $(this).html(items.map(bdRenderCard).join(''));
            $(this).attr('data-page', 0);
        });

        anime({
            targets: '.bd-card',
            opacity: [0, 1],
            translateY: [16, 0],
            delay: anime.stagger(40),
            duration: 500,
            easing: 'easeOutQuad'
        });

        /* Carrusel: avanza/retrocede de a 3 tarjetas */
        const BD_CARDS_PER_PAGE = 3;

        function bdActualizarBotones($wrap) {
            const $track = $wrap.find('.bd-carousel-track');
            const $cards = $track.find('.bd-card');
            const totalPages = Math.ceil($cards.length / BD_CARDS_PER_PAGE);
            const page = $track.data('page');

            $wrap.closest('.bd-section').find('.bd-carousel-prev').prop('disabled', page <= 0);
            $wrap.closest('.bd-section').find('.bd-carousel-next').prop('disabled', page >= totalPages - 1);
        }

        function bdIrAPagina($wrap, nuevaPagina) {
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
            bdActualizarBotones($wrap);
        }

        $('.bd-carousel-next').on('click', function () {
            const $wrap = $(this).closest('.bd-section').find('.bd-carousel-wrap');
            const $track = $wrap.find('.bd-carousel-track');
            bdIrAPagina($wrap, $track.data('page') + 1);
        });

        $('.bd-carousel-prev').on('click', function () {
            const $wrap = $(this).closest('.bd-section').find('.bd-carousel-wrap');
            const $track = $wrap.find('.bd-carousel-track');
            bdIrAPagina($wrap, $track.data('page') - 1);
        });

        $('.bd-carousel-wrap').each(function () { bdActualizarBotones($(this)); });

        $(document).on('click', '.bd-play-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(this);
            anime({
                targets: $btn[0],
                scale: [1, 0.8, 1],
                duration: 300,
                easing: 'easeOutElastic(1, .6)'
            });
            console.log('Reproduciendo id:', $btn.data('id'));
        });

        function bdBuscar(query) {
            query = query.trim().toLowerCase();
            const $box = $('#bd-search-results');
            if (!query) { $box.hide().empty(); return; }

            const resultados = bdCatalogo.filter(c =>
                c.titulo.toLowerCase().includes(query) || c.artista.toLowerCase().includes(query)
            );

            if (resultados.length === 0) {
                window.alert('Lo sentimos, esta canción/artista no se encuentra disponible.');
                $box.hide().empty();
                return;
            }

            $box.html(resultados.map(r =>
                `<div class="bd-result-item" data-id="${r.id}"><span>${r.titulo}</span><span>${r.artista}</span></div>`
            ).join('') + `<div class="bd-result-item" id="bd-ver-todos"><span>Ver todos los resultados para "${query}"</span></div>`).show();
        }

        let bdSearchTimer = null;
        $('#bd-search-input').on('input', function () {
            clearTimeout(bdSearchTimer);
            const val = $(this).val();
            bdSearchTimer = setTimeout(() => bdBuscar(val), 400);
        });

        $('#bd-search-btn').on('click', function () {
            bdBuscar($('#bd-search-input').val());
        });

        $(document).on('click', '#bd-ver-todos', function () {
            const val = $('#bd-search-input').val().trim();
            window.location.href = 'search.html?q=' + encodeURIComponent(val);
        });

        $(document).on('click', '.bd-result-item:not(#bd-ver-todos)', function () {
            window.location.href = 'single.html?id=' + $(this).data('id');
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

});