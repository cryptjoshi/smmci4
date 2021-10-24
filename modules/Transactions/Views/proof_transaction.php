<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><?=lang("show_slip")?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <!-- <span aria-hidden="true">&times;</span> -->
        </button>
      </div>
      <div class="modal-body align-items-center">
      <input type="text" name="transaction_id" id="transaction_id" value="" hidden/>
      <img src="" name="transaction_slip" id="transaction_slip" class="transaction_slip" alt=""/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang("cancel")?></button>
        <button type="button" class="proof btn btn-primary" data-redirect="<?=cn()?>transactions" data-url="<?=cn()?>transactions/ajax_update_slip"><?=lang("proof")?></button>
      </div>
    </div>
  </div>
</div>
<style>
    .modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px; /* Adjusts for spacing */
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
.transaction_slip {
        border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 200px;
}
</style>
<script>
    $(document).ready(()=>{
        $(".open-slip").on("click",function(){
            pageOverlay.show();
            transaction_id = $(this).data('id')
         
            $("#transaction_id").val(transaction_id)
            $('#staticBackdrop').modal('show');
            
            var _action = $(this).data('action'),
                _data   = _data + '&' + $.param({id:transaction_id,token:token});
            $.post(_action,_data,(_result)=>{
                setTimeout(function(){
                    pageOverlay.hide();
                },1000)
                _result = JSON.parse(_result);
                setTimeout(function(){
                    $("#transaction_slip").attr("src",_result.slip)
                    },1000)
               
            })
        })
        $(".proof").on("click",function(){
            pageOverlay.show();
            var _action = $(this).data('url'),
                _redirect = $(this).data('redirect'),
                transaction_id=$("#transaction_id").val()
                _data   =  $.param({id:transaction_id,token:token});
                $.post(_action,_data,(_result)=>{
              
               

                if (is_json(_result)) {
                    _result = JSON.parse(_result);
                    setTimeout(function(){
                        notify(_result.message, _result.status);
                        $('#staticBackdrop').modal('hide');
                    },1000)
                    setTimeout(function(){
                        if(_result.status == 'success' && typeof _redirect != "undefined"){
                            pageOverlay.hide();
                            reloadPage(_redirect);
                        }
                    }, 1000)
                }else{
                    setTimeout(function(){
                        $("#result_notification").html(_result);
                    }, 1500)
                }
               
            })
        })
    })
</script>