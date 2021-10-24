
<div class="row justify-content-md-center">
<form id="paymentFrm" method="post" class="form omisepayForm" id="checkout" action="<?=cn($module."/omise/otp")?>" data-redirect="<?=cn($module)."/omise/otp"?>">
        <div class="container  justify-content-center">

                    <div class="card p-5">
                        <div class="card-header d-flex align-items-center">
                            <h1 class="page-title">
                                <div class="d-flex flex-row align-items-center"> <img
                                        src="https://www.truemoney.com/wp-content/uploads/2020/11/truemoney-wallet-logo-1x.png"
                                        class="rounded" width="100%"></div>

                            </h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="omise_phonenumber" class="form-label"><?=lang("phone_number")?>:</label>
                                <input type="text" id="omise_phonenumber" class="form-control" name="omise_phonenumber">
                                <input type="hidden" name="payment_method" value="<?=$payment_method?>" />
                                <button id="submit" class="btn btn-primary"><b><?=lang("send_now")?></b></button>
                                <?=lang( 'one_time' ); ?>
                            </div>
                         
                        </div>
                    
                    </div>
         
        </div>
        <input type="hidden" name="user_id" value="<?=session("uid")?>" />
                    <input type="hidden" name="{csrf_token}"
                        value="{csrf_hash}">
    </form>
</div>
<script>
    $("#submit").on("click", function() {

    })
     //$("#paymentFrm").attr("data-redirect",'<?=cn($module."/omise")?>'+'/'+optionValue);
</script>
<style>
form .row-1 {
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 0.5rem;
    outline: none;
    width: 100%;
    min-width: unset;
    border-radius: 5px;
    background-color: rgba(221, 228, 236, 0.301);
    border-color: rgba(221, 228, 236, 0.459);
    margin: 2vh 0;
    overflow: hidden
}

.btn {
    width: 100%;
    background-color: rgb(65, 202, 127);
    border-color: rgb(65, 202, 127);
    color: white;
    justify-content: center;
    padding: 2vh 0;
    margin-top: 3vh
}

.btn:focus {
    box-shadow: none;
    outline: none;
    box-shadow: none;
    color: white;
    -webkit-box-shadow: none;
    -webkit-user-select: none;
    transition: none
}

.btn:hover {
    color: white
}
</style>