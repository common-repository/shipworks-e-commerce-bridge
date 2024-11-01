<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class User_shipAdv {
	protected $username_shipworks;
	protected $password_shipworks;

	public function __construct() {
		if(get_option('adv_username')) :
			$this->username_shipworks = get_option('adv_username');
			$this->password_shipworks = get_option('adv_password');
		else :
			//for previous version or egal to 4.6.3
			global $wpdb;
			$table_name = $wpdb->prefix.'shipworks_bridge';
			if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") == $table_name) {
				$row = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = '1'");
				$name = $row ->username_shipworks;
				$password = $row ->password_shipworks;
				update_option('adv_username', $name);
				update_option('adv_password', $password);
				$this->username_shipworks = $name;
				$this->password_shipworks = $password;
			}
			else {
				update_option('adv_username', '');
				update_option('adv_password', '');
			}
		endif;
    }

	public function getUsername() {
		return $this->username_shipworks;
	}

	public function getPassword() {
		return $this->password_shipworks;
	}

	public function setCredentials($name,$password) {
		update_option('adv_username', $name);
		update_option('adv_password', $password);
		$this->username_shipworks = $name;
		$this->password_shipworks = $password;
	}
	public function checkCredentials($username, $password) {
		return (esc_attr($username) == $this->username_shipworks ) && ( esc_attr($password) == $this->password_shipworks);
	}
}
