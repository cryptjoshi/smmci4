function Custom(){
    var self= this;
    this.init= function(){
        //Callback
     
        self.mass_order();
        self.mass_change_service();
        // self.calculateMassOrderCharge()
        // if ($("#order_resume").length > 0) {
        //     self.order();
        //     self.calculateOrderCharge();
        // }
        
        // if ($(".sidebar").length > 0) {
        //     _url = window.location.href;
        //     _url = _url.split("?t=");
        //     if(_url.length == 2){
        //         $('[data-content="'+_url[1]+'"]').trigger("click");
        //     }
        // }        
        
    };

    this.mass_change_service=function(){
        //$(document).on("input", ".ajaxmassQuantity" , function(){

        $(document).on("change", ".massChangeService" , function(){
            event.preventDefault();
            _that         = $(this);
       
            _id           = _that.val();
            _dripfeed     = _that.children("option:selected").data("dripfeed");
            _service_type = _that.children("option:selected").data("type");
            _action     = _that.data("url") + _id;
            _data       = $.param({token:token});

             
            switch(_service_type) {
              case "subscriptions":
                $("#new_mass_order input[name=sub_expiry]").val('');
                
                $("#new_mass_order .order-default-link").addClass("d-none");
                $("#new_mass_order .order-default-quantity").addClass("d-none");
                $("#new_mass_order #result_total_charge").addClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                
                $("#new_mass_order .order-subscriptions").removeClass("d-none");
                break;

              case "custom_comments":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-comments").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");
               
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");

                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-default-quantity input[name=quantity]").attr("disabled", true);
                
                $("#new_mass_order .order-subscriptions").addClass("d-none");
                break; 

              case "custom_comments_package":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-comments-custom-package").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");
                

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-default-quantity").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");
                break; 

              case "mentions_with_hashtags":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-usernames").removeClass("d-none");
                $("#new_mass_order .order-hashtags").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                
                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;

              case "mentions_custom_list":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-usernames-custom").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-default-quantity input[name=quantity]").attr("disabled", true);
                
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                
                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;  

              case "mentions_hashtag":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-hashtag").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;

              case "mentions_user_followers":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-username").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");
                break;

              case "mentions_media_likers":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-media").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;  

              case "package":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");
                

                $("#new_mass_order .order-default-quantity").addClass("d-none");
                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;

              case "comment_likes":
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order .order-username").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");
                $("#new_mass_order .order-subscriptions").addClass("d-none");
                break;

              default:
                $("#new_mass_order .order-default-link").removeClass("d-none");
                $("#new_mass_order .order-default-quantity").removeClass("d-none");
                $("#new_mass_order #result_total_charge").removeClass("d-none");

                
                $("#new_mass_order .order-comments").addClass("d-none");
                $("#new_mass_order .order-usernames").addClass("d-none");
                $("#new_mass_order .order-hashtags").addClass("d-none");
                $("#new_mass_order .order-username").addClass("d-none");
                $("#new_mass_order .order-hashtag").addClass("d-none");
                $("#new_mass_order .order-media").addClass("d-none");

                $("#new_mass_order .order-subscriptions").addClass("d-none");

                break;
            }

            if (_dripfeed) {
                $("#new_mass_order .drip-feed-option").removeClass("d-none");
            } else {
                $("#new_mass_order .drip-feed-option").addClass("d-none");
            }


            $.post( _action, _data,function(_result){
               // $("#result_onChangeService").html(_result);
                result = JSON.parse(_result)
                id_ = _that.closest('tr').attr('id').replace('tr','')
                //console.log(id_)
                
             
                $(`#price_${id_}`).val(result.service.price)
               
                
                
                //console.log($("#mass_form"))
                $input = $(`#quantity_${id_}`)
                $input.attr("data-original-title", `Min:${result.service.min},Max:${result.service.max}`)
                $input.attr("data-required",`Min:${result.service.min},Max:${result.service.max}`)
                $input.attr("title", `Min:${result.service.min},Max:${result.service.max}`)
                $input.attr("max",result.service.max)
                $input.attr("min",result.service.min)
                $input.attr("required",true)
                
                if(!$(`.child_${id_}`).is(':visible'))
                $(`.child_${id_}`).toggle();
                // $( `.child_${id_}` ).toggle( "slow", function() {

                // })
               // console.log($input)
               // addrule($input,result.service.max,result.service.min)
                
            })
        })
    }

    this.mass_order= function(){

        $(document).on("input", ".ajaxmassQuantity" , function(){
            _that           = $(this);
            
            id_ = _that.closest('tr').attr('id').replace('tr','')
            _quantity       = _that.val();
            
            //_service_id     = $("#service_id").val();
            _service_max    = _that.attr("max")
            _service_min    = _that.attr("min")
            _service_price  = $(`#price_${id_}`).val();//$("#order_resume input[name=service_price]").val();
            //console.log(_quantity,_service_max,_service_min,_service_price)
            _is_drip_feed   = $(`#new_mass_order input[name=is_drip_feed${id_}]:checked`).val();
            if (_is_drip_feed) {
                _runs           = $(`#new_mass_order input[name=runs${id_}]`).val();
                _interval       = $(`#new_mass_order input[name=interval${id_}]`).val();
                _total_quantity = _runs * _quantity;
                if (_total_quantity != "") {
                    $(`#new_mass_order input[name=total_quantity${id_}]`).val(_total_quantity);
                }
            }else{
                _total_quantity = _quantity;
            }
            _total_charge = (_total_quantity != "" && _service_price != "") ? (_total_quantity * _service_price)/1000 : 0;
            
            _currency_symbol = $(`#new_mass_order input[name=currency_symbol${id_}]`).val();
            
            $(`#new_mass_order input[name=total_charge${id_}]`).val(_total_charge);
            
            $(`#new_mass_order .total_charge${id_} span`).html(_currency_symbol + _total_charge);
            self.summary()
            //$(`#mainTable .total_charge span`).html(_currency_symbol + _total_charge);
 
        })
    }
    this.summary=function(){
        var totalPoints = 0;
        var _currency_symbol="";
        $.when($('#mainTable tbody tr.mass_tr').each(function(i,n) {
            _currency_symbol = $(`#new_mass_order input[name=currency_symbol${id_}]`).val();
            totalPoints += parseFloat($("input[name='total_charge"+i+"'").val())
        })).then(()=>{
            $(`#mainTable .total_charge span`).html(_currency_symbol + totalPoints.toFixed(3));
        });
        
    }
}
Custom= new Custom();
$(function(){
    Custom.init();
});