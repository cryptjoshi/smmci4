
<div id="service_mass" class="table-responsive-sm">
<input type="hidden" id="massnumber" name="massnumber" value="0"?>
<input type="hidden" name="{csrf_token}"
                        value="{csrf_hash}">
<table id="mainTable" class="table table-responsive table-striped table-min">
    <thead>
        <tr>
      
            <th><?=lang("app.id_name")?></th>
            <th><?=lang("app.price")?></th>
            <th><?=lang("app.quantity")?></th>
            <th><?=lang("app.link")?></th>

            <th><?=lang("app.option")?></th>
            <th></th>
        </tr>
    </thead>
  
    <tbody >
    
        <tr id="0">
            
            <td style="padding-left: 0;">
            <div class="form-group result-services" id="result_services">
                    <select name="mass_order[0][0]"  id="services_option_0" class="chosen-select massChangeService" data-url="<?=cn($module."/ajax_load_services/")?>">
                    <option> <?=lang("app.choose_a_service")?></option>
                 
                </select>
                  </div>
            </td>
            <td><input class="form-control mass-service-link" id="link_0" name="mass_order[0][3]" /></td>
            <td>
            </td>
            <td style="min-width: 80;padding-left:5px;">
                <span>
                <a id="add_0" href="javascript:addrow(0)" class="mass_add"><i id="addbtn" class="addbtn fa fa-external-link-square fa-2x text-primary" aria-hidden="true" style="cursor: pointer;"></i></a>
                <a id="copy_0" href="javascript:copyrow(0)" class="mass_copy">    <i id="copybtn" class="copybtn fa fa-clone fa-2x text-primary" aria-hidden="true" style="cursor: pointer;"></i></a>
                <a href="javascript:delrow(0)" class="mass_del"><i class="delbtn fa fa-trash-o fa-2x"></i> </a>   
            </span>
            </td>
        </tr>
       
    </tbody>
    <tfoot>
     <tr><td><?=lang("app.summary")?></td><td><p class="btn btn-success total_charge"> <span class="charge_number">$0</span></p></td></tr>
    </tfoot>

</table>
</div>
<script src="<?=BASE?>assets/js/mindmup-editabletable.js"></script>
<script src="<?=BASE?>assets/js/numeric-input-example.js"></script>
<script src="<?=BASE?>assets/js/custom.js"></script>
<script>
 
