<?php
/**
 * 
 */
class Post_Checkbox_Field extends CMB_Field {

    public function object_query( $args ) {

        /* query all the posts from this post type */
        $objects = new WP_Query( $args );
        
        /* get an array of the post ids */
        return $objects->posts;

    }

    public function object_reset_query() {
        wp_reset_query();
    }

    public function object_query_args() {

        global $post;

        /* build the query args */
        $object_checkbox_query_args = array(
            'posts_per_page'    => 500,
            'no_found_rows'     => true,
            'fields'            => 'ids'
        );

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

    public function allow_none_output() {}

	/**
	 * html
	 *
	 * this provides the html output for the form input for this field type
	 * it gets the taxonomy terms for the taxonomy declared looping through each term
	 * and show the input checkbox for each.
	 * checkboxes are marked as checked if the term value is in the values array
	 * 
	 * @param  array $values 	the current saved values for the all the taxonomy terms
	 */
    public function html( $values, $object_id ) {        

        /* is this terms id in the values array */
        if( in_array( $object_id, $values ) ) {
            $checked = true;
        } else {
            $checked = false;
        }

        ?>
        <li>
            <label for="<?php echo $this->name ?>-<?php echo esc_attr( $object_id ); ?>">
                <input id="<?php echo $this->name ?>-<?php echo esc_attr( $object_id ); ?>" type="checkbox" name="<?php echo $this->name ?>" value="<?php echo absint( $object_id ); ?>" <?php checked( true, $checked ); ?> />
                <?php echo esc_html( get_the_title( $object_id ) ); ?>
            </label>
        </li>
        <?php        

    }

	/**
     * display
     * controls how the field is displayed inside the metabox
     * this function calls the html function to output each fields input html
     */
    public function display() {
        
        // If there are no values and it's not repeateble, we want to do one with empty string
        if ( ! $this->get_values() && ! $this->args['repeatable'] )
            $values = array( '' );
        else
            $values = $this->get_values();

        /* fire out the title and description of the field */
        $this->title();
        $this->description();

        /* output the display of this field type */
        ?>
        <div class="field-item" data-class="<?php echo esc_attr( get_class( $this ) ); ?>" style="position: relative; <?php echo esc_attr( $this->args['style'] ); ?>">

            <?php

                /* query all the posts from this post type */
                $objects = $this->object_query( $this->object_query_args() );
                
                /* reset the query */
                $this->object_reset_query();
                
                /* check we have posts to output checkboxes for */
                if( ! empty( $objects ) ) {

                    ?>
                    <ul>
                    <?php

                    /* loop through each term */
                    foreach( $objects as $object ) {

                        $this->html( $values, $object );

                    }

                    $this->allow_none_output();

                    ?>
                    </ul>
                    <?php

                } else {

                    ?>
                    <p><?php _e( 'None found.', 'highrise-cmb-fields' ); ?></p>
                    <?php

                }

            ?>

        </div>
        <?php

    }

}