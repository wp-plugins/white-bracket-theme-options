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
		elseif( isset($_POST["save-tag-settings"]) ):
			unset($_POST["save-tag-settings"]);
			$option_data = array();
			foreach($_POST as $value):
				$option_data[] = $value;
			endforeach;
			$option_name = strtolower(str_replace(' ', '_', 'white_bracket_setting_'.$option_data[0][0]));
			$option_value = serialize( array( 'tag' => $option_data[0][0], 'font-size' => $option_data[0][1], 'colour' => $option_data[0][2] ) );
			update_option( $option_name, $option_value );
			$options_updated = 'yes';
			echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($) {';
					echo '$(\'#wb-settings-tab\').addClass(\'active\');';
        			echo '$(\'#wb-content-tab\').removeClass(\'active\');';
					echo '$(\'.wb-content\').hide();';
	       			echo '$(\'.wb-settings\').show();';
	       		echo '});';
			echo '</script>';
		endif;
	endif;

	echo '<div class="wrap">';
		echo '<h1>Theme Options</h1>';
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

		echo '<div class="wb-tabs">';
			echo '<div class="wb-tab active" id="wb-content-tab">Content</div>';
			echo '<div class="wb-tab" id="wb-settings-tab">Settings</div>';
			echo '<div class="wb-tab" id="wb-documentation-tab">Documentation</div>';
		echo '</div>';
		echo '<div class="wb-content">';
			echo '<h3>Current Options</h3>';
			get_current_white_bracket_options();
			echo '<h3>Add New Option</h3>';
			echo '<div class="options-header">';
				echo '<div class="row">';
					echo '<div class="option-title"><h4>Option Title</h4></div>';
					echo '<div class="option-type"><h4>Option Type</h4></div>';
					echo '<div class="option-value" style="width:300px;"><h4>Option Value</h4></div>';
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
					echo '</select>';
				echo '</div>';
				echo '<div class="add-option-button">';
					echo '<button class="add-more-options button button-primary button-large">Add Option</button>';
				echo '</div>';
			echo '</div>';
		echo '</div>';//.content
		echo '<div class="wb-settings">';
			echo '<h3>Settings</h3>';
			get_current_white_bracket_settings();
		echo '</div>';
		echo '<div class="wb-documentation">';
			echo '<h3>Documentation</h3>';
			get_white_bracket_documntation();
		echo '</div>';
	echo '</div>';
}

function get_current_white_bracket_options(){

	global $wpdb;

	$prefix = $wpdb->prefix;
	$current_wb_options = $wpdb->get_results("SELECT * FROM ".$prefix."options WHERE option_name LIKE 'wb_%' ORDER BY option_id ASC");

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
									$settings = array( 'textarea_name' => $name, 'editor_height' => 250 );
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

function get_current_white_bracket_settings(){

	$tags = array();
	$tags[] = array( 'title' => 'H1 Settings (Heading One)', 'tag' => 'h1' );
	$tags[] = array( 'title' => 'H2 Settings (Heading Two)', 'tag' => 'h2' );
	$tags[] = array( 'title' => 'H3 Settings (Heading Three)', 'tag' => 'h3' );
	$tags[] = array( 'title' => 'H4 Settings (Heading Four)', 'tag' => 'h4' );
	$tags[] = array( 'title' => 'H5 Settings (Heading Five)', 'tag' => 'h5' );
	$tags[] = array( 'title' => 'H6 Settings (Heading Six)', 'tag' => 'h6' );
	$tags[] = array( 'title' => 'p Settings (Paragraphs)', 'tag' => 'p' );
	$tags[] = array( 'title' => 'a Settings (Text Links)', 'tag' => 'a' );
	$tags[] = array( 'title' => 'li Settings (List Items)', 'tag' => 'li' );

	foreach($tags as $tag):
		$title = $tag["title"];
		$tag = $tag["tag"];
		echo generate_tag_setting_inputs( $title, $tag );
	endforeach;
}

function generate_tag_setting_inputs( $title, $tag ){

	$font_size = '';
	$colour = '';

	$option_value = get_option('white_bracket_setting_'.$tag);
	if($option_value):
		$value = unserialize($option_value);
		$font_size = $value["font-size"];
		$colour = $value["colour"];
	endif;

	$output = '<div class="row">';
		$output .= '<div class="row-title">'.$title;
			$output .= '<div class="toggle-content"><img src="'.plugin_dir_url( __FILE__ ).'images/toggle-content.png" width="20" height="20" /></div>';
		$output .= '</div>';
		$output .= '<div class="row-content">';
			$output .= '<form action="'.$_SERVER["REQUEST_URI"].'" method="post">';
				$output .= '<input type="hidden" name="'.$tag.'-settings[]" value="'.$tag.'" />';
				$output .= '<div class="label">Font Size:</div>';
				$output .= '<div class="label-input"><input type="text" name="'.$tag.'-settings[]" value="'.$font_size.'" placeholder="e.g. 24" class="font-size" />px</div>';
				$output .= '<div class="splitter"></div>';
				$output .= '<div class="label">Colour:</div>';
				$output .= '<div class="label-input"><input type="text" name="'.$tag.'-settings[]" value="'.$colour.'" placeholder="e.g. #ffffff" class="colour-picker" /></div>';
				$output .= '<div class="splitter"></div>';
				$output .= '<input class="button button-primary button-large" type="submit" name="save-tag-settings" value="Save Settings" />';
			$output .= '</form>';
		$output .= '</div>';
	$output .= '</div>';

	return $output;
}

function get_white_bracket_documntation(){

	$output = '<h4>Standard shortcode</h4>';
	$output .= '<p>[wbo option_name="wb_XXXX"]</p>';

	$output .= '<h4>Remove p tags</h4>';
	$output .= '<p>[wbo option_name="wb_XXXX" wpautop="no"]</p>';

	echo $output;
}