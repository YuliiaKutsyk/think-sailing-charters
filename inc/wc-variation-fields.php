<?php

add_action( 'woocommerce_variation_options_pricing', 'sc_add_nightrate_pricing', 10, 3 );
function sc_add_nightrate_pricing( $loop, $variation_data, $variation )
{
    $variation_id = $variation->ID;
    $attrs = wc_get_product_variation_attributes($variation_id);
    $attr = $attrs['attribute_pa_service'];
    $rate_1_2_allowed = ['day-charters', 'evening-cruises', 'romantic-evening-cruises', 'multi-day-charters', 'bareboat-charters'];
    $rate_3_allowed = ['multi-day-charters', 'bareboat-charters'];

    if( in_array( $attr, $rate_1_2_allowed ) ) {
        woocommerce_wp_text_input(array(
            'id' => '_rate_1_' . $loop,
            'wrapper_class' => 'form-row form-row-first',
            'class' => 'short wc_input_price',
            'label' => __('Rate 1', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => wc_format_localized_price(get_post_meta($variation_id, '_rate_1', true)),
            'type' => 'number',
            'custom_attributes' => array('step' => '1')
        ));

        woocommerce_wp_text_input(array(
            'id' => '_rate_2_' . $loop,
            'wrapper_class' => 'form-row form-row-last',
            'class' => 'short wc_input_price',
            'label' => __('Rate 2', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => wc_format_localized_price(get_post_meta($variation_id, '_rate_2', true)),
            'type' => 'number',
            'custom_attributes' => array('step' => '1')
        ));
    }

    if( in_array( $attr, $rate_3_allowed ) ) {
        woocommerce_wp_text_input(array(
            'id' => '_rate_3_' . $loop,
            'wrapper_class' => 'form-row form-row-first',
            'class' => 'short wc_input_price',
            'label' => __('Rate 3', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => wc_format_localized_price(get_post_meta($variation_id, '_rate_3', true)),
            'type' => 'number',
            'custom_attributes' => array('step' => '1')
        ));
    }

    woocommerce_wp_text_input( array(
        'id' => '_total_people_' . $loop,
        'wrapper_class' => 'form-row form-row-first',
        'class' => 'short wc_input_price',
        'label' => __( 'Person Limit', 'woocommerce' ),
        'value' => wc_format_localized_price( get_post_meta( $variation_id, '_total_people', true ) ),
        'type' => 'number',
        'custom_attributes' => array('step' => '1')
    ) );

    woocommerce_wp_text_input( array(
        'id' => '_default_people_' . $loop,
        'wrapper_class' => 'form-row form-row-last',
        'class' => 'short wc_input_price',
        'label' => __( 'Person Max', 'woocommerce' ),
        'value' => wc_format_localized_price( get_post_meta( $variation_id, '_default_people', true ) ),
        'type' => 'number',
        'custom_attributes' => array('step' => '1', 'min' => '0', 'max' => get_post_meta( $variation_id, '_total_people', true ))
    ) );

    woocommerce_wp_text_input( array(
        'id' => '_extra_person_price_' . $loop,
        'wrapper_class' => 'form-row form-row-first',
        'class' => 'short wc_input_price',
        'label' => __( 'Additional Person Price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        'value' => wc_format_localized_price( get_post_meta( $variation_id, '_extra_person_price', true ) ),
        'type' => 'number',
        'custom_attributes' => array('step' => '1')
    ) );
}

add_action( 'woocommerce_save_product_variation','save_variation_options_pricing_msrp',10 ,2 );
function save_variation_options_pricing_msrp( $variation_id, $loop )
{
    if (isset($_POST['_rate_1_' . $loop]))
        update_post_meta($variation_id, '_rate_1', wc_clean(wp_unslash(str_replace(',', '.', $_POST['_rate_1_' . $loop]))));

    if (isset($_POST['_rate_2_' . $loop]))
        update_post_meta($variation_id, '_rate_2', wc_clean(wp_unslash(str_replace(',', '.', $_POST['_rate_2_' . $loop]))));

    if (isset($_POST['_rate_3_' . $loop]))
        update_post_meta($variation_id, '_rate_3', wc_clean(wp_unslash(str_replace(',', '.', $_POST['_rate_3_' . $loop]))));

    if( isset($_POST['_default_people_'.$loop]) )
        update_post_meta( $variation_id, '_default_people', wc_clean( wp_unslash( str_replace( ',', '.', $_POST['_default_people_'.$loop] ) ) ) );

    if( isset($_POST['_extra_person_price_'.$loop]) )
        update_post_meta( $variation_id, '_extra_person_price', wc_clean( wp_unslash( str_replace( ',', '.', $_POST['_extra_person_price_'.$loop] ) ) ) );

    if( isset($_POST['_total_people_'.$loop]) )
        update_post_meta( $variation_id, '_total_people', wc_clean( wp_unslash( str_replace( ',', '.', $_POST['_total_people_'.$loop] ) ) ) );

}