<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>
<?php
	function notice_error_xml_shipAdv() { ?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo __( 'Shipworks Connector: XML library is not activated on your hosting, please contact your hosting and ask them to enable module libxml', 'shipworks-connector' ); ?></p>
		</div><!--notice-->
<?php }
	add_action( 'admin_notices', 'notice_error_xml_shipAdv'); ?>
