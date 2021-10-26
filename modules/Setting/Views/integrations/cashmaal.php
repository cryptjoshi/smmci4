
    <div class="card content">
      <div class="card-header">
        <h3 class="card-title"><i class="fe fe-credit-card"></i> <?=lang("app.cashmaal_Integration")?></h3>
      </div>
      <div class="card-body">
        <form class="actionForm" action="<?=cn("$module/ajax_general_settings")?>" method="POST" data-redirect="<?=cn($module."?t=".$tab)?>">
          <div class="row">

            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <div class="form-label"><?=lang("app.status")?></div>
                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_active_cashmaal" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_active_cashmaal" value="1" <?=(get_option('is_active_cashmaal', "") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("app.active")?></span>
                  </label>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.transaction_fee")?></label>
                    <select name="cashmaal_chagre_fee" class="form-control square">
                      <?php
                        for ($i = 0; $i <= 10; $i++) {
                      ?>
                      <option value="<?=$i?>" <?=(get_option("cashmaal_chagre_fee", 4) == $i)? "selected" : ''?>><?=$i?>%</option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?=lang("app.currency_rate")?></label>
                    <div class="input-group">
                      <span class="input-group-prepend">
                        <span class="input-group-text">1USD =</span>
                      </span>
                      <input type="text" class="form-control text-right" name="cashmaal_currency_rate_to_usd" value="<?=get_option('cashmaal_currency_rate_to_usd', 76)?>">
                      <span class="input-group-append">
                        <span class="input-group-text">INR/PKR</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label"><?=lang("app.cashmaal_web_id")?></label>
                <input class="form-control" name="cashmaal_web_id" value="<?=get_option('cashmaal_web_id',"")?>">
              </div>

            </div> 
            <div class="col-md-12 col-lg-12">
              <div class="form-footer">
                <button class="btn btn-primary btn-min-width btn-lg text-uppercase"><?=lang("app.save")?></button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
