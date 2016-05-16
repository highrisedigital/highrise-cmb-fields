<?php
/*
Plugin Name: Highrise CMB Fields
Plugin URI: https://highrise.digital
Description: Provides an additonal suite of field types for the Custom Meta Box Framework plugin by Human Made.
Version: 1.0
Author: Mark Wilkinson
Author URI: https://highrise.digital
License: GPLv2 or later
*/

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) exit;

/* define variable for path to this plugin file. */
define( 'HDCMBF_LOCATION', dirname( __FILE__ ) );

/* load required files & functions */
require_once( dirname( __FILE__ ) . '/inc/fields.php' );

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
		$field_types[ 'taxonomy_checkbox' ]	= 'Taxonomy_Checkbox_Field';
		$field_types[ 'email' ]				= 'Email_Field';
		$field_types[ 'list_attachments' ]	= 'List_Attachments_Field';
		$field_types[ 'post_checkbox' ]		= 'Post_Checkbox_Field';
		$field_types[ 'post_radio' ]		= 'Post_Radio_Field';
		$field_types[ 'information' ]		= 'Information_Field';
		$field_types[ 'number' ]			= 'Number_Field';
		$field_types[ 'gallery' ]			= 'Gallery_Field';

		/* return the modified field types array */
    	return $field_types;

	} 

}

$highrise_cmb_fields = new Highrise_CMB_Fields();