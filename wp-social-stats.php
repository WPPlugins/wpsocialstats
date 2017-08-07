<?php
/*
Plugin Name: WP Social Statistics
Plugin URI: http://www.thewebcitizen.com/social-analytics
Description: The best social analytics plugin to track the performance of your posts and webpages at Facebook, Twitter, Google+, Pinterest, Linkedin, Stumbleupon
Author: WP Social Statistics
Version: 2.0.5
Author URI: http://www.thewebcitizen.com/social-analytics/?utm_source=link&utm_medium=via-wp-installations&utm_campaign=installations
License: GPL2
*/

/*  Copyright 2012  WP Social Statistics  (email : support@wpsocialstats.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php 

// Pre-2.6 compatibility. For the future needs
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl' . '/wp-content'));
}
if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

//defines
define('SOCIAL_STATISTICS_PLUGIN_FILE', __FILE__ );
define('SOCIAL_STATISTICS_PLUGIN_URL', plugins_url("",__FILE__));
define('SOCIAL_STATISTICS_PLUGIN_DIR', dirname(__FILE__));
define('SOCIAL_STATISTICS_TRACKING_URL', "http://www.thewebcitizen.com/social-analytics/wp-admin/admin-ajax.php");

//includes
require( SOCIAL_STATISTICS_PLUGIN_DIR . "/includes/functions.php");
require(SOCIAL_STATISTICS_PLUGIN_DIR . "/classes/social_stats_table.php");
require( SOCIAL_STATISTICS_PLUGIN_DIR . "/classes/social_stats_dashboard.php");

if(is_admin()){
	$social_stats_admin_menu_instance = new WP_Social_Stats_Dashboard();
}

require( SOCIAL_STATISTICS_PLUGIN_DIR . "/includes/admin.php");

function add_thumbnails_for_cpt() {

    global $_wp_theme_features;

    if( empty($_wp_theme_features['post-thumbnails']) ){
        $_wp_theme_features['post-thumbnails'] = array( array('post','page') );
    }
    elseif( true === $_wp_theme_features['post-thumbnails'])
        return;

    elseif( is_array($_wp_theme_features['post-thumbnails'][0]) )
        $_wp_theme_features['post-thumbnails'][0][] = array( array('post','page') );
}

add_action( 'after_setup_theme', 'add_thumbnails_for_cpt');
add_action('init', 'wordpress_social_stats_init');
add_action('admin_menu', 'wordpress_social_stats_admin_menu');

?>