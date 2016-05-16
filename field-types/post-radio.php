<?php
/**
 * 
 */
class Post_Radio_Field extends Post_Checkbox_Field {

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
                <input id="<?php echo $this->name ?>-<?php echo esc_attr( $object_id ); ?>" type="radio" name="<?php echo $this->name ?>" value="<?php echo absint( $object_id ); ?>" <?php checked( true, $checked ); ?> />
                <?php echo esc_html( get_the_title( $object_id ) ); ?>
            </label>
        </li>
        <?php        

    }

    public function allow_none_output() {

        /* if allow none isset and equal to true */
        if( isset( $this->args[ 'allow_none' ] ) && $this->args[ 'allow_none' ] == true ) {

            ?>
            <li style="color: #999999">
                <label for="<?php echo $this->name ?>-0">
                    <input id="<?php echo $this->name ?>-0" type="radio" name="<?php echo $this->name ?>" value="" />
                    <?php _e( 'None', 'highrise-cmb-fields' ); ?>
                </label>
            </li>
            <?php

        }

    }

}