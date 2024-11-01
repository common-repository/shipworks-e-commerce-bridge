<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function getInformation( $row, $field) {
	global $wpdb;
	if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
		$table = $wpdb->prefix."wc_orders_meta";
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id = " . $row['id'] . " and meta_key = '" . $field ."'", ARRAY_A);
	} else {
		$table = $wpdb->postmeta;
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $row['ID'] . " and meta_key = '" . $field ."'", ARRAY_A);
	}
	if($result) return $result['meta_value'];
	else return false;
}
function getAddresses($row, $type) {
	global $wpdb;
	//only for table usage enabled == true
	$table = $wpdb->prefix."wc_order_addresses";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE
							order_id ='" . $row['id'] . "'
							AND address_type = '".$type."'", ARRAY_A);
	if($result) return $result;
	else return false;
}
function getInfoOrder($row, $field) {
	global $wpdb;
	//only for table usage enabled == true
	$table = $wpdb->prefix."wc_orders";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id ='" . $row['id'] . "'", ARRAY_A);
	if($result) return $result[$field];
	else return false;
}
function getInfoOrderOperational($row, $field) {
	global $wpdb;
	//only for table usage enabled == true
	$table = $wpdb->prefix."wc_order_operational_data";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id ='" . $row['id'] . "'", ARRAY_A);
	if($result) return $result[$field];
	else return false;
}

function getStatus( $software, $row ) {
	//$split = explode( '.' , $software->getVersion() );
	global $wpdb;
	if (is_plugin_active_custom( "woocommerce-order-status-manager/woocommerce-order-status-manager.php")) :
		$tab = Array( 	0 => "pending",
									1 => "failed",
									2 => "on-hold",
									3 => "processing",
									4 => "completed",
									5 => "refunded",
									6=>  "cancelled");
			$statusIds = $wpdb->get_results("SELECT ID, post_title, post_name FROM " . $wpdb->posts . " WHERE post_type = 'wc_order_status' ORDER BY ID ASC" , ARRAY_A);
			$columnName = 'post_name';
			if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
				$columnStatus = 'status';
			} else {
				$columnStatus = 'post_status';
			}

		foreach ( $statusIds as $statusId ) :
			if(!in_array($statusId[$columnName], $tab)) $tab[] = $statusId[$columnName];
		endforeach;
		foreach( $tab as $key => $el ) {
			if( $el == substr( $row[$columnStatus], 3 ) ) {
				return $key;
			}
		}
	else :
		if (version_compare($software->getVersion(), '2.2.0', '>=')) { //version >= 2.2.0
		//if ( ($split[0] >= 2 && $split[1] >= 2) or $split[0] >= 3  ) { //version >= 2.2.0 or version >= 3.0.0
			$tab = Array( 0 => "pending",
					1 => "failed",
					2 => "on-hold",
					3 => "processing",
					4 => "completed",
					5 => "refunded",
					6=>  "cancelled");
			if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
				$statusColumn = "status";
			} else {
				$statusColumn = "post_status";
			}
			foreach( $tab as $key => $el ) {
				if( $el == substr( $row[$statusColumn], 3 ) ) {
					return $key;
				}
			}
		} else {
			$table = $wpdb->term_relationships;
			$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
			$table = $wpdb->term_taxonomy;
			$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
			if($result) return $result['term_id'];
			else return false;
		}
	endif;
}

function getStatusName( $software, $row ) {
	//$split = explode( '.' , $software->getVersion() );
	global $wpdb;
	if (version_compare($software->getVersion(), '2.2.0', '>=')) { //version >= 2.2.0
	//if ( $split[0] >= 2 && $split[1] >= 2 ) {
		return substr( $row["post_status"], 3 );
	} else {
		global $wpdb;
		$table = $wpdb->term_relationships;
		$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE object_id = " . $row['ID'], ARRAY_A);
		if($row) {
			if( $row['term_taxonomy_id'] != null ) {
				$table = $wpdb->term_taxonomy;
				$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
				if( $row['term_id'] != null ) {
					$table = $wpdb->terms;
					$results = $wpdb->get_row("SELECT * FROM " . $table . " WHERE term_id = " . $row['term_id'], ARRAY_A);
					if($results) return $results['slug'];
					else return false;
				}
			}
		} else {
			return false;
		}
	}
}

