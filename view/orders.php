<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$settingsAdv = new Settings_shipAdv();
$show_variationId_shipAdv = $settingsAdv->getShow_variationId();
$show_SKU_shipAdv = $settingsAdv->getShow_SKU();
if ( is_plugin_active_custom( 'woocommerce-product-addons/woocommerce-product-addons.php' )){
	$show_variationId_shipAdv = true;
}

?>
<?php echo '<?xml version="1.0" standalone="yes" ?>'; ?>
<ShipWorks moduleVersion="3.1.22.3273" schemaVersion="1.0.0">
	<Orders>
	<?php foreach( $orders->getOrders() as $order ) { ?>
	<?php 	if ( !isset( $numberLimite ) || ( isset( $numberLimite ) && $numberLimite > 0 ) ) {?>
		<Order>
			<?php if($order->getIdOrderParent() == 0) { ?>
				<OrderNumber><?php echo htmlspecialchars($order->getIdOrder()); ?></OrderNumber>
			<?php } else { ?>
				<OrderNumber><?php echo htmlspecialchars($order->getIdOrderParent()); ?></OrderNumber>
			<?php }?>
			<?php if($order->getIdOrderPostfix()) echo '<OrderNumberPostfix>-'.htmlspecialchars($order->getIdOrderPostfix()).'</OrderNumberPostfix>';?>
			<?php if($order->getIdOrderPrefix()) echo '<OrderNumberPrefix>'.htmlspecialchars($order->getIdOrderPrefix()).'</OrderNumberPrefix>';?>
			<OrderDate><?php echo htmlspecialchars($order->getCreationDate()); ?></OrderDate>
			<LastModified><?php echo htmlspecialchars($order->getModifiedDate()); ?></LastModified>
			<ShippingMethod><?php echo htmlspecialchars($order->getShippingOption()); ?></ShippingMethod>
			<StatusCode><?php echo htmlspecialchars($order->getStatus()); ?></StatusCode>
			<?php if ( is_plugin_active_custom( "woocommerce-order-delivery/woocommerce-order-delivery.php")) :
					if($order->get_shipping_date() != "") echo '<ShipByDate>'.htmlspecialchars($order->get_shipping_date()).'</ShipByDate>';
			endif;?>
			<ShippingAddress>
				<FirstName><?php echo htmlspecialchars($order->getShipFirstname()); ?></FirstName>
				<MiddleName><?php echo htmlspecialchars($order->getMiddleName()); ?></MiddleName>
				<LastName><?php echo htmlspecialchars($order->getShipLastname()); ?></LastName>
				<Company><?php echo htmlspecialchars($order->getShipCompany()); ?></Company>
				<Street1><?php echo htmlspecialchars($order->getShipAddress()); ?></Street1>
				<Street2><?php echo htmlspecialchars($order->getShipStreet2()); ?></Street2>
				<Street3></Street3>
				<City><?php echo htmlspecialchars($order->getShipCity()); ?></City>
				<State><?php echo htmlspecialchars($order->getShipState()); ?></State>
				<PostalCode><?php echo htmlspecialchars($order->getShipPostcode()); ?></PostalCode>
				<Country><?php echo htmlspecialchars($order->getShipCountry()); ?></Country>
				<Residential><?php echo htmlspecialchars($order->getResidential()); ?></Residential>
				<Phone><?php echo htmlspecialchars($order->getShipPhone()); ?></Phone>
				<Fax><?php echo htmlspecialchars($order->getFax()); ?></Fax>
				<Email><?php echo htmlspecialchars($order->getEmail()); ?></Email>
				<Website><?php echo htmlspecialchars($order->getWebsite()); ?></Website>
			</ShippingAddress>
			<BillingAddress>
				<FirstName><?php echo htmlspecialchars($order->getFirstName()); ?></FirstName>
				<MiddleName><?php echo htmlspecialchars($order->getMiddleName()); ?></MiddleName>
				<LastName><?php echo htmlspecialchars($order->getLastName()); ?></LastName>
				<Company><?php echo htmlspecialchars($order->getCompany()); ?></Company>
				<Street1><?php echo htmlspecialchars($order->getAddress()); ?></Street1>
				<Street2><?php echo htmlspecialchars($order->getStreet2()); ?></Street2>
				<Street3><?php echo htmlspecialchars($order->getStreet3()); ?></Street3>
				<City><?php echo htmlspecialchars($order->getCity()); ?></City>
				<State><?php echo htmlspecialchars($order->getState()); ?></State>
				<PostalCode><?php echo htmlspecialchars($order->getPostCode()); ?></PostalCode>
				<Country><?php echo htmlspecialchars($order->getCountry()); ?></Country>
				<Residential><?php echo htmlspecialchars($order->getResidential()); ?></Residential>
				<Phone><?php echo htmlspecialchars($order->getPhone()); ?></Phone>
				<Fax><?php echo htmlspecialchars($order->getFax()); ?></Fax>
				<Email><?php echo htmlspecialchars($order->getEmail()); ?></Email>
				<Website><?php echo htmlspecialchars($order->getWebsite()); ?></Website>
			</BillingAddress>
<?php		if($order->getCardtype()) : ?>
			<Payment>
				<Method><?php echo htmlspecialchars($order->getCardtype()); ?></Method>
			</Payment>
<?php		endif; ?>
			<CustomerID><?php if($order->getCustomerID()) echo htmlspecialchars($order->getCustomerID()); ?></CustomerID>
			<Notes>
<?php 		if ($order->getCoupons() != null) {
				foreach( $order->getCoupons() as $coupon ) {
					if($coupon) :?>
						<Note public="true"><?php echo htmlspecialchars($coupon);?></Note>
<?php 				endif;
					}
				}
			if ($order->getPrivateNotes() != null ) {
				foreach( $order->getPrivateNotes() as $note ) {
					if($note) :?>
						<Note public="false"><?php echo htmlspecialchars($note);?></Note>
<?php 				endif;
				}
			}
			if ($order->getCustomerMessage() != null ) {
				foreach( $order->getCustomerMessage() as $message ) {
					if($message) : ?>
						<Note public="true"><?php echo htmlspecialchars($message);?></Note>
<?php 				endif;
				}
			}
			if ($order->getDiscountMessage() != null ) {
				foreach( $order->getDiscountMessage() as $message ) {
					if($message) : ?>
						<Note public="true">Discount: <?php echo htmlspecialchars($message);?></Note>
<?php 				endif;
				}
			}?>
			</Notes>
			<?php if (is_plugin_active_custom( "shipworks-addon-eddieswelding/shipworks-addon-eddieswelding.php")) {
				if($order->get__purchase_order_number()){?><Custom1><?php echo htmlspecialchars($order->get__purchase_order_number());?></Custom1><?php }
				if($order->get_order_name()){?><Custom2><?php echo htmlspecialchars($order->get_order_name());?></Custom2><?php }
				if($order->get_order_phone()){?><Custom3><?php echo htmlspecialchars($order->get_order_phone());?></Custom3><?php }
				if($order->get_order_salesman()){?><Custom4><?php echo htmlspecialchars($order->get_order_salesman());?></Custom4>
			<?php }}
				if (is_plugin_active_custom( "shipworks-addon-direct-native-plants/shipworks-addon-direct-native-plants.php")) {
					if($order->get_location()){
						if ( is_plugin_active_custom( "woocommerce-order-delivery/woocommerce-order-delivery.php")) {
							if($order->get_delivery_date() != "January 1, 1970") {echo '<Custom1>'.htmlspecialchars($order->get_delivery_date()).'</Custom1>';}?>
							<Custom2>
<?php 			} else { ?>
							<Custom1>
<?php 			} ?>
							<?php echo htmlspecialchars($order->get_location());
						if ( is_plugin_active_custom( "woocommerce-order-delivery/woocommerce-order-delivery.php")) {?>
						</Custom2>
	<?php 		} else { ?>
						</Custom1>
	<?php 		}
					}
				} ?>
			<Items>
			<?php foreach( $order->getItems() as $item ) { ?>
				<Item>
					<ItemID><?php echo htmlspecialchars($item->getItemID()); ?></ItemID>
					<ProductID><?php echo htmlspecialchars($item->getProductID()); ?></ProductID>
					<Code><?php echo htmlspecialchars($item->getCode()); ?></Code>
					<?php if($show_SKU_shipAdv) { ?>
						<SKU><?php echo htmlspecialchars($item->getSku()); ?></SKU>
					<?php } ?>
					<Name><?php echo htmlspecialchars($item->getName()); ?></Name>
					<Quantity><?php echo htmlspecialchars($item->getQuantity()); ?></Quantity>
					<UnitPrice><?php echo htmlspecialchars(round($item->getUnitPrice(),2)); ?></UnitPrice>
					<UnitCost><?php echo htmlspecialchars(round($item->getUnitCost(),2)); ?></UnitCost>
					<Image><?php echo htmlspecialchars($item->getImage()); ?></Image>
					<ThumbnailImage><?php echo htmlspecialchars($item->getImageThumbnail()); ?></ThumbnailImage>
					<Weight><?php echo htmlspecialchars(round($item->getWeight(),2)); ?></Weight>
					<Length><?php echo htmlspecialchars(round($item->getLength(),2)); ?></Length>
					<Width><?php echo htmlspecialchars(round($item->getWidth(),2)); ?></Width>
					<Height><?php echo htmlspecialchars(round($item->getHeight(),2)); ?></Height>
					<?php if( $item->getAttributes() != null and $show_variationId_shipAdv == true) { ?>
					<Attributes>
					<?php foreach( $item->getAttributes() as $i => $attribute ) { ?>
						<Attribute>
							<AttributeID><?php echo htmlspecialchars($i); ?></AttributeID>
							<Name><?php echo htmlspecialchars($attribute->getName()); ?></Name>
							<Value><?php echo htmlspecialchars($attribute->getValue()); ?></Value>
							<Price><?php echo htmlspecialchars($attribute->getPrice()); ?></Price>
						</Attribute>
					<?php } ?>
					</Attributes>
					<?php } ?>
				</Item>
			<?php } ?>
			</Items>
			<Totals>
				<Total name="<?php echo apply_filters("adv_desc_tax", "Tax",$order);?>" class="tax"><?php echo htmlspecialchars(round($order->getTax(),2)); ?></Total>
				<Total name="<?php echo apply_filters("adv_desc_shipping", "Shipping",$order);?>" class="shipping"><?php echo htmlspecialchars(round($order->getFreight(),2)); ?></Total>
				<Total name="<?php echo apply_filters("adv_desc_discount", "Discount",$order);?>" class="Discount" impact="subtract"><?php echo htmlspecialchars(round($order->getDiscount(),2)); ?></Total>
				<Total name="<?php echo apply_filters("adv_desc_fee", "Fee",$order);?>" class="Fee" impact="add"><?php echo htmlspecialchars(round($order->getFee(),2)); ?></Total>
			</Totals>
		</Order>
		<?php if ( isset( $numberLimite ) ) { $numberLimite--; }
				} ?>
		<?php } ?>
	</Orders>
</ShipWorks>
