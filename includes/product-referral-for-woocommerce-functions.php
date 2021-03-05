<?php
//Do some functions here

/**
 * Get Product Referral Link
 * @param string $user_id
 * @param string $product_id
 * @return string
 */
if( !function_exists( 'prfwc_get_referral_link' ) ):

    function prfwc_get_referral_link($user_id = '', $product_id = '' )
    {
        $user_id = empty( $user_id ) ? get_current_user_id() : $user_id;

        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;

        $referral_link = get_permalink( $product_id ) . '?ref_id=' . $user_id;

        return $referral_link;
    }

endif;

/**
 * Get Discount Type
 * @param string $product_id
 * @since 1.0
 * @version 1.0
 */
if ( !function_exists( 'prfwc_get_discount_type' ) ):

    function prfwc_get_discount_type( $product_id = '' )
    {
        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;
    }

endif;

/**
 * Get Discount Price
 * @param string $product_id
 * @since 1.0
 * @version 1.0
 * @return bool|float|int|mixed|string|void
 */
if ( !function_exists( 'prfwc_get_discount_price' ) ):

    function prfwc_get_discount_price($product_id = '' )
    {
        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;

        global $woocommerce;

        $product = wc_get_product( $product_id );

        $product_referral = 'product_referral';

        $show_referrals_on = get_option( 'wc_product_referral_show_referral_on' );

        $product_ids = explode( ',', get_option( 'wc_product_referral_product_ids' ) );

        $discount_type = get_option( 'wc_product_referral_discount_type' );

        $discount_value  = get_option( 'wc_product_referral_discount_value' );

        $discount_on = get_option( 'wc_product_referral_discount_on' );

        $referral_numbers = get_option( 'wc_product_referral_referral_numbers' );

        $message_for_user = get_option( 'wc_product_referral_message_for_user' );

        if ( in_array( $product_id, $product_ids ) )
        {
            $apply_discount_on = '';

            $apply_discount_type = '';

            if ( $discount_on == 'whole_product' )
            {
                $apply_discount_on = wc_get_price_including_tax( $product );
            }

            if ( $discount_on == 'shipment' )
            {
                $shipping_class_id = $product->get_shipping_class_id();

                $shipping_class= $product->get_shipping_class();

                $fee = 0;

                if ( $shipping_class_id )
                {
                    $flat_rates = get_option("woocommerce_flat_rates");

                    $fee = $flat_rates[$shipping_class]['cost'];
                }

                $flat_rate_settings = get_option("woocommerce_flat_rate_settings");

                $apply_discount_on =  $flat_rate_settings['cost_per_order'] + $fee;
            }

            if ( $discount_on == 'tax' )
            {
                $tax_price = wc_get_price_including_tax( $product ) - wc_get_price_excluding_tax( $product );

                $apply_discount_on = $tax_price;
            }

            if ( $discount_type == 'flat' )
                $apply_discount_on = $apply_discount_on - $discount_value;

            if ( $discount_type == 'percent' )
            {
                $discount_value = $discount_value * 0.01;

                $apply_discount_on = $discount_value * $apply_discount_on;

                $apply_discount_on = wc_get_price_including_tax( $product ) - $apply_discount_on;
            }

            return $apply_discount_on;
        }
        else
        {
            return 'Not a Referral Product';
        }
    }

endif;
