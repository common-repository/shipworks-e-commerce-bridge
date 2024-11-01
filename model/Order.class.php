<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Order_shipAdv {
	protected $software;
	protected $date;
	protected $row;

	protected $id_order;
	protected $id_order_main;
	protected $id_order_post;
	protected $id_order_pre;
	protected $id_parentID;

	protected $createdDate;
	protected $modifiedDate;
	protected $shipoption;
	protected $status;
	protected $firstname;
	protected $middlename;
	protected $lastname;
	protected $customerID;
	protected $company;
	protected $shipCompany;
	protected $address;
	protected $street2;
	protected $street3;
	protected $xaddress;
	protected $city;
	protected $state;
	protected $postcode;
	protected $country;
	protected $residential = 'true';
	protected $phone;
	protected $email;
	protected $fax;
	protected $website;
	protected $shipfirstname;
	protected $shiplastname;
	protected $shipaddress;
	protected $shipstreet2;
	protected $shipxaddress;
	protected $shipcity;
	protected $shipstate;
	protected $shippostcode;
	protected $shipcountry;
	protected $shipPhone;
	protected $cardtype;
	protected $rewardPoint;
	protected $redeemedPoint;
	protected $shipping_date;
	protected $delivery_date;
	protected $location;

	protected $freight;
	protected $tax;
	protected $discount;
	protected $fee;

	protected $_purchase_order_number;
	protected $order_name;
	protected $order_phone;
	protected $order_salesman;

	protected $coupons = Array();
	protected $privateNotes = Array();
	protected $customerMessage = Array();
	protected $discountMessage = Array();

	protected $items = Array();
	public function __construct( $software, $date, $row, $id_parentID = false, $id_order_post = false ) {
		$this->software = $software;
		$this->row = $row;
		$this->date = $date;
		if($id_parentID) $this->id_parentID = $id_parentID;
		if($id_order_post) $this->id_order_post = $id_order_post;
    $this->setInformations();
  }

	public function getNumber() {
		return $this->number;
	}

	protected function setInformations() {
		$split = explode( '.' , $this->software->getVersion() );
		global $wpdb;
		// Cas Shopperpress
		if ( 'shopperpress' == $this->software->getSoftware() ) {
			$this->setInfoShopperpress();
		}// Cas Shopp
		else if ( 'Shopp' == $this->software->getSoftware() ) {
			$this->setInfoShopp();
		} // Cas Woocommerce
		else if ( 'Woocommerce' == $this->software->getSoftware() ) {
			$this->setInfoWoocommerce();
		} // Cas WP eCommerce
		else if ( 'WP eCommerce' == $this->software->getSoftware() ) {
			$this->setInfoWPeCommerce();
		} // Cas Cart66 Lite
		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {
			$this->setInfoCart66();
		} // Cas Cart66 Pro
		else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {
			$this->setInfoCart66();
		}// Cas Jigoshop
		else if ( 'Jigoshop' == $this->software->getSoftware() ) {
			$this->setInfoJigoshop();
		}
		// On filtre les champs
		$this->filtre();
	}

	protected function filtre() {
		// On ne filtre pas l'id
		$this->createdDate = filtreString( $this->createdDate );
		$this->modifiedDate = filtreString( $this->modifiedDate );
		$this->status = filtreEntier( $this->status );
		$this->shipoption = filtreString( $this->shipoption );
		$this->firstname =  filtreString( $this->firstname );
		$this->middlename = filtreString( $this->middlename );
		$this->lastname =  filtreString( $this->lastname );
		$this->company =  filtreString( $this->company );
		$this->shipCompany =  filtreString( $this->shipCompany );
		$this->address =  filtreString( $this->address );
		$this->street2 = filtreString( $this->street2 );
		$this->street3 = filtreString( $this->street3 );
		$this->xaddress =  filtreString( $this->xaddress );
		$this->city =  filtreString( $this->city );
		$this->state =  filtreString( $this->state );
		$this->postcode =  filtreString( $this->postcode );
		$this->country =  filtreString( $this->country );
		$this->residential =  filtreString( $this->residential );
		$this->email = filtreString( $this->email );
		$this->phone =  filtreString( $this->phone );
		$this->fax = filtreString( $this->fax );
		$this->website = filtreString( $this->website );
		$this->shipfirstname = filtreString( $this->shipfirstname );
		$this->shiplastname = filtreString( $this->shiplastname );
		$this->shipaddress = filtreString( $this->shipaddress );
		$this->shipstreet2 = filtreString( $this->shipstreet2 );
		$this->shipxaddress = filtreString( $this->shipxaddress );
		$this->shipcity =  filtreString( $this->shipcity );
		$this->shipstate =  filtreString( $this->shipstate );
		$this->shippostcode = filtreString( $this->shippostcode );
		$this->shipcountry = filtreString( $this->shipcountry );
		$this->shipPhone = filtreString( $this->shipPhone );
		$this->cardtype = filtreString( $this->cardtype );

		foreach( $this->coupons as $key => $coupon ) {
			$this->coupons[$key] = filtreString( $coupon );
		}

		foreach( $this->privateNotes as $key => $note ) {
			$this->privateNotes[$key] = filtreString( $note );
		}

		$this->freight = filtreFloat( $this->freight );
		$this->tax = filtreFloat( $this->tax );
		// On met une valeur absolue car les ShipWorks veut une valeur positive pour les discount
		$this->discount = abs( filtreFloat( $this->discount ) );
		$this->fee = filtreFloat( $this->fee);
	}

	protected function setInfoShopperpress() {
		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopperpress/functionsShopperpress.php');
		$this->id_order = (int) $this->row['autoid'];
		$this->createdDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['order_date'].' '.$this->row['order_time']));
		$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['order_date'].' '.$this->row['order_time']));
		$this->status = getStatus($this->row);
		$this->firstname =  getShippingInformation($this->row,'first_name');
		$this->middlename = '';
		$this->lastname =  getShippingInformation($this->row,'last_name');
		$this->company =  getShippingInformation($this->row,'company');
		$this->address =  getShippingInformation($this->row,'address');
		$this->street2 = '';
		$this->street3 = '';
		$this->xaddress =  getShippingInformation($this->row,'address');
		$this->city =  getShippingInformation($this->row,'city');
		$this->state =  getShippingInformation($this->row,'state');
		$this->postcode =  getShippingInformation($this->row,'postcode');
		$this->country =  $this->row['order_country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';
		}
		$this->email =  getShippingInformation($this->row,'email');
		$this->phone =  getShippingInformation($this->row,'phone');
		$this->shipPhone = getShippingInformation($this->row,'phone');
		$this->fax = '';
		$this->website = '';
		$this->shipfirstname =  $this->firstname;
		$this->shiplastname =  $this->lastname;
		$this->shipaddress =  $this->address;
		$this->shipxaddress =  '';
		$this->shipcity =  $this->city;
		$this->shipstate =  $this->state;
		$this->shippostcode =  $this->postcode;
		$this->shipcountry =  $this->country;
		$this->cardtype =  '';
		if (requestedShippingAddress($this->row)) {
			$this->shipfirstname =  getRequestedShippingInformation($this->row,'first_name');
			$this->shiplastname = getRequestedShippingInformation($this->row,'last_name');
			$this->shipaddress =  getRequestedShippingInformation($this->row,'address');
			$this->shipcity =  getRequestedShippingInformation($this->row,'city');
			$this->shipstate =  getRequestedShippingInformation($this->row,'state');
			$this->shippostcode =  getRequestedShippingInformation($this->row,'postcode');
			$this->shipcountry =  getRequestedShippingInformation($this->row,'country');
		}

		$this->freight = $this->row['order_shipping'];
		$this->tax = $this->row['order_tax'];
		$this->discount = $this->row['order_coupon'];

		for($k = 1; $k <= getItemQuantity($this->row);$k ++){
			array_push($this->items,new Item_shipAdv($this->software, $this->date,$this->row,$k));
		}
	}

	protected function setInfoShopp() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['created']));
		$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['modified']));
		$this->shipoption = $this->row['shipoption'];
		$this->status = $this->row['status'];
		$this->firstname = $this->row['firstname'];
		$this->middlename = '';
		$this->lastname = $this->row['lastname'];
		$this->company = $this->row['company'];
		$this->address = $this->row['address'];
		$this->xaddress = $this->row['xaddress'];
		$this->street2 = $this->row['xaddress']; // add to correct no address line 2 shipping on shopp
		$this->street3 = '';
		$this->city = $this->row['city'];
		$this->state = $this->row['state'];
		$this->postcode = $this->row['postcode'];
		$this->country = $this->row['country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';
		}
		$this->phone = $this->row['phone'];
		$this->shipPhone = $this->row['phone'];
		$this->email = $this->row['email'];
		$this->fax = '';
		$this->website = '';
		//if ( empty( $this->row['shipxaddress'] ) ) {

		$parts = explode(" ", $this->row['shipname']);
		$lastname = array_pop($parts);
		$firstname = implode(" ", $parts);
		$this->shipfirstname = $firstname;
		$this->shiplastname = $lastname;

		$this->shipaddress = $this->row['shipaddress'];
		$this->shipxaddress = $this->row['shipxaddress'];
		$this->shipstreet2 = $this->row['shipxaddress']; // add to correct no address line 2 billing on shopp

		$this->shipcity = $this->row['shipcity'];
		$this->shipstate = $this->row['shipstate'];
		$this->shippostcode = $this->row['shippostcode'];
		$this->shipcountry = $this->row['shipcountry'];
		$this->cardtype = $this->row['cardtype'];

		$this->freight = $this->row['freight']; // Shipping Fee
		$this->tax = $this->row['tax']; //Tax Fee
		$this->discount = $this->row['discount']; // Discount
		$this->fees = $this->row['fees']; // Add Fee

		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."shopp_purchase AS p LEFT JOIN ". $wpdb->prefix ."shopp_purchased AS ped ON ped.purchase = p.id WHERE p.modified > '" . $dateInLocal . "' and p.id='" . $this->id_order . "' order by p.id"
						, ARRAY_A);
		for($k = 0; $k < count( $rows );$k ++){
			// On ne veut pas prendre en compte les item downloadable
			if ( !($rows[$k]["type"] == "Download") ) {
				array_push($this->items,new Item_shipAdv($this->software, $this->date,$rows[$k]));

				if ( $rows[$k]['addons'] == 'yes' ) {
					// On ajoute les Addons
					global $wpdb;
					$table = $wpdb->prefix . "shopp_meta";
					$addons = $wpdb->get_results("SELECT * FROM " . $table . " WHERE parent = " . $rows[$k]['id'] . " and type = 'addon'" , ARRAY_A);
					foreach( $addons as $addon ) {
						$addon['product'] = $rows[$k]['product'];
						$addon['quantity'] = $rows[$k]['quantity'];
						array_push($this->items,new Item_shipAdv($this->software, $this->date,$addon));
					}
				}
			}
		}

		// Ajout des coupons
		if ( $this->row['promos'] != null ) {
			$coupons = getCoupons( $this->row );
			foreach( $coupons as $coupon ) {
				/*var_dump( $coupon );*/
				array_push( $this->coupons, __('Coupon: ', "shipworks-connector") . $coupon );
			}
		}

		// Ajout des notes
		if ( getNotes( $this->row['id'] ) != null ) {
			$notes = getNotes( $this->row['id'] );
			foreach( $notes as $note ) {
				$content = unserialize( $note['value'] );

				array_push( $this->privateNotes, $content->message );
			}
		}

	}

	protected function setInfoWoocommerce() {

		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php';
		$noteSent = false;
		$this->status = getStatus( $this->software, $this->row );
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			//billing information
			$billing_address = getAddresses($this->row, 'billing');
			if($billing_address) {
				$this->firstname =  $billing_address['first_name'];
				$this->middlename = '';
				$this->lastname = $billing_address['last_name'];
				$this->company = $billing_address['company'];
				$this->address = $billing_address['address_1'];
				$this->xaddress = '';
				$this->street2 = $billing_address['address_2'];
				$this->street3 = '';
				$this->city = $billing_address['city'];
				$this->state = $billing_address['state'];
				$this->postcode = $billing_address['postcode'];
				$this->country = $billing_address['country'];
				$this->phone = $billing_address['phone'];
				$this->email = $billing_address['email'];
			} else{
				$this->firstname =  '';
				$this->middlename = '';
				$this->lastname = '';
				$this->company = '';
				$this->address = '';
				$this->xaddress = '';
				$this->street2 = '';
				$this->street3 = '';
				$this->city = '';
				$this->state = '';
				$this->postcode = '';
				$this->country = '';
				$this->phone = '';
				$this->email = '';
			}
			//shipping information
			$shipping_address = getAddresses($this->row, 'shipping');
			if($shipping_address) {
				$this->shipfirstname = $shipping_address['first_name'];
				$this->shiplastname = $shipping_address['last_name'];
				$this->shipCompany = $shipping_address['company'];
				$this->shipaddress = $shipping_address['address_1'];
				$this->shipstreet2 = $shipping_address['address_2'];
				$this->shipxaddress = $shipping_address['address_1'];
				$this->shipcity = $shipping_address['city'];
				$this->shipstate = $shipping_address['state'];
				$this->shippostcode = $shipping_address['postcode'];
				$this->shipcountry = $shipping_address['country'];
				$this->shipPhone = $shipping_address['phone'];
			} else {
				$this->shipfirstname = '';
				$this->shiplastname = '';
				$this->shipCompany = '';
				$this->shipaddress = '';
				$this->shipstreet2 = '';
				$this->shipxaddress = '';
				$this->shipcity = '';
				$this->shipstate = '';
				$this->shippostcode = '';
				$this->shipcountry = '';
				$this->shipPhone = '';
			}
			//other information from order table
			$this->customerID = getInfoOrder($this->row, 'customer_id');
			$this->cardtype = getInfoOrder( $this->row, 'payment_method_title' );
			//other information from order_operational_data table
			$this->freight = (float)getInfoOrderOperational( $this->row, 'shipping_total_amount' ); // Shipping Fee
			$this->tax = ((float)getInfoOrderOperational( $this->row, 'shipping_tax_amount' ))+((float)getInfoOrder( $this->row, 'tax_amount' )); //Tax Fee
			$this->id_order = $this->row['id'];
			$this->id_order_main = $this->row['id'];
		}else {
			$this->firstname =  getInformation( $this->row, '_billing_first_name' );
			$this->middlename = '';
			$this->lastname = getInformation( $this->row, '_billing_last_name' );
			$this->customerID = getInformation( $this->row, '_customer_user' );
			$this->company = getInformation( $this->row, '_billing_company' );
			$this->address = getInformation( $this->row, '_billing_address_1' );
			$this->xaddress = '';
			$this->street2 = getInformation( $this->row, '_billing_address_2' );
			$this->street3 = '';
			$this->city = getInformation( $this->row, '_billing_city' );
			$this->state = getInformation( $this->row, '_billing_state' );
			$this->postcode = getInformation( $this->row, '_billing_postcode' );
			$this->country = getInformation( $this->row, '_billing_country' );
			$this->phone = getInformation( $this->row, '_billing_phone' );
			$this->shipPhone = getInformation( $this->row, '_billing_phone' );
			$this->email = getInformation( $this->row, '_billing_email' );
			$this->shipfirstname = getInformation( $this->row, '_shipping_first_name' );
			$this->shiplastname = getInformation( $this->row, '_shipping_last_name' );
			$this->shipCompany = getInformation( $this->row, '_shipping_company' );
			$this->shipaddress = getInformation( $this->row, '_shipping_address_1' );
			$this->shipstreet2 = getInformation( $this->row, '_shipping_address_2' );
			$this->shipxaddress = getInformation( $this->row, '_shipping_address_1' );
			$this->shipcity = getInformation( $this->row, '_shipping_city' );
			$this->shipstate = getInformation( $this->row, '_shipping_state' );
			$this->shippostcode = getInformation( $this->row, '_shipping_postcode' );
			$this->shipcountry = getInformation( $this->row, '_shipping_country' );
			$this->cardtype = getInformation( $this->row, '_payment_method_title' );
			$this->freight = getInformation( $this->row, '_order_shipping' ); // Shipping Fee
			$this->tax = ((float)getInformation( $this->row, '_order_tax' ))+((float)getInformation( $this->row, '_order_shipping_tax' )); //Tax Fee
			$this->id_order = $this->row['ID'];
			$this->id_order_main = $this->row['ID'];
			$this->shipoption = getInformation( $this->row, '_shipping_method_title' );
		}
		if (  $this->company != '' ) $this->residential = 'false';
		$this->fax = '' ;
		$this->website = '';

		if ( is_plugin_active_custom( "woocommerce-order-delivery/woocommerce-order-delivery.php")) :
			$order = wc_get_order($this->id_order);
			$this->shipping_date = $order->get_meta('_shipping_date');
			$this->delivery_date = $order->get_meta('_delivery_date');
		endif;
		//addon Direct Native Plants
		if (is_plugin_active_custom( "shipworks-addon-direct-native-plants/shipworks-addon-direct-native-plants.php")) :
			$order = wc_get_order($this->id_order);
			$this->location = $order->get_meta('order_location');
		endif;

		if ( ( is_plugin_active_custom( "woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php")
				||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php")
					||  is_plugin_active_custom( "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php") ) ) {

			if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
			 	|| is_plugin_active_custom("woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")
				&& $this->id_parentID != 0) :
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){ $columnId = "id"; } else { $columnId = "ID";}

				$rowParent = array();
				$rowParent[$columnId] = $this->id_parentID; //1093
				$this->id_parentID = getInformation( $rowParent, '_order_number' ); //New parent ID with Sequential Order Number
				//$id_order_preToClean = getInformation( $rowParent, '_order_number_formatted' );
				//$this->id_order_pre = str_replace(getInformation( $rowParent, '_order_number' ), "", $id_order_preToClean);
				$this->id_order_pre = get_option("woocommerce_order_number_prefix");
			else :
				//$id_order_preToClean = getInformation( $this->row, '_order_number_formatted' );
				$this->id_order_pre = get_option("woocommerce_order_number_prefix");
				if(getInformation( $this->row, '_order_number' ) != NULL) {
					$this->id_order = getInformation( $this->row, '_order_number' );
				}
				//if($id_order_preToClean) {
					//$this->id_order_pre = str_replace($this->id_order, "", $id_order_preToClean);
				//}
			endif;
		}
		if(is_plugin_active_custom( "shipworks-addon-pinehurst-coins/shipworks-addon-pinehurst-coins.php")) {
			$SequentialWebtoffee = new Wt_Advanced_Order_Number();
			$this->id_order = Wt_Advanced_Order_Number_Common::get_order_meta($this->id_order, '_order_number');
		}
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$date_gmt = $this->row['date_created_gmt'];
			$date_modified_gmt = $this->row['date_updated_gmt'];
		} else {
			$date_gmt = $this->row['post_date_gmt'];
			$date_modified_gmt = $this->row['post_modified_gmt'];
		}
		if($date_gmt != "0000-00-00 00:00:00") { $this->createdDate = date("Y-m-d\TH:i:s\Z", strtotime($date_gmt)); } else { $this->createdDate = '2010-01-01T00:00:00Z'; }
		if($date_modified_gmt != "0000-00-00 00:00:00") {
			if (is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php")
					|| is_plugin_active_custom( "woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php")) :
				global $wpdb;
				if (!is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) {
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$resultPostParent = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE id ='".$this->row['parent_order_id']."' and type = 'shop_order' AND status !='draft' AND status !='auto-draft' AND status !='trash' order by date_updated_gmt ASC", ARRAY_A );
					}
					else {
						$resultPostParent = $wpdb->get_row("SELECT * FROM " . $wpdb->posts . " WHERE ID ='".$this->row['post_parent']."' and post_type = 'shop_order' AND post_status !='draft' AND post_status !='auto-draft' order by post_modified_gmt ASC", ARRAY_A );
					}
				} else {
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$resultPostParent = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix."wc_orders WHERE id ='".$this->row['parent_order_id']."' and type = 'shop_order' AND (status ='wc-completed' OR status ='wc-unshipped' OR status ='wc-backorder') order by date_updated_gmt ASC", ARRAY_A );
					} else {
						$resultPostParent = $wpdb->get_row("SELECT * FROM " . $wpdb->posts . " WHERE ID ='".$this->row['post_parent']."' and post_type = 'shop_order' AND (post_status ='wc-completed' OR post_status ='wc-unshipped' OR post_status ='wc-backorder') order by post_modified_gmt ASC", ARRAY_A );
					}
				}
				if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
					if($resultPostParent){
						$resultPostParent_updated_date = $resultPostParent['date_updated_gmt'];
						if($resultPostParent_updated_date) :
							$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($resultPostParent['date_updated_gmt']));
						else :
							$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['date_updated_gmt']));
						endif;
					} else {
						$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['date_updated_gmt']));
					}
					//CHECK IF PACKAGE SHIPPING WERE MODIFIED
					$order                  = wc_get_order( $this->row['parent_order_id'] );
					$packages               = get_post_meta( $this->row['parent_order_id'], '_wcms_packages', true );
				}
				else {
					if($resultPostParent){
						$resultPostParent_updated_date = $resultPostParent['post_modified_gmt'];
						if($resultPostParent_updated_date) :
							$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($resultPostParent['post_modified_gmt']));
						else :
							$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($date_modified_gmt));
						endif;
					} else {
						$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($date_modified_gmt));
					}
					//CHECK IF PACKAGE SHIPPING WERE MODIFIED
					$order                  = wc_get_order( $this->row['post_parent'] );
					$packages               = get_post_meta( $this->row['post_parent'], '_wcms_packages', true );
				}

				if (  $order and $packages ) : $i = 0;
					foreach ( $packages as $x => $package ) : $i++;
						if($this->id_order_post == $i) :
							//ADD SHIPPING ADDRESS PER SPLIT ORDER
							$this->shipfirstname = $package['destination']['first_name'];
							$this->shiplastname = $package['destination']['last_name'];
							$this->shipCompany = $package['destination']['company'];
							$this->shipaddress = $package['destination']['address_1'];
							$this->shipstreet2 = $package['destination']['address_2'];
							$this->shipcity = $package['destination']['city'];
							$this->shipstate = $package['destination']['state'];
							$this->shippostcode = $package['destination']['postcode'];
							$this->shipcountry = $package['destination']['country'];
							//ADD INDIVIDUAL NOTE
							array_push($this->customerMessage, $package['note'] );
							$noteSent = true;
						endif;
					endforeach;
				endif;

			else :
				$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($date_modified_gmt));
			endif;
		} else { $this->modifiedDate = '2010-01-01T00:00:00Z'; }
		//$split = explode( '.' , $this->software->getVersion() );
		//$this->shipoption = getInformation( $this->row, '_shipping_method_title' );
		if (version_compare($this->software->getVersion(), '2.1.2', '>')) { // for version > 2.1.2
			if ( null != getShippingInfo( $this->row ) ) {
				$this->shipoption = getShippingInfo( $this->row ) ;
			}
		}
		//addon Cookie Bouquets
		if (is_plugin_active_custom( "shipworks-addon-cookiebouquets/shipworks-addon-cookiebouquets.php")) :
			global $wpdb;
			if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
				$rowDelivery = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."wc_orders_meta` WHERE order_id = " . $this->id_order_main." AND (meta_key = 'Delivery Date' OR meta_key = 'Future Delivery Date')" , ARRAY_A);
			} else{
				$rowDelivery = $wpdb->get_row("SELECT * FROM `".$wpdb->postmeta."` WHERE post_id = " . $this->id_order_main." AND (meta_key = 'Delivery Date' OR meta_key = 'Future Delivery Date')" , ARRAY_A);
			}
			if ( null !== $rowDelivery ) {
				$this->shipoption = __("Future Date: ", "shipworks-connector").$rowDelivery['meta_value'];
			}
		endif;

		$smartCouponTotal = 0;
		if (is_plugin_active_custom( "woocommerce-smart-coupons/woocommerce-smart-coupons.php")) :
			$smartCoupons =  unserialize(getInformation( $this->row, 'smart_coupons_contribution' ));
			foreach($smartCoupons as $smartCoupon):
				$smartCouponTotal += $smartCoupon;
			endforeach;
		endif;
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$this->discount = ((float)getInfoOrderOperational( $this->row, 'discount_total_amount' ))+((float)$smartCouponTotal);
		} else {
			$this->discount = ((float)getInformation( $this->row, '_order_discount' ))+((float)getInformation( $this->row, '_cart_discount' ))+((float)$smartCouponTotal);
		}

		$this->fee = '';
		$settingsAdv = new Settings_shipAdv();
		$show_coupon_shipAdv = $settingsAdv->getShow_coupon();
		$show_private_message_shipAdv = $settingsAdv->getShow_private();
		$show_customer_message_shipAdv = $settingsAdv->getShow_customer_message();
		$woocommerce_api_shipAdv = $settingsAdv->getWoocommerce_Api();
		$admin_notes_restriction_shipAdv = $settingsAdv->getAdmin_notes_restriction();

		//CALCULATE Fee
		global $wpdb;
		$totalFee = 0;
		$totalShipping = 0;
		if($woocommerce_api_shipAdv and class_exists( 'WC_Order' ) ) {
			//TEST IF ORDER EXIST
			$post_object = get_post( $this->id_order_main );
			if ( $post_object AND in_array( $post_object->post_type, wc_get_order_types(), true ) ) {
				//ORDER ID EXIST
				$the_order = wc_get_order( $this->id_order_main );
				if($the_order) :
					$allFees = $the_order->get_items('fee');
					foreach( $allFees as $item_id => $item_fee ){
						$totalFee += (float)$item_fee->get_total();
					}
					$this->fee = $totalFee;

					$allShipping = $the_order->get_items('shipping');
					foreach( $allShipping as $item_id => $item_shipping ){
						if(is_numeric($item_shipping->get_total())) $totalShipping += $item_shipping->get_total();
					}
					$this->freight = $totalShipping; // Shipping Fee

				else :
					$sqlFees = $wpdb->get_results( "SELECT woim.meta_value  FROM ".$wpdb->prefix."woocommerce_order_items AS woi
								LEFT JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
								WHERE woim.meta_key = '_fee_amount' AND woi.order_item_type ='fee' AND woi.order_id ='".$this->id_order_main."'", ARRAY_A);
					$sqlShipping = $wpdb->get_results( "SELECT woim.meta_value  FROM ".$wpdb->prefix."woocommerce_order_items AS woi
											LEFT JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
											WHERE woim.meta_key = 'cost' AND woi.order_item_type ='shipping' AND woi.order_id ='".$this->id_order_main."'", ARRAY_A);
					if($sqlFees) :
						foreach($sqlFees as $fee) :
							$totalFee += floatval($fee["meta_value"]);
						endforeach;
						$this->fee = $totalFee;
					endif;
					if($sqlShipping) :
						foreach($sqlShipping as $ship) :
							$totalShipping += floatval($ship["meta_value"]);
						endforeach;
						$this->freight = $totalShipping;
					endif;
				endif;
			}
		}
		else {
			$sqlFees = $wpdb->get_results( "SELECT woim.meta_value  FROM ".$wpdb->prefix."woocommerce_order_items AS woi
						LEFT JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
						WHERE woim.meta_key = '_fee_amount' AND woi.order_item_type ='fee' AND woi.order_id ='".$this->id_order_main."'", ARRAY_A);
			$sqlShipping = $wpdb->get_results( "SELECT woim.meta_value  FROM ".$wpdb->prefix."woocommerce_order_items AS woi
												LEFT JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
												WHERE woim.meta_key = 'cost' AND woi.order_item_type ='shipping' AND woi.order_id ='".$this->id_order_main."'", ARRAY_A);

			if($sqlFees) :
				foreach($sqlFees as $fee) :
					$totalFee += floatval($fee["meta_value"]);
				endforeach;
				$this->fee = $totalFee;
			endif;
			if($sqlShipping) :
				foreach($sqlShipping as $ship) :
					$totalShipping += floatval($ship["meta_value"]);
				endforeach;
				$this->freight = $totalShipping;
			endif;
		}

		// Ajout des coupons
		if ( getCoupons( $this->row ) != null && $show_coupon_shipAdv == '1') {
			$coupons = getCoupons( $this->row );
			foreach( $coupons as $coupon ) {
				array_push($this->coupons, __('Coupon: ', "shipworks-connector") . $coupon['order_item_name']);
			}
		}

		if (is_plugin_active_custom( "shipworks-addon-anuskin/shipworks-addon-anuskin.php")) {
			$this->setRewardPoint();
			$this->setRedeemedPoint();
			$rowPoint = $wpdb->get_results( "SELECT * FROM `".$wpdb->options."`
							WHERE `option_name` = 'wc_points_rewards_redeem_points_ratio'", ARRAY_A);
			if($rowPoint) {
				$ratioPointDollars = $rowPoint[0]['option_value'];
				list( $points, $monetary_value ) = explode( ':', $ratioPointDollars);
				if( $this->rewardPoint > 0) {
					$rewardsPoints = $this->rewardPoint;
					$dollarsRewards = number_format( $rewardsPoints * ( $monetary_value / $points ), 2, '.', '' );
				}
				if($this->redeemedPoint > 0) {
					$redeemedPoints = $this->redeemedPoint;
					$dollarsRedeemeds = number_format( $redeemedPoints * ( $monetary_value / $points ), 2, '.', '' );
				}
				if($this->discount > 0){
					$discount = $this->discount;
					if($dollarsRedeemeds) $discount = $discount - $dollarsRedeemeds;
				}
				if(isset($discount)) $discountMessage .= __("Preferred Customer discount $", "shipworks-connector").number_format($discount, 2, '.', '' ).", ";
				if(isset($dollarsRedeemeds))$discountMessage .= __("Reward Dollars Spent $", "shipworks-connector").$dollarsRedeemeds.", ";
				if(isset($dollarsRewards))$discountMessage .= __("Reward Dollars Earned $", "shipworks-connector").$dollarsRewards.", ";
				if(isset($discount) or isset($dollarsRedeemeds) or isset($dollarsRewards)) {
					$discountMessage = substr($discountMessage, 0, -2);
					array_push($this->discountMessage, $discountMessage);
				}
			}
		}

		// Ajout des notes
		$notes = getOrderNotes($this->id_order_main);

		foreach ($notes as $note) {
			if (getNotePrivacy($note['comment_ID'] ) == 1) {
				if($show_coupon_shipAdv) {
					array_push($this->coupons, $note['comment_content']);
				}
			}
		}
		//ADMIN NOTES
		if($show_private_message_shipAdv) {
			if($admin_notes_restriction_shipAdv != 0 and is_numeric($admin_notes_restriction_shipAdv)) {
				if(is_array($notes)) $notes = array_splice($notes, -$admin_notes_restriction_shipAdv);
			}
			foreach ($notes as $note) {
				if (getNotePrivacy($note['comment_ID'] ) == 0) {
					array_push($this->privateNotes, $note['comment_content']);
				}
			}
		}

		if($show_customer_message_shipAdv and $noteSent == false) {
			array_push($this->customerMessage, getOrderMessage($this->id_order_main));
		}

		//Ajout les champs du custom checkout field si le plugin est la
		if ( is_plugin_active_custom( "woocommerce-checkout-field-editor/checkout-field-editor.php" ) || is_plugin_active_custom( "woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php" ) ) {
			$plugin_data = get_file_data(PLUGINS_PATH."/woocommerce-checkout-field-editor/checkout-field-editor.php", array('Version' => 'Version'), false);
			if($plugin_data['Version'] == "") $plugin_data = get_file_data(PLUGINS_PATH."/woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php", array('Version' => 'Version'), false);
			$plugin_version = $plugin_data['Version'];
			//$split_wocfe = explode( '.' , $plugin_version );
			$oldVersion_wocfe = false;
			//if($split_wocfe[0] == 1 && $split_wocfe[1] <= 5 || $split_wocfe[0] == 0) { // <= 1.5 and > 0
			if (version_compare($plugin_version, '1.5.4', '<=')) {
				$oldVersion_wocfe = true;
				//if($split_wocfe[0] == 1 && $split_wocfe[1] == 5 && $split_wocfe[2] > 4 || $split_wocfe[0] >= 2 || $split_wocfe[0] == 1 && $split_wocfe[1] > 5) $oldVersion_wocfe = false;
			}
			if($oldVersion_wocfe == true) {  // for version < 1.5.4
				$table = $wpdb->options;
				// On list les champs additionnels qui existent
				$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE option_name = 'wc_fields_additional'", ARRAY_A);
				if($row) :
					$fields = unserialize( $row["option_value"] );
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$table = $wpdb->prefix."wc_orders_meta";
					} else {
						$table = $wpdb->postmeta;
					}

					foreach( $fields as $key => $value ) {
						if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
							$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id = " . $this->id_order_main . " AND meta_key = '" . $key . "'", ARRAY_A);
						}else {
							$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $this->id_order_main . " AND meta_key = '" . $key . "'", ARRAY_A);
						}
						$value = $row["meta_value"];
						array_push($this->coupons, $value);
					}
				endif;
			}
			else { // for version >= 1.5.4
				$table = $wpdb->options;
				// On list les champs additionnels qui existent
				$rows = $wpdb->get_results("SELECT * FROM " . $table . " WHERE option_name = 'wc_fields_billing' or option_name = 'wc_fields_additional' or option_name = 'wc_fields_shipping'", ARRAY_A);
				if($rows) :
					if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
						$table = $wpdb->prefix."wc_orders_meta";
					} else {
						$table = $wpdb->postmeta;
					}
					foreach($rows as $row):
						$fields = unserialize( $row["option_value"] );
						foreach( $fields as $key => $value ) {
							if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
								$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id = " . $this->id_order_main . " AND meta_key = '" . $key . "'", ARRAY_A);
							}else {
								$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $this->id_order_main . " AND meta_key = '" . $key . "'", ARRAY_A);
							}

							$value = $row["meta_value"];
							array_push($this->coupons, $value);
						}
					endforeach;
				endif;
			}
		}
		if ( is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) :
			$order = wc_get_order($this->id_order_main);
			$this->_purchase_order_number = $order->get_meta("_purchase_order_number");
			$this->order_name = $order->get_meta("order_name");
			$this->order_phone = $order->get_meta("order_phone");
			$this->order_salesman = $order->get_meta("order_salesman");
		endif;

		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."woocommerce_order_items WHERE order_id = " . $this->id_order_main . " AND order_item_type = 'line_item'"
						, ARRAY_A);
		foreach($rows as $row) :
				array_push($this->items,new Item_shipAdv($this->software, $this->date,$row));
		endforeach;
	}

	protected function setInfoWPeCommerce() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = date("Y-m-d\TH:i:s\Z", $this->row['date'] );
		$this->modifiedDate = date("Y-m-d\TH:i:s\Z", $this->row['date'] );
		$this->shipoption = $this->row['shipping_option'];
		$this->status = $this->row['processed'];
		$this->firstname =  getInformation( $this->row, 2);
		$this->middlename = '';
		$this->lastname = getInformation( $this->row, 3 );
		/*$this->company = getInformation( $this->row, '_billing_company' );*/
		$this->address = getInformation( $this->row, 4 );
		$this->xaddress = '';
		$this->street2 = '';
		$this->street3 = '';
		$this->city = getInformation( $this->row, 5 );
		$this->state = getInformation( $this->row, 6 );
		$this->postcode = getInformation( $this->row, 8 );
		$this->country = getInformation( $this->row, 7 );
		if ( '' != $this->company ) {
			$this->residential = 'false';
		}
		$this->phone = getInformation( $this->row, 18 );
		$this->shipPhone = getInformation( $this->row, 18 );
		$this->email = getInformation( $this->row, 9 );
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = getInformation( $this->row, 11 );
		$this->shiplastname = getInformation( $this->row, 12 );
		$this->shipaddress = getInformation( $this->row, 13 );
		$this->shipstreet2 = '';
		$this->shipxaddress = '';
		$this->shipcity = getInformation( $this->row, 14 );
		$this->shipstate = getInformation( $this->row, 15 );
		$this->shippostcode = getInformation( $this->row, 17 );
		$this->shipcountry = getInformation( $this->row, 16 );
		$this->cardtype = $this->row['gateway'];

		$this->freight = $this->row['base_shipping']; // Shipping Fee
		$this->tax = $this->row['wpec_taxes_total']; //Tax Fee
		$this->discount = $this->row['discount_value']; // Discount
		$this->fees = ''; // Add Fee

		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."wpsc_cart_contents WHERE purchaseid = " . $this->row['id'] , ARRAY_A);
		for ($k = 0; $k < count( $rows );$k ++) {
				$this->freight += (float)$rows[$k]['pnp']; // On ajoute les shipp propres aux items
				/*$this->tax += (float)$rows[$k]['tax_charged'];*/ // On ajoute pas les taxes si elles sont inclues dans le prix de l'article
				array_push($this->items,new Item_shipAdv($this->software, $this->date,$rows[$k]));
		}

		if ( $this->row['discount_data'] != null ) {
			// On ne peut avoir qu'un seul coupon sur WPeCommerce
			array_push($this->coupons, 'Coupon : ' . $this->row['discount_data'] );
		}

		if ( $this->row['notes'] != null ) {
			array_push($this->privateNotes, $this->row['notes'] );
		}

	}

	protected function setInfoCart66() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/cart66/functionsCart66.php');
		$this->id_order = $this->row['id'];
		$this->createdDate = date("Y-m-d\TH:i:s\Z", strtotime( $this->row['ordered_on'] ) );
		$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime( $this->row['ordered_on'] ) );
		$this->shipoption = $this->row['shipping_method'];
		$this->status = getStatus( $this->row['status'] );
		$this->firstname =  $this->row['bill_first_name'];
		$this->middlename = '';
		$this->lastname = $this->row['bill_last_name'];
		$this->company = '';
		$this->address = $this->row['bill_address'];
		$this->xaddress = '';
		$this->street2 = $this->row['bill_address2'];
		$this->street3 = '';
		$this->city = $this->row['bill_city'];
		$this->state = $this->row['bill_state'];
		$this->postcode = $this->row['bill_zip'];
		$this->country = $this->row['bill_country'];
		if ( '' != $this->company ) {
			$this->residential = 'false';
		}
		$this->phone = $this->row['phone'];
		$this->shipPhone = $this->row['phone'];
		$this->email = $this->row['email'];
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = $this->row['ship_first_name'];
		$this->shiplastname = $this->row['ship_last_name'];
		$this->shipaddress = $this->row['ship_address'];
		$this->shipstreet2 = $this->row['ship_address2'];
		$this->shipxaddress = '';
		$this->shipcity = $this->row['ship_city'];
		$this->shipstate = $this->row['ship_state'];
		$this->shippostcode = $this->row['ship_zip'];
		$this->shipcountry = $this->row['ship_country'];
		$this->cardtype = $this->row['gateway'];

		$this->freight = $this->row['shipping']; // Shipping Fee
		$this->tax = $this->row['tax']; //Tax Fee
		$this->discount = $this->row['discount_amount']; // Discount
		$this->fees = ''; // Add Fee

		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$rows = $wpdb->get_results(
					"SELECT * FROM ". $wpdb->prefix ."cart66_order_items WHERE order_id = " . $this->row['id'] , ARRAY_A);
		for ($k = 0; $k < count( $rows );$k ++) {
				array_push($this->items,new Item_shipAdv($this->software, $this->date,$rows[$k]));
		}

		// On ajoute les coupons
		if ( $this->row['coupon'] != 'none' ) {
			array_push($this->coupons, 'Coupon : ' . $this->row['coupon']);
		}

		// Ajout des notes
		if ( $this->row['notes'] != null ) {
			array_push($this->privateNotes, $this->row['notes']);
		}
	}

	protected function setInfoJigoshop() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/jigoshop/functionsJigoshop.php' );
		$this->id_order = $this->row['ID'];
		$this->createdDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['post_date_gmt']));
		$this->modifiedDate = date("Y-m-d\TH:i:s\Z", strtotime($this->row['post_modified_gmt']));
		$this->shipoption = getInformation( $this->row, 'shipping_service' );
		$this->status = getStatus( $this->row );
		$this->firstname =  getInformation( $this->row, 'billing_first_name' );
		$this->middlename = '';
		$this->lastname = getInformation( $this->row, 'billing_last_name' );
		$this->company = getInformation( $this->row, 'billing_company' );
		$this->address = getInformation( $this->row, 'billing_address_1' );
		$this->xaddress = '';
		$this->street2 = getInformation( $this->row, 'billing_address_2' );
		$this->street3 = '';
		$this->city = getInformation( $this->row, 'billing_city' );
		$this->state = getInformation( $this->row, 'billing_state' );
		$this->postcode = getInformation( $this->row, 'billing_postcode' );
		$this->country = getInformation( $this->row, 'billing_country' );
		if ( '' != $this->company ) {
			$this->residential = 'false';
		}
		$this->phone = getInformation( $this->row, 'billing_phone' );
		$this->shipPhone = getInformation( $this->row, 'billing_phone' );
		$this->email = getInformation( $this->row, 'billing_email' );
		$this->fax = '' ;
		$this->website = '';
		$this->shipfirstname = getInformation( $this->row, 'shipping_first_name' );
		$this->shiplastname = getInformation( $this->row, 'shipping_last_name' );
		$this->shipaddress = getInformation( $this->row, 'shipping_address_1' );
		$this->shipstreet2 = getInformation( $this->row, 'shipping_address_2' );
		$this->shipxaddress = getInformation( $this->row, 'shipping_address_1' );
		$this->shipcity = getInformation( $this->row, 'shipping_city' );
		$this->shipstate = getInformation( $this->row, 'shipping_state' );
		$this->shippostcode = getInformation( $this->row, 'shipping_postcode' );
		$this->shipcountry = getInformation( $this->row, 'shipping_country' );
		$this->cardtype = getInformation( $this->row, 'payment_method_title' );

		$this->freight = getInformation( $this->row, 'order_shipping' ); // Shipping Fee
		$this->tax = ((float)getInformation( $this->row, 'order_tax_no_shipping_tax' ))+((float)getInformation( $this->row, 'order_shipping_tax' )); //Tax Fee
		$this->discount = ((float)getInformation( $this->row, 'order_discount' )); // Discount
		$this->fees = ''; // Add Fee

		global $wpdb;
		$time = strtotime( $this->date . ' UTC' );
		$dateInLocal = date( "Y-m-d H:i:s", $time );
		$table = $wpdb->postmeta;
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $this->row['ID'] . " and meta_key = 'order_items'", ARRAY_A);

		$object = unserialize( $result['meta_value'] );
		foreach( $object as $key => $value ) {
				array_push($this->items,new Item_shipAdv( $this->software, $this->date,$value, $result['meta_id'] ));
		}

		/* Les coupons */
		if ( getCoupons( $this->row['ID'] ) != null ) {
			$coupons = getCoupons( $this->row['ID'] );
			foreach( $coupons as $coupon ) {
				array_push($this->coupons, __('Coupon : ', "shipworks-connector") . $coupon['code']);
			}
		}

		// La note customer
		if ( $this->row['post_excerpt'] != null ) {
			array_push($this->coupons, $this->row['post_excerpt']);
		}
	}

	public function getIdOrder() {
		return $this->id_order;
	}
	//used with Mutliple order plugin
	public function getIdOrderPostfix() {
		return $this->id_order_post;
	}
	public function getIdOrderPrefix() {
		return $this->id_order_pre;
	}
	public function getIdOrderParent() {
		return $this->id_parentID;
	}

	public function getCreationDate() {
		return $this->createdDate;
	}

	public function getModifiedDate() {
		return $this->modifiedDate;
	}

	public function getShippingOption() {
		return $this->shipoption;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getMiddleName() {
		return $this->middlename;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getCustomerID() {
		return $this->customerID;
	}

	public function getCompany() {
		return $this->company;
	}

	public function getShipCompany() {
		return $this->shipCompany;
	}

	public function getAddress() {
		return $this->address;
	}

	public function getStreet2() {
		return $this->street2;
	}

	public function getStreet3() {
		return $this->street3;
	}

	public function getXAddress() {
		return $this->xaddress;
	}

	public function getCity() {
		return $this->city;
	}

	public function getState() {
		return $this->state;
	}

	public function getPostCode() {
		return $this->postcode;
	}

	public function getCountry() {
		return $this->country;
	}

	public function getResidential() {
		return $this->residential;
	}

	public function getPhone() {
		return $this->phone;
	}
	public function getShipPhone() {
		return $this->shipPhone;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function getShipFirstname() {
		return $this->shipfirstname;
	}

	public function getShipLastname() {
		return $this->shiplastname;
	}

	public function getShipAddress() {
		return $this->shipaddress;
	}

	public function getShipStreet2() {
		return $this->shipstreet2;
	}

	public function getShipCity() {
		return $this->shipcity;
	}

	public function getShipState() {
		return $this->shipstate;
	}

	public function getShipCountry() {
		return $this->shipcountry;
	}

	public function getShipPostcode() {
		return $this->shippostcode;
	}

	public function getCardtype() {
		return $this->cardtype;
	}

	public function getItems() {
		return $this->items;
	}

	public function getFreight() {
		return $this->freight;
	}

	public function getTax() {
		return $this->tax;
	}

	public function getDiscount() {
		return $this->discount;
	}

	public function getFee() {
		return $this->fee;
	}

	public function getCoupons() {
		return $this->coupons;
	}

	public function getPrivateNotes() {
		return $this->privateNotes;
	}
	public function getCustomerMessage() {
		return $this->customerMessage;
	}
	public function getDiscountMessage() {
		return $this->discountMessage;
	}
	protected function getRewardPoint() {
		return $this->rewardPoint;
	}
	protected function getRedeemedPoint() {
		return $this->redeemedPoint;
	}
	public function get_shipping_date() {
		$timeoffset = get_option('gmt_offset');
		return date("Y-m-d\TH:i:s", strtotime($this->shipping_date." ".-1*$timeoffset. "hour"));
	}
	public function get_delivery_date() {
		return date("F j, Y", strtotime($this->delivery_date));
	}
	public function get__purchase_order_number(){
		return $this->_purchase_order_number;
	}
	public function get_order_name(){
		return $this->order_name;
	}
	public function get_order_phone(){
		return $this->order_phone;
	}
	public function get_order_salesman(){
		return $this->order_salesman;
	}
	public function get_location() {
		return $this->location;
	}
	protected function setRewardPoint() {
		global $wpdb;
		$idOrder = $this->id_order_main;
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$rowReward = $wpdb->get_results( "SELECT meta_value FROM `".$wpdb->prefix."wc_orders_meta`
								WHERE `meta_key` = '_wc_points_earned'
								AND `order_id` = ".$idOrder, ARRAY_A);
		} else {
			$rowReward = $wpdb->get_results( "SELECT meta_value FROM `".$wpdb->postmeta."`
								WHERE `meta_key` = '_wc_points_earned'
								AND `post_id` = ".$idOrder, ARRAY_A);
		}

		if($rowReward) :
			$points = $rowReward[0]['meta_value'];
			$this->rewardPoint = $points;
		else:
			$this->rewardPoint = 0;
		endif;
	}
	protected function setRedeemedPoint() {
		global $wpdb;
		$idOrder = $this->id_order_main;
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$rowReward = $wpdb->get_results( "SELECT meta_value FROM `".$wpdb->prefix."wc_orders_meta`
								WHERE `meta_key` = '_wc_points_redeemed'
								AND `order_id` = ".$idOrder, ARRAY_A);
		} else {
			$rowReward = $wpdb->get_results( "SELECT meta_value FROM `".$wpdb->postmeta."`
								WHERE `meta_key` = '_wc_points_redeemed'
								AND `post_id` = ".$idOrder, ARRAY_A);
		}

		if($rowReward) :
			$redeemed = $rowReward[0]['meta_value'];
			$this->redeemedPoint = $redeemed;
		else:
			$this->redeemedPoint = 0;
		endif;
	}
}
