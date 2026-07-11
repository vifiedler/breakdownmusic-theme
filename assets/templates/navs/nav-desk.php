<div class="offcanvas offcanvas-start bd-sidebar-vertical offcanvas-lg" tabindex="-1" id="bd-sidebar">
    <div class="bd-sidebar-menu-container">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'menu-superior',
            'menu_class'     => 'bd-vertical-nav-list',
            'container'      => 'false',
            'depth'          => 2,
            'walker'         => new bootstrap_5_wp_nav_menu_walker(),
            'fallback_cb'    => 'bootstrap_5_wp_nav_menu_walker::fallback',
        ));
        ?>
    </div>
</div>