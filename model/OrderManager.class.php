<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OrderManager_shipAdv {
	protected $software;
	protected $number;
	protected $timeCaclFree;
	protected $hasPayed = false;
	protected $communicationError = false;
	protected $communicationMessage;
	protected $tempFree = false;
	protected $dateTempFree;
	protected $isClient = false;

	public function __construct( $software ) {
		$this->software = $software;
    $this->setInformations();
		$this->setHasPayed();
  }

	protected function getTimeCaclFree() {
		return $this->timeCaclFree;
	}

	protected function setInformations() {
		date_default_timezone_set('UTC');
		$this->timeCaclFree = date("Y-m-d\TH:i:s\Z", time() - 60*60*24*30);
		$count = new Count_shipAdv($this->software, $this->timeCaclFree);
		$this->number = $count->getNumber();
		$this->filtre();
	}

	protected function filtre() {
		$this->number = filtreEntier( $this->number );
	}

	public function isFree() {
		return $this->number <= 30 ;
	}

	protected function setHasPayed() {
		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/PremiumTimeStamp.class.php' );
		$timestamp = new PremiumTimeStamp_shipAdv();
		$getLastTimeStamp = $timestamp->getTimeStamp();
		if($getLastTimeStamp < strtotime("-1 week")):
			$url = SHIPWORKSWORDPRESS_HOME. "wp-admin/admin.php?page=shipworks-admin" ;
			$urlClient = $_SERVER['HTTP_HOST'];
			$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'timeout' => 2,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'action' => 'controlV3', 'url' => $urlClient ),
				'cookies' => array()
				)
			);
			if ( is_wp_error( $response ) ) {
				if($timestamp->getSubscriptionValid()) {
					$this->hasPayed = true;
				}
				else {
					$this->communicationMessage = __("The server couldn't be reach, please try again later", "shipworks-connector");
					$this->communicationError = true;
				}
			} else {
				$json = json_decode($response['body']);
				if($json != NULL) :
					$this->hasPayed = $json->hasPayed;
					$this->isClient = $json->isclient;
					if($this->hasPayed == true) :
						if($getLastTimeStamp < strtotime("-1 day")) $timestamp->setTimeStamp();
					endif;
				endif;
			}
		else:
			$this->hasPayed = true;
			$this->isClient = true;
		endif;
	}

	public function getFreeOrdersNumber( ) {
		$freeCount = 30;
		$count = new Count_shipAdv( $this->software, $this->timeCaclFree, $freeCount );
		$number = $count->getNumber();
		if ( $number <= $freeCount ) {
			return $number;
		} else {
			return 0;
		}
	}

	public function hasPayed() {
		return $this->hasPayed;
	}

	public function getTempFree() {
		return $this->tempFree;
	}

	public function getDateTempFree() {
		return $this->dateTempFree;
	}
	public function getIsClient() {
		return $this->isClient;
	}
	public function isCommunicationError() {
		return $this->communicationError;
	}

	public function getCommunicationMessage() {
		return $this->communicationMessage;
	}
}
