<?php
if ( ! defined( 'ABSPATH' ) ) {	exit;}?>
<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
  <Module>
    <Platform><?php echo $software->getSoftware(); ?></Platform>
    <Developer>Advanced Creation</Developer>
    <Capabilities>
      <DownloadStrategy>ByModifiedTime</DownloadStrategy>
      <OnlineCustomerID supported="true" dataType="text"/>
      <OnlineStatus supported="true" dataType="numeric" supportsComments="<?php echo $software->getSupportComments(); ?>"/>
      <OnlineShipmentUpdate supported="true"/>
    </Capabilities>
    <Communications>
      <Http expect100Continue="false"/>
    </Communications>
  </Module>
</ShipWorks>
