<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>

<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
<div class="">
	<h3><?php echo __( 'Message Settings','shipworks-connector' ); ?></h3>
    <p><?php echo __("Select which messages to include in Shipworks!","shipworks-connector");?></p>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="show_coupon_shipAdv" <?php if($show_coupon_shipAdv) {echo "checked";} ?> />
                <?php echo __("Show Coupon","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="show_customer_message_shipAdv" <?php if($show_customer_message_shipAdv) {echo "checked";} ?> />
                <?php echo __("Show Customer Message","shipworks-connector");?>
            </label>
        </div><!--checkbox-->

</div><!--highlight compatible-->

<div class="">
	<h3><?php echo __( 'Product information','shipworks-connector' ); ?></h3>
    <p><?php echo __("Select what information to include under products","shipworks-connector");?></p>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="show_variationId_shipAdv" <?php if($show_variationId_shipAdv) {echo "checked";} ?> />
                <?php echo __("Show Variation Id","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="show_SKU_shipAdv" <?php if($show_SKU_shipAdv) {echo "checked";} ?> />
                <?php echo __("Show Sku","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
</div><!--highlight compatible-->
<div class="">
	<h3><?php echo __( 'Downloadable Product','shipworks-connector' ); ?></h3>
    <p><?php echo __("Check if you want to download even virtual product","shipworks-connector");?></p>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="download_virtualProd_shipAdv" <?php if($download_virtualProd_shipAdv) {echo "checked";} ?> />
                <?php echo __("Download all virtual products in Shipworks","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
</div><!--highlight compatible-->
<div class="notes_adv">
	<h3><?php echo __( 'Notes','shipworks-connector' ); ?></h3>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="admin_notes_enabled_shipAdv" value="1" size="30" name="show_private_message_shipAdv" <?php if($show_private_message_shipAdv) {echo "checked";} ?> />
                <?php echo __("Enabled Admin notes","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
				<div class="numbers" <?php if(!$show_private_message_shipAdv) echo 'style="display:none"';?>>
					<label style="font-weight:400"><?php echo __("Restrict Admin notes, it will send only the last Admin notes (0 for unlimited)","shipworks-connector");?></label>
					<input type="number" name="admin_notes_restriction_shipAdv" min="0" max="20" value="<?php echo $admin_notes_restriction_shipAdv; ?>"/>
				</div>
</div><!--highlight compatible-->

<div class="">
	<h3><?php echo __( 'Woocommerce API','shipworks-connector' ); ?></h3>
    <p><?php echo __("Check to use woocommerce API (Woocommerce will send emails automatically)","shipworks-connector");?></p>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" size="30" name="woocommerce_api_shipAdv" <?php if($woocommerce_api_shipAdv) {echo "checked";} ?> />
                <?php echo __("Use Woocommerce API","shipworks-connector");?>
            </label>
        </div><!--checkbox-->
</div><!--highlight compatible-->
<div style="margin-top:20px;">
		<button type="submit" class="button-primary" name="show_message_submit"><?php echo __("Save","shipworks-connector");?></button>
		<input type="hidden" name="adv_save" value="Y" />
    </form>
</div>

<script>
jQuery.noConflict();
(function( $ ) {
  $(function() {
    $("#admin_notes_enabled_shipAdv").on("click", function() {
			$(this).parents(".notes_adv").find(".numbers").slideToggle("fast");
		})
  });
})(jQuery);
</script>
