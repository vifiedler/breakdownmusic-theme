<?php
function js_function()
{
    if (!(is_admin())) {
        wp_register_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', array('jquery'), '1', true);
        wp_register_script('custom-js', get_template_directory_uri() . '/assets/librerias/js/js-bdmusic.js', array('jquery'), null, true);

        wp_enqueue_script('bootstrap-js');
        wp_enqueue_script('custom-js');
    }
}
add_action("wp_enqueue_scripts", "js_function", 999);