<?php
if ( ! defined( 'ABSPATH' ) ) {	exit;}?>
<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="5.9.3.1" schemaVersion="1.0.0">
  <Parameters>
      <OrderID><?php echo $trackingManager->get_order_id();?></OrderID>
      <Tracking><?php echo $trackingManager->get_tracking();?></Tracking>
  </Parameters>
  <UpdateSuccess/>
</ShipWorks>
