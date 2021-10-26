<div id="perfectmoney" class="tab-pane fade">
  <form class="form actionAddFundsForm" action method="POST">
    <div class="row">
      <div class="col-md-12">

        <div class="for-group text-center">
          <img src="<?=BASE?>/assets/images/payments/perfect_money.svg" alt="Perfect Money icon">
          <p class="p-t-10"><small><?=sprintf(lang("app.you_can_deposit_funds_with_paypal_they_will_be_automaticly_added_into_your_account"), 'Perfect Money')?></small></p>
        </div>

        <div class="form-group">
          <label><?=sprintf(lang("app.amount_usd"), get_option("currency_code",'USD'))?></label>
          <input class="form-control square" type="number" name="amount" placeholder="<?=get_option('perfectmoney_payment_transaction_min')?>">
          <input type="hidden" name="payment_method" value="perfectmoney">
        </div>

        <div class="form-group">
          <span class="small"><?=lang("app.note")?> 
            <ul>
              <li><?=lang("app.transaction_fee")?>: <strong><?=(get_option("perfectmoney_chagre_fee", 4))?>%</strong></li>
              <li><?php echo lang("app.clicking_return_to_shop_merchant_after_payment_successfully_completed"); ?></li>
            </ul>
          </span>
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