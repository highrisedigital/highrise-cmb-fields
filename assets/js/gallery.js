/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @param    object    $    A reference to the jQuery object
 * @since    0.1.0
 */
function renderMediaUploader( $, inputName ) {
	'use strict';

	var file_frame, image_data, json;

	/**
	 * If an instance of file_frame already exists, then we can open it
	 * rather than creating a new instance.
	 */
	if ( undefined !== file_frame ) {

		file_frame.open();
		return;

	}

	/**
	 * If we're this far, then an instance does not exist, so we need to
	 * create our own.
	 *
	 * Here, use the wp.media library to define the settings of the Media
	 * Uploader. We're opting to use the 'post' frame which is a template
	 * defined in WordPress core and are initializing the file frame
	 * with the 'insert' state.
	 *
	 * We're also not allowing the user to select more than one image.
	 */
	file_frame = wp.media.frames.file_frame = wp.media({
		frame:    'post',
		state:    'insert',
		library : {
			type : 'image',
		},
		multiple: true
	});

	/**
	 * Setup an event handler for what to do when an image has been
	 * selected.
	 *
	 * Since we're using the 'view' state when initializing
	 * the file_frame, we need to make sure that the handler is attached
	 * to the insert event.
	 */
	file_frame.on( 'insert', function() {

		// Read the JSON data returned from the Media Uploader
		json = file_frame.state().get( 'selection' ).toJSON();

		$.each( json , function( index, value ) {
			//console.log( value );
			$( '#cmb-gallery-holder' ).append(
				'<div class="gallery-media-item gallery-media-item-'+ value.id +'" id="gallery-item-'+ value.id +'"><img src="' + value.sizes.thumbnail.url + '" /><input type="hidden" value="' + value.id + '" name="' + inputName + '" /><button type="button" class="remove-gallery-item remove-gallery-item-' + value.id + '" data-id="' + value.id + '">X</button></div>'
			);

		});

	});



	// Now display the actual file_frame
	file_frame.open();

}

( function( $ ) {

	var addGalleryItems = function () {

		$( '#add-cmb-gallery-button' ).click( function( evt ) {
			
			// Stop the anchor's default behavior
			evt.preventDefault();

			/* get the input name */
			inputName = $( this ).data( 'name' );

			// Display the media uploader
			renderMediaUploader( $, inputName );

		});

	}

	$(document).ready(addGalleryItems);

	var removeGalleryItems = function() {

		$( '.remove-gallery-item' ).live( 'click', function( evt ) {
			
			console.log( 'remove-clicked' );
			/* get the id of the media item to remove */
			media_id = $( this ).data( 'id' );

			/* remove the item with that id */
			$( '.gallery-media-item-' + media_id ).remove();

		});

	}

	$(document).ready(removeGalleryItems);

}) ( jQuery );