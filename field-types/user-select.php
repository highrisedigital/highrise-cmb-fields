<?php
/**
 * 
 */
class User_Select_Field extends CMB_Select {

	/**
	 * Return the default args for the Taxonomy select field.
	 *
	 * @return array $args
	 */
	public function get_default_args() {
		return array_merge(
			parent::get_default_args(),
			array(
				'query'   => array( 'role' => '' ),
			)
		);
	}


	public function __construct() {

		$args = func_get_args();

		call_user_func_array( array( 'parent', '__construct' ), $args );

		$this->args['data_delegate'] = array( $this, 'get_delegate_data' );

	}

	public function get_delegate_data() {

		$users = $this->get_users();

		if( empty( $users ) ) {
			return array();
		}

		$users_array = array();

		foreach( $users as $user ) {
			$users_array[ $user->ID ] = $user->display_name;
		}

		return $users_array;

	}

	private function get_users() {
		
		$args = $this->get_default_args();

		$users = new WP_User_Query( $args[ 'query' ] );
		return $users->get_results();

	}

}