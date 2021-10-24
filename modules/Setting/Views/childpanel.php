<div class="card content">
      <div class="card-header">
        <h3 class="card-title"><i class="fe fe-archive"></i> <?= lang("childpanel_setting") ?></h3>
    </div>
         <div class="card-body">
        <form class="actionForm" action="<?= cn("$module/ajax_general_settings") ?>" method="POST" data-redirect="<?php echo get_current_url(); ?>">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <div class="form-label"><?= lang("Childpanel_status") ?></div>
                        <label class="custom-switch">
                            <input type="hidden" name="is_childpanel_status" value="0">
                            <input type="checkbox" name="is_childpanel_status" class="custom-switch-input" <?= (get_option("is_childpanel_status", 0) == 1) ? "checked" : "" ?> value="1">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?= lang("Active") ?></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?= lang("childpanel_price") ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><?= get_option('currency_symbol') ?></span>
                            </span>
                            <input type="number" class="form-control" name="childpanel_price" value="<?= get_option('childpanel_price') ?>">
                        </div>
                        <small class="text-muted"><span class="text-danger">*</span> <?= lang("this_is_the_price_of_childpanel") ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= lang("Nameserver_1") ?>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ns1" value="<?= get_option('ns1') ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= lang("Nameserver_2") ?>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ns2" value="<?= get_option('ns2') ?>">
                        </div>
                    </div>
                    
                   <h5 class="text-info"> <?=lang("childpanel_description")?></h5 class="text-info">
                   <div class="form-group">
                     <textarea rows="3" name="childpanel_desc" id="childpanel_desc" class="form-control plugin_editor"><?=get_option('childpanel_desc')?></textarea>
                   </div> 
                    
                </div>
                
                <div class="col-md-12">
                    <div class="">
                        <button class="btn btn-primary btn-min-width btn-lg text-uppercase"><?= lang("Save") ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
 