<div class="wrap">

	<div class="socnet_wrap">

		<div id='wss-signup' class="updated below-h2">
			<p style='text-align:center'>
				<span style='white-space:nowrap'>Get our <strong>FREE social media publishing schedule template</strong> every marketer needs! </span>
				<span style='white-space:nowrap'><input type='text' id='signup-name' onfocus='if( this.value == "Your Name" ) this.value=""' onblur='if( this.value == "" ) this.value="Your Name"' value="Your Name" /> <input type='text' id='signup-email' value="Your Email"  onfocus='if( this.value == "Your Email" ) this.value=""' onblur='if( this.value == "" ) this.value="Your Email"' /> <input type='button' class='button button-primary' id='signup' value='Grab It' /></span>
			</p>
		</div>
		
		<div class="socnet-header-left">
			<p style="width: 375px;">" From September 1, 2013, WPSocialstats will be Social Analytics by Thewebcitizen. New Awesome product, New Awesome features. You can download it from <a href="http://www.thewebcitizen.com/social-analytics">www.thewebcitizen.com/social-analytics</a> "</p>
		</div>

		<div class="tablefilter">
			<div style="float:left;margin:5px 5px 5px 0px;vertical-align: middle;">
				<form method="get" action="admin.php">
					<span> Show: </span>
					<select name="post_type" id="post_type">
			 			<option value="post" <?php if( 'post' == $options["post_type"] ) echo ' selected="selected"'; ?> >Posts</option>
			 			<option value="page" <?php if( 'page' == $options["post_type"] ) echo ' selected="selected"'; ?> >Pages</option>
					</select>

					<?php echo $category_dropdown; ?>

					<select name="date">
						<option value="default">All time</option>
						<?php echo $date_options; ?>
					</select>

					<select name="per_page">
						<option value="10" <?php if( '10' == $options["per_page"]) echo ' selected="selected" '; ?> >10 items</option>
						<option value="25" <?php if( '25' == $options["per_page"]) echo ' selected="selected" '; ?> >25 items</option>
					</select>
					<input type='hidden' name='page' value='<?php echo self::SOCIAL_STATS_ADMIN_MENU_SLUG; ?>' />
					<input type="submit" class="button" value="Filter"/>
				</form>
			</div>
			<div class="socnet-creds"><a href="http://bit.ly/XZPkSR">Feedback ?</a> or visit <a href="http://www.thewebcitizen.com/social-analytics/?utm_source=link&utm_medium=via-wp-installations&utm_campaign=installations">www.thewebcitizen.com/social-analytics</a>
	    </div>

	    <div class="clear"></div>
	    		
	    <div class='updated below-h2 below-h2' ><p>Last update <?php if( $last_update == "n/a" ) echo "n/a"; else echo $this->_ago( $last_update )." ago"; ?></p></div>

	    <div id='wss_progress' >
	    	<div id='wss_progress_content' >

	   		 <?php if( count( $options["all_data"] ) == 0 ){ ?>

	   		<div id='wss_message' class='below-h2' ></div>
	   		
	   		 <?php } else if( !$options["sortable"] ) { ?>

	    		<div id='wss_message' class="error below-h2"><p>Sorting is disabled..<?php echo count( $options["old_data"] );?> entr<?php if( count( $options["old_data"] ) !== 1 ) echo "ies"; else echo "y"; ?> <?php if( count( $options["old_data"] ) !== 1 ) echo "have"; else echo "has"; ?> missing social stats.. <input type='button' value='Update missing social stats' class="button" id='wss_update_missing' /></p></div>
	   
	    		<div id='wss_progressbar_text' ></div>
	    		<div id='ws_buttons' >
	    			<div id='ws_update_buttons' >
			    		<input type='button' value='Update all social stats' class="button" id='wss_update_all' />
			    		<span class='description' >&nbsp;&nbsp; updates all stats in current selection</span>
			    		<br/>
			    	</div>
		    	</div>
	    	<?php } else { ?>
	   		<div id='wss_message' class='below-h2' ></div>
	    		<div id='wss_progressbar_text' ></div>
	    		<div id='ws_buttons' >
	    			<div id='ws_update_buttons' >
			    		<input type='button' value='Update all social stats' class="button" id='wss_update_all' />
			    		<span class='description' >&nbsp;&nbsp; updates all stats in current selection</span>
			    	</div>
		    	</div>
	    	<?php } ?>
	    	
	    	</div>
	    </div>
	    		

	    <div class="clear"></div>

		<?php $table->display(); ?>

		<div class="socnet_nav" style="margin-top:7px;float:left"><?php //echo $page_links; ?></div>
		<div class="socialmedia-buttons" style="float:right !important;margin-top:7px">
			<span style="position:relative;bottom:2px">Follow Us! We Rock!</span>&nbsp;&nbsp;&nbsp;
			<a href="http://twitter.com/share" class="twitter-share-button" data-text="WP Social Stats is an advanced social media analytics plugin that analyzes how your posts perform on Social Networks" data-count="horizontal" data-url="http://www.twitter.com/wpsocialstats">Tweet</a>
			<g:plusone size="medium" href="http://www.thewebcitizen.com/social-analytics"></g:plusone>
			<iframe src="http://www.facebook.com/plugins/like.php?href=http://www.thewebcitizen.com/social-analytics&send=false&layout=button_count&width=100&show_faces=false&action=like&colorscheme=light&locale=en_US" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
		</div>
	</div>
</div>
<script type='text/javascript' >
	var WSS_MISSING = <?php echo json_encode( $options["old_data"] ); ?>;
	var WSS_ALL = <?php echo json_encode( $options["all_data"] ); ?>;
</script>