var t = 0;

 
function addrow(id){
 
   // var id = parseInt($("#massnumber").val())+1
   var tr=`<tr id="tr`+id+`" class="mass_tr">`
           
           tr += `
            <td style="padding-left: 0;">
                   <div class="form-group result-services" id="result_services_`+id+`}">
                           <select name="mass_order[`+id+`][0]"  id="services_option_`+id+`" class="chosen-select massChangeService" data-url="<?=cn($module."/ajax_load_services/")?>">
                           <option> <?=lang("app.choose_a_service")?></option>
                          
                </select>
                  </div>
            </td>
            `
    tr += `<td style="padding-left: 0;"><input class="form-control mass-price" name="mass_order[`+id+`][1]"  id="price_`+id+`" readonly /></td>
    <td style="padding-left: 0;"><input type="number" class="form-control mass-quantity ajaxmassQuantity" name="mass_order[`+id+`][2]" id="quantity_${id}" data-toggle="tooltip" data-placement="top" required title=""/></td>
            <td><input class="form-control mass-service-link" id="link_`+id+`" name="mass_order[`+id+`][3]" /></td>
            <td class="clickable" data-toggle="collapse" id="child`+id+`" data-target=".child_`+id+`"><i class="fa fa-sliders" aria-hidden="true" style="font-size:24px"></i></td>
          <td style="min-width: 80;padding-left:5px;">
                <span>
                <a id="add_0" href="javascript:loadrapid(${id})" class="mass_add"><i id="addbtn" class="addbtn fas fa-external-link-square-alt fa-2x text-primary" aria-hidden="true" style="cursor: pointer;"></i></a>
                <a id="copy_0" href="javascript:copyrow(${id})" class="mass_copy">    <i id="copybtn" class="copybtn fa fa-clone fa-2x text-primary" aria-hidden="true" style="cursor: pointer;"></i></a>
                <a href="javascript:delrow(${id})" class="mass_del"><i class="delbtn fas fa-trash-alt fa-2x"></i> </a>   
            </span>
    </tr>
    <tr class="collapse child child_`+id+`">
    <td></td><td></td><td></td><td>
    <div id="new_mass_order">
            <div class="row d-none">
                    <div class="col-md-4  col-sm-12 col-xs-12">
                      <div class="form-group">
                        <label><?=lang("app.minimum_amount")?></label>
                        <input class="form-control square" name="service_min`+id+`" type="text" value="" readonly>
                      </div>
                    </div>

                    <div class="col-md-4  col-sm-12 col-xs-12">
                      <div class="form-group">
                        <label><?=lang("app.maximum_amount")?></label>
                        <input class="form-control square" name="service_max`+id+`" type="text" value="" readonly>
                      </div>
                    </div>

                    <div class="col-md-4  col-sm-12 col-xs-12">
                      <div class="form-group">
                        <label><?=lang("app.price_per_1000")?></label>
                        <input class="form-control square" name="service_price`+id+`" type="text" value="" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group order-comments d-none">
                  <label class="order-comment-label" for=""><?=lang("app.comments")?> <?php lang('app.1_per_line')?></label>
                    <!-- <label for=""><?=lang("app.comments")?> <?php lang('app.1_per_line')?></label> -->
                    <textarea  rows="10" name="comments`+id+`" class="form-control square ajax_custom_comments"></textarea>
                  </div> 

                  <div class="form-group order-comments-custom-package d-none">
                    <label for=""><?=lang("app.comments")?> <?php lang('app.1_per_line')?></label>
                    <textarea  rows="10" name="comments_custom_package`+id+`" class="form-control square"></textarea>
                  </div>

                  <div class="form-group order-usernames d-none">
                    <label for=""><?=lang("app.usernames")?></label>
                    <input type="text" class="form-control input-tags" name="usernames`+id+`" value="usenameA,usenameB,usenameC,usenameD">
                  </div>

                  <div class="form-group order-usernames-custom d-none">
                    <label for=""><?=lang("app.usernames")?> <?php lang('1_per_line')?></label>
                    <textarea  rows="10" name="usernames_custom`+id+`" class="form-control square ajax_custom_lists"></textarea>
                  </div>

                  <div class="form-group order-hashtags d-none">
                    <label for=""><?=lang("app.hashtags_format_hashtag")?></label>
                    <input type="text" class="form-control input-tags" name="hashtags`+id+`" value="#goodphoto,#love,#nice,#sunny">
                  </div>

                  <div class="form-group order-hashtag d-none">
                    <label for=""><?=lang("app.hashtag")?> </label>
                    <input class="form-control square" type="text" name="hashtag`+id+`">
                  </div>

                  <div class="form-group order-username d-none">
                    <label for=""><?=lang("app.username")?></label>
                    <input class="form-control square" name="username`+id+`" type="text">
                  </div>   
                  
                  <!-- Mentions Media Likers -->
                  <div class="form-group order-media d-none">
                    <label for=""><?=lang("app.media_Url")?></label>
                    <input class="form-control square" name="media_url`+id+`" type="link">
                  </div>

                  <!-- Subscriptions  -->
                  <div class="row order-subscriptions d-none">

                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=lang("app.username")?></label>
                        <input class="form-control square" type="text" name="sub_username`+id+`">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=lang("app.new_posts")?></label>
                        <input class="form-control square" type="number" placeholder="<?=lang("app.minimum_1_post")?>" name="sub_posts`+id+`">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=lang("app.quantity")?></label>
                        <input class="form-control square" type="number" name="sub_min`+id+`" placeholder="<?=lang("app.min")?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>&nbsp;</label>
                        <input class="form-control square" type="number" name="sub_max`+id+`" placeholder="<?=lang("app.max")?>">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=lang("app.delay")?> (<?=lang("app.minutes")?>)</label>
                        <select name="sub_delay`+id+`" class="form-control square">
                          <option value="0"><?=lang("app.")?><?=lang("app.no_delay")?></option>
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="30">30</option>
                          <option value="60">60</option>
                          <option value="90">90</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?=lang("app.expiry")?></label>
                        <div class="input-group">
                          <input type="text" class="form-control datepicker" name="sub_expiry`+id+`" onkeydown="return false" name="expiry" placeholder="" id="expiry">
                          <span class="input-group-append">
                            <button class="btn btn-info" type="button" onclick="document.getElementById('expiry').value = ''"><i class="fe fe-trash-2"></i></button>
                          </span>
                        </div>
                      </div>
                    </div>

                  </div>
                  <?php
                    if (get_option("enable_drip_feed","") == 1) {
                  ?>
                  <div class="row drip-feed-option d-none">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="form-label"><?=lang("app.dripfeed")?> 
                          <label class="custom-switch">
                            <span class="custom-switch-description m-r-20"><i class="fa fa-question-circle" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?=lang("app.drip_feed_desc")?>" data-title="<?=lang("app.what_is_dripfeed")?>"></i></span>

                            <input type="checkbox" name="is_drip_feed`+id+`" class="is_drip_feed custom-switch-input" data-toggle="collapse" data-target="#drip-feed" aria-expanded="false" aria-controls="drip-feed">
                            <span class="custom-switch-indicator"></span>
                          </label>
                        </div>
                      </div>

                      <div class="row collapse" id="drip-feed">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label><?=lang("app.runs")?></label>
                            <input class="form-control square ajaxDripFeedRuns" type="number" name="runs`+id+`" value="<?=get_option("default_drip_feed_runs", "")?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label><?=lang("app.interval_in_minutes")?></label>
                            <select name="interval`+id+`" class="form-control square">
                              <?php
                                for ($i = 1; $i <= 60; $i++) {
                                  if ($i%10 == 0) {
                              ?>
                              <option value="<?=$i?>" <?=(get_option("default_drip_feed_interval", "") == $i)? "selected" : ''?>><?=$i?></option>
                              <?php }} ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label><?=lang("app.total_quantity")?></label>
                            <input class="form-control square" name="total_quantity`+id+`" type="number" disabled>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php }?>
                  <div class="form-group" id="result_total_charge`+id+`">
                    <input type="hidden" name="total_charge`+id+`" value="0.00">
                    <input type="hidden" name="currency_symbol`+id+`" value="<?=get_option("currency_symbol", "")?>">
                    <p class="btn btn-info total_charge`+id+`"><?=lang("app.total_charge")?> <span class="charge_number">$0</span></p>
                    
                    <?php
                      $user = $model->get("balance, custom_rate", 'general_users', ['id' => session('uid')]);
                      if ($user->custom_rate > 0 ) {
                    ?>
                    <p class="small text-muted"><?=lang("app.custom_rate")?>: <span class="charge_number"><?=$user->custom_rate?>%</span></p>
                    <?php }?>
                    <div class="alert alert-icon alert-danger d-none" role="alert">
                      <i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i><?=lang("app.order_amount_exceeds_available_funds")?>
                    </div>
                  </div>
                </div>
                </td>
    </tr>`
            
           $("#massnumber").prop('value',id)
           
           if($('#mainTable tbody tr').length>1){
           $('#mainTable tbody tr:last').after(tr);
           }else {
           $('#mainTable tbody').append(tr);
           }
   
    $(".chosen-select").chosen({width: "95%"})
    
    $.when($(`#services_option_${id} .chosen-search-input`).change()).then(()=>{
        $("#services_option_"+id).empty();
       // console.log(id)
         $.post("<?=cn($module."/service_search")?>",{"key":$(`#services_option_${id} .chosen-search-input`).val(),"token":'<?php //echo strip_tags(csrf_hash()); ?>'},
         function(response){
           var res = JSON.parse(response)
           
           $.when($.each(res.service,(key,element) =>  {
                $("#services_option_"+id).append(`<option value="${element.id}" data-type="${element.type}" data-dripfeed="${element.dripfeed}">
                ID:${element.id}-${element.name}-${element.price}
                </option>`) 
              //  $(".chosen-select").trigger("chosen:updated");
               // $(".chosen-results").append(`<li class="active-result" data-option-array-index="${element.id}">${element.id}-${element.name}-${element.price}</li>`)
             })).then(()=>$(`#services_option_${id}`).trigger("chosen:updated"));
           
             
         })
    })
    
}
 
