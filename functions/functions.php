<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function getVersion( $path ) {
  $wordpressData = get_file_data(PLUGINS_PATH.$path, array(
      'Version' => 'Version'
  ) );
  return $wordpressData['Version'];
}

function filtreString($var) {
  return html_entity_decode($var);
}

function filtreAttribut($var) {
  $var = trim($var);
  if (empty($var)) {
    return '';
  } else {
    return html_entity_decode(trim($var));
  }
}

function filtreFloat($var) {
  $var = trim($var);
  if (empty($var)) {
    return 0;
  }	elseif (!is_numeric($var)) {
    return 0;
  }	else {
    return trim($var);
  }
}

function filtreEntier($var) {
  if (is_int($var)) {
    return $var;
  }	elseif (is_numeric($var)) {
    return (int)$var;
  }	else {
    return (int)$var;
  }
}

function sendUsingDate() {
  $url = SHIPWORKSWORDPRESS_HOME. "wp-admin/admin.php?page=shipworks-admin" ;
  $urlClient = $_SERVER['HTTP_HOST'];
  $response = wp_remote_post( $url, array(
    'method' => 'POST',
    'timeout' => 2,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'body' => array(
      'action' => 'date',
      'url' => $urlClient
    ),
    'cookies' => array()
  	)
  );

  if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    $communicationMessage = $error_message;
    $communicationError = true;
  }
}

function is_plugin_active_custom( $plugin ) {
  $array = (array) get_option( 'active_plugins', array() );
  $toReturn = false;
  foreach( $array as $el ) {
    if( strpos( $el , $plugin ) !== false ) {
      $toReturn = true;
    }
  }
  return $toReturn;
}
