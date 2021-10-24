
<div id="omise" class="tab-pane fade">
    <script type="text/javascript" src="https://cdn.omise.co/omise.js"></script>
    <form class="form actionForm" id="checkout-form" action="<?=cn($module."/process")?>"
        data-redirect="<?=cn($module."/omise")?>" method="POST">
        <div class="row">
            <div class="col-md-12">
                <div class="for-group text-center">
                    <img src="<?=BASE?>/assets/images/omise.svg" width="50%" alt="omise icon">
                    <p class="p-t-10">
                        <small><?=sprintf(lang("you_can_deposit_funds_with_paypal_they_will_be_automaticly_added_into_your_account"), 'omise')?></small>
                    </p>
                </div>
                <label><?=sprintf(lang("select_amount")) ?></label>
                <div id="amount_toggle" class="btn-group special btn-group-toggle " data-toggle="buttons">
                    <?php 
                    
                    foreach (explode(",",json_decode($payment->params)->options->omise_amount_group) as $key => $row): ?>
                    <label class="btn btn-secondary btn-label">
                        <input type="radio" name="options" autocomplete="off" value="<?=$row?>""><?=$row?>
                    </label>
                  <?php endforeach ?>
              </div>
              <div class=" form-group">
                        <label><?=sprintf(lang("amount")) ?>(<?=get_option('currency_code')?>)</label>
                        <input class="form-control square" type="number" id="amount" name="amount" placeholder="0"
                            id="omise_amount">
                        <input type="hidden" name="payment_method" value="omise">
                        <input type="hidden" name="omiseToken">
                </div>

                <div class="form-group">
                    <small class=""><?=lang("transaction_fee")?>:
                        <strong><?=json_decode($payment->params)->option->tnx_fee?>%</strong></small>
                </div>

                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="agree" value="1">
                        <span
                            class="custom-control-label"><?=lang("yes_i_understand_after_the_funds_added_i_will_not_ask_fraudulent_dispute_or_chargeback")?></span>
                    </label>
                </div>

                <div class="form-actions left">
                    <button id="pay-button-thb" type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1">
                        <?=lang("Pay")?>
                    </button>
                </div>

            </div>
        </div>
    </form>
    <script>
    $("#amount_toggle input:radio").change(function() {
        var optionValue = $(this).val();
        $("#amount").val(optionValue);
       // console.log(optionValue);
    });
    </script>
    <style>
    .btn-group.special {
        display: flex;
    }

    .special .btn {
        flex: 1
    }

    .btn-label {
        font-size: 1rem;
        font-weight: 100;
        color: #000;
        width: 100px;
        min-width: unset;
        margin: 0
    }
    </style>
</div>
