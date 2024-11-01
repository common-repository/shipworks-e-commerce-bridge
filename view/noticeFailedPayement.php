<?php

if ( ! defined( 'ABSPATH' ) ) {	exit;}
function notice_failed_payement_shipAdv() { ?>

  <div class="notice notice-error is-dismissible">

    <p><?php echo __( 'Shipworks Connector: Your last payement failed, please contact us to update your payment information: Advanced Creation Tel (800) 401 2238', 'shipworks-connector' ); ?></p>

  </div><!--notice-->

<?php }

add_action( 'admin_notices', 'notice_failed_payement_shipAdv');
