<div id="paytm_manual" class="tab-pane fade">
  <form class="form actionForm" action="<?=cn($module."/paytm_manual/verify")?>" data-redirect="<?=cn($module."/success")?>" method="POST">
    <div class="row">
      <div class="col-md-12">


        <div class="for-group text-center">
          <img src="<?=BASE?>/assets/images/paytm.jpg" alt="Paytm icon">
          <p class="p-t-10">Pay on <span class="h4"><?=get_option('paytm_number')?></span></p>
          <img src="<?=get_option('paytm_qr_url')?>">  
        </div>

        <div class="form-group">
          <label><?=sprintf(lang("app.amount_usd"), 'INR')?></label>
          <input class="form-control square" type="number" name="amount" required placeholder="<?=get_option('paytm_payment_transaction_min')?>">
          <input type="hidden" name="payment_method" value="paytm_manual">
        </div>

        <div class="form-group">
          <label>Transaction ID (Enter Exact ID*)</label>
          <input class="form-control square" type="number" required name="txn_id" placeholder="20200311XXXXXXXXXXX">
        </div>

        <div class="form-group">
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="agree" value="1">
            <span class="custom-control-label"><?=lang("app.yes_i_understand_after_the_funds_added_i_will_not_ask_fraudulent_dispute_or_chargeback")?></span>
          </label>
        </div>
        
        <div class="form-actions left">
          <button type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1">
            <?=lang("app.pay")?>
          </button>
        </div>

      </div>  
    </div>
  </form>
</div>