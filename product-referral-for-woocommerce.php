<?php
/**
 * Plugin Name: Product Referral For WooCommerce
 * Plugin URI: https://www.scintelligencia.com/
 * Author: SCI Intelligencia
 * Description: Refer WooCommerce Product and get amazing discounts.
 * Version: 1.0
 * Author: Syed Muhammad Usman
 * Author URI: https://www.linkedin.com/in/syed-muhammad-usman/
 * License: GPL v2 or later
 * Stable tag: 1.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Tags: WooCommerce, products, referral, discounts, referral for woocommerce, wc referral
 * @author Syed Muhammad Usman
 * @url https://www.fiverr.com/mr_ussi
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('ProductReferralForWooCommerce') ) {
    class ProductReferralForWooCommerce
    {

        /**
         * @var $product_referral
         * @since 1.0
         * @version 1.0
         */
        private $product_referral;

        /**
         * ProductReferralForWooCommerce constructor.
         * @since 1.0
         * @version 1.0
         */
        public function __construct()
        {
            add_action( 'admin_init', array( $this, 'check_requirements' ) );
        }

        public function check_requirements()
        {
            if ( !$this->is_wc_active() )
            {
            ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e( 'In order to use Product Referral for WooCommerce make sure to install and activate <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>', 'prfwc' ); ?></p>
                </div>
            <?php
            }

            else
                $this->run();
        }

        public function is_wc_active()
        {
            if ( is_plugin_active( 'woocommerce/woocommerce.php' ) )
                return true;
            else
                return false;
        }

        /**
         * Runs Plugins
         * @since 1.0
         * @version 1.0
         */
        public function run()
        {
            $this->product_referral = 'product_referral';
            $this->constants();
            $this->includes();
            $this->add_actions();
            $this->register_hooks();
        }

        /**
         * @param $name Name of constant
         * @param $value Value of constant
         * @since 1.0
         * @version 1.0
         */
        public function define($name, $value)
        {
            if ( !defined( $name ) )
                define($name, $value);
        }

        /**
         * Defines Constants
         * @since 1.0
         * @version 1.0
         */
        public function constants()
        {
            $this->define('PRFWC_VERSION', '1.0');
            $this->define('PRFWC_PREFIX', 'prfwc_');
            $this->define('PRFWC_TEXT_DOMAIN', 'prfwc');
            $this->define('PRFWC_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
            $this->define('PRFWC_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
        }

        /**
         * Require File
         * @since 1.0
         * @version 1.0
         */
        public function file( $required_file ) {
            if ( file_exists( $required_file ) )
                require_once $required_file;
            else
                echo 'File Not Found';
        }

        /**
         * Include files
         * @since 1.0
         * @version 1.0
         */
        public function includes()
        {
            $this->file(PRFWC_PLUGIN_DIR_PATH. 'includes/product-referral-for-woocommerce-functions.php');
        }

        /**
         * Enqueue Styles and Scripts
         * @since 1.0
         * @version 1.0
         */
        public function enqueue_scripts()
        {
            add_action("wp_ajax_custom_ajax", [$this, 'custom_ajax']);
            add_action("wp_ajax_nopriv_custom_ajax", [$this, 'custom_ajax']);
            wp_enqueue_style(PRFWC_TEXT_DOMAIN . '-css', PRFWC_PLUGIN_DIR_URL . 'assets/css/style.css', '', PRFWC_VERSION);
            wp_enqueue_script(PRFWC_TEXT_DOMAIN . '-custom-js', PRFWC_PLUGIN_DIR_URL . 'assets/js/custom.js', '', PRFWC_VERSION);
        }

        /**
         * Ajax Call
         * @since 1.0
         * @version 1.0
         */
        public function custom_ajax()
        {
            die('YAY');
        }

        /**
         * Add Product Referral
         * @param $settings_tabs
         * @return mixed
         * @since 1.0
         * @version 1.0
         */
        public function add_product_referral_tab($settings_tabs )
        {
            $settings_tabs[$this->product_referral] = __( 'Product Referral', 'prfwc' );
            return $settings_tabs;
        }

        /**
         * Product Referral Settings
         * @since 1.0
         * @version 1.0
         */
        public function product_referral_settings()
        {
            woocommerce_admin_fields( $this->get_product_referral_settings() );

        }

        /**
         * Updates Product Referral Settings
         * @since 1.0
         * @version 1.0
         */
        public function update_product_referral_settings()
        {
            woocommerce_update_options( $this->get_product_referral_settings() );
        }

        /**
         * @return mixed|void
         * @since 1.0
         * @version 1.0
         */
        public function get_product_referral_settings()
        {
            $settings = array(
                'section_title' => array(
                    'name'     => __( 'Product Referral', 'prfwc' ),
                    'type'     => 'title',
                    'desc'     => '',
                    'id'       => 'wc_product_referral_demo_section_title'
                ),

                'product_id' => array(
                    'name' => __( 'Product ID', 'prfwc' ),
                    'type' => 'number',
                    'desc' => __( 'Enter Product ID to apply referral, Enter 0 to apply on all products.', 'prfwc' ),
                    'id'   => 'wc_product_referral_product_id',
                    //'desc_tip' => true,
                ),

                'discount_type' => array(
                    'name'  => __( 'Discount Type', 'prfwc' ),
                    'type'  => 'select',
                    'desc'  => __( 'Select discount type to apply referrals {discount_type}.', 'prfwc' ),
                    'id'    => 'wc_product_referral_discount_type',
                    'default'  => 'flat',
                    'class'    => 'wc-enhanced-select',
                    'options'  => array(
                        'flat'          => __( 'Flat Discount', 'prfwc' ),
                        'percent'       => __( 'Percent Discount', 'prfwc' ),
                    ),
                    //'desc_tip' => true,
                ),

                'discount_value' => array(
                    'name' => __( 'Discount Cost/ Percent', 'prfwc' ),
                    'type' => 'number',
                    'desc' => __( 'Enter discount value to apply on product. {discount_value}', 'prfwc' ),
                    'id'   => 'wc_product_referral_discount_value',
                   //'desc_tip' => true,
                ),

                'referral_numbers' => array(
                    'name' => __( 'Number Of Referral', 'prfwc' ),
                    'type' => 'number',
                    'desc' => __( 'Number of referral user need to get discount {referral_numbers}.', 'prfwc' ),
                    'id'   => 'wc_product_referral_referral_numbers',
                    //'desc_tip' => true,
                ),

                'discount_on'   => array(
                    'name'  =>  __( 'Apply Discount On', 'prfwc' ),
                    'type'  =>  'select',
                    'desc'  =>  __( 'Apply discount on selected product feature.', 'prfwc' ),
                    'id'    =>  'wc_product_referral_discount_on',
                    'default'   =>  'whole_product',
                    'class'    => 'wc-enhanced-select',
                    'options'  => array(
                        'whole_product' => __( 'On Entire Product', 'prfwc' ),
                        'shipment'       => __( 'On Shipment Charges', 'prfwc' ),
                        'tax'       => __( 'On Tax', 'prfwc' ),
                    ),
                    'desc_tip' => true,
                ),

                'show_referral_on'   => array(
                    'name'      =>  __( 'Show Referral Message On', 'prfwc' ),
                    'type'      =>  'select',
                    'desc'      =>  __( 'Show referral link on. {referral_link}', 'prfwc' ),
                    'id'        =>  'wc_product_referral_show_referral_on',
                    'default'   =>  'on_products',
                    'class'     => 'wc-enhanced-select',
                    'options'   => array(
                        'on_products'                               => __( 'On Products Grid', 'prfwc' ),
                        'woocommerce_before_single_product'         => __( 'Before Product', 'prfwc' ),
                        'woocommerce_before_single_product_summary' => __( 'Before Product Summary', 'prfwc' ),
                        'woocommerce_single_product_summary'        => __( 'Single Product Summary', 'prfwc' ),
                        'woocommerce_before_add_to_cart_form'       => __( 'Before Add To Cart Form', 'prfwc' ),
                        'woocommerce_product_thumbnails '           => __( 'After Product Thumbnail', 'prfwc' ),
                        'woocommerce_before_variations_form '       => __( 'Before Variations Form', 'prfwc' ),
                        'woocommerce_before_add_to_cart_button '    => __( 'Before Add To Cart Button', 'prfwc' ),
                        'woocommerce_before_single_variation '      => __( 'Before Variation', 'prfwc' ),
                        'woocommerce_single_variation '             => __( 'After Variation', 'prfwc' ),
                        'woocommerce_before_add_to_cart_quantity '  => __( 'Before Add To Cart Quantity', 'prfwc' ),
                        'woocommerce_after_add_to_cart_quantity '   => __( 'After Add To Cart Quantity', 'prfwc' ),
                        'woocommerce_after_single_variation '       => __( 'After Single Variation', 'prfwc' ),
                        'woocommerce_after_add_to_cart_button '     => __( 'After Add to Cart Button', 'prfwc' ),
                        'woocommerce_after_variations_form '        => __( 'After Variation Form', 'prfwc' ),
                        'woocommerce_after_add_to_cart_form '       => __( 'After Add To Cart Form', 'prfwc' ),
                        'woocommerce_product_meta_start '           => __( 'Before Product Meta', 'prfwc' ),
                        'woocommerce_product_meta_end '             => __( 'After Product Meta', 'prfwc' ),
                        'woocommerce_share '                        => __( 'Share', 'prfwc' ),
                        'woocommerce_after_single_product_summary ' => __( 'After Product Summary', 'prfwc' ),
                        'woocommerce_after_single_product '         => __( 'After Product', 'prfwc' ),
                    ),
                    //'desc_tip' => true,
                ),

                'message_for_user'  =>  array(
                    'name'      =>  __( 'Message for User', 'prfwc' ),
                    'type'      =>  'textarea',
                    'desc'      =>  __( 'Show referral message to user.', 'prfwc' ),
                    'id'        =>  'wc_product_referral_message_for_user',
                    'default'   =>  __( 'Refer This product to {referral_numbers} people, and get {discount_value}, on {discount_type}, Share Link {referral_link}.', 'prfwc' ),
                    'desc_tip'  => true,
                ),

                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_product_referral_section_end'
                )
            );

            return apply_filters( 'wc_'.$this->product_referral.'_settings', $settings );
        }


        /**
         * Add Actions
         * @since 1.0
         * @version 1.0
         */
        public function add_actions()
        {
            add_action('init', [$this, 'enqueue_scripts']);

            //Add Settings Tab
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_product_referral_tab' ), 50 );

            //Add settings
            add_action( 'woocommerce_settings_tabs_' . $this->product_referral, array( $this, 'product_referral_settings' ) );

            //Update Settings
            add_action( 'woocommerce_update_options_' . $this->product_referral, array( $this, 'update_product_referral_settings') );

        }

        /**
         * Register Activation, Deactivation and Uninstall Hooks
         * @since 1.0
         * @version 1.0
         */
        public function register_hooks()
        {
            register_activation_hook( __FILE__, [$this, 'activate'] );

            register_deactivation_hook( __FILE__, [$this, 'deactivate'] );

            register_uninstall_hook(__FILE__, 'prfwc_uninstall');
        }

        /**
         * Runs on Plugin's activation
         * @since 1.0
         * @version 1.0
         */
        public function activate()
        {

        }

        /**
         * Runs on Plugin's Deactivation
         * @since 1.0
         * @version 1.0
         */
        public function deactivate()
        {

        }
    }
}

if ( !function_exists( 'load_prfwc' ) )
{
    function load_prfwc()
    {
        new ProductReferralForWooCommerce();
    }
}

add_action( 'plugins_loaded', 'load_prfwc' );
