<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if (!function_exists('prfwc_uninstall'))
{
    /**
     * Run on Plugin's Uninstall
     * @since 1.0
     * @version 1.0
     */
    function prfwc_uninstall()
    {
        //Run Uninstall logic here
    }
}
