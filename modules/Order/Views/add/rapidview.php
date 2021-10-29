<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalTitle">Post Choose</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <!-- <span aria-hidden="true">&times;</span> -->
                        </button>
            </div>
            <div class="modal-body">
                <section id="landing-services-likes" class="service-section">
                    <div class="service-section">
                        <form id="inline-form" method="post" action="/">
                            <div class="form-inline">
                                <input type="hidden" name="<?php //echo $this->security->get_csrf_token_name(); ?>" value="<?php //echo $this->security->get_csrf_hash(); ?>">
                                <input name='url_path' type="hidden" />
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="inputUsername" class="sr-only">Username</label>
                                    <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Username">
                                </div>
                                <button type="button" id="btn_load" class="btn btn-primary mb-2" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Show post">show post</button>
                            </div>
                        </form>
                        <div class="inner">
                            <div id="service-illustration" >
                            <section id="post-list" class="post-list">
                               
                            </section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <!-- <input type="hidden" name="prev_cursor" value=""> -->
                <input type="hidden" name="_cursor" value="0">
                <button type="button" class="btn btn-secondary" id="load_more"><?=lang("app.load_more")?></button>
                <!-- <button type="button" class="btn btn-secondary" id="prev_cursor"><?=lang("app.prev")?></button>
                <button type="button" class="btn btn-secondary" id="next_cursor"><?=lang("app.next")?></button> -->
                <button type="button" class="btn btn-primary" id="savemodal"><?=lang("app.save_changes")?></button>
            </div>
        </div>
    </div>
</div>

