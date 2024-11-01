<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings_shipAdv {
	protected $show_coupon;
	protected $show_private_message;
	protected $show_customer_message;
	protected $show_variationId;
	protected $show_SKU;
	protected $download_virtualProd;
	protected $woocommerce_api_use;
	protected $admin_notes_restriction;

	function __construct(){
		if(get_option('show_coupon_shipAdv') === false) $this->setShow_coupon();
		$this->show_coupon	= get_option('show_coupon_shipAdv');
		if(get_option('show_private_message_shipAdv') === false) $this->setShow_private();
		$this->show_private_message = get_option('show_private_message_shipAdv');
		if(get_option('show_customer_message_shipAdv') === false) $this->setShow_customer_message();
		$this->show_customer_message = get_option('show_customer_message_shipAdv');
		if(get_option('show_variationId_shipAdv') === false) $this->setShow_variationId();
		$this->show_variationId = get_option('show_variationId_shipAdv');
		if(get_option('show_SKU_shipAdv') === false) $this->setShow_SKU();
		$this->show_SKU = get_option('show_SKU_shipAdv');
		if(get_option('download_virtualProd_shipAdv') === false) $this->setDownload_virtualProd();
		$this->download_virtualProd = get_option('download_virtualProd_shipAdv');
		if(get_option('woocommerce_api_shipAdv') === false) $this->setWoocommerce_Api();
		$this->woocommerce_api_use = get_option('woocommerce_api_shipAdv');
		if(get_option('admin_notes_restriction_shipAdv') === false) $this->setAdmin_notes_restriction();
		$this->admin_notes_restriction = get_option('admin_notes_restriction_shipAdv');
	}
	public function getShow_coupon() {
		return $this->show_coupon;
	}
	public function getShow_private() {
		return $this->show_private_message;
	}
	public function getShow_customer_message() {
		return $this->show_customer_message;
	}
	public function getShow_variationId() {
		return $this->show_variationId;
	}
	public function getShow_SKU() {
		return $this->show_SKU;
	}
	public function getDownload_virtualProd() {
		return $this->download_virtualProd;
	}
	public function getWoocommerce_Api() {
		return $this->woocommerce_api_use;
	}
	public function getAdmin_notes_restriction() {
		return $this->admin_notes_restriction;
	}

	public function setShow_coupon($option = true) {
		update_option('show_coupon_shipAdv', $option);
	}
	public function setShow_private($option = true) {
		update_option('show_private_message_shipAdv', $option);
	}
	public function setShow_customer_message($option = true) {
		update_option('show_customer_message_shipAdv', $option);
	}
	public function setShow_variationId($option = true) {
		update_option('show_variationId_shipAdv', $option);
	}
	public function setShow_SKU($option = true) {
		update_option('show_SKU_shipAdv', $option);
	}
	public function setDownload_virtualProd($option = false) {
		update_option('download_virtualProd_shipAdv', $option);
	}
	public function setWoocommerce_Api($option = true) {
		update_option('woocommerce_api_shipAdv', $option);
	}
	public function setAdmin_notes_restriction($option = 10) {
		update_option('admin_notes_restriction_shipAdv', $option);
	}
}
