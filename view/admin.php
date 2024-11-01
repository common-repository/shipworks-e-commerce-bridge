<?php

if ( ! defined( 'ABSPATH' ) ) {	exit;}?>


  <?php if ( $software->isCompatible() ) { ?>

    <div class="highlight compatible">

      <p>

        <?php echo $software->getCompatibleMessage(); ?>

      </p>

    </div>

  <?php  } else { ?>

    <div class="highlight not-compatible">

      <p>

        <?php echo $software->getNotCompatibleMessage(); ?>

      </p>

    </div>

  <?php } ?>

  <?php if (isset($message)) { ?>
    <div class="notice <?php echo $noticeClass; ?>">
      <p><strong><?php echo $message; ?></strong></p>
    </div>
  <?php } ?>

  <form name="shipworks_account_form" method="post" action="<?php PLUGIN_PATH_SHIPWORKSWORDPRESS.'../../shipworks-e-commerce-bridge/view/control/controlAdmin.php'?>">

    <h3><?php echo __("Shipworks Account", "shipworks-connector");?></h3>
    <p><?php echo __("Create your username and password below (Do not use Shipworks account username/password)", "shipworks-connector");?><br/>

      <?php echo __("Once you create your own username and password use them to create your store into Shipworks", "shipworks-connector"); ?></p>

      <table class="form-table">

        <tbody>

          <tr>

            <td align="right"><strong><?php echo __("Username", "shipworks-connector");?><span class="required">*</span></strong></td>

            <td align="left"><input type="text" value="<?php echo $user->getUsername(); ?>" size="30" name="adv_username" required></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo __("Password", "shipworks-connector");?><span class="required">*</span></strong></td>

            <td align="left"><input type="password" value="<?php echo $user->getPassword(); ?>" size="30" name="adv_password" required></td>

          </tr>

          <tr>

            <td></td>

            <td align="left"><input type="submit" class="button-primary" value="<?php if(isset($boutonUpdate)) echo $boutonUpdate; else echo __('Create', "shipworks-connector"); ?>" name="adv_send-credentials"></td>

          </tr>

          <?php if(isset($boutonUpdate)) :?>
            <tr>
              <td></td>
              <td align="left"><?php echo __("You created your username and password with success. You can now create your store in Shipworks", "shipworks-connector"); ?></td>
            </tr>
          <?php endif; ?>
            <tr>

              <td align="right" valign="top"><strong><?php echo __("URL Module SSL", "shipworks-connector");?></strong></td>

              <td align="left"><strong><?php echo SHIPWORKSWORDPRESS_URL_SSL; ?></strong><br />

                <span style="font-size:x-small"><?php echo __("(Please replace https by http if you are not using an SSL certificate)", "shipworks-connector");?></span>

              </td>

            </tr>

          </tbody>

        </table>

      </form>
