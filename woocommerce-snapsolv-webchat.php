<?php
/*
Plugin Name: Snapsolv Live Chat
Plugin URI: https://snapsolv.com/
Description: Snapsolv is a FREE and simple customer messaging tool to chat with customers on your website and your Facebook page from one single dashboard. 
Version: 1.0.0
Author: Snapsolv 
Author URI: https://snapsolv.com/
License: GNU General Public License
Text Domain: woocommmerce-snapsolv
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'TAB_MAIN_FILE' ) ) {
	define( 'TAB_MAIN_FILE', __FILE__ );
}

if ( ! defined( 'TAB_URL' ) ) {
	define( 'TAB_URL', plugin_dir_url(__FILE__) );
}

require_once( plugin_dir_path( __FILE__ ) . '/class-snapsolv-webchat.php' );
$WC_TAB = new Snapsolv_Webchat();
