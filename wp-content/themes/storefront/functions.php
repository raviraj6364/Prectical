<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
//custom filters start
add_filter( 'woocommerce_shortcode_products_query', 'woocommerce_shortcode_products_orderby' );
function woocommerce_shortcode_products_orderby( $args ) {
	$standard_array = array('menu_order','title','date','rand','id');
	if( isset( $args['orderby'] ) && !in_array( $args['orderby'], $standard_array ) ) {
		$args['meta_key'] = $args['orderby'];
		$args['orderby']  = '_regular_price'; 
	}
	return $args;
}
add_filter ('add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}
add_action('wp_ajax_custom_filter', 'custom_filter');
add_action('wp_ajax_nopriv_custom_filter', 'custom_filter');
function custom_filter() {
  $filtre_value = $_POST['filtre_value'];

	switch ($filtre_value) {
	case popular:
		$response = do_shortcode('[products limit="6" columns="3" orderby="_regular_price" order="asc" class="popular"]');
		break;
	case featured:
		$response = do_shortcode('[products limit="3" columns="3" visibility="featured" orderby="_regular_price" class="featured"]');
		break;
	case categories:
		$response = do_shortcode('[product_categories ids="31,32" columns="3" class="categories"]');
		break;
    default:
		$response = '<h2>Featured Products</h2>'.do_shortcode('[products limit="3" columns="3" visibility="featured" orderby="_regular_price" class="featured"]'); 
		$response .='<h2>Popular Products</h2>'.do_shortcode('[products limit="6" columns="3" orderby="_regular_price" order="asc" class="popular"]');
		$response .='<h2>Product Categories</h2>'.do_shortcode('[product_categories ids="31,32" columns="3" class="categories"]');
}
 

  echo $response;
  exit;
}
//custom filters End