<?php

add_action( 'admin_menu', 'white_bracket_theme_options_setup' );

function white_bracket_theme_options_setup() {
    add_theme_page('Theme Options', 'Theme Options', 'manage_options', 'white-bracket-theme-options', 'white_bracket_theme_options');
}

function white_bracket_theme_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $wpdb;
	$options_saved = 'no';
	$options_updated = 'no';

	if( $_POST ):
		if( isset($_POST["save-options"]) ):
			unset($_POST["save-options"]);
			foreach($_POST as $value):
				$option_name = strtolower(str_replace(' ', '_', 'wb_'.$value[0]));
				$option_title = sanitize_text_field($value[0]);
				$option_type = sanitize_text_field($value[1]);
				$option_text = sanitize_text_field($value[2]);
				$option_value = serialize( array( 'option_title' => $option_title, 'option_type' => $option_type, 'option_text' => $option_text ) );
				update_option( $option_name, $option_value, 'yes' );
				$options_saved = 'yes';
			endforeach;
		elseif( isset($_POST["update-options"]) ):
			unset($_POST["update-options"]);
			foreach($_POST as $value):
				if( $value[0] == 'delete' ):
					$option_name = strtolower(str_replace(' ', '_', 'wb_'.$value[1]));
					delete_option( $option_name );
				else:
					$option_name = strtolower(str_replace(' ', '_', 'wb_'.$value[0]));
					$option_title = sanitize_text_field($value[0]);
					$option_type = sanitize_text_field($value[1]);
					$option_text = stripslashes($value[2]);
					$option_value = serialize( array( 'option_title' => $option_title, 'option_type' => $option_type, 'option_text' => $option_text ) );
					update_option( $option_name, $option_value );
				endif;
			endforeach;
			$options_updated = 'yes';
		endif;
	endif;

	echo '<div class="wrap">';
		echo '<h2>Theme Options</h2>';
		if( $options_saved == 'yes' ):
			echo '<div class="success">';
				echo '<p>Options Saved!</p>';
			echo '</div>';
		endif;
		if( $options_updated == 'yes' ):
			echo '<div class="success">';
				echo '<p>Options Updated!</p>';
			echo '</div>';
		endif;
		echo '<h3>Current Options</h3>';
		get_current_white_bracket_options();
		echo '<h3>Add New Option</h3>';
		echo '<div class="options-header">';
			echo '<div class="row">';
				echo '<div class="option-title"><h4>Option Title</h4></div>';
				echo '<div class="option-type"><h4>Option Type</h4></div>';
				echo '<div class="option-value"><h4>Option Value</h4></div>';
			echo '</div>';
		echo '</div>';

		echo '<form action="'.$_SERVER["REQUEST_URI"].'" method="post" enctype="multipart/form-data">';
			echo '<div class="add-new-option-container"></div>';
			echo '<input class="button button-primary button-large" type="submit" name="save-options" value="Save Options" />';
		echo '</form>';

		echo '<div class="add-more-options-container">';
			echo '<div class="option-type-select-text">';
				echo '<p>What type of option would you like to add?</p>';
			echo '</div>';
			echo '<div class="option-type-select">';
				echo '<select name="option-type">';
					echo '<option value="input">Input Box</option>';
					echo '<option value="wysiwyg">WYSIWYG Editor</option>';
					//echo '<option value="file">File</option>';
				echo '</select>';
			echo '</div>';
			echo '<div class="add-option-button">';
				echo '<button class="add-more-options button button-primary button-large">Add Option</button>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function get_current_white_bracket_options(){

	global $wpdb;

	$prefix = $wpdb->prefix;
	$current_wb_options = $wpdb->get_results("SELECT * FROM ".$prefix."options WHERE option_name LIKE '%wb_%' ORDER BY option_id ASC");

	if( $current_wb_options ):
		echo '<div class="row">';
			echo '<div class="option-checkbox"><h4>Delete</h4></div>';
			echo '<div class="option-title"><h4>Title</h4></div>';
			echo '<div class="option-shortcode"><h4>Shortcode</h4></div>';
		echo '</div>';
		echo '<div class="options-header">';
			echo '<form action="'.$_SERVER["REQUEST_URI"].'" method="post">';
				foreach( $current_wb_options as $option ):
					$option_value = unserialize(unserialize($option->option_value));
					echo '<div class="row">';
						echo '<div class="row-title">';
							echo '<div class="option-checkbox"><input type="checkbox" name="'.$option->option_name.'[]" value="delete" /></div>';
							echo '<div class="option-title"><h4>'.$option_value["option_title"].'</h4></div>';
							echo '<div class="option-shortcode">[wbo option_name="'.$option->option_name.'"]</div>';
							echo '<div class="toggle-content"><img src="'.plugin_dir_url( __FILE__ ).'images/toggle-content.png" width="20" height="20" /></div>';
						echo '</div>';
						echo '<div class="row-content">';
							if( $option_value["option_type"] == 'input' ):
								echo '<div class="option-value">';
									echo '<input type="hidden" name="'.$option->option_name.'[]" value="'.$option_value["option_title"].'" />';
									echo '<input type="hidden" name="'.$option->option_name.'[]" value="'.$option_value["option_type"].'" />';
									echo '<input type="text" name="'.$option->option_name.'[]" value="'.$option_value["option_text"].'" />';
								echo '</div>';
							elseif( $option_value["option_type"] == 'wysiwyg' ):
								$name = $option->option_name.'[]';
								echo '<div class="option-value">';
									echo '<input type="hidden" name="'.$name.'" value="'.$option_value["option_title"].'" />';
									echo '<input type="hidden" name="'.$name.'" value="'.$option_value["option_type"].'" />';
									$settings = array( 'textarea_name' => $name, 'textarea_rows' => 5 );
									wp_editor( $option_value["option_text"], $option->option_name, $settings );
								echo '</div>';
							endif;
						echo '</div>';
					echo '</div>';
				endforeach;
				echo '<input class="button button-primary button-large" type="submit" name="update-options" value="Update Options" />';
			echo '</form>';
		echo '</div>';
	else:
		echo '<p>No options have been set up</p>';
	endif;
}