<script>

    function save_modal(){
        
        var category = ['tiktok', 'instagram', 'facebook', 'youtube', 'twitter'].filter((el, index, array) => {
            $selecting = $("select[name='category_id'] option").filter(':selected')
            if ($selecting.text().toLowerCase().includes(el)){
                $selecting.toggleClass('post-overlay')
                return el
            }
        })
       
        switch(category[0]){
            case 'instagram':
                
                if($("#btn_load").is(":hidden"))
                url ='https://www.'+category+'.com/'+$(`input[name='username']`).val()
                else
                url ='https://www.'+category+'.com/p/'+$(`input[name='post-it']`).val()

                $("input[name='link']").prop('value',url);
                break;
            case 'tiktok':
                if($("#btn_load").is(":hidden"))
                url ='https://www.'+category+'.com/'+$(`input[name='username']`).val()
                else
                url ='https://www.'+category+'.com/@'+$(`input[name='username']`).val()+'/video/'+$(`input[name='post-it']`).val()

                $("input[name='link']").prop('value',url);
                break;
            case 'facebook':
                if($("#btn_load").is(":hidden"))
                url ='https://www.'+category+'.com/'+$(`input[name='username']`).val()
                else
                url =$(`input[name='username']`).val()+'/posts/'+$(`input[name='post-it']`).val()

                $("input[name='link']").prop('value',url);
                break;
            case 'twitter':
                if($("#btn_load").is(":hidden"))
                url ='https://www.'+category+'.com/'+$(`input[name='username']`).val()
                else
                url ='https://www.'+category+'.com/p/'+$(`input[name='post-it']`).val()

                $("input[name='link']").prop('value',url);
                break;
            case 'youtube':
                if($("#btn_load").is(":hidden"))
                url ='https://www.'+category+'.com/channel/'+$(`input[name='username']`).val()
                else
                url ='https://www.'+category+'.com/watch?v='+$(`input[name='post-it']`).val()

                $("input[name='link']").prop('value',url);
                break;
        }
        $("#post-list").empty()
        $('#serviceModal').modal('hide');
    }
     
    function addlink(url){
    $(`input[id="attrib-${url}"]`).prop('checked',true)
    }

    $(function() {

    var url_path = "";
    var _arrcursor = [""];
    
    
    $("select[name='category_id']").on('change', function() {

        $("input[name='link']").prop('value','');
        var category = ['tiktok', 'instagram', 'facebook', 'youtube', 'twitter'].filter((el, index, array) => {
            if ($("select[name='category_id'] option").filter(':selected').text().toLowerCase().includes(
                    el))
                return el
        })
        
        if (category.length > 0) {
            $("#modal_view").prop('disabled', false)

            url_path = 'RAPID_' + category[0].toUpperCase()
            //mediatype = category[0].toUpperCase()
            $("input[name='mediatype']").val(category[0].toUpperCase())
            $("input[name='url_path']").prop("value", url_path)

        } else {
            $("#modal_view").prop('disabled', true)
            $("input[name='mediatype']").val("")
        }
        
         
         var _action = "<?php echo cn('order/getdata') ?>",
             _token = '<?php //echo strip_tags($this->security->get_csrf_hash()); ?>',
             _data = $.param({
                         token: _token,
                         category_id: $("select[name='category_id'] option").filter(':selected').val()
                     })
                    $.post(_action, _data, function(_result) {
                     var btn =  JSON.parse(_result);
                     //var btn = JSON.parse(response.data);
                     if(btn!=null){
                     $("#modal_view").prop('value',btn.text_btn)
                     if(btn.action_btn=="user")
                        {
                            $("#btn_load").hide();
                        }else 
                        {
                            $("#btn_load").show();
                        }

                     }
                   
                    })
                   
                
    })
  
    $("#savemodal").on('click',function(){
        save_modal()
    })

    // $("#prev_cursor").on('click',function(){
 
    //     $(`input[name="_cursor"]`).val(parseInt($(`input[name="_cursor"]`).val())>0?parseInt($(`input[name="_cursor"]`).val())-1:0)
     
    //       $("#post-list").empty()
    //           load_view( $(`input[name="_cursor"]`).val())
    //     // }
    // })

    // $("#next_cursor").on('click',function(){

    //     $(`input[name="_cursor"]`).val(parseInt($(`input[name="_cursor"]`).val())<_arrcursor.length?parseInt($(`input[name="_cursor"]`).val())+1:parseInt($(`input[name="_cursor"]`).val()))
     
    //      $("#post-list").empty()
    //          load_view( $(`input[name="_cursor"]`).val())
    // })

    $("#btn_load").on('click', function() {
            var $this = $(this);
            $(this).prop("disabled", true);
            $(this).html(
                `<i class='fa fa-spinner fa-spin '></i>&nbspLoading....`
            );
            $("#post-list").empty()
            load_view(0)
    });
    $("#load_more").on('click', function() {
            var $this = $(this);
            $(this).prop("disabled", true);
            $(this).html(
                `<i class='fa fa-spinner fa-spin '></i>&nbspLoading....`
            );
           // $("#post-list").empty()
            //load_view(1)
            $(`input[name="_cursor"]`).val(parseInt($(`input[name="_cursor"]`).val())<_arrcursor.length?parseInt($(`input[name="_cursor"]`).val())+1:parseInt($(`input[name="_cursor"]`).val()))
            load_view( $(`input[name="_cursor"]`).val())
    });
    function load_view(index){
      
               
                mediatype = $("input[name='mediatype']").val()
                username = $("#inputUsername").val()
                
                var _action = "https://<?=RAPID_ENDPOINT?>/"+mediatype.toLocaleLowerCase()+"/"+username,
                //var _action = "<?=cn('rapid')?>/facebook/getmockup",
                    _token = '<?php //echo strip_tags($this->security->get_csrf_hash()); ?>',
                    _data={
                    //    "key":mediatype.toLocaleLowerCase(),
                        "username":username,
                        "cursor":_arrcursor[index],
                        "token":_token
                    }
                $.get(_action, function(_result) {
                    var response =  JSON.parse(_result);
                    
                    
                 if(_arrcursor.indexOf(_result.cursor_str)==-1) 
                    _arrcursor.push(_result.cursor_str) 
                    //_current =  _result.cursor_str
                    $(`input[name="cursor_str"]`).prop("value",_result.cursor_str)
                     $.each(response,(key,element) =>  {
                      
                        // $("#likes-posts-illustration").append(`<div class="tl post"><img  id="views-post-tl-canvas-`+element.id+`" src="`+element.thumbnail_src+`" size=150 /><option value="`+element.shortcode+`"></option></div>`)
                         $("#post-list").append(`<label class="attribsRadioButton four" for="attrib-${element.shortcode}"><input type="radio" name="post-it" value="${element.shortcode}" id="attrib-${element.shortcode}" ><a href="javascript:addlink('`+element.shortcode+`')" class="post" >
                            <figure class="post-image">
                            <img src="`+element.cover_src+`" alt="">
                            </figure>
                            <span class="post-overlay">
                                <span class="post-likes"></span>
                              
                            </span>
                            <span class="post-title `+mediatype.toLocaleLowerCase()+`"><div>${element.text}</div></span>
                        </a></label>`)
  
                      })
                      $("#btn_load").html("Load post")
                      $("#load_more").prop("disabled",false)
                      $("#load_more").html("Load more")
                    })
        }
       
      });
     
