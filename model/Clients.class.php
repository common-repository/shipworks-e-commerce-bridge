<?php

if ( ! defined( 'ABSPATH' ) ) {	exit;}



class Clients_shipAdv {

  protected $hasPayed = false;

  protected $isClient = false;

  protected $username;

  protected $password;

  public function __construct(){

    include_once( PLUGIN_PATH_SHIPWORKSWORDPRESS . 'model/PremiumTimeStamp.class.php' );

    $timestamp = new PremiumTimeStamp_shipAdv();

    $getLastTimeStamp = $timestamp->getTimeStamp();

    if($getLastTimeStamp < strtotime("-1 day")):

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

          'action' => 'controlV3',

          'url' => $urlClient

        ),

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



  public function getIsClient() {

    return $this->isClient;

  }

  public function getHasPayed() {

    return $this->hasPayed;

  }

  protected function checkSettings() {

    $user = new User_shipAdv();

    $this->username = $user->getUsername();

    $this->password = $user->getPassword();

  }

  public function getSettings() {

    $this->checkSettings();

    if($this->username == '' or $this->password == '') return false;

    else return true;

  }

}
