<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Software_shipAdv {

	protected $software;
	protected $version;
	protected $supportComments;

	public function __construct() {
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
			$this->software = "Woocommerce";
			$this->supportComments = true;
		} elseif ( in_array( 'shopp/Shopp.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
			$this->software = "Shopp";
			$this->supportComments = false;
		} elseif ( in_array( 'wp-e-commerce/wp-shopping-cart.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
			$this->software = "WP eCommerce";
			$this->supportComments = false;
		} elseif (in_array( 'cart66-lite/cart66.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
			$this->software = "Cart66 Lite";
			$this->supportComments = false;
		} elseif (in_array( 'cart66/cart66.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
			$this->software = "Cart66 Pro";
			$this->supportComments = false;
		}
		$this->setVersion();
	}

	public function getSoftware() {
		return $this->software;
	}

	public function getSupportComments() {
		return $this->supportComments;
	}

	protected function setVersion() {
 		if ( 'Shopp' == $this->getSoftware() ) {
			$this->version = $this->recordVersion( "shopp/Shopp.php" );
		} elseif ( 'Woocommerce' == $this->getSoftware() ) {
			$this->recordVersion( "woocommerce/woocommerce.php" );
		} elseif ( 'WP eCommerce' == $this->getSoftware() ) {
			$this->version = $this->recordVersion( "wp-e-commerce/wp-shopping-cart.php" );
		} elseif ( 'Cart66 Lite' == $this->getSoftware() ) {
			$this->version = $this->recordVersion( "cart66-lite/cart66.php" );
		} elseif ( 'Cart66 Pro' == $this->getSoftware() ) {
			$this->version = $this->recordVersion( "cart66-pro/cart66.php" );
		}
	}
	public function getVersion(){
		return $this->version;
	}

	public function isCompatible() {
		if( 'Shopp' == $this->software ) {
			return true;
		} elseif ( 'Woocommerce' == $this->software ) {
			return true;
		} elseif ( 'WP eCommerce' == $this->software ) {
			return true;
		} elseif ( 'Cart66 Lite' == $this->software ) {
			return true;
		} elseif ( 'Cart66 Pro' == $this->software ) {
			return true;
		}
		return false;
	}

	public function getCompatibleMessage() {
		return sprintf(__("You are currently running %s with the version %s .This is fully compatible with our plugin ShipWorks Connector.", "shipworks-connector"),$this->software, $this->version);
	}

	public function getNotCompatibleMessage() {
		$toReturn = __("We didn't find any E-commerce plugin activated on your website, please activate it. If you are using a multisite, please do not activate the plugin network wide. Activate the plugin on each instance new sub website and the plugin will work correctly.", "shipworks-connector");
		return $toReturn;
	}
	public function recordVersion($path) {
	  $versionData = get_file_data(PLUGINS_PATH.$path, array(
	      'Version' => 'Version'
	  ) );
		$this->version = $versionData['Version'];
	  return $this->version;
	}
}
