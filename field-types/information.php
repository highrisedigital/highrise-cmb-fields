<?php
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