function isDownloadable( $software, $date, $row, $ifDownloadVirtualProd = false ) {
	$toReturn = false;
	if(!$ifDownloadVirtualProd) {
		global $wpdb;
		if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
			$table = $wpdb->prefix."wc_orders_meta";
			$columnId = "order_id";
		} else {
			$table = $wpdb->postmeta;
			$columnId = "post_id";
		}
		$order = new Order_shipAdv($software, $date,$row);
		$i = 0; $j = 0;
		foreach ( $order->getItems() as $item ) {
			$i++;
			$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ".$columnId." = " . 	$item->getProductID() . " and meta_key = '_downloadable'", ARRAY_A);
			if($result){
				if ( $result['meta_value'] == "yes" ) {
					$toReturn = true;
					$j++;
				}
			}
		}
		if($i != $j) { $toReturn = false; }
	}
	return $toReturn;
}

function getAttributeValue( $slug ) {
	global $wpdb;
	$table = $wpdb->terms;
	$results = $wpdb->get_row("SELECT * FROM " . $table . " WHERE slug = '" . $slug . "'", ARRAY_A);
	if($results) return $results['name'];
	else return false;
}

function getItemInfo( $row, $field ) {
	global $wpdb;

	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	if($result) return $result['meta_value'];
	else return false;
}

function getShippingInfo( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_items";
	if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
		$orderId = $row['id'];
	} else {
		$orderId = $row['ID'];
	}
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_id = " . $orderId . " AND order_item_type = 'shipping'", ARRAY_A);
	if($result) return $result['order_item_name'];
	else return false;
}

function getCoupons( $row ) {
	global $wpdb;
	$table = $wpdb->prefix . "woocommerce_order_items";
	if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
		$orderId = $row['id'];
	} else {
		$orderId = $row['ID'];
	}
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE order_id = " . $orderId . " AND order_item_type = 'coupon'", ARRAY_A);
	if($results) return $results;
	else return false;
}

function isComposed( $row ) {
	global $wpdb;
	$field = '_composite_item';
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $row['order_item_id'] . " AND meta_key = '" . $field . "'", ARRAY_A);
	if($result) return $result;
	else return false;
}

function isAttributeTMOption( $id ) {
	global $wpdb;
	$field = "'_tmcartepo_data'";
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $id . " AND meta_key = " . $field . " ", ARRAY_A);
	if($result)	return $result['meta_id'];
	else return false;
}

function getTMOptionTab( $id ) {
	global $wpdb;
	$field = '_tmcartepo_data';
	$table = $wpdb->prefix . "woocommerce_order_itemmeta";
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE order_item_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
	if($row){
		$str = $row['meta_value'];
		$tab = unserialize( $str );
		return $tab;
	} else {
		return false;
	}
}

function isWooSeqNumber( $row ) {
	$result = getInformation( $row , '_order_number' );
	return $result != null ;
}

function getProductInfo_adv( $id, $field ) {
	global $wpdb;
	$table = $wpdb->postmeta;
	if($id) :
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE post_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
		return $result['meta_value'];
	endif;
	return 0;
}

function getProductName( $id ) {
	global $wpdb;
	$table = $wpdb->posts;
	if($id) :
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
		if($result) return $result['post_title'];
		else return false;
	endif;
	return false;
}

function getOrderNotes( $id ) {
	global $wpdb;
	$table = $wpdb->comments;
	$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE comment_post_ID = " . $id . " and comment_type = 'order_note'", ARRAY_A);
	if($results) return $results;
	else return false;
}

function getOrderMessage($id) {
	global $wpdb;
	if(WOOCOMMERCE_CUSTOM_TABLE_ADV){
		$table = $wpdb->prefix."wc_orders";
		$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE id = $id", ARRAY_A);
		if($result) return $result['customer_note'];
	} else {
		$table = $wpdb->posts;
		$results = $wpdb->get_results("SELECT * FROM " . $table . " WHERE ID = $id", ARRAY_A);
		if($results) return $results[0]['post_excerpt'];
	}
	return false;
}

