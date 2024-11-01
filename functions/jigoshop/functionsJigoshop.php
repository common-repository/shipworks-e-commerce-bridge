<?php
if ( ! defined( 'ABSPATH' ) ) {	exit;}

function getInformation( $row, $field) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id = " . $row['ID'] . " and meta_key = 'order_data'", ARRAY_A);
  $object = unserialize( $result['meta_value'] );
  foreach( $object as $key => $value ) {
    if ( $key == $field ) {
      return $value;
    }
  }
}

function getStatus( $row ) {
  global $wpdb;
  $row = $wpdb->get_row("SELECT * FROM $wpdb->term_relationships WHERE object_id = " . $row['ID'], ARRAY_A);
  $result = $wpdb->get_row("SELECT * FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
  return $result['term_id'];
}

function getStatusName( $row ) {
  global $wpdb;
  $row = $wpdb->get_row("SELECT * FROM $wpdb->term_relationships WHERE object_id = " . $row['ID'], ARRAY_A);
  $row = $wpdb->get_row("SELECT * FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = " . $row['term_taxonomy_id'], ARRAY_A);
  $results = $wpdb->get_row("SELECT * FROM $wpdb->terms WHERE term_id = " . $row['term_id'], ARRAY_A);
  return $results['slug'];
}

function getProductInfo_adv( $id, $field ) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id = " . $id . " AND meta_key = '" . $field . "'", ARRAY_A);
  if ( $field = 'sku' && $result['meta_value'] == null ) {
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = " . $id , ARRAY_A);
    if ( $row['post_parent'] != 0 ) {
      return getProductInfo_adv( $row['post_parent'], 'sku' );
    }
  }
  if ( $field == 'weight' ) {
    $result['meta_value'] = convertWeight( $result['meta_value'] );
  }
  return $result['meta_value'];
}

function getCoupons( $id ) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id = " . $id . " AND meta_key = 'order_data'", ARRAY_A);
  $result = unserialize( $result['meta_value'] );
  foreach( $result as $key => $value ) {
    if( $key == 'order_discount_coupons' ) {
      $toReturn = $value;
    }
  }
  return $toReturn;
}

function convertWeight( $weight ) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'jigoshop_options'", ARRAY_A);
  $result = unserialize( $result['option_value'] );
  foreach( $result as $key => $value ) {
    if( $key == 'jigoshop_weight_unit' ) {
      $unit = $value;
    }
  }
  $unitWanted = 'lbs';
  //Unify all units to kg first
  switch ($unit) {
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
  //Output desired unit
  switch ($unitWanted) {
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
  return $weight;
}

function add_note( $note, $id ) {
  global $wpdb;
  $table = $wpdb->posts;	$row = $wpdb->get_row("SELECT * FROM " . $table . " WHERE ID = " . $id, ARRAY_A);
  $excerpt = $row['post_excerpt'];
  $result = $wpdb->update( $table,
    array(
      'post_excerpt' => $excerpt . ' ' . $note,
    ),
    array( 'ID' => $id )
  );
  return $result;
}

function add_comment( $comment, $id ) {
  $is_customer_note = intval( 0 );
  $comment_post_ID 		= $id;
  $comment_author 		= 'ShipWorks';
  $comment_author_url 	= '';
  $comment_content 		= $comment;
  $comment_agent			= 'Jigoshop';
  $comment_type			= '';
  $comment_parent			= 0;
  $comment_approved 		= 1;
  $commentdata 			= compact( 'comment_post_ID', 'comment_author', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' );
  $comment_id = wp_insert_comment( $commentdata );
  /*add_comment_meta( $comment_id, 'is_customer_note', $is_customer_note );*/
  return $comment_id;
}
