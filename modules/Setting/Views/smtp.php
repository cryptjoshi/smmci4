
    <div class="card content">
      <div class="card-header">
        <h3 class="card-title"><i class="fe fe-mail"></i> <?=lang("app.email_setting")?></h3>
      </div>
      <div class="card-body">
        <form class="actionForm" action="<?=cn("$module/ajax_general_settings/")?>" method="POST" data-redirect="<?php echo get_current_url(); ?>">
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <div class="form-group">

                <div class="form-label"><?=lang("app.email_notifications")?></div>

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_verification_new_account" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_verification_new_account" value="1" <?=(get_option('is_verification_new_account', 0) == 1)? "checked" : ''?>>
                    <span class="custom-control-label"> <?=lang("app.email_verification_for_new_customer_accounts_preventing_spam_account")?></span>
                  </label>
                </div>   

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_welcome_email" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_welcome_email" value="1" <?=(get_option('is_welcome_email',"") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.new_user_welcome_email")?></span>
                  </label>
                </div>     

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_new_user_email" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_new_user_email" value="1" <?=(get_option('is_new_user_email',"") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.new_user_notification_email")?> <small><?=lang("app.receive_notification_when_a_new_user_registers_to_the_site")?></small></span>
                  </label>
                </div>

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_payment_notice_email" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_payment_notice_email" value="1" <?=(get_option('is_payment_notice_email',"") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.payment_notification_email")?> <small><?=lang("app.send_notification_when_a_new_user_add_funds_successfully_to_user_balance")?></small></span>
                  </label>
                </div>

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_ticket_notice_email" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_ticket_notice_email" value="1" <?=(get_option('is_ticket_notice_email',"") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.ticket_notification_email")?> <small><?=lang("app.send_notification_to_user_when_admin_reply_to_a_ticket")?></small></span>
                  </label>
                </div>

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_ticket_notice_email_admin" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_ticket_notice_email_admin" value="1" <?=(get_option('is_ticket_notice_email_admin', "") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.ticket_notification_email")?> <small><?php echo lang("app.send_notification_to_admin_when_user_open_a_ticket"); ?></small></span>
                  </label>
                </div>

                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_order_notice_email" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_order_notice_email" value="1" <?=(get_option('is_order_notice_email',"") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.order_notification_email")?> <small><?=lang("app.receive_notification_when_a_user_place_order_successfully")?></small></span>
                  </label>
                </div>

              </div>

              <div class="form-group">
                <label class="form-label"><?=lang("app.from")?></label>
                <input class="form-control" name="email_from" value="<?=get_option('email_from',"")?>">
              </div>  

              <div class="form-group">
                <label class="form-label"><?=lang("app.your_name")?></label>
                <input class="form-control" name="email_name" value="<?=get_option('email_name',"")?>">
              </div>
              
              <div class="form-group">
                <div class="form-label"><?=lang("app.email_protocol")?></div>
                <div class="custom-switches-stacked">
                  <label class="custom-switch">
                    <input type="radio" name="email_protocol_type" class="custom-switch-input" value="php_mail" <?=(get_option('email_protocol_type',"php_mail") == 'php_mail')? "checked" : ''?>>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><?=lang("app.php_mail_function")?></span>
                  </label>
                  <label class="custom-switch">
                    <input type="radio" name="email_protocol_type" value="smtp" class="custom-switch-input" <?=(get_option('email_protocol_type',"php_mail") == 'smtp')? "checked" : ''?>> 
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><?=lang("app.smtp")?> <small><?=lang("app.recommended")?></small></span>
                  </label>
                  <small><strong><?=lang("app.note")?></strong> <?=lang("app.sometime_email_is_going_into__recipients_spam_folders_if_php_mail_function_is_enabled")?></small>
                </div>
              </div>  

              <div class="row smtp-configure <?=(get_option('email_protocol_type',"") == 'smtp')? "" : 'd-none'?>">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.smtp_server")?></label>
                    <input class="form-control" name="smtp_server" value="<?=get_option('smtp_server',"")?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.smtp_port")?> <small>(25, 465, 587, 2525)</small></label>
                    <input class="form-control" name="smtp_port" value="<?=get_option('smtp_port',"")?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.smtp_encryption")?></label>
                    <select  name="smtp_encryption" class="form-control square">
                      <option value="none" <?=(get_option('smtp_encryption',"") == 'none')? "selected" : ''?>>None</option>
                      <option value="ssl" <?=(get_option('smtp_encryption',"") == 'ssl')? "selected" : ''?> >SSL</option>
                      <option value="tls" <?=(get_option('smtp_encryption',"") == 'tls')? "selected" : ''?> >TLS</option>
                  </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.smtp_username")?></label>
                    <input class="form-control" name="smtp_username" value="<?=get_option('smtp_username',"")?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.smtp_password")?></label>
                    <input class="form-control" name="smtp_password" value="<?=get_option('smtp_password',"")?>">
                  </div>
                </div>

              </div>
            </div>
            <div class="col-md-8">
              <div class="form-footer">
                <button class="btn btn-primary btn-min-width btn-lg text-uppercase"><?=lang("app.save")?></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
