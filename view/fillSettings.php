<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>
<?php
	function notice_settings_shipAdv() { ?>
		<div class="notice notice-warning">
			<p><?php echo __( 'Shipworks Connector: Please create an username and password on the <a href="admin.php?page=shipworks-wordpress">setting page</a>', 'shipAdv' ); ?></p>
		</div><!--notice-->
<?php }
	add_action( 'admin_notices', 'notice_settings_shipAdv'); ?>
