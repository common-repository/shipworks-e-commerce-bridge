<?php

if ( ! defined( 'ABSPATH' ) ) {	exit;}?>

<form name="shipworks_account_form" method="post" action="<?php admin_url( 'options-general.php?page=shipworks-shopperpress' ); ?>">

  <h3><?php echo __( 'Shipworks Account','shipworks-connector' ); ?></h3>
  <p><?php echo __("Username and Password you will enter in Shipworks Store (Shipworks will ask this username and password after click on create a Store)","shipworks-connector");?></p>

  <table class="form-table">

    <tbody>

      <tr>

        <td align="right"><strong><?php echo __("Shipworks Username","shipworks-connector");?><span class="required">*</span></strong></td>

        <td align="left"><input type="text" value="" size="30" name="username_shipwork" required></td>

      </tr>

      <tr>

        <td align="right"><strong><?php echo __("Shipworks Password","shipworks-connector");?><span class="required">*</span></strong></td>

        <td align="left"><input type="password" value="<?php if(isset($password_shipwork)) echo $password_shipwork; ?>" size="30" name="password_shipwork" required></td>

      </tr>

      <tr>

        <td></td>

        <td align="left"><input type="submit" class="button-primary" value="<?php if(isset($buttonValueAccount)) echo $buttonValueAccount; else echo __('Create','shipworks-connector' ); ?>" name="send_account"></td>

      </tr>

      <tr>

        <td align="right" valign="top"><strong><?php echo __("URL Module","shipworks-connector");?></strong></td>

        <td align="left"><strong><?php echo SHIPWORKSWORDPRESS_URL; ?></strong><br />

          <span style="font-size:x-small">(<?php echo __("Please enter this url when you will set up your Shipworks Account","shipworks-connector");?>)</span></td>

        </tr>

      </tbody>

    </table>

  </form>
