

<?php

print_r('single 0');
    if(is_singular('destination')) {
        wp_redirect( '/destinations/');
    }
    get_header();
    get_footer();
    ?>