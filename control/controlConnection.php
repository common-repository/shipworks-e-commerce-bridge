<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// On désactive les erreurs
error_reporting(0);
// On récupère les données

class Sc_adv_settings {
  protected $orders_table_usage_enabled = false;
  public function __construct() {
    $this->check_custom_orders_table_usage_enabled();
  }
  protected function check_custom_orders_table_usage_enabled(){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
      if ( version_compare( WC_VERSION, '6.5.0', '>=' ) ) {
        if ( class_exists( \Automattic\WooCommerce\Utilities\OrderUtil::class ) ) {
          if ( \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
            return $this->orders_table_usage_enabled = true;
          } else {
            return $this->orders_table_usage_enabled = false;
          }
        } else{
          return $this->orders_table_usage_enabled = false;
        }
      } else {
        return $this->orders_table_usage_enabled = false;
      }
      return $this->orders_table_usage_enabled = false;
    }
  }
  public function get_orders_table_usage_enabled(){
    return $this->orders_table_usage_enabled;
  }
}
$sc_adv_settings = new Sc_adv_settings();
if (!defined('WOOCOMMERCE_CUSTOM_TABLE_ADV')) define('WOOCOMMERCE_CUSTOM_TABLE_ADV',$sc_adv_settings->get_orders_table_usage_enabled());

include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/User.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Software.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/StatusCodes.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Count.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Order.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Orders.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Item.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Attribute.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/StatusManager.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/OrderManager.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/TrackingManager.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/Settings.class.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/functions.php';
include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/PremiumTimeStamp.class.php';

$user = new User_shipAdv();
$date = null;

$software = new Software_shipAdv();
// On commence le traitement si l'idification a été faite
if ( $user->checkCredentials($_POST['username'],$_POST['password']) ) {

	$timestamp = new PremiumTimeStamp_shipAdv();
	$getLastTimeStamp = $timestamp->getTimeStamp();
	if($getLastTimeStamp < strtotime("-1 day")) sendUsingDate();

	$action = esc_html( $_POST['action'] );
	if( 'getmodule' == $action ) {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/module.php';
	} elseif( 'getstore' == $action ) {
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/store.php';
	} elseif ( $software->isCompatible() ) {

		if ( 'getstatuscodes' == $action ) {
			$statusCodes = new StatusCodes_shipAdv($software);
			include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusCode.php';
		} elseif ( 'getcount' == $action ) {

			$orderManager = new OrderManager_shipAdv( $software );

			if ( $orderManager->isCommunicationError() ) {
				$description = $orderManager->getCommunicationMessage();
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
			} elseif ( !$orderManager->hasPayed()
									&& !$orderManager->getTempFree()
										&& isset($_POST['start'])
											&& $orderManager->getFreeOrdersNumber() == 0
												&& $orderManager->getIsClient()
												) {
					$description = __("Your last payment failed, please call us at (800) 401 2238 to update your credit card information", "shipworks-connector");
					include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
			} elseif ( !$orderManager->isFree()
								&& !$orderManager->hasPayed()
									&& !$orderManager->getTempFree()
										&& isset($_POST['start'])
											&& $orderManager->getFreeOrdersNumber() == 0
											) {
					$description = __("You are using the free version of Shipworks Connector. The free version is only if you have less than 30 orders / month. Please take a subscription on our website (take less than 5 min):https://www.advanced-creation.com/get-your-shipworks-wordpress-plugin/", "shipworks-connector");
					include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
			} else {
				if ( isset($_POST['start']) ) {
					$date = esc_html($_POST['start']);
					$count = new Count_shipAdv( $software, $date );
					include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/count.php';
				} else {
					$count = new Count_shipAdv($software);
					include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/count.php';
				}
			}
		} elseif ( 'getorders' == $action ) {

			$orderManager = new OrderManager_shipAdv( $software );
			if ( isset($_POST['start']) ) {
					if ( !$orderManager->isFree()
							&& !$orderManager->hasPayed()
								&& !$orderManager->getTempFree()
									&& isset($_POST['start'])
										&& $orderManager->getFreeOrdersNumber() > 0 ) {
										// Variable pour la vue
										$numberLimite = $orderManager->getFreeOrdersNumber();
						}
						if ( !$orderManager->isFree()
							&& !$orderManager->hasPayed()
								&& !$orderManager->getTempFree()
									&& isset($_POST['start'])
										&& $orderManager->getFreeOrdersNumber() == 0
											&& $orderManager->getIsClient()) {
							$description = __("Your last payment failed, please call us at (800) 401 2238 to update your credit card", "shipworks-connector");
							include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
						} elseif ( !$orderManager->isFree()
							&& !$orderManager->hasPayed()
								&& !$orderManager->getTempFree()
									&& isset($_POST['start'])
										&& $orderManager->getFreeOrdersNumber() == 0 ) {
							$description = __("You are using the free version of Shipworks Connector. The free version is only if you have less than 30 orders / month. Please take a subscription on our website (take less than 5 min):https://www.advanced-creation.com/get-your-shipworks-wordpress-plugin/", "shipworks-connector");
							include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
						}
						else {
							if(isset($_POST['maxcount']) ) { $maxCount = esc_html($_POST['maxcount']); }
							else {$maxCount = 50;}
							$date = esc_html($_POST['start']);
							$orders = new Orders_shipAdv( $software, $date, $maxCount );
							include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/orders.php';
						}
			} else {
				$orders = new Orders_shipAdv($software);
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/orders.php';
			}
		} elseif ( 'updatestatus' == $action ) {
			$order = esc_html( $_POST['order'] );
			$status = esc_html( $_POST['status'] );
			$statusManager = new StatusManager_shipAdv(  $software, $date, $order, $status, "" );
			if ( $statusManager->getResult() ) {
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusSuccess.php';
			} else {
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/statusFail.php';
			}
		} elseif ( 'updateshipment' == $action ) {
			$order = esc_html( $_POST['order'] );
			#USE This BELOW#
			$date = esc_html( $_POST['shippingdate'] );
			$carrier = esc_html( $_POST['carrier'] );
			$tracking = esc_html( $_POST['tracking'] );
			$trackingManager = new TrackingManager_shipAdv(  $software, $date,  $carrier, $order, $tracking );
			if ( $trackingManager->getResult() ) {
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/trackingSuccess.php';
			} else {
				include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/trackingFail.php';
			}
		}
	} else {
		$description = __("You have succesfully installed Shipworks Connector on your website. We didn't find any E-commerce plugin activated on your website, please activate it. If you are using a multisite, please do not activate the plugin network wide. Activate the plugin on each instance new sub website and the plugin will work correctly", "shipworks-connector");
		include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
	}
} else {
	// Cas ou les identifiants ne sont pas bons
	$description = __("Wrong credentials. Please copy and paste your username and password from your wordpress (plugin Shipworks connector) to Shipworks");
	include_once PLUGIN_PATH_SHIPWORKSWORDPRESS . 'view/error.php';
}
