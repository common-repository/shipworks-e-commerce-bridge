=== ShipWorks Connector for Woocommerce ===
Contributors: AdvancedCreation
Donate link: https://adv.design
Tags: shipworks, order manager, shipping manager, woocommerce shipping, woocommerce
Requires at least: 3.0.1
Tested up to: 6.6.2
Stable tag: 5.1.16
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==
Our plugin ShipWorks Connector allows Woocommerce to synchronize with Shipworks.
Your orders in Woocommerce will be download in Shipworks with all information from your customer for the shipping and manage fully your orders in Shipworks.
Once you ship it from Shipworks, orders status will be modified in Woocommerce along with the tracking number.
Our plugin is free for any website then have less than 30 orders per month, after that, you will need to take a subscription on our website.
[Subscribe for Unlimited Orders](https://adv.design/product/shipworks-connector-woocommerce/)

[youtube https://youtu.be/MsE3js5Fd_s]

= Links =

[Installation Guide](https://support.shipworks.com/hc/en-us/article_attachments/360019752771/Adding_a_WooCommerce_Store.pdf)
[Subscribe for Unlimited Orders](https://adv.design/product/shipworks-connector-woocommerce/)

= Compatible E-Commmerce software =
* Woocommerce ( Minimum version 2.0, tested up to 9.3.3 )

= Minimum Requires: =
* Wordpress 3.0 ( Minimum version 3.0, tested up to 6.6.2)
* Shipworks 3.0 (or minimum version you have)

= Plugins Compatible With =
* Woocommerce Composite Products (By SomewhereWarm)
* Woocommerce Sequential Order Numbers (By SkyVerge)
* Woocommerce Sequential Order Numbers Pro (By SkyVerge)
* Woocommerce Shipment Tracking (By WooCommerce)
* Woocommerce Checkout Field Editor (By WooCommerce)
* Woocommerce Product Add-ons (By WooCommerce)
* TM Extra Product Options plugin (By themecomplete)
* Woocommerce Bulk Discount (By Rene Puchinger)
* WooCommerce Dynamic Pricing (By Lucas Stark)
* Woocommerce Shipping Multiple Addresses (By WooCommerce)
* WooCommerce Cost of Goods (By SkyVerge)
* Woocommerce Order Status Manager (By SkyVerge)
* Twilio SMS notifications (By SkyVerge)
* AfterShip – WooCommerce Tracking (By AfterShip)
* Woocommerce Smart Coupons (By StoreApps)
* Advanced Shipment Tracking for WooCommerce (By Zorem)

Do you need another plugin to be compatible with Shipworks Connector for Woocommerce? Contact us on our website, via the live chat or the form.

= Ship faster with integrated shipping tools =
With the most complete shipping integrations available, your shipments get done faster so you can focus
your time on other aspects of your business.
= Save time and enhance your customer service =
ShipWorks includes support for viewing rates and transit times and printing live shipping labels from within ShipWorks. With direct integrations to Endicia, Express1, Fedex, Stamps.com, UPS, and USPS, ShipWorks is your one-stop solution for generating shipping labels for your orders with any of the major carriers. And since tracking numbers are automatically imported and saved, sending tracking emails and responding to customer calls is a snap.

= Reduce costs by simplifying order processing =
ShipWorks has proven itself to save hours per day in real world order fulfillment tasks. From the moment you begin using ShipWorks you will notice an intuitive interface that reduces order management to point and click. With its low learning curve and multi-computer networking support, you and your employees will be able to start taking advantage of ShipWorks right away. Every element of ShipWorks has been designed with an emphasis on time savings and usability, which ultimately translates into reduced costs for your business.
= Work smarter with tools designed to make your life easier =
ShipWorks offers unparalleled support for your computer peripherals. ShipWorks supports all standard inkjet and laser printers, as well as Eltron thermal printers. ShipWorks allows you to specify which printer and tray each print job should go to, greatly simplifying your printing process. And with zero-configuration support for most
scales, weighing your packages is always fast and accurate.

== Installation ==
* Go in your WordPress Plugins -> Search for Shipworks Connector
* Install + Activate it (Same plugin with the subscription)
* Go in Shipworks Connector Tab -> Settings
* Create your own Username and Password
* Go in Shipworks Software on your computer
* Add a Store -> Add Username and password you just created

And you are all set!

== Frequently Asked Questions ==

= When you ship an order, the status is not modified in Woocommerce =

In ShipWorks go into the menu in Manage -> Actions.
A window opens and there should be an action that is running when "A shipment is processed" and which task is "Upload the shipment details".
If not create one with the appropriate store. So the details of that shipment are automatically updated online when you ship your orders.

= I have an error "Reference to undeclared entity 'lsaquo' =

This error is most of the time a redirection on your website, it could be www to no www or http to https, to find out tape your URL website in a browser and your module URL should start by that, per example for adv.design: https://adv.design/

= What to do if I have an error 406 ? =

Error 406 is meaning your hosting have a mod_security module on their server. So you have to contact your hosting to ask them to remove it for your account. Sometimes they will need your IP to unlock it only for your computer.

= What to do if I have an error 500, 404 or 403 ? =

Please deactivate all plugin that could block an external direct connection like a plugin firewall or the plugin query monitor. You can also try to whitelist our IPs and your IPs where Shipworks is installed.

= What to do If I have a bug in my orders (in Shipworks) ? =

Please contact us at contact@advanced-creation.com, we will fix the bug as soon as possible. (Most of the time we are fixing bugs in less than 24h)
If you have any questions or issues about the plugin don't hesitate to contact us :
contact@advanced-creation.com.
We also have an online chat on our website [Website](https://adv.design/)

= I have an error wrong credentials =

Your username and password don't match between your website and your Shipworks
* Open your Store Connection in Shipworks (Manage -> Stores -> Edit -> Store Connection)
* Go in your website dashboard -> Shipworks Connector -> Settings
* And modify your username and password, it should be the same on both side

= You have an error "The server couldn't be reach, please try again later"? =

Please whitelist our IP to fix this issue:
147.135.15.26

= Where can I find my IP ? =

You can find it on internet by clicking on this link: http://www.whatismyip.com/

= Wordpress Multi Site Instructions. =

Please do not activate the plugin network-wide. Activate the plugin on each instance new sub website and the plugin will work correctly.

= Woocommerce Order Delivery

To add columns Shipping and Delivery by date, click right in Shipworks on Columns label, check Ship By Date (for the Shipping by Date) and check Custom Field 1 (For the Delivery by Date)

== Screenshots ==
1. Shipworks Connector Settings - Create your username and password
2. Shipworks Software window - Click on download to download your orders
3. Our website to purchase a subscription

== Changelog ==
= 5.1.15 =
* Fixed issue with Fee from Get total WooCommerce output a string instead of a number (float)
= 5.1.11 =
* Fixed issue with plugin Shipment tracking
= 5.1.10 =
* Put back Weight information and added Dimensions
= 5.1.9 =
* Added some filters
= 5.1.8 =
* Fixed Woocommerce get_attributes() return errors
= 5.1.7 =
* Added back variation attributes
= 5.1.6 =
* Fixed sku wasn't displaying with High-performance order storage in Woocommerce
= 5.1.5 =
* Fixed a small bug for Multi Address Orders
= 5.1.4 =
* Fixed issue with Multi Address that was skipping not multi Address orders
= 5.1.3 =
* Fixed tracking number not showing up on Note with High-performance order storage in Woocommerce
= 5.1.2 =
* Updated Mutli Shipping Plugin, not sending parent main order but only split orders
= 5.1.1 =
* Fixed issue with Admin notes that could generated a fatal error
= 5.1.0 =
* Add compatibility for Custom Table orders WooCommerce
* Fixed issue with Draft orders been pulled
* Fixed issue with Shipping Phone order
* Fixed issue with Suffix on Sequential Order Number plugin (Suffix will not show up in Shipworks to insure compatibility with multiple address shipping plugin)

= 5.0.8 =
* Fixed issue with empty orders
= 5.0.6 =
* Fixed issue with Shipping Line Item
= 5.0.5 =
* Removed auto-draft orders to be exported to Shipworks
= 5.0.4 =
* Fixed issue with attributes
= 5.0.3 =
* Update maxcount orders sent by shipworks
= 4.9.3 =
* Fixed issue with Wordpress native functions only for unstable websites
= 4.9.2 =
* Fixed a minor code about weight units
= 4.9.1 =
* Add Addons in Shipworks for plugin Woocommerce Product Addons
= 4.9.0 =
* update for Advanced Shipment Tracking for WooCommerce (free and pro version)
= 4.8.9 =
* Add options to limit number of Admin Notes sent
= 4.8.7 =
* Add compatibility plugin to Woocommerce Order Delivery
= 4.8.6 =
* Modified answered send to Shipworks for Order Status and Tracking numbers
= 4.8.5 =
* Fix bug updating order Status on simple order with plugin WooCommerce Ship to Multiple Addresses activated
= 4.8.4 =
* Update individual package instead of the full order only for WooCommerce Ship to Multiple Addresses
= 4.8.3 =
* Fixed split orders for WooCommerce Ship to Multiple Addresses
= 4.8.2 =
* Fixed bug WooCommerce Ship to Multiple Addresses
= 4.8.1 =
* Add temporisation to avoid orders dowloading in Shipworks partialy or empty (Woocommerce)
= 4.8.0 =
* Update plugin connecting to our new website adv.design
= 4.7.2 =
* Fixed bug, Fees wasn't showing in Shipworks (woocommerce)
= 4.7.1 =
* Test if order exist before to trigger Object Woocommerce, in case of the order is deleted in woocommerce (woocommerce)
= 4.7.0 =
* Remove SKU, variation ID and attribute if SKU and Variation ID aren't check in the settings (all)
= 4.6.9 =
* Added individual notes for multiple orders (Update for WooCommerce Ship to Multiple Addresses)
= 4.6.8 =
* Fixed shipping address issue when a package shipping was changed on a multiple order (Update for WooCommerce Ship to Multiple Addresses)
= 4.6.7 =
* Adding compatibility with plugin Advanced Shipment Tracking for WooCommerce (By Zorem)
= 4.6.6 =
* Reactivated Notice Admin if payment failed and improved connection
* Fixed error 500 with get_plugin_data()
= 4.6.5 =
* Added French translation
* Added Spanish translation
= 4.6.4 =
* Fixed warning for php 7.3
= 4.6.3 =
* Fixed bug on old order number with WooCommerce Sequential Order Number activated (Woocommerce)
= 4.6.2 =
* Fixed bug on order number with plugin WooCommerce Sequential Order Numbers Free version
= 4.6.1 =
* Add compatibility between plugin WooCommerce Sequential Order Numbers Pro and WooCommerce Ship to Multiple Addresses
* Separate Multiple Addresses order with a PostFix and keep the order parent as Order Numbers
* Add Prefix for WooCommerce Sequential Order Numbers
= 4.6.0 =
* Cleanup some code and make plugin compatible with Windows Server
= 4.5.12 =
* Reactive notice information (all)
= 4.5.9 =
* Fixed issue compatibility with general function (all)
= 4.5.6 =
* Fixed issue compatibility with Sequential order pro on Tracking Number using WooCommerce Shipment Tracking (Woocommerce)
= 4.5.3 =
* Fixed issue compatibility with Sequential order pro  (Woocommerce)
= 4.5.2 =
* Find issue with plugin WooCommerce Ship to Multiple Addresses when 1 order ship to more than 10 differents location  (Woocommerce)
= 4.5.1 =
* Made Plugin Woocommerce Smart Coupons compatible with Shipworks Connector (Woocommerce)
= 4.5.0 =
* Updates on connection faster / more compatible (All)
= 4.4.8 =
* Update for plugin Woocommerce Checkout Field Editor (WooCommerce)
= 4.4.7 =
* Update for Woocommerce Product Add-ons version 3.0.0 (Woocommerce)
= 4.4.6 =
* Make Aftership compatible with our plugin (Woocommerce)
= 4.4.5 =
* Control if xml library is enabled on the hosting to avoid issue with pay account to be limited (all)
= 4.4.4 =
* Fix bug conflict between Woocommerce Shipment Tracking and Woocommerce Shipment Tracking Pro (not the same plugin) (woocommerce)
= 4.4.2 =
* Fix error on Tracking with plugin Sequential number when woocommerce Api activated (woocommerce)
= 4.4.1 =
* Clean up code to remove php 7 warning (All)
= 4.4.0 =
* Remove Draft orders and fix issue with date when not updated on database (Woocommerce)
= 4.3.10 =
* Fix issue with Fees (Woocommerce)
* Fix issue on notes (Woocommerce)
= 4.3.7 =
* Fix bug on version 4.3.6
= 4.3.6 =
* Round float numerical to avoid shipworks error on long decimals
= 4.3.5 =
* Add note Tracking number in the notification Completed invoice (Woocmmerce)
= 4.3.4 =
* Fixed php error in a class (all)
= 4.3.3 =
* Fixed bug on tracking plugin Woocommerce Shipment Tracking (Woocommerce)
= 4.3.2 =
* Add option to use Woocommerce Api (Woocommerce)
* If option check Woocommerce Api used for Status Order, it now can send email when the status is modified (Woocommerce)
* Add Woocommerce Api for plugin Woocommerce Shipment Tracking (Woocommerce)
= 4.3.1 =
* add security ABSPATH to secure direct access(all)
= 4.3.0 =
* add Woocommerce API to get Woocommerce Version (Woocommerce)
* Fixed issue with date when date doesn't exist (all)
= 4.2.9 =
* Add option to download Downloadable product / Virtual Product (Woocommerce)
* Fix issue with plugin Woocommerce Shipping Multiple Addresses (Woocommerce)
= 4.2.8 =
* Update Display settings code, quicker faster (woocommerce)
= 4.2.7 =
* Update on connection to main server
= 4.2.6 =
* Add note when status updated (woocommerce)
= 4.2.5 =
* Update special character decode for shipworks (all)
= 4.2.4 =
* Update on date 1st time use for free user (all)
= 4.2.3 =
* Fix bug recording tracking number in plugin Woocommerce Shipment Tracking (woocommerce)
= 4.2.2 =
* Fix important issue with removing meta data only if you have plugin Woocommerce Shipment Tracking (woocommerce)
= 4.2.1 =
* Small update on recording data for plugin Woocommerce Shipment tracking (woocommerce)
= 4.1.13 =
* Fixed bug with plugin Woocommerce Shipment tracking (woocommerce)
= 4.1.12 =
* Fixed bug on user and password for previous users of our plugin (all)
= 4.1.9 =
* Remove Shipworks Connector Table and use wordpress option instead (all)
= 4.1.8 =
* Fixed bug conflict with other plugin on setting page form (all)
= 4.1.7 =
* Remove option attributes for single product as well - bugs on display variation attributes (woocommerce)
= 4.1.6 =
* Add option attributes for single product as well (woocommerce)
= 4.1.5 =
* Fixed issue connection error with woocommerce version 3.2.0 (woocommerce)
= 4.1.4 =
* Add payment method (woocommerce)
= 4.1.3 =
* Add checking if payment done in Dashboard, to allow user to know why they can't download when payment failed
= 4.1.2 =
* Fixed compatibility with Woocommerce Shipment Tracking for version > 1.6.3 (woocommerce)
= 4.1.1 =
* Fixed bug on weight calculation (Shopp)
= 4.1.0 =
* Fixed bug when next order download is 10 downloable orders (woocommerce)
= 4.0.11 =
* Improved code for plugin Woocommerce Status Manager and fixed a bug
= 4.0.10 =
* Improved query for Woocommerce order query limit and as well for query with the plugin Woocommerce multi addresses
= 4.0.9 =
* Fixed bug when update order Status from Shipworks to Woocommerce with plugin Woocommerce Order Status Manager
= 4.0.8 =
* Woocommerce Order Status Manager is now compatible with Shipworks Connector
= 4.0.7 =
*Call query limit to 100 to avoid timeout on large database
= 4.0.5 =
*Add connection to check if payment need to be updated
*Fix bug on plugin Woocommerce Shipping Tracking
= 4.0.4 =
PHP 7.0 compatible.
= 4.0.3 =
Add Customer ID send to Shipworks (only woocommerce for now)
= 4.0.0 =
Update notes "customer notes" in woocommerce
= 3.9.11 =
Remove a notice
= 3.9.10 =
Fix subscription page for trial member
= 3.9.9 =
Add notice to indicate to create username and password in dashboard
= 3.9.8 =
Fixed bug on Bulk Discount plugin, they created an update and add fixed and flat discount type
= 3.9.7 =
Add cost of item with plugin WooCommerce Cost of Goods
= 3.9.6 =
Add notice payment failed on dashboard
= 3.9.5 =
Update error message when customer as a failed credit card
= 3.9.2 =
Fix status code
= 3.9.1 =
Fix bug woocommerce multiple shipping, some orders with multi shipping was cut
= 3.9.0 =
Fix bug log
= 3.8.9 =
Fix ajax updates conflict
= 3.8.8 =
Fix error database, that creating error on shipworks
= 3.8.4 =
Add option display Variation id or sku
= 3.8.3 =
Fixed bug empty item information was creating error on Shipworks
= 3.8.2 =
Fixed error sku
= 3.8.1 =
Add sku number or orders and variation id for variation item for woocommerce
= 3.8.0 =
Remove spacing on Username/password on the settings page
= 3.7.9 =
Make it compatible with WooCommerce Dynamic Pricing
= 3.7.8 =
Add Free Trial version, Free for a month unlimited orders
= 3.7.7 =
Fixed bug on subscription page
= 3.7.6 =
Fixed bug control last connection
= 3.7.5 =
Modification on show notes/private message/customer message
= 3.7.4 =
Update on custom addon
= 3.7.3 =
Modify all Classes to avoid conflict with other plugins
= 3.7.2 =
Cleanup tables + add a security for windows hosting, fixed issue with windows hosting looping
= 3.7.1 =
Updates for addon Woocommerce Tracking Number for version < 1.3.0 and now compatible with version >= 1.3.0
= 3.7.0 =
Fixed error on shipwork when tracking number is sent
= 3.6.9 =
Fixed bug free orders
= 3.6.8 =
Fixed bug with link on extension Woocommerce Shipment Tracking - add links
= 3.6.7 =
Fixed bug id tracking number
= 3.6.6 =
Fixed unit price bug
= 3.6.5 =
Fixed issue with Special Character
= 3.6.4 =
Add plugin extension Woocommerce Shipping Multiple addresses compatible
= 3.6.3 =
Not display Module URL if primary url is https already
= 3.6.2 =
Fixed date bug with modify date shipworks, it was created issue with last modify order and fix time issue
= 3.6.1 =
Fixed bug on Shopp version
= 3.6.0 =
Download orders 10 per 10 instead of 100 per 100.
= 3.5.9 =
Added custom plugin addon.
= 3.5.8 =
Added SSL info in admin.
= 3.5.7 =
Added adjustment for https / http.
= 3.5.6 =
Added special character fix for other fields in XML.
= 3.5.5 =
Fixed update shipping method
= 3.5.2 =
Fixed if customer message is empty
= 3.5.1 =
Added new settings to set which messages are sent. Fixed issue with special character in the message.
= 3.4.10 =
Added customer message from order.
= 3.4.9 =
Fixed issue with premium subscription not working if no internet connection. Is valid for 1 week, since last check.
= 3.4.7 =
Fixed looping page settings on windows hosting
= 3.4.5 =
Fixed bug on downloadable function / could skip some orders
= 3.4.1 =
All orders are download, even canceled order
= 3.3.9 =
Compatible with TM Extra Procut Options.
= 3.3.5 =
Bugs were fixed, and compatible with Shipment Tracking plugin.
= 3.1 =
Bugs were fixed, and better support for notes and comments from ShipWorks. Better display for tracking informations.
= 2.9.16 =
Support for addons and variable products on Shopp.
= 2.9.11 =
Coupons can now appear on invoices.
= 2.9.11 =
This new version runs better on Bluehost servers.
= 2.9.7 =
Some issues were fixed on variable products attributes.
= 2.9.2 =
This new version supports variable products in Woocommerce including attributes.
It also converts the weight in lbs for ShipWorks on Woocommerce.
= 2.9.1 =
This new version supports variable products in Jigoshop, and some issues were fixed.
= 2.9 =
This new version supports variable products in Woocommerce.
= 2.8 =
* Tracking numbers are now sent to Woocommerce via ShipWorks.
* Status option available on the ShipWorks side for Cart66.
= 2.7.5 =
* Better communication with ShipWorks on tracking numbers.
= 2.7.4 =
* Accept UPS tracking number.
= 2.7.3 =
* Better compatibility with every version of Cart66 Pro.
= 2.7.2 =
* Jigoshop displays better on the invoice. Shipping option and SKU number displayed.
= 2.7 =
* Compatible with Cart66 Pro and Woocommerce Composite Products
= 2.6 =
* SKU number and Shipping option on invoices for WP eCommerce, Shopp, and Shopperpress.
= 2.5.2 =
* Better compatibility with Woocommerce
= 2.5.1 =
* SKU number on invoices for easier management
= 2.5 =
* Better compatibility with Woocommerce
* Fixed some bugs
= 2.4 =
* Compatible with Jigoshop
* Fixed some bugs
= 2.3 =
* Compatible with Cart66 Lite
* Fixed some bugs
= 2.2 =
* Compatible with WP e-Commerce
* Update for Shopp 1.2.9 version
* Fixed some bugs
= 2.1 =
* Compatible with Woocommerce
* Fixed some bugs
= 2.0 =
* Totally rebuilt
* More stable
* Compatible with shopperpress
= 1.0 =
* First version
