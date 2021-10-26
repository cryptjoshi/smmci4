
<div id="main-modal-content">
  <div class="modal-right">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php
          $ids = (!empty($api->ids))? $api->ids: '';
          if ($ids != "") {
            $url = cn($module."/ajax_update/$ids");
          }else{
            $url = cn($module."/ajax_update");
          }
        ?>
        <form class="form actionForm" action="<?=$url?>" data-redirect="<?=cn($module)?>" method="POST">
          <div class="modal-header bg-pantone">
            <h4 class="modal-title"><i class="fa fa-edit"></i> <?=lang("app.edit_api")?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <div class="form-body">
              <div class="row justify-content-md-center">

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <small class="text-primary"><?=lang("app.add_edit_provider_note_desc")?></small>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label ><?=lang("app.name")?></label>
                    <input type="text" class="form-control square" name="name" value="<?=(!empty($api->name))? $api->name: ''?>">
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label >URL</label>
                    <input type="text" class="form-control square" name="api_url" value="<?=(!empty($api->url))? $api->url: ''?>">
                  </div>
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label ><?=lang("app.api_key")?></label>
                    <input type="text" class="form-control square" name="api_key" value="<?=($api->key)? hide_api_key($api->key) : ''?>">
                  </div>
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label><?=lang("app.status")?></label>
                    <select name="status" class="form-control square">
                      <option value="1" <?=(!empty($api->status) && $api->status == 1)? 'selected': ''?>><?=lang("app.active")?></option>
                      <option value="0" <?=(isset($api->status) && $api->status != 1)? 'selected': ''?>><?=lang("app.deactive")?></option>
                    </select>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label><?=lang("app.description")?></label>
                    <textarea rows="3" id="editor" class="form-control square" name="description"><?=(!empty($api->description))? $api->description: ''?></textarea>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1"><?=lang("app.submit")?></button>
            <button type="button" class="btn round btn-default btn-min-width mr-1 mb-1" data-dismiss="modal"><?=lang("app.cancel")?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
