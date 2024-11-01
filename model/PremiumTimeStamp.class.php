<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PremiumTimeStamp_shipAdv {
	protected $current_timestamp;
	protected $saved_timestamp;

	public function __construct() {
		$this->current_timestamp = time();
		$this->saved_timestamp = get_option('adv_timestamp');
	}
	public function setTimeStamp() {
		$checkIfOption = get_option('adv_timestamp');
		if(!$checkIfOption or empty($checkIfOption)) {
			$this->current_timestamp = time() - (8 * 24 * 60 * 60); //minus +1week
		}
		$setStamp = update_option('adv_timestamp', $this->current_timestamp);
	}
	public function getTimeStamp() {
		return $this->saved_timestamp;
	}
	public function getSubscriptionValid() {
		$timestamp = $this->getTimeStamp();
		$oneweek = strtotime("-1 week");
		if($timestamp > $oneweek) {
			return true;
		} else {
			return false;
		}
	}
}
?>
