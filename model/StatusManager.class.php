<?php
if ( ! defined( 'ABSPATH' ) )  exit;

class StatusManager_shipAdv {

	protected $software;
	protected $date;
	protected $order;
	protected $orderIdSent;
	protected $id_order_post;
	protected $status;
	protected $result;
	protected $code;
	protected $description;
	protected $comment;

	public function __construct( $software, $date, $order = '', $status = '', $comment = '') {

		$this->software = $software;
		$this->order = $order;
		$this->orderIdSent = $order;
		$this->status = $status;
		$this->comment = $comment;
		$this->setInformations();
  }

	protected function setInformations() {
		$order = $this->order;
		$status = $this->status;
		$split = explode( '.' , $this->software->getVersion() );

		if ($order == '' or $status == '') {
			if ($order == '' and $status == '') {
				$this->result = false;
				$this->code = 'ERR001';
				$this->description = __('Order and Status not communicate correctly', "shipworks-connector");
			} elseif ($order == '' and $status != '') {
				$this->result = false;
				$this->code = 'ERR002';
				$this->description = __('Order not communicate correctly', "shipworks-connector");
			} elseif ($order != '' and $status == '') {
				$this->result = false;
				$this->code = 'ERR003';
				$this->description = __('Status not communicate correctly', "shipworks-connector");
			}
		}	else {
				if ( 'Shopp' == $this->software->getSoftware() ) {
					$this->setInfoShopp();
				} elseif ( 'Woocommerce' == $this->software->getSoftware() ) {
						if (version_compare($this->software->getVersion(), '2.1.2', '>=')) { //version >= 2.2.0
						//if ( ($split[0] >= 2 && $split[1] >= 2) || $split[0] >= 3  ) { //version > 2.1.2
							$this->setInfoWoocommerce2v2();
						} else {
							$this->setInfoWoocommerce();
						}
				} elseif ( 'WP eCommerce' == $this->software->getSoftware() ) {
					$this->setInfoWPeCommerce();
				} elseif ( 'Cart66 Lite' == $this->software->getSoftware() ) {
					$this->setInfoCart66();
				} elseif ( 'Cart66 Pro' == $this->software->getSoftware() ) {
					$this->setInfoCart66();
				}
		}
		$this->filtre();
	}

	protected function setInfoShopp() {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php';
		global $wpdb;
		$table = $wpdb->prefix . "shopp_purchase";
		$status = $this->status;
		$this->result = $wpdb->update( $table,
				array(
						'status' => $status,
					),
				array( 'id' => $this->order )
		);
		if ( $this->result === false ) {
			$this->code = 'ERR004';
			$this->description = __("The Status coudn't be update in the database", "shipworks-connector");
		}
	}

	protected function setInfoWoocommerce() {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php';
		global $wpdb;
		$status = $this->status;

		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php")
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php")
			 		||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php")) {
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$row = $wpdb->get_row(
									"SELECT * FROM " . $wpdb->prefix."wc_orders_meta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
							if ($row) $this->order = $row['order_id'];
						} else {
							$row = $wpdb->get_row(
									"SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
							if ($row) $this->order = $row['post_id'];
						}

		}
		$table = $wpdb->term_taxonomy;
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $status, ARRAY_A);

		$table = $wpdb->term_relationships;
		if ( $row['term_taxonomy_id'] != null ) {
			$this->result = $wpdb->update( $table,
					array(
							'term_taxonomy_id' => $row['term_taxonomy_id']
						),
					array( 'object_id' => $this->order )
			);
			if ( $this->result === 0 ) {
				$this->result = true;
			}
		}

