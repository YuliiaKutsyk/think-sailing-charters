<?php

add_action('wp_enqueue_scripts', 'init_scripts');
function init_scripts(){
    wp_enqueue_style('intl-style', get_template_directory_uri() . '/assets/libs/intl-tel/css/intlTelInput.min.css', array(), filemtime(get_template_directory() . '/assets/libs/intl-tel/css/intlTelInput.min.css'), 'all');
    wp_enqueue_style('wpeasy-style', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime(get_template_directory() . '/assets/css/main.css'), 'all');
    wp_enqueue_style('wpmedia-style', get_template_directory_uri() . '/assets/css/media.css', array(), filemtime(get_template_directory() . '/assets/css/media.css'), 'all');
    wp_enqueue_style('wpeasy-viewbox');
    wp_enqueue_style('sailing-main', get_stylesheet_directory_uri() . '/style.css', array(), filemtime(get_template_directory() . '/style.css'), 'all');
    wp_enqueue_style('flatpickr-css', get_template_directory_uri() . '/assets/libs/flatpickr/dist/flatpickr.css');

    wp_enqueue_script('intl-script', get_template_directory_uri() . '/assets/libs/intl-tel/js/intlTelInput.min.js', array('jquery'));
    wp_enqueue_script('wpeOwl', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'));
    wp_enqueue_script('wpeScriptViewbox', get_template_directory_uri() . '/assets/js/jquery.viewbox.min.js', array('jquery'));
    wp_enqueue_script('wpeScriptCookie', get_template_directory_uri() . '/assets/js/jquery.cookie.js', array('jquery'));
    wp_enqueue_script('flatpickr-js', get_template_directory_uri() . '/assets/libs/flatpickr/dist/flatpickr.js', array('jquery'));
    wp_enqueue_script('wpeScripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'));

    if ( is_checkout() ) {
        wp_enqueue_script('wpeScriptsCheckout', get_template_directory_uri() . '/assets/js/checkout-scripts.js', array('jquery'));
    }

    wp_localize_script( 'wpeScriptsCheckout', 'backendVars',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );

    wp_localize_script( 'wpeScripts', 'beVars',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'sc_excluded_dates' => sc_exclude_dates()
        )
    );
}