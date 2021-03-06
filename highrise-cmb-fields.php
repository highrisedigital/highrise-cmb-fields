<?php
/*
Plugin Name: Highrise CMB Fields
Plugin URI: https://highrise.digital
Description: Provides an additonal suite of field types for the <a href="https://github.com/humanmade/Custom-Meta-Boxes">Custom Meta Box Framework plugin by Human Made</a>.
Version: 1.0
Author: Highrise Digital Ltd
Author URI: https://highrise.digital
License: GPLv2 or later
Text Domain: highrise-cmb-fields
*/

/**
 * Copyright (c) 2016 Highrise Digitial Ltd. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * and the Custom Metabox Framework from Human Made (https://hmn.md)
 * https://github.com/humanmade/Custom-Meta-Boxes
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) exit;

/* define variable for path to this plugin file. */
define( 'HDCMBF_LOCATION', dirname( __FILE__ ) );

/* load required files & functions */
require_once( dirname( __FILE__ ) . '/field-types/email.php' );
require_once( dirname( __FILE__ ) . '/field-types/information.php' );
require_once( dirname( __FILE__ ) . '/field-types/number.php' );
require_once( dirname( __FILE__ ) . '/field-types/gallery.php' );
require_once( dirname( __FILE__ ) . '/field-types/list-attachments.php' );
require_once( dirname( __FILE__ ) . '/field-types/post-checkbox.php' );
require_once( dirname( __FILE__ ) . '/field-types/post-radio.php' );
require_once( dirname( __FILE__ ) . '/field-types/user-checkbox.php' );
require_once( dirname( __FILE__ ) . '/field-types/user-radio.php' );
require_once( dirname( __FILE__ ) . '/field-types/user-select.php' );

/**
 * 
 */
class Highrise_CMB_Fields {

	/**
	 * constructor - register hooks & filters
	 */
	public function __construct() {

		/* add our new field types to cmb */
		add_filter( 'cmb_field_types', array( $this, 'add_field_types' ) );

		/* run the init function on the init hook - text domain stuff */
		add_action( 'plugins_loaded', array( $this, 'init' ) );

	}

	/**
	 * Fires on init().
	 * Set up translation for the plugin itself.
	 */
	public function init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'say_what' );
		load_textdomain( 'highrise-cmb-fields', WP_LANG_DIR.'/highrise-cmb-fields/highrise-cmb-fields-' . $locale . '.mo' );
		load_plugin_textdomain( 'highrise-cmb-fields', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * add_field_types
	 *
	 * adds the new field types created by the plugin hooking them into
	 * cmb with the field types filter in construct
	 * @return [type] [description]
	 */
	public function add_field_types( $field_types ) {

		/* add our new field types to the field types array */
		$field_types[ 'email' ]				= 'Email_Field';
		$field_types[ 'list_attachments' ]	= 'List_Attachments_Field';
		$field_types[ 'post_checkbox' ]		= 'Post_Checkbox_Field';
		$field_types[ 'post_radio' ]		= 'Post_Radio_Field';
		$field_types[ 'user_checkbox' ]		= 'User_Checkbox_Field';
		$field_types[ 'user_radio' ]		= 'User_Radio_Field';
		$field_types[ 'user_select' ]		= 'User_Select_Field';
		$field_types[ 'information' ]		= 'Information_Field';
		$field_types[ 'number' ]			= 'Number_Field';
		$field_types[ 'gallery' ]			= 'Gallery_Field';

		/* return the modified field types array */
    	return $field_types;

	} 

}

$highrise_cmb_fields = new Highrise_CMB_Fields();