<?php

/**
 * Returns Product id's selected by admin
 * @return false|string[]
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'prfwc_get_product_ids' ) ):

    function prfwc_get_product_ids()
    {
        $product_ids = explode( ',', get_option( 'wc_product_referral_product_ids' ) );

        return $product_ids;
    }

endif;

/**
 * Returns Show referral's place
 * @return bool|mixed|void
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'prfwc_show_referrals_on' ) ):

    function prfwc_show_referrals_on()
    {
        $show_referrals_on = get_option( 'wc_product_referral_show_referral_on' );

        return $show_referrals_on;
    }

endif;

/**
 * Get Discount Type
 * @since 1.0
 * @version 1.0
 */
if ( !function_exists( 'prfwc_get_discount_type' ) ):

    function prfwc_get_discount_type()
    {
        $discount_type = get_option( 'wc_product_referral_discount_type' );

        return $discount_type;
    }

endif;

/**
 * Get Discount Value
 * @return bool|mixed|void
 * @version 1.0
 * @since 1.0
 */
if ( !function_exists( 'prfwc_get_discount_value' ) ):

    function prfwc_get_discount_value()
    {
        $discount_value = get_option( 'wc_product_referral_discount_value' );

        return $discount_value;
    }

endif;

/**
 * Get Discount On
 * @return bool|mixed|void
 * @version 1.0
 * @since 1.0
 */
if ( !function_exists( 'prfwc_get_discount_on' ) ):

    function prfwc_get_discount_on()
    {
        $discount_on = get_option( 'wc_product_referral_discount_on' );

        return $discount_on;
    }

endif;

/**
 * Get Referral Numbers
 * @return bool|mixed|void
 * @version 1.0
 * @since 1.0
 */
if ( !function_exists( 'prfwc_get_referral_numbers' ) ):

    function prfwc_get_referral_numbers()
    {
        $referral_numbers = get_option( 'wc_product_referral_referral_numbers' );

        return $referral_numbers;
    }

endif;

/**
 * Get Message For User
 * @return bool|mixed|void
 * @version 1.0
 * @since 1.0
 */
if ( !function_exists( 'prfwc_get_message_for_user' ) ):

    function prfwc_get_message_for_user()
    {
        $message = get_option( 'wc_product_referral_message_for_user' );

        return $message;
    }

endif;

/**
 * Get Product Referral Link
 * @param string $user_id
 * @param string $product_id
 * @return string
 */
if( !function_exists( 'prfwc_get_referral_link' ) ):

    function prfwc_get_referral_link( $user_id = '', $product_id = '' )
    {
        $user_id = empty( $user_id ) ? get_current_user_id() : $user_id;

        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;

        $referral_link = get_permalink( $product_id ) . '?ref_id=' . $user_id;

        return $referral_link;
    }

endif;

/**
 * Get User's IP
 * @return string
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'prfwc_get_users_ip' ) ):

    function prfwc_get_users_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
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

    function prfwc_get_discount_price( $product_id = '' )
    {
        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;

        global $woocommerce;

        $product = wc_get_product( $product_id );

        $product_ids = prfwc_get_product_ids();

        $discount_type = prfwc_get_discount_type();

        $discount_value  = prfwc_get_discount_value();

        $discount_on = prfwc_get_discount_on();

        if ( in_array( $product_id, $product_ids ) )
        {
            $apply_discount_on = '';

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

/**
 * @param $product_id
 * @return mixed
 * @since 1.0
 * @version 1.0
 */

if ( !function_exists( 'prfwc_if_referred' ) ):

    function prfwc_if_referred( $product_id = '' )
    {
        $product_ids = prfwc_get_product_ids();

        $product_id = empty( $product_id ) ? get_the_ID() : $product_id;

        $user_id = get_current_user_id();

        if ( is_product() )
        {

            if ( isset( $_GET['ref_id'] ) && in_array( $product_id, $product_ids ) )
            {
                $ref_id = $_GET['ref_id'];

                if ( $user_id != $ref_id )
                {

                    $user_data = get_userdata( $ref_id );

                    if ( $user_data )
                    {
                        $meta_key = 'prfwc_ref_id_' . $ref_id . '_discount_' . prfwc_get_discount_type();

                        $post_meta = get_post_meta( $product_id, $meta_key, true );

                        $user_ip = prfwc_get_users_ip();

                        if ( !empty( $post_meta ) )
                        {
                            $referral_data = get_post_meta( $product_id, $meta_key, true );

                            if ( !in_array( $user_ip[0], $referral_data ) )
                            {
                                array_push( $referral_data, $user_ip[0] );

                                update_post_meta( $product_id, $meta_key, $referral_data );
                            }
                        }
                        else
                        {
                            update_post_meta( $product_id, $meta_key, $user_ip );
                        }
                    }
                    else
                    {
                        wc_add_notice( __( 'No Such Referee', 'prfwc' ) , 'error' );
                    }
                }
            }
        }
    }

endif;

/**
 * Applies Discount On Product
 * @return false|string[]
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'prfwc_apply_discount' ) ):

    function prfwc_apply_discount( $price_html, $product ) {

        // Only front end
        if ( is_admin() ) return $price_html;

        // If price ain't null
        if ( '' === $product->get_price() ) return $price_html;

        $product_id =  get_the_ID();

        $user_id = empty( $user_id ) ? get_current_user_id() : $user_id;

        $product_ids = prfwc_get_product_ids();

        $required_ref = prfwc_get_referral_numbers();

        $meta_key = 'prfwc_ref_id_' . $user_id . '_discount_' . prfwc_get_discount_type();

        $discount_price = prfwc_get_discount_price( $product_id );

        $product_data = get_post_meta( $product_id, $meta_key, true );

        if ( in_array( $product_id, $product_ids ) )
        {
            if ( count( $product_data ) >= $required_ref  )
            {

                wc_add_notice( __( "Referral Completed, You'll be charged discounted.", "prfwc" ), 'success', array( 'id'   =>  'prfwc-success-notification' ) );

                $original_price = wc_get_price_to_display( $product );

                $price_html = wc_price( $discount_price );

            }
        }


        return $price_html;

    }
endif;



/**
 * Applies Discount On Product
 * @return false|string[]
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'prfwc_apply_discount_checkout' ) ):

    function prfwc_apply_discount_checkout( $cart )
    {
        //Only on front end
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

        // If user ain't logged in don't
        if ( !is_user_logged_in() ) return;

        $user_id = get_current_user_id();

        $product_ids = prfwc_get_product_ids();

        $required_ref = prfwc_get_referral_numbers();

        $meta_key = 'prfwc_ref_id_' . $user_id . '_discount_' . prfwc_get_discount_type();

        // Apply discount through loop
        foreach ( $cart->get_cart() as $cart_item_key => $cart_item )
        {
            $product = $cart_item['data'];

            $price = $product->get_price();

            $product_id = $product->get_id();

            $discount_price = prfwc_get_discount_price( $product_id );

            $product_data = get_post_meta($product_id, $meta_key, true);

            if ( in_array( $product_id, $product_ids ) )
            {
                if ( count( $product_data ) >= $required_ref )
                {
                    $cart_item['data']->set_price( $discount_price );
                }
            }
        }
    }

endif;
