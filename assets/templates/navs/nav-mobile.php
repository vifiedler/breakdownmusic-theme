<nav id="site-navigation" class="navbar navbar-expand-lg navbar-light nav-mobile">
    <!---conatiner-->
    <div class="container-fluid">
        <!---row-->
        <div class="row w-100 d-flex justify-content-between align-items-center">
            <!---navbrand-->
            <div class="navbar-brand col-7">

                <?php the_custom_logo( 
    array( 
        'size' => 'full', 
        'class' => 'col-12 col-sm-3 col-md-3' ) 
    );
?> <?php
                
                if (is_front_page() && is_home()) :
                ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                        rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php
                else :
                ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                        rel="home"><?php bloginfo('name'); ?></a></p>
                <?php 
                endif;
                $fundaciones_description = get_bloginfo('description', 'display');
                if ($fundaciones_description || is_customize_preview()) :
                ?>
                <p class="site-description"><?php echo $fundaciones_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                ?></p>
                <?php endif; ?>
            </div>


            <button class="navbar-toggler col-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="boton-hamburguesa">
                    <span class="linea-hamburguesa"></span>
                    <span class="linea-hamburguesa"></span>
                    <span class="linea-hamburguesa"></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">


                <?php //OPCIÓN MENU 1 SIMPLE CON BOOTSTRAP
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-mobile',
                        'menu_id'        => 'navbar',
                        'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0',
                        'container_class' =>'menu-mobile nav col-12 col-md-8 d-block d-sm-none',
                    )
                );
                ?>

                <?php if ( function_exists( 'aws_get_search_form' ) ) { aws_get_search_form( true, array( 'id' => '62968e5c35f00' ) ); } ?>
            </div>
        </div>
    </div>
</nav>