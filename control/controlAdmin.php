<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// On affiche la page de base et on récupère les données

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS .'model/User.class.php' ) ;

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Software.class.php' ) ;

include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS .'functions/functions.php' );



$user = new User_shipAdv();

$software = new Software_shipAdv();

$test = $user->getUsername();

if (!empty($test)) {

	$boutonUpdate = __("Update", "shipworks-connector");

}


//------- On identifie le formulaire en fonction du nom du bouton submit ---------

if (isset($_POST['adv_send-credentials'])) {

	if (!empty($_POST['adv_username']) && !empty($_POST['adv_password'])) {

		$user->setCredentials(htmlspecialchars($_POST['adv_username']),htmlspecialchars($_POST['adv_password']));

		$message = __("Your Username and password were updated", "shipworks-connector");
		$noticeClass = "notice-success";

	}

	else {

		$message = __("Your Username and password can't be empty, please create them and click on update", "shipworks-connector");
		$noticeClass = "notice-warning";
	}

}
include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/admin.php' );
