<?php
/**
 * 
 */
class User_Checkbox_Field extends Post_Checkbox_Field {

    public function object_query( $args ) {

        /* query all the posts from this post type */
        $users = new WP_User_Query( $args );
        $users_ids = array();
        
        /* get an array of the post ids */
        $users = $users->get_results();
        
        /* if we have users */
        if( ! empty( $users) ) {
            foreach( $users as $user ) {
                $users_ids[ $user->ID ] = $user->ID;
            }            
        }
    
        return $users_ids;

    }

    public function object_query_args() {

        global $post;

        /* build the query args */
        $object_checkbox_query_args = array( 'role' => '' );

        /* if we have an query args in the meta box args */
        if( isset( $this->args[ 'query' ] ) ) {

            /* loop through each query args passed in */
            foreach( $this->args[ 'query' ] as $key => $value ) {

                /* add this query arg to the array */
                $object_checkbox_query_args[ $key ] = $value;

            }

        }

        /* make the query args filterable */
        return apply_filters(
            'hdcmbf_object_checkbox_query_args',
            $object_checkbox_query_args,
            $this,
            $post
        );

    }

    public function html( $values, $object_id ) {        

        /* is this terms id in the values array */
        if( in_array( $object_id, $values ) ) {
            $checked = true;
        } else {
            $checked = false;
        }

        /* get the user */
        $user = get_userdata( $object_id );

        ?>
        <li>
            <label for="<?php echo $this->name ?>-<?php echo esc_attr( $object_id ); ?>">
                <input id="<?php echo $this->name ?>-<?php echo esc_attr( $object_id ); ?>" type="checkbox" name="<?php echo $this->name ?>" value="<?php echo absint( $object_id ); ?>" <?php checked( true, $checked ); ?> />
                <?php echo esc_html( $user->display_name ); ?>
            </label>
        </li>
        <?php        

    }

}