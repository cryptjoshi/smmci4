<div id="main-modal-content">
  <div class="modal-right">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php
          $ids = (!empty($category->ids))? $category->ids: '';
          if ($ids != "") {
            $url = cn($module."/ajax_update/$ids");
          }else{
            $url = cn($module."/ajax_update");
          }
        ?>
        <form class="form actionForm" action="<?=$url?>" data-redirect="<?=cn($module)?>" method="POST">
          <div class="modal-header bg-pantone">
            <h4 class="modal-title"><i class="fa fa-edit"></i> <?=lang("app.edit_category")?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <div class="form-body">
              <div class="row justify-content-md-center">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label ><?=lang('app.name')?></label>
                    <input type="text" class="form-control square"  name="name" value="<?=(!empty($category->name))? $category->name: ''?>">
                  </div>
                </div> 
                
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <label for="eventRegInput1"><?=lang("app.default_sorting_number")?></label>
                    <input type="number" class="form-control square" name="sort"  value="<?=(!empty($category->sort))? $category->sort: ''?>">
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <label><?=lang("app.status")?></label>
                    <select name="status" class="form-control square">
                      <option value="1" <?=(!empty($category->status) && $category->status == 1) ? 'selected' : ''?>><?=lang("app.active")?></option>
                      <option value="0" <?=(isset($category->status) && $category->status != 1) ? 'selected' : ''?>><?=lang("app.deactive")?></option>
                    </select>
                  </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <label for="eventRegInput1"><?=lang("app.default_action")?></label>
                    <select name="action_btn" class="form-control square">
                      <option value="user" <?=(!empty($btn->action_btn) && $btn->action_btn == 'user') ? 'selected' : ''?>><?=lang("app.uer")?></option>
                      <option value="post" <?=(isset($btn->action_btn) && $btn->action_btn == 'post') ? 'selected' : ''?>><?=lang("app.post")?></option>
                      <option value="link" <?=(isset($btn->action_btn) && $btn->action_btn == 'link') ? 'selected' : ''?>><?=lang("app.link")?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <label for="eventRegInput1"><?=lang("app.default_button_text")?></label>
                    <input type="text" class="form-control square" name="text_btn"  value="<?=(!empty($btn->text_btn))? $btn->text_btn: ''?>">
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <label><?=lang("app.active")?></label>
                    <select name="noButton" class="form-control square">
                      <option value="1" <?=(!empty($data->noButton) && $data->noButton == 1) ? 'selected' : ''?>><?=lang("app.active")?></option>
                      <option value="0" <?=(isset($data->noButton) && $data->noButton != 1) ? 'selected' : ''?>><?=lang("app.deactive")?></option>
                    </select>
                  </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                  <label for="eventRegInput1"><?=lang("app.description_show")?></label>
                  <select name="description_text" class="form-control square">
                      <option value="keyword" <?=(!empty($data->description_text) && $data->description_text == 'keyword') ? 'selected' : ''?>><?=lang("app.keyword")?></option>
                      <option value="description" <?=(isset($data->description_text) && $data->description_text != 'keyword') ? 'selected' : ''?>><?=lang("app.description")?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label ><?=lang('Class')?></label>
                    <input type="text" class="form-control square"  name="classname" value="<?=(!empty($category->classname))? $category->classname: ''?>">
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label><?=lang("app.description")?></label>
                    <textarea rows="3" class="form-control square plugin_editor" name="desc" placeholder="About Project">
                      <?=(!empty($api->description))? html_entity_decode($api->description, ENT_QUOTES): ''?>
                    </textarea>
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

<script>
  $(function(){
    $('.datepicker').datepicker({
      autoclose: true,
      startDate: truncateDate(new Date())
    });
    $(".datepicker").datepicker().datepicker("setDate", new Date());

    function truncateDate(date) {
      return new Date(date.getFullYear(), date.getMonth(), date.getDate());
    }
  });
</script>

<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200});
  });
</script>