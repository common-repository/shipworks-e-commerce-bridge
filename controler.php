<?php
/*
* Plugin Name: ShipWorks Connector
* Plugin URI: https://adv.design
* Description: ShipWorks Connector will connect your E-Commerce site (such as WooCommerce) and ShipWorks.
* Version: 5.1.16
* Author: AdvancedCreation
* Author URI: https://adv.design
* Text Domain: shipworks-connector
* Domain Path: /languages
* WC requires at least: 3.0
* WC tested up to: 9.3.3
License: GPL2
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('SHIPWORKSWORDPRESS_VERSION')) define('SHIPWORKSWORDPRESS_VERSION','5.1.16');
if (!defined('SHIPWORKSWORDPRESS_HOME')) define('SHIPWORKSWORDPRESS_HOME','https://adv.design/');
if (!defined('PLUGIN_URL_SHIPWORKSWORDPRESS'))  define( 'PLUGIN_URL_SHIPWORKSWORDPRESS', plugin_dir_url( __FILE__ ) );
if (!defined('PLUGIN_PATH_SHIPWORKSWORDPRESS')) define( 'PLUGIN_PATH_SHIPWORKSWORDPRESS', plugin_dir_path( __FILE__ ) );
if (!defined('THEMES_PATH')) define('THEMES_PATH', get_theme_root());
if (!defined('ROOT_URL')) define('ROOT_URL', get_option('siteurl') . '/');
if (!defined('ROOT_URL_SSL')) define('ROOT_URL_SSL', site_url( '/', 'https' ));
if (!defined('SHIPWORKSWORDPRESS_URL')) define('SHIPWORKSWORDPRESS_URL',ROOT_URL . 'wp-admin/admin.php?page=shipworks-wordpress');
if (!defined('SHIPWORKSWORDPRESS_URL_SSL')) define('SHIPWORKSWORDPRESS_URL_SSL',ROOT_URL_SSL . 'wp-admin/admin.php?page=shipworks-wordpress');
if (!defined('PLUGINS_PATH')) define('PLUGINS_PATH',plugin_dir_path( __DIR__ ));

/*= ADD MENU PAGE IN THE BACKEND */
add_action('admin_menu', 'shipworks_admin_menu');
function shipworks_admin_menu() {
		add_menu_page(__('Shipworks WordPress'), __('Shipworks WP','shipworks-connector'),'manage_options','shipworks-wordpress', 'shipworks_admin',PLUGIN_URL_SHIPWORKSWORDPRESS.'/img/logo.png');
		add_submenu_page('shipworks-wordpress',__('Set Up | Shipworks WordPress','shipworks-connector'),__('Set Up','shipworks-connector'),'manage_options','set-up','shipworks_set_up');
		add_submenu_page('shipworks-wordpress',__('Subscription | Shipworks WordPress','shipworks-connector'),__('Subscription','shipworks-connector'),'manage_options','subscription','shipworks_subscription');
		add_submenu_page('shipworks-wordpress',__('Options','shipworks-connector'),__('Options','shipworks-connector'),'manage_options','options','shipworks_message_settings');
}
function shipworks_admin() {
		require_once 'control/controlAdmin.php';
		wp_enqueue_style( 'ShipworksCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/admin.css');
}
function shipworks_set_up() {
		require_once 'control/controlSetUp.php';
		wp_enqueue_style( 'ShipworksCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/admin.css');
}
function shipworks_subscription() {
		require_once 'control/controlSubscription.php';
		wp_enqueue_style( 'ShipworksCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/admin.css');
		wp_enqueue_style( 'ModalCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/bootstrap.min.css');
		wp_enqueue_script( 'ModalJs', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/bootstrap.min.js');
}
function shipworks_message_settings() {
		require_once 'control/controlMessageSettings.php';
		wp_enqueue_style( 'ShipworksCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/admin.css');
		wp_enqueue_style( 'ModalCss', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/bootstrap.min.css');
		wp_enqueue_script( 'ModalJs', PLUGIN_URL_SHIPWORKSWORDPRESS.'css/bootstrap.min.js');
}
function shipworks_connector_load_plugin_textdomain() {
    load_plugin_textdomain( 'shipworks-connector', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'shipworks_connector_load_plugin_textdomain' );

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

add_action( 'wp_loaded', 'shipworks_connection' );
function shipworks_connection() {
	if(isset($_POST['action'])) {
		if ( ($_POST['action'] == 'getmodule' or $_POST['action'] == 'getstore' or $_POST['action'] == 'getcount' or $_POST['action'] == 'getorders' or $_POST['action'] == 'updatestatus' or $_POST['action'] == 'updateshipment' or $_POST['action'] == 'getstatuscodes') && isset($_POST['username']) && isset($_POST['password']) ) {
			include_once 'connection.php';
			exit;
		}
	}
	else {
		if(is_admin()) {
			include_once 'control/controlNotice.php';
		}
	}
}
