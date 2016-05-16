<?php
/**
 * Email_field
 *
 * adds an email input type field
 */
class Email_Field extends CMB_Field {

	public function html() {

		/* lets see if there is a placeholder */
		if( isset( $this->args[ 'placeholder' ] ) ) {
			$placeholder = $this->args[ 'placeholder' ];
		} else {
			$placeholder = '';
		}

        ?>
        <p>
            <input type="email" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="<?php echo $this->name ?>" value="<?php echo esc_attr( $this->get_value() ) ?>" />
        </p>
        <?php

    }

    /**
     * do something with the value before its saved
     */
    public function parse_save_value() {
        $this->value = sanitize_email( $this->value );
    }

}

/**
 * Information_Field
 *
 * display information text as a field with cmb
 * requires the actual information to exist in the 'info' arg when declaring
 * the fields with the cmb_meta_boxes filter
 */
class Information_Field extends CMB_Field {

    public function display() {

        /* output the display of this field type */
        ?>
        <div class="field-item" data-class="<?php echo esc_attr( get_class( $this ) ); ?>" style="position: relative; <?php echo esc_attr( $this->args['style'] ); ?>">

            <?php

                /* output the field title / name */
                $this->title();

                /* if we have information text to display */
                if( isset( $this->args[ 'info' ] ) ) {
                    echo '<div class="info">' . $this->args[ 'info' ] . '</div>';
                }

            ?>

        </div>
        <?php

    }

}

/**
 * Number_Field
 *
 * provies the field with the number input type
 */
class Number_Field extends CMB_Field {

    public function html() {

        /* lets see if there is a placeholder */
        if( isset( $this->args[ 'placeholder' ] ) ) {
            $placeholder = $this->args[ 'placeholder' ];
        } else {
            $placeholder = '';
        }

        ?>
        <p>
            <input type="number" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="<?php echo $this->name ?>" value="<?php echo esc_attr( $this->get_value() ) ?>" />
        </p>
        <?php

    }

    /**
     * do something with the value before its saved
     */
    public function parse_save_value() {
        $this->value = intval( $this->value );
    }

}

/**
 * List_Attachments_Field
 *
 * this field lists all of the attachments that have been added to the current field
 * the mime types returned can be manipulated using the hdcmbf_list_attachments_query_args
 * filter.
 */
class List_Attachments_Field extends CMB_Field {
	
	public function html() {
		
		global $post;
			
		/* get the attachment posts for this post */
		$attachments = new WP_Query(
			apply_filters(
				'hdcmbf_list_attachments_query_args',
				array(
					'post_type'			=> 'attachment',
					'post_parent'		=> absint( $post->ID ),
					'post_status'		=> 'inherit',
					'posts_per_page'	=> 100,
					'no_found_rows'		=> true // we are not paginating
				),
				$this,
                $post
			)
		);
		
		/* check we have attachments */
		if( $attachments->have_posts() ) {
			
			?>
			
			<div class="attachments">
				
				<ul class="attachment-list">
				
				<?php
					
					/* loop through attachments */
					while( $attachments->have_posts() ) : $attachments->the_post();
					
						/* get the attachment URL */
						$url = wp_get_attachment_url( get_the_ID() );
					
						?>
						
						<li <?php post_class(); ?>><a href="<?php echo esc_url( $url ); ?>"><?php the_title(); ?></a></li>
						
						<?php
					
					/* end loop */
					endwhile;	
					
				?>
				
				</ul>
				
			</div>
			
			<?php
			
		} else {

			?>
			<p><?php _e( 'This post has no attachments.', 'highrise-cmb-fields' ); ?></p>
			<?php

		} // end if have attachments
		
		/* reset query */
		wp_reset_query();
		
	}

}

/**
 * 
 */
class Post_Checkbox_Field extends CMB_Field {

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
    public function html( $values ) {

        global $post;

        /* build the query args */
        $post_checkbox_query_args = array(
            'posts_per_page'    => 500,
            'no_found_rows'     => true,
            'fields'            => 'ids'
        );

        /* if we have an query args in the meta box args */
        if( isset( $this->args[ 'query' ] ) ) {

            /* loop through each query args passed in */
            foreach( $this->args[ 'query' ] as $key => $value ) {

                /* add this query arg to the array */
                $post_checkbox_query_args[ $key ] = $value;

            }

        }

        /* make the query args filterable */
        $post_checkbox_query_args = apply_filters(
            'hdcmbf_post_checkbox_query_args',
            $post_checkbox_query_args,
            $this,
            $post
        );

        /* query all the posts from this post type */
        $theposts = new WP_Query(
        	$post_checkbox_query_args
        );

        /* get an array of the post ids */
        $theposts = $theposts->posts;
        
        /* reset the query */
        wp_reset_query();

        /* check we have posts to output checkboxes for */
        if( ! empty( $theposts ) ) {

        	/* loop through each term */
            foreach( $theposts as $thepost ) {

                /* is this terms id in the values array */
                if( in_array( $thepost, $values ) ) {
                    $checked = true;
                } else {
                    $checked = false;
                }

                ?>
                <li>
                    <label for="<?php echo esc_attr( $post_type ); ?>-<?php echo esc_attr( $thepost ); ?>">
                        <input id="<?php echo esc_attr( $post_type ); ?>-<?php echo esc_attr( $thepost ); ?>" type="checkbox" name="<?php echo $this->name ?>" value="<?php echo absint( $thepost ); ?>" <?php checked( true, $checked ); ?> />
                        <?php echo esc_html( get_the_title( $thepost ) ); ?>
                    </label>
                </li>
                <?php

            }

        } else {

        	?>
			<p><?php _e( 'No posts found.', 'highrise-cmb-fields' ); ?></p>
			<?php

        }

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

            <ul>
                <?php $this->html( $values ); ?>
            </ul>

        </div>
        <?php

    }

}

