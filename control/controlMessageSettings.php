<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS.'model/Settings.class.php';
$settingsAdv = new Settings_shipAdv();

	if(isset($_POST['adv_save'])) :
		$show_coupon_shipAdv = false;
		$show_private_message_shipAdv = false;
		$show_customer_message_shipAdv = false;
		$show_variationId_shipAdv = false;
		$show_SKU_shipAdv = false;
		$download_virtualProd_shipAdv = false;
		$woocommerce_api_shipAdv = false;
		if(isset($_POST['show_coupon_shipAdv'])) $show_coupon_shipAdv = true;
		if(isset($_POST['show_private_message_shipAdv'])) $show_private_message_shipAdv = true;
		if(isset($_POST['show_customer_message_shipAdv'])) $show_customer_message_shipAdv = true;
		if(isset($_POST['show_variationId_shipAdv'])) $show_variationId_shipAdv = true;
		if(isset($_POST['show_SKU_shipAdv'])) $show_SKU_shipAdv = true;
		if(isset($_POST['download_virtualProd_shipAdv'])) $download_virtualProd_shipAdv = true;
		if(isset($_POST['woocommerce_api_shipAdv'])) $woocommerce_api_shipAdv = true;
		if(isset($_POST['admin_notes_restriction_shipAdv'])) $admin_notes_restriction_shipAdv = esc_html($_POST['admin_notes_restriction_shipAdv']);

		$settingsAdv->setShow_coupon($show_coupon_shipAdv);
		$settingsAdv->setShow_private($show_private_message_shipAdv);
		$settingsAdv->setShow_customer_message($show_customer_message_shipAdv);
		$settingsAdv->setShow_variationId($show_variationId_shipAdv);
		$settingsAdv->setShow_SKU($show_SKU_shipAdv);
		$settingsAdv->setDownload_virtualProd($download_virtualProd_shipAdv);
		$settingsAdv->setWoocommerce_Api($woocommerce_api_shipAdv);
		$settingsAdv->setAdmin_notes_restriction($admin_notes_restriction_shipAdv);
	else :
		$show_coupon_shipAdv = $settingsAdv->getShow_coupon();
		$show_private_message_shipAdv = $settingsAdv->getShow_private();
		$show_customer_message_shipAdv = $settingsAdv->getShow_customer_message();
		$show_variationId_shipAdv = $settingsAdv->getShow_variationId();
		$show_SKU_shipAdv = $settingsAdv->getShow_SKU();
		$download_virtualProd_shipAdv = $settingsAdv->getDownload_virtualProd();
		$woocommerce_api_shipAdv = $settingsAdv->getWoocommerce_Api();
		$admin_notes_restriction_shipAdv = $settingsAdv->getAdmin_notes_restriction();
	endif;

include_once PLUGIN_PATH_SHIPWORKSWORDPRESS.'view/messageSettings.php';