function copyrow(val){
    var clone_id = parseInt(val)
     
    addrow(clone_id+1);
    var val_select = $("#services_option_"+clone_id).val()
     
    $.post("<?=cn($module."/load_services")?>",{"id":val_select,"token":'<?php //echo strip_tags($this->security->get_csrf_hash()); ?>'},
         function(response){
           var res = JSON.parse(response),
           _id = parseInt($("#massnumber").val())
           $.when($.each(res.service,(key,element) =>  {
                $("#services_option_"+_id).append(`<option value="${element.id}" data-type="${element.type}" data-dripfeed="${element.dripfeed}">
                ID:${element.id}-${element.name}-${element.price}
                </option>`) 
             })).then(()=>$(`#services_option_${_id}`).trigger("chosen:updated"));
           
             
         })


   title =  $(`#quantity_${clone_id}`).attr("data-original-title")
   price =  $(`#price_${clone_id}`).val()
  // console.log(title)
   $(`#quantity_${_id}`).attr("title",title)
   $(`#price_${_id}`).val(price)
  // summary()


   
 
}
function summary(){
    var totalPoints = 0;
    $('#mainTable tbody tr').each(function() {
        var $tr = $(this);
        
        $("input[id^='quantity_']", $tr).each(function(i,n) { 
        
        if(parseFloat($(n).val())>0){
           
            totalPoints += (parseFloat($(n).val())*parseFloat($(`input[id="price_${i}"]`).val()))
            _currency_symbol = $(`#new_mass_order input[name=currency_symbol${i}]`).val();
            $(`#mainTable .total_charge span`).html(_currency_symbol + totalPoints);
            //$(`#summary`).html(totalPoints+"$")
        }
        });
    });
}