// $('.btn').on('click', function() {
//     var $this = $(this);
//   $this.button('loading');
//     setTimeout(function() {
//        $this.button('reset');
//    }, 8000);
// });

</script>
<style>
    
 
.post-list {
  display: grid;
  grid-template-columns: repeat(3, minmax(100px, 293px));
  justify-content: center;
  grid-gap: 8px;
  padding-top: 5px;
}
.post {
  cursor: pointer;
  position: relative;
  display: block;
}
.post-image {
  margin: 0;
}
.post-image img {
  width: 100%;
  vertical-align: top;
}
.post-overlay {
  background: rgba(0,0,0, .5);
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  display: none;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}
.post:hover .post-overlay {
			display: flex;
}


 
/* input[type="radio"]:checked + span.post-overlay::after */
/* .radio-check {
   background: red;
   display: flex;
background:url(https://cdn1.iconfinder.com/data/icons/mimiGlyphs/16/check_mark.png) no-repeat center center;
    position: absolute;
    content:'';
} */

input[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}
/* IMAGE STYLES */
input[type=radio] + a >figure>img {
  cursor: pointer;
}

/* CHECKED STYLES */
input[type=radio]:checked + a>figure + span {
  /* background:url(https://cdn1.iconfinder.com/data/icons/mimiGlyphs/16/check_mark.png) no-repeat left top; 
  display: flex; */
  
  background:url('<?=BASE?>assets/images/checkmark.jpg');
  background-repeat: no-repeat;
  background-position: center;
  background-size: 32px 32px;

  position: absolute;
  top: 15%; left: 15%;
  width: 32px; height: 32px;
  transform: translate(-50%,-50%);
  color: red;
  font-size: 20px;
  line-height: 80px;
  text-align: center;
  border: 2px solid white;
  border-radius: 50%;
  display: flex;
  
}
.attribsRadioButton four {
background-color: #00acee;
}

.post-title {
    
    position: absolute;
    top: 30%;
    left: 5%;
    color: #fff;
    font-size: 15px;
    display: flex;
}


.post-title .facebook {
    
    position: absolute;
    top: 30%;
    left: 5%;
    color: #000;
    font-size: 15px;
    display: flex;
}
/* input[type="radio"] + label
{
    background-image:url(http://www.clker.com/cliparts/c/q/l/t/l/B/radiobutton-unchecked-sm-md.png); 
    height: 300px;
    width: 300px; 
    display:inline-block;
    padding: 0 0 0 0px;
    cursor:pointer;
}
input[type="radio"]:checked + label 
{
  background-image:url(http://www.clker.com/cliparts/M/2/V/6/F/u/radiobutton-checked-sm-md.png); 
  
 
} */

.post-like,
.post-comments {
  width: 80px;
  margin: 5px;
  font-weight: bold;
  text-align: center;
  display: inline-block;
}

@media screen and (max-width: 768px) {
  .post-list {
    grid-gap: 3px;
  }
}
</style>