		if ( $this->result === false ) {
			$this->code = 'ERR004';
			$this->description = __("The Status coudn't be update in the database", "shipworks-connector");
		} elseif ( $this->comment != '' ) {
			add_private_note( $this->comment, $this->order );
		}
	}

	protected function setInfoWoocommerce2v2() {

		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php';

		global $wpdb;
		global $woocommerce;

		$settingsAdv = new Settings_shipAdv();
		$woocommerce_api_shipAdv = $settingsAdv->getWoocommerce_Api();
		$status = $this->status;

		if(is_plugin_active_custom( "shipworks-addon-pinehurst-coins/shipworks-addon-pinehurst-coins.php")) {
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$row = $wpdb->get_row(
									"SELECT * FROM " . $wpdb->prefix."wc_orders_meta WHERE meta_key = '_order_number' and meta_value = '" . $this->order."'", ARRAY_A);
							if ($row) $this->order = $row['order_id'];
						} else {
							$row = $wpdb->get_row(
									"SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = '_order_number' and meta_value = '" . $this->order."'", ARRAY_A);
							if ($row) $this->order = $row['post_id'];
						}
		}
		//REMOVE PREFIX AND POSTFIX
		$pref = get_option("woocommerce_order_number_prefix");
		if($pref){
			$orderId = str_replace ($pref, "", $this->order);
			if(strpos($orderId, "-") !== false) {
				$this->order = substr($orderId, 0, strpos($orderId, "-"));
				$this->id_order_post = substr($orderId, (strpos($orderId, "-") - (strlen($orderId) -1)));
			} else {$this->order = $orderId; }
		}

		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php")
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php")
			||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php") ) {
			if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
				$row = $wpdb->get_row(
						"SELECT * FROM " . $wpdb->prefix."wc_orders_meta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
				if ($row) $this->order = $row['order_id'];
			} else {
				$row = $wpdb->get_row(
						"SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
				if ($row)	$this->order = $row['post_id'];
			}


		}

		$table = $wpdb->posts;
		if (is_plugin_active_custom( "woocommerce-order-status-manager/woocommerce-order-status-manager.php")) :
			 $tab = Array( 	0 => "pending",
									1 => "failed",
									2 => "on-hold",
									3 => "processing",
									4 => "completed",
									5 => "refunded",
									6=>  "cancelled");
			$statusIds = $wpdb->get_results("SELECT ID, post_title, post_name FROM " . $wpdb->posts . " WHERE post_type = 'wc_order_status' ORDER BY ID ASC" , ARRAY_A);
			foreach ( $statusIds as $statusId ) :
				if(!in_array($statusId['post_name'], $tab)) $tab[] = $statusId['post_name'];
			endforeach;
		else :
			$tab = Array(
				0 => "pending",
				1 => "failed",
				2 => "on-hold",
				3 => "processing",
				4 => "completed",
				5 => "refunded",
 				6 =>  "cancelled");
		endif;
		if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
					|| is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")) :

			$packages = get_post_meta( $this->order, '_wcms_packages', true );
			if ($packages ) : $i = 0; $statusCount = 0; $packageNumber = count($packages);
				foreach ( $packages as $x => $package ) : $i++;
					if($this->id_order_post == $i) :
						$packages[ $x ]['status'] = 'Completed';
						update_post_meta( $this->order, '_wcms_packages', $packages);
						$statusCount++;
					else :
						if($packages[ $x ]['status'] == 'Completed') $statusCount++;
					endif;
				endforeach;
			endif;
		endif;

		if ( $tab[$this->status] != null ) {
			if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
						|| is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")) :
						if($packageNumber) :
							if($packageNumber == $statusCount ) :
								$post_object = get_post( $this->order );
								if ( $post_object AND in_array( $post_object->post_type, wc_get_order_types(), true ) ) :
									$wc_order = new WC_Order( $this->order );
									$wc_order->set_status($tab[$this->status]);
									$this->result = $wc_order->save();
								endif;
							endif;
						else :
							if(class_exists( 'WC_Order' )):
								$post_object = get_post( $this->order );
								if ( $post_object AND in_array( $post_object->post_type, wc_get_order_types(), true ) ) {
									//ORDER ID EXIST
									$wc_order = new WC_Order( $this->order );
									$wc_order->set_status($tab[$this->status]);
									$this->result = $wc_order->save();
								}
							endif;
						endif;
			else :
				if($woocommerce_api_shipAdv and class_exists( 'WC_Order' )) {
					//TEST IF ORDER EXIST
					$post_object = get_post( $this->order );
					if ( $post_object AND in_array( $post_object->post_type, wc_get_order_types(), true ) ) {
						//ORDER ID EXIST
						$wc_order = new WC_Order( $this->order );
						$wc_order->set_status($tab[$this->status]);
						$this->result = $wc_order->save();
					}
				}
				else {
					//add a note
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$select = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."wc_orders WHERE id = ".$this->order, ARRAY_A);
					} else {
						$select = $wpdb->get_row("SELECT * FROM ".$wpdb->posts." WHERE ID = ".$this->order, ARRAY_A);$select = $wpdb->get_row("SELECT * FROM ".$wpdb->posts." WHERE ID = ".$this->order, ARRAY_A);
					}

					if($select) :
						$actualStatus = str_replace("wc-", "",$select['post_status']);
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$this->result = $wpdb->update( $wpdb->prefix."wc_orders",
									array(
											'status' => 'wc-' . $tab[$this->status]
										),
									array( 'id' => $this->order )
							);
						} else {
							$this->result = $wpdb->update( $wpdb->posts,
									array(
											'post_status' => 'wc-' . $tab[$this->status]
										),
									array( 'ID' => $this->order )
							);
						}

						if($this->result) {
							$note = sprintf(__("Order %s changed from %s to %s", "shipworks-connector"),$this->orderIdSent, ucwords($actualStatus),$tab[$this->status]);
							add_customer_note( $note, $this->order );
						}
					endif;
				}
				if ( is_plugin_active_custom( "shipworks-addon-vampfangs/shipworks-addon-vampfangs.php" ) ) {
					$time = strtotime($date.' UTC');
					$dateUTC = date("Y-m-d H:i:s", $time);
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$this->result2 = $wpdb->update( $wpdb->posts,
							array(
									'date_updated_gmt' =>  $dateUTC
								),
							array( 'id' => $this->order )
						);
					} else {
						$this->result2 = $wpdb->update( $wpdb->prefix."wc_orders",
							array(
									'post_modified_gmt' =>  $dateUTC
								),
							array( 'ID' => $this->order )
						);
					}

				}
			endif;
		}

		if ( $this->result === false ) {
			$this->code = 'ERR004';
			$this->description = __("The Status coudn't be update in the database", "shipworks-connector");
		} elseif ( $this->comment != '' ) {
			add_private_note( $this->comment, $this->order );
		}
	}

	protected function setInfoWPeCommerce() {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php';
		global $wpdb;
		$status = $this->status;
		$table = $wpdb->prefix . "wpsc_purchase_logs";
		$this->result = $wpdb->update( $table,
				array(
						'processed' => $status
					),
				array( 'id' => $this->order )
		);
		if ( $this->result === false ) {
			$this->code = 'ERR004';
			$this->description = __("The Status coudn't be update in the database", "shipworks-connector");
		}
	}

	protected function setInfoCart66() {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/cart66/functionsCart66.php';
		global $wpdb;
		$table = $wpdb->prefix . "cart66_cart_settings";
		$rows = $wpdb->get_results("SELECT * FROM " . $table, ARRAY_A);
		foreach( $rows as $line ) {
			if( $line['key'] == 'status_options' ) {
				$val = $line['value'];
			}
		}
		$status = preg_split("/,/", $val);
		if( $val != null ) {
			$tab = Array();
			$i = 1;
			foreach( $status as $stat ) {
				$tab[$i] = trim( $stat );
				$i++;
			}
			$status = $tab[$this->status];
		} else {
			$status = getStatusName( $this->status );
		}
		$table = $wpdb->prefix . "cart66_orders";
		$this->result = $wpdb->update( $table,
				array(
						'status' => $status
					),
				array( 'id' => $this->order )
		);
		if ( $this->result === false ) {
			$this->code = 'ERR004';
			$this->description = __("The Status coudn't be update in the database", "shipworks-connector");
		}
	}

	protected function filtre() {

		// Cas ou la valeur était deja a jour sur woocommerce mais pas sur shipworks, 0 lignes ont été updatées
		if ( $this->result === 0 ) {
				$this->result = true;
		}
		$this->description = filtreString( $this->description );
		$this->code = filtreString( $this->code );
		$this->comment = filtreString( $this->comment );
	}

	public function getResult() {
		return $this->result;
	}

	public function getCode() {
		return $this->code;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getComment() {
		return $this->comment;
	}
	public function get_status(){
		return $this->status;
	}
}
