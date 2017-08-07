<?php 
//admin menu construction
function wordpress_social_stats_admin_menu(){
	global $social_stats_admin_menu_instance;
	$social_stats_admin_menu_instance->add_menus();
}

//social networks main page
function wordpress_social_stats_display_admin_page(){
	global $social_stats_admin_menu_instance;
	$social_stats_admin_menu_instance->admin_page();
}

//'social tips' page
function wordpress_social_stats_display_media_tips_page(){
	global $social_stats_admin_menu_instance;
	$social_stats_admin_menu_instance->social_media_tips_page();
}

?>