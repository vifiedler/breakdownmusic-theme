<?php
/**
 * Search form for Breakdown Music
 * Se usa en el topbar
 */
?>
<form role="search" method="get" class="bd-search-form position-relative" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" 
           id="bd-search-input" 
           class="bd-search-input form-control bg-transparent border-0 text-white" 
           placeholder="Buscar canciones, artistas, álbumes..." 
           value="<?php echo get_search_query(); ?>" 
           name="s"
           autocomplete="off"
           style="flex:1; outline:none; padding:6px 12px;">
    <button type="submit" class="bd-search-btn btn btn-danger rounded-circle p-0" id="bd-search-btn" style="width:34px;height:34px;flex-shrink:0;">
        <i class="bi bi-search"></i>
    </button>
    <div id="bd-search-results" class="position-absolute w-100 bg-dark rounded-3 shadow-lg" style="top:110%; left:0; z-index:60; display:none; max-height:320px; overflow-y:auto; border:1px solid #2a2a2a;">
        <!-- Los resultados se inyectan vía JS -->
    </div>
</form>