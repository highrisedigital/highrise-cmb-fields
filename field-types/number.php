<?php
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