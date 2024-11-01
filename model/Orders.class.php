<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Orders_shipAdv {

	protected $software;
	protected $date;
	protected $maxCount;
	protected $orders = Array();

	public function __construct( $software, $date = '2010-01-01T00:00:00Z', $maxCount = 50) {
		$this->software = $software;
		$this->maxCount = $maxCount;
		$this->date = $date;
        $this->setInformations($date);
    }



	protected function setInformations() {

		$split = explode( '.' , $this->software->getVersion() );

		// Cas Shopperpress

		if ( 'shopperpress' == $this->software->getSoftware() ) {

			$this->setOrdersShopperpress();

		}// Cas Shopp

		else if ( 'Shopp' == $this->software->getSoftware() ) {

			$this->setOrdersShopp();

		} // Cas Woocommerce

		else if ( 'Woocommerce' == $this->software->getSoftware() ) {

			$this->setOrdersWoocommerce();

		} // Cas WP eCommerce

		else if ( 'WP eCommerce' == $this->software->getSoftware() ) {

			$this->setOrdersWPeCommerce();

		} // Cas Cart66 Lite

		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {

			$this->setOrdersCart66();

		}// Cas Cart66 Pro

		else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {

			$this->setOrdersCart66();

		} // Cas Jigoshop

		else if ( 'Jigoshop' == $this->software->getSoftware() ) {

			$this->setOrdersJigoshop();

		}

	}



	protected function setOrdersShopperpress() {

		$time = strtotime($this->date.' UTC');

		$dateInLocal = date("Y-m-d H:i:s", $time);

		global $wpdb;

		$rows = $wpdb->get_results(

					"SELECT * FROM " . $wpdb->prefix . "orderdata WHERE CONCAT(order_date,' ',order_time) > '" . $dateInLocal . "' order by CONCAT(order_date,' ',order_time) ASC LIMIT 100"

					, ARRAY_A);

		//CREATE AN HOOK ON FUNCTION sc_orders_rows to allow customers to make changes

		$rows = apply_filters( 'sc_orders_rows', $rows, $this );

		foreach ( $rows as $row ) {

			array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));

		}

	}



	protected function setOrdersShopp() {

		$time = strtotime($this->date.' UTC');

		$dateInLocal = date("Y-m-d H:i:s", $time);

		global $wpdb;

		$rows = $wpdb->get_results(

					"SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE modified > '" . $dateInLocal . "' order by modified ASC LIMIT 100"

						, ARRAY_A);



		//CREATE AN HOOK ON FUNCTION sc_orders_rows to allow customers to make changes

		$rows = apply_filters( 'sc_orders_rows', $rows, $this );

		foreach ( $rows as $row ) {

			$orderObj = new Order_shipAdv($this->software, $this->date,$row);

			$array = $orderObj->getItems();

			if( !empty( $array ) ) {

				array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));

			}

		}

	}



	protected function setOrdersWoocommerce() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');
		date_default_timezone_set('UTC');
		if($this->date != '2010-01-01T00:00:00Z') $time = strtotime($this->date.' UTC');
		$dateInLocal = date("Y-m-d H:i:s", $time);
		$now = date("Y-m-d H:i:s", strtotime("NOW - 30 SECONDS"));
		global $wpdb;

		$count = 0;
		$countMax = $this->maxCount;

		//CHECK IF VIRTUAL PRODUCT NEED TO BE DOWNLOAD
		$settingsAdv = new Settings_shipAdv();
		$ifDownloadVirtualProd = $settingsAdv->getDownload_virtualProd();
		$skipParentOrder = array();
		//if plugin shipping multiple addresses activated
		if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
				|| is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")) {
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					$rows = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix."wc_orders WHERE date_updated_gmt > '" . $dateInLocal . "' AND date_updated_gmt < '".$now."'
					AND type = 'shop_order' AND status !='draft' AND status !='auto-draft' AND status !='trash' order by date_updated_gmt ASC LIMIT 100" , ARRAY_A);
				} else {
					$rows = $wpdb->get_results( "SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_modified_gmt < '".$now."'
					AND post_type = 'shop_order' AND post_status !='draft' AND post_status !='auto-draft' order by post_modified_gmt ASC LIMIT 100" , ARRAY_A);
				}
			$newOrders = array();
			foreach ( $rows as $order ) :
				if ( $count < $countMax ) {
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$sepOrders = $wpdb->get_results(
	"SELECT * FROM " . $wpdb->prefix."wc_orders WHERE parent_order_id ='".$order['id']."' and type = 'order_shipment' AND status !='draft' AND status !='auto-draft' AND status !='trash' ORDER BY id ASC", ARRAY_A );
						$id = "id";
					} else {
						$sepOrders = $wpdb->get_results(
	"SELECT * FROM " . $wpdb->posts . " WHERE post_parent ='".$order['ID']."' and post_type = 'order_shipment' AND post_status !='draft' AND post_status !='auto-draft' ORDER BY ID ASC", ARRAY_A );
						$id = "ID";
					}
					if(!$sepOrders) :
						$newOrders[] = $order;
						$count++;
					else :

						$id_order_post = 0;
						$id_parentID = $order[$id];
						$skipParentOrder[] = $order[$id];
						$nbOrders = count($sepOrders);
						foreach ( $sepOrders as $seporder ) : $id_order_post++;
							if ( !isDownloadable($this->software, null, $seporder, $ifDownloadVirtualProd ) ) {
								if($nbOrders == 1) $id_order_post = "";
								array_push($this->orders,new Order_shipAdv($this->software, $this->date,$seporder, $id_parentID, $id_order_post));
							}
						endforeach;
					endif;
				}
				else {
					break 1;
				}
			endforeach;

				if($newOrders) $rows = $newOrders;
			}
			if (!is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) {
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					$rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE date_updated_gmt > '" . $dateInLocal . "' AND date_updated_gmt < '".$now."'
					AND type = 'shop_order' AND status !='draft' AND status !='auto-draft' AND status !='trash' order by date_updated_gmt ASC LIMIT 100" , ARRAY_A );
				}else{
					$rows = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_modified_gmt < '".$now."'
					AND post_type = 'shop_order' AND post_status !='draft' AND post_status !='auto-draft' order by post_modified_gmt ASC LIMIT 100" , ARRAY_A );
					}
			} else {
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					$rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE date_updated_gmt > '" . $dateInLocal . "' AND date_updated_gmt < '".$now."'
					AND type = 'shop_order' AND (status ='wc-completed' OR status ='wc-unshipped' OR status ='wc-backorder') order by date_updated_gmt ASC LIMIT 100" , ARRAY_A );
				} else {
					$rows = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_modified_gmt < '".$now."'
					AND post_type = 'shop_order' AND (post_status ='wc-completed' OR post_status ='wc-unshipped' OR post_status ='wc-backorder') order by post_modified_gmt ASC LIMIT 100" , ARRAY_A );
				}
			}

			$count = 0;
			$skipParentOrder = array_unique($skipParentOrder);
			foreach ( $rows as $row ) {
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					$order_id = $row['id'];
				} else{
					$order_id = $row['ID'];
				}
				if(!in_array($order_id, $skipParentOrder)) {
					if ( $count < $countMax ) {
						if ( !isDownloadable($this->software, null, $row, $ifDownloadVirtualProd ) ) {
							array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));
							$count++;
						}
					} else {
						break 1;
					}
				}
			}
		//}
	}



	protected function setOrdersWPeCommerce() {

		$time = strtotime($this->date.' UTC');

		$dateInLocal = date("Y-m-d H:i:s", $time);

		global $wpdb;

		$rows = $wpdb->get_results(

						"SELECT * FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE date > '" . $time . "' order by date ASC LIMIT 100" , ARRAY_A );



		//CREATE AN HOOK ON FUNCTION sc_orders_rows to allow customers to make changes

		$rows = apply_filters( 'sc_orders_rows', $rows, $this );

		foreach ( $rows as $row ) {
			array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));
		}
	}



	protected function setOrdersCart66() {

		$time = strtotime($this->date.' UTC');

		$dateInLocal = date("Y-m-d H:i:s", $time);

		global $wpdb;

		$rows = $wpdb->get_results(

						"SELECT * FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on > '" . $dateInLocal . "' order by ordered_on ASC LIMIT 100",

						ARRAY_A);



		//CREATE AN HOOK ON FUNCTION sc_orders_rows to allow customers to make changes

		$rows = apply_filters( 'sc_orders_rows', $rows, $this );

		foreach ( $rows as $row ) {

			array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));

		}



	}



	protected function setOrdersJigoshop() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php');

		$time = strtotime($this->date.' UTC');

		$dateInLocal = date("Y-m-d H:i:s", $time);

		global $wpdb;

		$rows = $wpdb->get_results(

						"SELECT * FROM " . $wpdb->posts . " WHERE post_modified_gmt > '" . $dateInLocal . "' AND post_type = 'shop_order' order by post_modified_gmt ASC LIMIT 100" , ARRAY_A

					);


		//CREATE AN HOOK ON FUNCTION sc_orders_rows to allow customers to make changes

		$rows = apply_filters( 'sc_orders_rows', $rows, $this );

		foreach ( $rows as $row ) {

			array_push($this->orders,new Order_shipAdv($this->software, $this->date,$row));

		}

	}



	public function getOrders() {

		return $this->orders;

	}

}
