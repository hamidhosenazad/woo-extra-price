<?php
/**
 * Woo Extra Price
 *
 * @package     woo-extra-price
 * @author      Hamid Azad
 * @copyright   2024 Hamid Azad
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Woo Extra Price
 * Plugin URI:  https://github.com/hamidhosenazad/sms-notification-contact-form-twilio
 * Description: Plugin for WooCommerce to Add Product's Extra Addons options on the product details page
 * Version:     1.0.0
 * Author:      Hamid Azad
 * Author URI:  https://github.com/hamidhosenazad
 * Text Domain: woo-extra-price
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
 * If this file is called directly, abort.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if woo is active.
 */
function wep_check_woo_active() {
	if ( class_exists( 'WooCommerce' ) ) {
		require_once __DIR__ . '/loader.php';
	} else {
		add_action( 'admin_notices', 'wep_requires_woo' );
	}
}
add_action( 'plugins_loaded', 'wep_check_woo_active' );

/**
 * Admin notice if contact form is not active.
 */
function wep_requires_woo() {
	$class = 'notice notice-error';
	printf( '<div class="%1$s"><p style="' . esc_attr( 'display: inline-block;' ) . '">%2$s</p> <a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="' . esc_attr( '_blank' ) . '">Install and Activate WooCommerce</a>.</div>', esc_attr( $class ), esc_html__( 'Woo extra price requires WooCommerce plugin to be active.', 'woo-extra-price' ) );
}
