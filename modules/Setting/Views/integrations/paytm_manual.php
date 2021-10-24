
    <div class="card content">
      <div class="card-header">
        <h3 class="card-title"><i class="fe fe-credit-card"></i> <?=lang("Paytm_Integration")?></h3>
      </div>
      <div class="card-body">
        <form class="actionForm" action="<?=cn("$module/ajax_general_settings")?>" method="POST" data-redirect="<?=cn($module."?t=".$tab)?>">
          <div class="row">

            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <div class="form-label"><?=lang("Status")?></div>
                <div class="custom-controls-stacked">
                  <label class="custom-control custom-checkbox">
                    <input type="hidden" name="is_active_paytm" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_active_paytm_manual" value="1" <?=(get_option('is_active_paytm_manual', "") == 1)? "checked" : ''?>>
                    <span class="custom-control-label"><?=lang("Active")?></span>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="form-label">Paytm number</label>
                <input type="number" class="form-control" name="paytm_number" value="<?=get_option('paytm_number',"")?>">
              </div>
              <div class="form-group">
                <label class="form-label">Paytm Email</label>
                <input class="form-control" name="paytm_email" value="<?=get_option('paytm_email',"")?>">
              </div>

              <div class="form-group">
                <label class="form-label">Paytm Email Password</label>
                <input class="form-control" name="paytm_password" value="<?=get_option('paytm_password',"")?>">
              </div>
              <div class="form-group">
                <label class="form-label">QR Image URL</label>
                <input class="form-control" name="paytm_qr_url" value="<?=get_option('paytm_qr_url',"")?>">
              </div>

            </div> 
            <div class="col-md-12 col-lg-12">
              <div class="form-footer">
                <button class="btn btn-primary btn-min-width btn-lg text-uppercase"><?=lang("Save")?></button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
