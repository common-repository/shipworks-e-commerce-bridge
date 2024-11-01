<?php
if ( ! defined( 'ABSPATH' ) ) {	exit;}?>
<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="5.9.3.1" schemaVersion="1.0.0">
  <Parameters>
      <OrderID><?php echo $order;?></OrderID>
      <Status><?php echo $statusManager->get_status();?></Status>
  </Parameters>
  <UpdateSuccess/>
</ShipWorks>
