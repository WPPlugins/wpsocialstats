jQuery(document).ready(function($){

	var updating_all = false;

	$("#signup").click(function(){

		var email = $("#signup-email").val(),
			name  = $("#signup-name").val();

		if( name.indexOf("Your Name") > -1 ){

			alert("Please enter your name");
		}	
		else if( name.replace(/[^a-zA-Z]/g,"").length < 2 ){

			alert("Please enter your name");
		}
		else if( !email.match( /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/ )){

			alert("Please enter valid email");
		}
		else{

			$.post( wss_main.ajaxurl +"?"+new Date().getTime() , { "action" : "wss_signup" , "email": email , "name" : name } , function(){

			});

			$("#wss-signup p").text("Thanks for signing up...");

			setTimeout(function(){
				$("#wss-signup").fadeOut();
			},5000);
		}
	})

	var currentIndex,working = false,started_time,refresh_interval,items = new Array();

	function isWorking() {
		if(working)
			return "Stats are currently being updated.. You will be cancelling update if you leave the page";
	}

	window.onbeforeunload = isWorking;

	window.WSS_INIT = function(){

		$("table.wp-list-table.entries").each(function(){

			var $sorted_column = $(this).find("th.sorted");

			if( $sorted_column.size() > 0 ){

				var index = $(this).find("th").index( $(this).find("th.sorted") );

				$("tbody tr",this).each(function(){

					$(this).find("td").eq( index ).addClass("sorted");
					
				});
			}
		});
	
		$("#wss_update_all").click(function(e){

			if( working ) return;

			working = true;

			updating_all =  true;

			$("#wss_update_all,#wss_update_missing").css("opacity",0.5);

			currentIndex = 0;

			items = WSS_ALL;

			started_time = new Date().getTime();

			start_ajax();

		});
	
		$("#wss_update_missing").click(function(e){

			if( working ) return;

			working = true;

			$("#wss_update_all,#wss_update_missing").css("opacity",0.5);

			currentIndex = 0;

			items = WSS_MISSING;

			started_time = new Date().getTime();

			start_ajax();

		});

	}

	var refresh = function(){

		working = false;

		$("#wss_update_all,#wss_update_missing").css("opacity",1);

		window.location.reload();
	}

	var start_ajax = function(){

		$("#wss_message").removeClass("error").addClass("updated").html( "<p>Updating social stats</p>" );

		$("#wss_progressbar_text").html( "<p><strong>Completed</strong> : <span id='wss_progressbar' ></span><br/><span id='ws_progress_remaining' ></span></p>" );

		$("#wss_progressbar").progressBar({
			boxImage: wss_main.pluginurl +'/images/progressbar.gif',		
			barImage:  {
				0:	wss_main.pluginurl +'/images/progressbg_red.gif',
				30: wss_main.pluginurl +'/images/progressbg_orange.gif',
				70: wss_main.pluginurl +'/images/progressbg_green.gif'
			},
			callback : function( e ){

			} 
		});

		if( currentIndex < items.length ){
			
			ajax();
		}
	}

	var ajax = function(){

		var percent =  Math.floor( ( currentIndex + 1 ) * 100 * 10 / items.length ) / 10 ;

		$.post( wss_main.ajaxurl +"?"+new Date().getTime() , { "action" : "wss_update_stats" , "id": items[currentIndex] , "percent" : percent , "all" : updating_all ? "yes" : "no" } , function(){

			currentIndex++;

			$("#wss_progressbar").progressBar( percent );

			var current_time = new Date().getTime();

			var passed_seconds = Math.floor( ( current_time - started_time ) / 1000 )  ;

			var seconds_per_item = passed_seconds / currentIndex;

			var remaining_time = Math.floor( seconds_per_item * ( items.length - currentIndex ) );

			var remaining_minutes = Math.floor( remaining_time / 60 );

			var remaining_seconds = remaining_time%60;

			$("#ws_progress_remaining").html("<strong>Remaining</strong> : "+remaining_minutes+" "+(remaining_minutes!==1?"minutes":"minute")+" "+remaining_seconds+" "+(remaining_seconds!==1?"seconds":"second") );

			if( currentIndex < items.length ){
				
				ajax();
			}
			else {

				updating_all = false;

				$("#wss_message").html( "<p>Updating 100% done! Reloading page in 3 seconds..</p>" );

				var count = 0;

				refresh_interval = setInterval(function(){ 

					count++;

					$("#wss_message").html( "<p>Updating 100% done! Reloading page in "+( 3 - count )+" seconds..</p>" );

					if( count == 3 ){

						clearInterval( refresh_interval );

						refresh(); 
					}

				},1000);
			}


		});
	};

	WSS_INIT();

});