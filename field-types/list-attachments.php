<?php
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
		$hdcmbf_list_attachments_query_args = array(
            'post_type'         => 'attachment',
            'post_parent'       => absint( $post->ID ),
            'post_status'       => 'inherit',
            'posts_per_page'    => 100,
            'no_found_rows'     => true // we are not paginating
		);

        /* if we have an query args in the meta box args */
        if( isset( $this->args[ 'query' ] ) ) {

            /* loop through each query args passed in */
            foreach( $this->args[ 'query' ] as $key => $value ) {

                /* add this query arg to the array */
                $hdcmbf_list_attachments_query_args[ $key ] = $value;

            }

        }

        /* make the query args filterable */
        $hdcmbf_list_attachments_query_args = apply_filters(
            'hdcmbf_list_attachments_query_args',
            $hdcmbf_list_attachments_query_args,
            $this,
            $post
        );

        /* get the attachment posts for this post */
        $attachments = new WP_Query(
            $hdcmbf_list_attachments_query_args
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