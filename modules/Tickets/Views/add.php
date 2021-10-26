<div id="main-modal-content">
  <div class="modal-right">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" id="add_new_ticket">
        <form class="form actionForm" action="<?=cn($module."/ajax_add")?>" data-redirect="<?=cn($module)?>" method="POST">
          <div class="modal-header bg-pantone">
            <h4 class="modal-title"><i class="fe fe-edit"></i> <?=lang("app.add_new_ticket")?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body" >
            <div class="form-body">
              <div class="row justify-content-md-center">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label><?=lang("app.subject")?></label>
                    <select name="subject" class="form-control square ajaxChangeTicketSubject">
                      <option value="subject_order"><?=lang("app.order")?></option>
                      <option value="subject_payment"><?=lang("app.poayment")?></option>
                      <option value="subject_service"><?=lang("app.service")?></option>
                      <option value="subject_other"><?=lang("app.other")?></option>
                    </select>
                  </div>

                  <div class="form-group subject-order">
                    <label><?=lang("app.request")?></label>
                    <select name="request" class="form-control square">
                      <option value="refill"><?=lang("app.refill")?></option>
                      <option value="cancellation"><?=lang("app.cancellation")?></option>
                      <option value="speed_up"><?=lang("app.speed_Up")?></option>
                      <option value="other"><?=lang("app.other")?></option>
                    </select>
                  </div>

                  <div class="form-group subject-order">
                    <label><?=lang("app.order_id")?></label>
                    <input class="form-control square" type="text" name="orderid" placeholder="<?=lang("app.for_multiple_orders_please_separate_them_using_comma_example_123451234512345")?>">
                  </div>

                  <div class="form-group subject-payment d-none">
                    <label><?=lang("app.payment")?></label>
                    <select name="payment" class="form-control square">
                      <option value="paypal"><?=lang("app.paypal")?></option>
                      <option value="stripe"><?=lang("app.stripe")?></option>
                      <option value="twocheckout"><?=lang("app.2Checkout")?></option>
                      <option value="other"><?=lang("app.other")?></option>
                    </select>
                  </div>

                  <div class="form-group subject-payment d-none">
                    <label><?=lang("app.transaction_ID")?></label>
                    <input class="form-control square" type="text" name="transaction_id" placeholder="<?=lang("app.enter_the_transaction_id")?>">
                    </select>
                  </div>
                </div> 
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label><?=lang("app.description")?></label>
                    <textarea rows="3" class="form-control square plugin_editor" name="description"></textarea>
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
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200});
  });
</script>