function loadrapid(id){
   var val_select =  $(`#services_option_${id}`).val()
   
    $.post("<?=cn($module."/load_services")?>",{"id":val_select,"token":'<?php //echo strip_tags($this->security->get_csrf_hash()); ?>'},
         function(response){
           var res = JSON.parse(response),
           _name = res.service[0].name,
           mediatype=['tiktok', 'instagram', 'facebook', 'youtube', 'twitter'].filter((el, index, array) => {
            if (_name.toLowerCase().includes(
                    el))
                return el
        })
         $("input[name='mediatype']").val(mediatype[0].toUpperCase())
           $('#serviceModal').modal('show');
         })

    
}



function delrow(id){
   
    if(id>0){
    $("#tr"+id).remove();
    
    }
}

 


        
        $(document).on('click',"td[id^='child']",function (event) {
            _that = $(this)
            id_ = _that.closest('tr').attr('id').replace('tr','')
            var t = $(this).data('target');
            //if($(`${t}`).is(':visible'))
                $(`${t}`).toggle();
            //console.log(event)
        })
    
        $(document).on('keyup',"input[id^='quantity_']",function(e)
        {
            $input = $(this)
            // $input.validate()
            // $input.rules("add", {
            //     number: true,
            //     max: $input.attr('data-max'),
            //     min: $input.attr('data-min'),
            //     messages: {
            //     number: "Please enter a valid Quantity"
            //     }
            // })
            
            if($input.val()>$input.attr('max')){
                //alert("Lower")
                $input.addClass("is-invalid").focus();
            } else  if($input.val()<$input.attr('min'))
            {
                $input.addClass("is-invalid").focus();
               // alert("Upper")
            } else 
            {
                $input.removeClass("is-invalid").focus();
            }
        })
    
</script>

<style>
 table .collapse.in {
	display:table-row;
}
 tr.child>td {
     background-color: #fff;
    border-top: none !important;
}
    .result-services{
        width: 500px !important;
    }
    .mass-quantity {
        width: 80px;
    }
    
    .mass-price {
        width: 80px;
        text-align: right;
    }
    .mass-service-link{
        width:300px;
    }

    .table-min {
        min-height: 480px;
    }
</style>