/**
 * 
 */
class Post_Radio_Field extends CMB_Field {

    /**
     * html
     *
     * this provides the html output for the form input for this field type
     * it gets the taxonomy terms for the taxonomy declared looping through each term
     * and show the input checkbox for each.
     * checkboxes are marked as checked if the term value is in the values array
     * 
     * @param  array $values    the current saved values for the all the taxonomy terms
     */
    public function html( $values ) {

        global $post;

        /* build the query args */
        $post_radio_query_args = array(
            'posts_per_page'    => 500,
            'no_found_rows'     => true,
            'fields'            => 'ids'
        );

        /* if we have an query args in the meta box args */
        if( isset( $this->args[ 'query' ] ) ) {

            /* loop through each query args passed in */
            foreach( $this->args[ 'query' ] as $key => $value ) {

                /* add this query arg to the array */
                $post_radio_query_args[ $key ] = $value;

            }

        }

        /* make the query args filterable */
        $post_radio_query_args = apply_filters(
            'hdcmbf_post_radio_query_args',
            $post_radio_query_args,
            $this,
            $post
        );

        /* query all the posts from this post type */
        $theposts = new WP_Query(
            $post_radio_query_args
        );

        /* get an array of the post ids */
        $theposts = $theposts->posts;
        
        /* reset the query */
        wp_reset_query();

        /* check we have posts to output checkboxes for */
        if( ! empty( $theposts ) ) {

            /* loop through each term */
            foreach( $theposts as $thepost ) {

                /* is this terms id in the values array */
                if( in_array( $thepost, $values ) ) {
                    $checked = true;
                } else {
                    $checked = false;
                }

                ?>
                <li>
                    <label for="<?php echo esc_attr( $post_type ); ?>-<?php echo esc_attr( $thepost ); ?>">
                        <input id="<?php echo esc_attr( $post_type ); ?>-<?php echo esc_attr( $thepost ); ?>" type="radio" name="<?php echo $this->name ?>" value="<?php echo absint( $thepost ); ?>" <?php checked( true, $checked ); ?> />
                        <?php echo esc_html( get_the_title( $thepost ) ); ?>
                    </label>
                </li>
                <?php

            }

        } else {

            ?>
            <p><?php _e( 'No posts found.', 'highrise-cmb-fields' ); ?></p>
            <?php

        }

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

            <ul>
                <?php $this->html( $values ); ?>
            </ul>

        </div>
        <?php

    }

}

/**
 * 
 */
class Gallery_Field extends CMB_Field {

    /**
     * display
     * controls how the field is displayed inside the metabox
     * this function calls the html function to output each fields input html
     */
    public function display() {

        /* fire out the title and description of the field */
        $this->title();
        $this->description();

        /* output the display of this field type */
        ?>
        <div class="field-item" data-class="<?php echo esc_attr( get_class( $this ) ); ?>" style="position: relative; <?php echo esc_attr( $this->args['style'] ); ?>">

            <button class="button button-primary add-cmb-gallery add_media" type="button" id="add-cmb-gallery-button" data-name="<?php echo esc_attr( $this->name ); ?>" style="margin-bottom: 10px;">Add Images</button>

            <div class="cmb-gallery gallery-clearfix" id="cmb-gallery-holder">

                <?php

                    $values = $this->get_values();

                    /* loop through each value */
                    foreach( $values as $value ) {

                        $this->html( $value );

                    }

                ?>

            </div>

        </div>
        <?php

    }

    public function html( $value ) {

        ?>

        <div class="field-item gallery-media-item gallery-media-item-<?php echo esc_attr( $value ); ?>" data-class="<?php echo esc_attr( get_class( $this ) ); ?> id="gallery-item-<?php echo esc_attr( $value ); ?>>

            <?php

                /* get the attachment source for the media item */
                $attachment_src = wp_get_attachment_image_src( $value, 'thumbnail' );
                $attachment_src = $attachment_src[0];

            ?>

            <img src="<?php echo esc_url( $attachment_src ); ?>" />

            <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo esc_attr( $value ); ?>" />

            <button type="button" class="remove-gallery-item remove-gallery-item-<?php echo esc_attr( $value ); ?>" data-id="<?php echo esc_attr( $value ); ?>">x</button>

        </div>

        <?php      

    }

    /**
     * 
     */
    public function enqueue_styles() {

        /* enqueue our gallery stylesheet */
        wp_enqueue_style( 'hdcmbf_gallery_style', plugins_url( '/assets/css/gallery.css', dirname( __FILE__ ) ) );

    }

    /**
     * 
     */
    public function enqueue_scripts() {

        wp_enqueue_media();

        wp_enqueue_script( 'hdcmbf_gallery_js', plugins_url( '/assets/js/gallery.js', dirname( __FILE__ ) ), array( 'jquery', 'jquery-ui-sortable' ) );

    }

}