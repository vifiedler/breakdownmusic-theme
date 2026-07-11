<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package breakdownmusic-theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text"
            href="#primary"><?php esc_html_e( 'Skip to content', 'breakdownmusic-theme' ); ?></a>
            <header id="masthead" class="fbs__net-navbar navbar navbar-expand-lg dark">
                <div class="container-fluid d-flex align-items-center justify-content-between">
                    <?php include get_template_directory() . '/assets/templates/navs/nav-desk.php'; ?>
                </div>
        </header><!-- #masthead -->

        <div class="offcanvas offcanvas-start bd-sidebar-vertical offcanvas-lg" tabindex="-1" id="bd-sidebar">
            <div class="bd-sidebar-menu-container">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'menu-sidebar',
                    'menu_class'     => 'bd-vertical-nav-list',
                    'container'      => 'false',
                    'depth'          => 2,
                    'walker'         => new bootstrap_5_wp_nav_menu_walker(),
                    'fallback_cb'    => 'bootstrap_5_wp_nav_menu_walker::fallback',
                ));
                ?>
            </div>
        </div>