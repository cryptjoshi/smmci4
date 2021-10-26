<div class="row justify-content-md-center">
    <form id="frm-off" method="post" class="form omisepayForm" action="<?=cn($module."/omise/process")?>"
        data-redirect="<?=cn($module)."/omise/success"?>" enctype="multipart/form-data">
        <div class="container  justify-content-center">
            <div class="card p-5">
                <div class="card-header d-flex align-items-center">
                    <h1 class="page-title">
                        <div class="d-flex flex-row align-items-center"><i class="fa fa-bank fa-x2 text-primary"
                                aria-hidden="true"></i> Offline Payment</div>

                    </h1>
                </div>
                <div class="card-body">
                    <div class="pricing p-3 rounded mt-4 d-flex justify-content-between">

                        <div class="d-flex flex-row align-items-center">
                            <!--pricing table-->

                            <label class="form-label">
                                <h1 class="page-title"><?=lang("app.account_number")?><?=sprintf(": %s",$user_info->account_number)?>
                                </h1>
                            </label>


                        </div>
                        <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID"
                            autocomplete="off" value="<?php echo  "ORDS" . rand(10000,99999999)?>">
                        <input type="hidden" name="account_number" value="<?=$user_info->account_number?>" />
                        <input type="hidden" name="payment_method" value="<?=$payment_method?>" />
                        <input type="hidden" name="user_id" value="<?=session("uid")?>" />
                        <input type="hidden" name="{csrf_token}"
                            value="{csrf_hash}">

                    </div>
                    <div class="form-group">

                        <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input" id="customFile" name="imagefile"
                                accept="image/*">
                            <label class="custom-file-label" for="customFile"><?=lang("app.choose_file")?></label>
                        </div>
                        <button id="submit" class="btn"><b><?=lang("app.send_now")?></b></button>

                    </div>

                </div>

            </div>

        </div>

    </form>
</div>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    this.temp_file = $(this).val();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
<style>
.full {
    -moz-transform: scale(3);
    -webkit-transform: scale(3);
    transform: scale(3);
}

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