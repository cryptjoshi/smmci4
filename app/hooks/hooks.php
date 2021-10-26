<?php  
defined('BASEPATH') OR exit('No direct script access allowed');  
class WebHook {
 
	public function __construct(){
	 
	}
	public function pre_system(){
	 	echo "<script src='https://js.pusher.com/7.0/pusher.min.js'></script><script>
		 
		 Pusher.logToConsole = true;
		 if(typeof pusher === 'undefined'){
			const pusher = new Pusher(
		  '".getenv('PUSHER_KEY')."', // Replace with 'key' from dashboard
		   {
			 cluster:'".getenv('PUSHER_CLUSTER')."', // Replace with 'cluster' from dashboard
			 forceTLS: true,
		   }
		 );
		 const channel = pusher.subscribe('my-channel');
		 channel.bind('my-event', (data) => {
			var _data     = $.param({id:'".session('uid')."',data:data});
			
			$.post('".cn('add_funds/omise/update')."',_data, function(_result){
				
				setTimeout(function(){
                    pageOverlay.show();
                },1000)
                
					_redirect   = _result.redirect;
				console.log(_result)
                        if(typeof _redirect != 'undefined'){
                            reloadPage(_redirect);
                        }
                  
				
            },'json')
			
		 });
		}
	
		 </script>";
 
	}
}
