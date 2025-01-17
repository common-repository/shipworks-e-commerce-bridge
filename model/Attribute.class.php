<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Attribute_shipAdv {

	protected $software;

	protected $date;

	protected $row;

	protected $attributeID;

	protected $name;

	protected $value;

	protected $price;

	public function __construct( $software, $date, $key, $value, $price = 0) {

		$this->software = $software;

		$this->date = $date;

		$this->row = $row;

		$this->price = $price;

		$this->name = $key;

		$this->value = $value;

		$this->setInformations();

		// On filtre les champs

		$this->filtre();

    }

	protected function setInformations() {

		$split = explode( '.' , $this->software->getVersion() );

		// Cas Shopperpress

		if ( 'shopperpress' == $this->software->getSoftware() ) {

			$this->setInfoShopperpress();

		}// Cas Shopp

		else if ( 'Shopp' == $this->software->getSoftware() ) {

			$this->setInfoShopp();

		}// Cas Woocommerce

		else if ( 'Woocommerce' == $this->software->getSoftware() ) {

			$this->setInfoWoocommerce();

		} // Cas WP eCommerce

		else if ( 'WP eCommerce' == $this->software->getSoftware() ) {

			$this->setInfoWPeCommerce();

		} // Cas Cart66 Lite

		else if ( 'Cart66 Lite' == $this->software->getSoftware() ) {

			$this->setInfoCart66();

		}  // Cas Cart66 Pro

		else if ( 'Cart66 Pro' == $this->software->getSoftware() ) {

			$this->setInfoCart66();

		}// Cas Jigoshop

		else if ( 'Jigoshop' == $this->software->getSoftware() ) {

			$this->setInfoJigoshop();

		}

		// On filtre les champs

		$this->filtre();

	}



	protected function setInfoShopperpress() {

		include_once(PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopperpress/functionsShopperpress.php');



	}



	protected function setInfoShopp() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/shopp/functionsShopp.php' );



	}



	protected function setInfoWoocommerce() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/woocommerce/functionsWoocommerce.php' );

		// Pour les attributs qu'on rentre à partir de Products -> attributes, quand le slug est custom un préfix pa_ est ajouté devant le nom

		if( strlen( $this->name ) > 3 && substr( $this->name, 0, 3) == 'pa_' ) {

			$this->name = substr( $this->name, 3, strlen( $this->name ) - 1 );

			//$this->value = ucfirst( getAttributeValue( $this->value ) );
			$this->value = $this->value;

		}

		// Les majuscules

		$this->name = ucfirst( $this->name );

		$this->value = ucfirst( $this->value );

	}



	protected function setInfoWPeCommerce() {

		include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'functions/wpecommerce/functionsWPeCommerce.php' );



	}



	protected function setInfoCart66() {



	}



	protected function setInfoJigoshop() {

		$this->name = ucfirst( substr( $this->name, 4, strlen( $this->name ) - 1 ) );

		$this->value = ucfirst( $this->value );

	}



	protected function filtre() {

		$this->attributeID = filtreEntier( $this->attributeID );

		$this->name = filtreString( $this->name );

		$this->value = filtreString( $this->value );

		$this->price = filtreFloat( $this->price );

	}



	public function gattributeID() {

		return $this->attributeID;

	}



	public function getName() {

		return $this->name;

	}



	public function getValue() {

		return $this->value;

	}



	public function getPrice() {

		return $this->price;

	}

}
