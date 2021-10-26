    <?=Modules::run(get_theme()."/header", false)?>
    <section class="sign-up-form">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Sign Up</h1>
                    <div class="form-container">
                        <form class="actionFormWithoutToast" action="<?=cn("auth/ajax_sign_up")?>" data-redirect="<?=cn('statistics')?>" method="POST" id="signUpForm" data-focus="false">
                            <div class="form-group">
                                <input type="email" class="form-control-input" name="email" required>
                                <label class="label-control" for="semail"><?php echo lang("app.email"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control-input" name="first_name" required>
                                <label class="label-control" for="sname"><?php echo lang("app.first_name"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control-input" name="last_name" required>
                                <label class="label-control" for="sname"><?php echo lang("app.last_name"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>

                            <?php
                                if (get_option('enable_signup_skype_field')) {
                            ?>

                            <div class="form-group">
                                <input type="text" class="form-control-input" name="skype_id" required>
                                <label class="label-control" for="sname"><?php echo lang("app.skype_id"); ?></label>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <input type="password" class="form-control-input" name="password" required>
                                <label class="label-control" for="spassword"><?php echo lang("app.password"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control-input" name="re_password" required>
                                <label class="label-control" for="spassword"><?php echo lang("app.confirm_password"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                              <select  name="timezone" class="form-control square">
                                <?php $time_zones = tz_list();
                                  if (!empty($time_zones)) {
                                    $location = get_location_info_by_ip(get_client_ip());
                                    $user_timezone = $location->timezone;
                                    if ($user_timezone == "" || $user_timezone == 'Unknow') {
                                      $user_timezone = get_option("default_timezone", 'UTC');
                                    }
                                    foreach ($time_zones as $key => $time_zone) {
                                ?>
                                <option value="<?=$time_zone['zone']?>" <?=($user_timezone == $time_zone["zone"])? 'selected': ''?>><?=$time_zone['time']?></option>
                                <?php }}?>
                              </select>
                            </div>

                            <div class="form-group mt-20">
                                <div id="alert-message" class="alert-message-reponse"></div>
                            </div>

                             <?php
                              if (get_option('enable_goolge_recapcha') &&  get_option('google_capcha_site_key') != "" && get_option('google_capcha_secret_key') != "") {
                            ?>
                            <div class="form-group">
                              <div class="g-recaptcha" data-sitekey="<?=get_option('google_capcha_site_key')?>"></div>
                            </div>
                            <?php } ?> 

                            <div class="form-group">
                              <label class="custom-control custom-checkbox">
                                <input type="checkbox" name="terms" class="custom-control-input" />
                                <span class="custom-control-label"><?=lang("app.i_agree_the")?> <a href="<?=cn('terms')?>"><?=lang("app.terms__policy")?></a></span>
                              </label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="form-control-submit-button btn-submit">SIGN UP</button>
                            </div>
                            
                        </form>
                        <div class="text-center text-muted">
                          <?=lang("app.already_have_account")?> <a href="<?=cn()?>"><?=lang("app.login")?></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
   
    <?=Modules::run(get_theme()."/footer", false)?>