function getNotePrivacy( $id ) {
	global $wpdb;
	$table = $wpdb->commentmeta;
	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE comment_id = " . $id , ARRAY_A);
	if(isset($row)) {return $row['meta_value']; }
	else return false;
}

/*
function getOrderComments( $id ) {
	global $wpdb;
	$table = $wpdb->posts;
	$result = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
	if($result) return $result[0]['post_title'];
	else return false;
}*/

function add_customer_note( $note, $id ) {
	$is_customer_note = 1;
	$comment_author_email 	= '';
	$comment_post_ID 		= $id;
	$comment_author 		= __( 'admin', 'woocommerce' );
	$comment_author_url 	= '';
	$comment_content 		= $note;
	$comment_agent			= 'WooCommerce';
	$comment_type			= 'order_note';
	$comment_parent			= 0;
	$comment_approved 		= 1;
	$commentdata 			= compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' );
	$comment_id = wp_insert_comment( $commentdata );
	add_comment_meta( $comment_id, 'is_customer_note', $is_customer_note ); //add extra line but add marker is_customer_note
	return $comment_id;
}

function add_private_note( $note, $id ) {
	$is_customer_note = intval( 0 );
	$comment_post_ID 		= $id;
	$comment_author 		= __( 'WooCommerce', 'woocommerce' );
	$comment_author_url 	= '';
	$comment_content 		= $note;
	$comment_agent			= 'WooCommerce';
	$comment_type			= 'order_note';
	$comment_parent			= 0;
	$comment_approved 		= 1;
	$commentdata 			= compact( 'comment_post_ID', 'comment_author', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' );
	$comment_id = wp_insert_comment( $commentdata );
	add_comment_meta( $comment_id, 'is_customer_note', $is_customer_note );
	return $comment_id;
}

function wooDimNormal($dim, $unit) {
	$dim = floatval($dim);
	$wooDimUnit = strtolower($current_unit = get_option('woocommerce_dimension_unit'));
	$unit = strtolower($unit);
	if ($wooDimUnit !== $unit) {
		switch ($wooDimUnit) {
			case 'inch':
				$dim *= 2.54;
				break;
			case 'm':
				$dim *= 100;
				break;
			case 'mm':
				$dim *= 0.1;
				break;
		}
		switch ($unit) {
			case 'inch':
				$dim *= 0.3937;
				break;
			case 'm':
				$dim *= 0.01;
				break;
			case 'mm':
				$dim *= 10;
				break;
		}
	}
	return $dim;
}

function wooWeightNormal($weight, $unit) {
	$weight = floatval($weight);
	$wooWeightUnit = strtolower($current_unit = get_option('woocommerce_weight_unit'));
	$unit = strtolower($unit);
	if ($wooWeightUnit !== $unit) {
		switch ($wooWeightUnit) {
			case 'g':
				$weight *= 0.001;
				break;
			case 'lbs':
				$weight *= 0.4535;
				break;
			case 'oz':
      	$weight *= 0.0283;
        break;
		}
		switch ($unit) {
			case 'g':
				$weight *= 1000;
				break;
			case 'lbs':
				$weight *= 2.204;
				break;
			case 'oz':
      	$weight *= 35.274;
        break;
		}
	}
	return $weight;
}
function wooDimension($dimension) {
	$dimension = floatval($dimension);
	$wooDimensionUnit = strtolower($current_unit = get_option('woocommerce_dimension_unit'));
	switch ($wooDimensionUnit) {
		case 'cm':
			$dimension *= 0.393701;
			break;
		case 'mm':
			$dimension *= 0.0393701;
			break;
		case 'yd':
      $dimension *= 36.00001944;
      break;
	}
	return $dimension;
}

function shipAdv_getTrackingVersion() {
	$version = false;
	if( is_plugin_active_custom( "woocommerce-shipment-tracking/shipment-tracking.php" ) ) :
		$version = getVersion( "woocommerce-shipment-tracking/shipment-tracking.php" );
	elseif( is_plugin_active_custom( "woocommerce-shipment-tracking/woocommerce-shipment-tracking.php" ) ) :
		$version = getVersion( "woocommerce-shipment-tracking/woocommerce-shipment-tracking.php" );
	endif;
	//if($version !== false) :
		//$version = str_replace(".", "", $version);
	//endif;
	return $version;
}
