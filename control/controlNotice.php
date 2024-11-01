<?php
if ( ! defined( 'ABSPATH' ) ) {	exit;}

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Clients.class.php' ) ;
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/User.class.php' ) ;
$clientAdv = new Clients_shipAdv();
$hasPayed = $clientAdv->getHasPayed();
$isClient = $clientAdv->getIsClient();
if($hasPayed == false and $isClient == true) {
  include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/noticeFailedPayement.php' );
}
if($clientAdv->getSettings() == false) {
  include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/fillSettings.php' );
}
