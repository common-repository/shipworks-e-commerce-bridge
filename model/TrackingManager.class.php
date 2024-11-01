<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TrackingManager_shipAdv {

	protected $software;

	protected $dateShipping;

	protected $carrier;

	protected $order;

	protected $orderIdSent;

	protected $tracking;

	protected $result;

	protected $code;

	protected $description;



	protected $ups_pattern = '/(\b\d{9}\b)|(\b1Z\d+\b)/';

	protected $usps_pattern = "/^\D{2}\d{9}\D{2}$|^9\d{15,21}$/";

	protected $fedex_pattern = '/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/';

	public function __construct( $software, $date, $carrier, $order = '', $tracking = '') {

		$this->software = $software;

		$this->dateShipping = $date;

		$this->carrier = $carrier;

		$this->order = $order;
		$this->orderIdSent = $order;

		$this->tracking = $tracking;

        $this->setInformations();

    }



	protected function setInformations() {

		$order = $this->order;

		$tracking = $this->tracking;

		$split = explode( '.' , $this->software->getVersion() );

		// Pour tous les e-commerce on regarde d'abord si la communication a été bonne

		if ($order == '' or $tracking == '') {

			if ($order == '' and $tracking == '') {

				$this->result = false;

				$this->code = 'ERR001';

				$this->description = __('Order and Tracking did not communicate correctly', "shipworks-connector");

			}

			else if ($order == '' and $tracking != '') {

				$this->result = false;

				$this->code = 'ERR002';

				$this->description = __('Order did not communicate correctly', "shipworks-connector");

			}

			else if ($order != '' and $tracking == '') {

				$this->result = false;

				$this->code = 'ERR003';

				$this->description = __('Tracking did not communicate correctly', "shipworks-connector");

			}

		}	else {

			// Cas Shopp ( on a pas de shopperpress )

			if ( 'Shopp' == $this->software->getSoftware() ) {

					$this->setInfoShopp();

			} else if ( 'WP eCommerce' == $this->software->getSoftware() ) {

					$this->setInfoWPeCommerce();

			} else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {

					$this->setInfoCart66();

			} else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {

					$this->setInfoCart66();

			} else if ( 'Woocommerce' == $this->software->getSoftware() ) {

					$this->setInfoWoocommerce();

			} else if ( 'Jigoshop' == $this->software->getSoftware() ) {

							$this->setInfoJigoshop();

				}

		}

		$this->filtre();

	}



	protected function setInfoShopp() {

		global $wpdb;

		$table = $wpdb->prefix . "shopp_purchase";

		$tracking_number = $this->tracking;



		//checking the identify shipping company

		$usps_pattern = $this->usps_pattern;

		$ups_pattern = $this->ups_pattern;

		$fedex_pattern = $this->fedex_pattern;

		$tracking_name = strtolower( $this->carrier );



		// On ne veut plus utiliser les filtres, on va directement récupérer le nom du carrier depuis la requete



		// Cheking if the order is in the database



		$row= $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "shopp_purchase WHERE id = '" . $this->order . "'" );

		if ( !$row ) {

			$this->result = false;

			$this->code = 'ERR004';

			$this->description = __('The order is not in the Database', "shipworks-connector");

		} else {

			// On sérialise le tracking

			$tracking = new stdClass;

			$tracking->tracking = $tracking_number;

			$tracking->carrier = $tracking_name;

			$tracking = serialize($tracking);



			// Check if the tracking number is already in the database or need an isert

			$rowTracking = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "shopp_meta WHERE parent  = '" . $this->order . "' and context = 'purchase' and name = 'shipped'" );

			$time = strtotime("now");

			$dateInLocal = date("Y-m-d H:i:s", $time);



			if ( $rowTracking === 'true' ) { // On update

				$table = $wpdb->prefix . "shopp_meta";

				$this->result = $wpdb->update( $table,

						array(

								'value' => $tracking,

								'modified' => $dateInLocal,

								'type' => 'event'

							),

						array( 	'parent' => $this->order,

								'context' => 'purchase',

								'name' => 'shipped'

						 )

				);



				if ( $this->result === false ) {

					$this->code = 'ERR009';

					$this->description = __("The tracking number coudn't be update in the database", "shipworks-connector");

				}

			} else { // On insert

				$table = $wpdb->prefix . "shopp_meta";

				$this->result = $wpdb->insert( $table,

						array(

								'parent' => $this->order,

								'context' => 'purchase',

								'name' => 'shipped',

								'type' => 'event',

								'value' => $tracking,

								'created' => $dateInLocal,

								'modified' => $dateInLocal

							)

						);



				if ( $this->result === false ) {

					$this->code = 'ERR010';

					$this->description = __("The tracking number coudn't be insert in the database", "shipworks-connector");

				}

			}

		}

	}



	protected function setInfoWPeCommerce() {

		global $wpdb;

		$table = $wpdb->prefix . "wpsc_purchase_logs";

		$tracking_number = $this->tracking;



		//checking the identify shipping company

		$usps_pattern = $this->usps_pattern;

		$ups_pattern = $this->ups_pattern;

		$fedex_pattern = $this->fedex_pattern;

		$tracking_name = strtolower( $this->carrier );



		// On ne veut plus utiliser les filtres, on va directement récupérer le nom du carrier depuis la requete



		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);

		if ( !$row ) {

			$this->result = false;

			$this->code = 'ERR004';

			$this->description = __('The order is not in the Database', "shipworks-connector");

		} else {

			$dateModify = date("m-d-Y", strtotime($this->dateShipping));

			$this->result = $wpdb->update( $table,

						array(

								'track_id' => $tracking_number,

								'notes' => $row['notes'] . '&#10;' . sprintf(__("Your order %s was shipped on %s via %s. Tracking number is %s", "shipworks-connector"), $this->orderIdSent, $dateModify, $this->carrier, $this->tracking)

							),

						array( 	'id' => $this->order

						 )

			);

			if ( $this->result === false ) {

				$this->code = 'ERR010';

				$this->description = __("The tracking number coudn't be insert in the database", "shipworks-connector");

			}

		}



	}



	protected function setInfoCart66() {

		global $wpdb;

		$table = $wpdb->prefix . "cart66_orders";

		$tracking_number = $this->tracking;



		//checking the identify shipping company

		$usps_pattern = $this->usps_pattern;

		$ups_pattern = $this->ups_pattern;

		$fedex_pattern = $this->fedex_pattern;

		$tracking_name = strtolower( $this->carrier );



		// On ne veut plus utiliser les filtres, on va directement récupérer le nom du carrier depuis la requete



		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);

		if ( !$row ) {

			$this->result = false;

			$this->code = 'ERR004';

			$this->description = __('The order is not in the Database', "shipworks-connector");

		} else {

			$dateModify = date("m-d-Y", strtotime($this->dateShipping));

			$this->result = $wpdb->update( $table,

						array(

								'tracking_number' => $tracking_number ,

								'notes' => $row['notes'] . '&#10;' . sprintf(__("Your order %s was shipped on %s via %s. Tracking number is %s", "shipworks-connector"), $this->orderIdSent, $dateModify, $this->carrier, $this->tracking)

							),

						array( 	'id' => $this->order

						 )

			);

			if ( $this->result === false ) {

				$this->code = 'ERR010';

				$this->description = __("The tracking number coudn't be insert in the database.", "shipworks-connector");

			}

		}

	}



	protected function setInfoWoocommerce() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php');

		$time = strtotime($this->dateShipping.' UTC');

		$this->dateShipping = date("y-m-d", $time);

		global $wpdb;

		$table = $wpdb->posts;

		$tracking_number = $this->tracking;



		//checking the identify shipping company

		$usps_pattern = $this->usps_pattern;

		$ups_pattern = $this->ups_pattern;

		$fedex_pattern = $this->fedex_pattern;

		$tracking_name = strtolower( $this->carrier );



		//REMOVE PREFIX AND POSTFIX
		$pref = get_option("woocommerce_order_number_prefix");
		$orderId = str_replace ($pref, "", $this->order);
		if(strpos($orderId, "-") !== false) {$this->order = substr($orderId, 0, strpos($orderId, "-"));}
		else {$this->order = $orderId; }



		if ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php")

				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php")
					 ||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php") ) {
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix."wc_orders_meta WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
						}
						else {
							$row = $wpdb->get_row("SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = '_order_number' and meta_value = " . $this->order, ARRAY_A);
						}
			if ( $row == null ) {

				$this->result = false;

				$this->code = 'ERR004';

				$this->description = __('The order is not in the Database', "shipworks-connector");

			} else {
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					$this->order = $row['order_id'];
				} else {
					$this->order = $row['post_id'];
				}

			}

		}



		// Cheking if the order is in the database
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$row= $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."wc_orders WHERE id = " . $this->order, ARRAY_A);
		} else {
			$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);
		}

		if ( !$row ) {

			$this->result = false;

			$this->code = 'ERR004';

			$this->description = __('The order is not in the Database', "shipworks-connector");

		} else {

			$dateModify = date("m-d-Y", strtotime($this->dateShipping));

			$note = sprintf(__("Your order %s was shipped on %s via %s. Tracking number is %s.", "shipworks-connector"), $this->orderIdSent, $dateModify, $this->carrier, $this->tracking);
			$settingsAdv = new Settings_shipAdv();
			$woocommerce_api_shipAdv = $settingsAdv->getWoocommerce_Api();
			// On regarde si le plugin Tracking Shipments est actif ou pas, auquel cas on doit insérer le tracking number à un autre endroit que dans les notes

			if( is_plugin_active_custom( "woocommerce-shipment-tracking/shipment-tracking.php" )
					or is_plugin_active_custom( "woocommerce-shipment-tracking/woocommerce-shipment-tracking.php" ) ) {
				$this->carrier = strtolower($this->carrier);
				$table = $wpdb->postmeta;
				//get version

				$versionTracking = shipAdv_getTrackingVersion();
				if (version_compare($versionTracking, '1.3.0', '<')) { //VERSION PLUGIN SHIPMENT TRACKING < 1.3.0
				//if($versionTracking < 130) { //VERSION PLUGIN SHIPMENT TRACKING < 1.3.0

					$this->result = $wpdb->replace( $table,

							array( 	'post_id' => $this->order,

									'meta_key' => '_tracking_number',

									'meta_value' => $tracking_number

								)

					);

					$wpdb->replace( $table,

							array( 	'post_id' => $this->order,

									'meta_key' => '_tracking_provider',

									'meta_value' => $this->carrier

								)

					);

					$wpdb->replace( $table,

							array( 	'post_id' => $this->order,

									'meta_key' => '_custom_tracking_provider',

									'meta_value' => $this->carrier

								)

					);

					$wpdb->replace( $table,

							array( 	'post_id' => $this->order,

									'meta_key' => '_date_shipped',

									'meta_value' => strtotime($this->dateShipping)

								)

					);
					if ( $this->result === false ) :
						$this->code = 'ERR010';
						$this->description = "The tracking number coudn't be insert in the database.";
					endif;
				}elseif (version_compare($versionTracking, '1.6.3', '<=')) { //VERSION PLUGIN SHIPMENT TRACKING <= 1.6.3
				//}elseif($versionTracking <= 163) { //VERSION PLUGIN SHIPMENT TRACKING <= 1.6.3

					$st = WC_Shipment_Tracking_Actions::get_instance();

					$provider_list = $st->get_providers();

					$custom = true;

					foreach ( $provider_list as $country ) {

						foreach ( $country as $provider_code => $url ) {

							if ( sanitize_title( $this->carrier ) === sanitize_title( $provider_code ) ) {

								$provider = sanitize_title( $provider_code );

								$custom = false;

								break;

							}

						}

						if ( ! $custom ) {

							break;

						}

					}

					$meta = array();



					if ( $custom ) {

						$meta['tracking_provider'] = '';

						$meta['tracking_number'] = $tracking_number;

						$meta['date_shipped'] = strtotime($this->dateShipping);

						$meta['custom_tracking_provider'] = $this->carrier;

						$meta['custom_tracking_link'] = '';

						$meta['tracking_id'] = '';

					}

					else {

						$meta['tracking_provider'] = $this->carrier;

						$meta['tracking_number'] = $tracking_number;

						$meta['date_shipped'] = strtotime($this->dateShipping);

						$meta['custom_tracking_provider'] = '';

						$meta['custom_tracking_link'] = '';

						$meta['tracking_id'] = '';

					}

					$this->result = $wpdb->replace( $table,

							array( 	'post_id' => $this->order,

									'meta_key' => '_wc_shipment_tracking_items',

									'meta_value' => serialize(array($meta))

								)

					);

				}else {
					$st = WC_Shipment_Tracking_Actions::get_instance();

					$provider_list = $st->get_providers();
					$custom = true;
					foreach ( $provider_list as $country ) {

						foreach ( $country as $provider_code => $url ) {

							if ( sanitize_title( $this->carrier ) === sanitize_title( $provider_code ) ) {

								$provider = sanitize_title( $provider_code );

								$custom = false;

								break;

							}

						}

						if ( ! $custom ) {

							break;

						}

					}

					if ( $custom ) {
						$args = array(
							'tracking_provider'        => '',
							'custom_tracking_provider' => $this->carrier,
							'custom_tracking_link'     => '',
							'tracking_number'          => $this->tracking,
							'date_shipped'             => strtotime($this->dateShipping),
						);
					}

					else {
						$args = array(
							'tracking_provider'        => $provider,
							'custom_tracking_provider' => '',
							'custom_tracking_link'     => '',
							'tracking_number'          => $this->tracking,
							'date_shipped'             => strtotime($this->dateShipping),
						);

					}
					$st->add_tracking_item( $this->order, $args );
					/*
					if($woocommerce_api_shipAdv and class_exists( 'WC_Shipment_Tracking_Actions' )) {
						$st->add_tracking_item( $this->order, $args );
					}
					else {
						//check if row exist
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$ifExist= $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."wc_orders_meta WHERE order_id = " . $this->order, ARRAY_A);
						} else {
							$ifExist = $wpdb->get_row("SELECT * FROM ".$table." WHERE post_id = ".$this->order." AND meta_key = '_wc_shipment_tracking_items'", ARRAY_A);
						}
						if(!$ifExist) {
							if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
								$this->result = $wpdb->update( $wpdb->prefix."wc_orders_meta",
										array( 	"order_id" => $this->order,
												'meta_key' => '_wc_shipment_tracking_items',
												'meta_value' => serialize(array($args))
											),
										array(
											'%d',
											'%s',
											'%s'
										)
							);
							} else {
								$this->result = $wpdb->update( $table,
										array( 	"post_id" => $this->order,
												'meta_key' => '_wc_shipment_tracking_items',
												'meta_value' => serialize(array($args))
											),
										array(
											'%d',
											'%s',
											'%s'
										)
								);
							}
						} else {

							$metaValueArray = unserialize($ifExist['meta_value']);

							$mergeArray = array_merge($metaValueArray, array($args));
							$serializeArray = serialize($mergeArray);

							if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
								$this->result = $wpdb->update( $wpdb->prefix."wc_orders_meta",
										array('meta_value' => $serializeArray),
										array("order_id" => $this->order,
												'meta_key' => '_wc_shipment_tracking_items'),
										array('%s'),
										array('%d',
													'%s')
								);
							} else{
								$this->result = $wpdb->update( $table,
										array('meta_value' => $serializeArray),
										array("post_id" => $this->order,
												'meta_key' => '_wc_shipment_tracking_items'),
										array('%s'),
										array('%d',
													'%s')
								);
							}
						}
					}
					*/
				}
			} else {
				if (function_exists( 'ast_insert_tracking_number' )) :
						$this->carrier = strtolower($this->carrier);
						ast_insert_tracking_number( $this->order,
																				wc_clean( $this->tracking ),
																				$this->carrier,
																				wc_clean( $this->dateShipping),
																				1 );

				elseif($woocommerce_api_shipAdv) :
					$order = wc_get_order( $this->order );
					if(!$order) :
						$this->result = false;
						$this->code = 'ERR004';
						$this->description = sprintf(__("The order %s is not in the Database", "shipworks-connector"), $this->order);
					else :
						$note = sprintf(__("Your order %s was shipped on %s via %s. Tracking number is %s.", "shipworks-connector"), $this->orderIdSent, $dateModify, $this->carrier, $this->tracking);
						$order->add_order_note( $note, 1);
						if($order->save()) $this->result = true;
					endif;
				else :
					$this->result = add_customer_note( $note, $this->order );
				endif;

			}

			if (is_plugin_active_custom('shipworks-plugin-addon/shipworks-plugin-addon.php')) {

				$tableAddon =$wpdb->prefix . "woocommerce_order_itemmeta";

				$this->result = $wpdb->replace( $tableAddon,
					array( 	'order_item_id' => $this->order,
							'meta_key' => '_shipwork_shipdate',
							'meta_value' => strtotime($this->dateShipping)
						));
			}
			if (is_plugin_active_custom('aftership-woocommerce-tracking/aftership.php')) {
				//CHECK IF AFTERSHIP HAS COURRIER RECORDED
				if(strtolower($this->carrier) != 'other'):
					$optionResult = get_option('aftership_option_name');
					if($optionResult) :
						$couriers = $optionResult['couriers'];
						$couriersArray = explode( ',' , $couriers);
						$courierFound = false;

						foreach( $couriersArray as $courier) :
							if($courier == strtolower($this->carrier)) $courierFound = true;
						endforeach;
						if($courierFound == false) { //add courrier
							$optionResult['couriers'] .= ','.strtolower($this->carrier);
							update_option('aftership_option_name', $optionResult);
						}
					endif;
				endif;
				//INSERT DATA SHIPMENT
				$tableAfterShip = $wpdb->postmeta;
				$resultProvider = $wpdb->replace( $tableAfterShip,
					array( 	'post_id' => $this->order,
							'meta_key' => '_aftership_tracking_provider_name',
							'meta_value' => $this->carrier
						));
				$resultTracking = $wpdb->replace( $tableAfterShip,
					array( 	'post_id' => $this->order,
							'meta_key' => '_aftership_tracking_number',
							'meta_value' => $this->tracking
						));
				if($resultProvider == true and $resultTracking == true ) $this->result = true;
			}
		}
	}



	protected function setInfoJigoshop() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php' );

		$time = strtotime($this->dateShipping.' UTC');

		$this->dateShipping = date("y-m-d", $time);

		global $wpdb;

		$table = $wpdb->posts;

		$tracking_number = $this->tracking;



		//checking the identify shipping company

		$usps_pattern = $this->usps_pattern;

		$ups_pattern = $this->ups_pattern;

		$fedex_pattern = $this->fedex_pattern;

		$tracking_name = strtolower( $this->carrier );

		// Cheking if the order is in the database

		$row= $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $this->order, ARRAY_A);

		if ( !$row ) {

			$this->result = false;

			$this->code = 'ERR004';

			$this->description = __('The order is not in the Database', "shipworks-connector");

		} else {



			$dateModify = date("m-d-Y", strtotime($this->dateShipping));

			$note = sprintf(__("Your order %s was shipped on %s via %s. Tracking number is %s.", "shipworks-connector"), $this->orderIdSent, $dateModify, $this->carrier, $this->tracking);

			$this->result = add_note( $note, $this->order );



			if ( $this->result === false ) {

				$this->code = 'ERR010';

				$this->description = __("The tracking number coudn't be insert in the database.", "shipworks-connector");

			}

		}

	}



	protected function filtre() {

		$this->description = filtreString( $this->description );

		$this->code = filtreString( $this->code );

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

	public function get_order_id() {
		return $this->order;
	}
	public function get_tracking(){
		return $this->tracking;
	}
}
