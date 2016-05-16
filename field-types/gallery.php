<?php
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