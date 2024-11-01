<?php
if ( ! defined( 'ABSPATH' ) )  exit;

class Count_shipAdv {

	protected $software;
	protected $number = 0;
	protected $count;

	public function __construct( $software, $date = '2010-01-01T00:00:00', $count = 50) {
		$this->software = $software;
		$this->count = $count;
		$this->setNumber($date);
	}

	public function getNumber() {
		return $this->number;
	}

	protected function setNumber($date) {
		$time = strtotime($date.' UTC');
		if($date == '2000-01-01T00:00:00Z') :
			date_default_timezone_set('UTC');
			$time = strtotime( '-355 days' );
		endif;
		$dateInLocal = date("Y-m-d H:i:s", $time);
		global $wpdb;

		//$split = explode( '.' , $this->software->getVersion() );

		if ( $this->software->isCompatible() ) {

			if ( 'Shopp' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE modified > '" . $dateInLocal . "' and (txnstatus = 'authed' or txnstatus = 'captured') order by modified ASC", ARRAY_A
						);
						foreach ( $orders as $order ) {
							$orderObj = new Order_shipAdv($this->software, $date,$order);
							$array = $orderObj->getItems();
							$this->number++;
						}
			} elseif ( 'Woocommerce' == $this->software->getSoftware() ) {
						include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
						date_default_timezone_set('UTC');
						$now = date("Y-m-d H:i:s", strtotime("NOW - 30 SECONDS"));
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							if (!is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) {
								$orders = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE date_updated_gmt > '" . $dateInLocal . "' AND date_updated_gmt < '".$now."'
													AND type = 'shop_order' AND status !='auto-draft' AND status !='draft' AND status !='trash' order by date_updated_gmt ASC LIMIT 100", ARRAY_A );
							}
							else {
								$orders = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE date_updated_gmt > '" . $dateInLocal . "' AND date_updated_gmt < '".$now."'
													AND type = 'shop_order' AND (status ='wc-completed' OR status ='wc-unshipped' OR status ='wc-backorder') order by date_updated_gmt ASC LIMIT 100", ARRAY_A );
							}
						} else {
							if (!is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) {
								$orders = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_modified_gmt < '".$now."'
													AND post_type = 'shop_order' AND post_status !='draft' AND post_status !='auto-draft' AND post_status !='trash' order by post_modified_gmt ASC LIMIT 100", ARRAY_A );
							} else {
								$orders = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_modified_gmt < '".$now."'
													AND post_type = 'shop_order' AND (post_status ='wc-completed' OR post_status ='wc-unshipped' OR post_status ='wc-backorder')  order by post_modified_gmt ASC LIMIT 100", ARRAY_A );
							}
						}
						$countMax = $this->count;

						//CHECK IF VIRTUAL PRODUCT NEED TO BE DOWNLOAD
						$settingsAdv = new Settings_shipAdv();
						$ifDownloadVirtualProd = $settingsAdv->getDownload_virtualProd();

						//if plugin shipping multiple address activated

						if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
								|| is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")) {
									$newOrders = array();
							foreach ( $orders as $order ) {
									if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
										$sepOrders = $wpdb->get_results(
	"SELECT * FROM " . $wpdb->prefix."wc_orders WHERE parent_order_id ='".$order['id']."' and type = 'order_shipment' AND status !='draft' AND status !='auto-draft' AND status !='trash' order by date_updated_gmt ASC", ARRAY_A );
									} else {
										$sepOrders = $wpdb->get_results(
	"SELECT * FROM " . $wpdb->posts . " WHERE post_parent ='".$order['ID']."' and post_type = 'order_shipment' AND post_status !='draft' AND post_status !='auto-draft' order by post_modified_gmt ASC", ARRAY_A );
									}
									if(!$sepOrders) :
										$newOrders[] = $order;
									else :
										foreach ( $sepOrders as $seporder ) : $countMax++;
											$newOrders[] = $seporder;
										endforeach;
									endif;
							}
							if($newOrders) $orders = $newOrders;
						}
						//end if plugin shipping multiple address

						if($orders) {
							foreach ( $orders as $order ) {
								if ( $this->number <= $countMax ) {
									if ( !isDownloadable($this->software, null, $order, $ifDownloadVirtualProd ) ) {
										$this->number++;
									}
								} else {
									break 1;
								}
							}
						}
			} elseif ( 'WP eCommerce' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE date > '" . $time . "' order by date ASC",
						ARRAY_A);
						foreach ( $orders as $order ) {
							$this->number++;
						}

			} elseif ( 'Cart66 Lite' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC",
						ARRAY_A);
						foreach ( $orders as $order ) {
							$this->number++;
						}
			} elseif ( 'Cart66 Pro' == $this->software->getSoftware() ) {
						$orders = $wpdb->get_results(
						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC",
						ARRAY_A);
						foreach ( $orders as $order ) {
							$this->number++;
						}
			}
		}
	}
}
