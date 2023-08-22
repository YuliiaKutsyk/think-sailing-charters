<?php
// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'sc_add_meta_boxes' );
if ( ! function_exists( 'sc_add_meta_boxes' ) )
{
    function sc_add_meta_boxes()
    {
        add_meta_box( 'adult_number', __('Adults','woocommerce'), 'sc_add_other_fields_for_packaging', 'shop_order', 'side', 'core');
        add_meta_box( 's_children', __('Children','woocommerce'), 'sc_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
        add_meta_box( 's_infants', __('Infants','woocommerce'), 'sc_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
        add_meta_box( 's_day_f', __('Date start','woocommerce'), 'sc_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
        add_meta_box( 's_endday_f', __('Date end','woocommerce'), 'sc_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
    }
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'sc_add_other_fields_for_packaging' ) )
{
    function sc_add_other_fields_for_packaging($order, $args)
    {
        global $post;

        $meta_field_data = get_post_meta( $post->ID, $args['id'], true ) ? get_post_meta( $post->ID, $args['id'], true ) : '0';

        echo '<input type="hidden" name="' . $args['id'] . '" value="' . wp_create_nonce() . '">
        <p style="border-bottom:solid 1px #eee;padding-bottom:13px;">
            <input type="text" style="width:250px;" name="' . $args['id'] . '" placeholder="' . $meta_field_data . '" value="' . $meta_field_data . '"></p>';

    }
}

// Save the data of the Meta field
add_action( 'save_post', 'sc_save_wc_order_other_fields', 10, 1 );
if ( ! function_exists( 'sc_save_wc_order_other_fields' ) )
{

    function sc_save_wc_order_other_fields( $post_id ) {

        // Sanitize user input  and update the meta field in the database.
        if ( $_POST[ 'adult_number' ] ){
        update_post_meta( $post_id, 'sc_adult_number', $_POST[ 'adult_number' ] );
        }
        if ( $_POST[ 's_children' ] ){
        update_post_meta( $post_id, 'sc_children_number', $_POST[ 's_children' ] );
        }
        if ( $_POST[ 's_infants' ] ){
        update_post_meta( $post_id, 'sc_infants_number', $_POST[ 's_infants' ] );
        }
        if ( $_POST[ 'adult_number' ] && $_POST[ 's_children' ] && $_POST[ 's_infants' ] ){
        update_post_meta( $post_id, 'sc_people_number', $_POST[ 'adult_number' ] + $_POST[ 's_children' ] +$_POST[ 's_infants' ] );
        }
        if ( $_POST[ 's_day_f' ] ){
        update_post_meta( $post_id, 'sc_day_f', $_POST[ 's_day_f' ] );
        }
        if ( $_POST[ 's_endday_f' ] ){
        update_post_meta( $post_id, 'sc_endday_f', $_POST[ 's_endday_f' ] );
        }


        }
}
