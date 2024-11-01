<?php
/*
Plugin Name: Sharon's Shortcode Emojis
Plugin URI: https://sharonmurphy.net/wordpress/shortcode-emojis/
Description: Implement emojis into your posts and pages. Using CSS, transparent PNGs seamlessly integrate with your favorite theme and color scheme.
Author: shpemu
Tags: emoji, emojis, emoticon, emoticons, smiley, smileys, smilie, smilies
Text Domain: shortcode_emojis
Domain Path: /languages
Version: 1.3
Author URI: https://www.sharonmurphy.net/
Last updated: 06-02-2018
*/

defined('ABSPATH') or die('No script kiddies please!');

function shortcode_emojis_load_textdomain() {
  load_plugin_textdomain( 'shortcode_emojis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'shortcode_emojis_load_textdomain' );


function sce_enqueue_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_register_script( 'shortcode_emojis_js', plugin_dir_url( __FILE__ ) . 'inc/shortcode_emojis.js', 'jquery', '1.0', true );
	wp_enqueue_script( 'shortcode_emojis_js' );
	wp_enqueue_style( 'shortcode_emojis_style', plugin_dir_url( __FILE__ ) . 'inc/style.css.php', '', '1.0', 'all');
	wp_enqueue_style( 'shortcode_emojis_keyframes', plugin_dir_url( __FILE__ ) . 'inc/keyframes.css', '', '1.0', 'all');
}
add_action( 'admin_enqueue_scripts', 'sce_enqueue_admin_scripts' );


add_action('media_buttons_context','sce_emoji_tinymce_media_button');
function sce_emoji_tinymce_media_button($context) {
	return $context.='<a href="#TB_inline?width=640&height=480&inlineId=sce_emoji_popup" type="button" class="button thickbox" id="sce_emoji_button"><img src="'.plugins_url( '/images/emojis.png',__FILE__ ).'"></a>';
}


add_action('admin_footer','sce_emoji_shortcode_popup');
function sce_emoji_shortcode_popup() { ?>
	<div id="sce_emoji_popup" style="display:none;">
		<div>
			<h2><?php _e('Shortcode Emojis', 'shortcode_emojis'); ?></h2>
			<p class="description">
				<?php _e('Click on the emoji you would like to add to your post. It will be inserted right where your pointer is, with a \'shortcode\' tag around it.', 'shortcode_emojis'); ?>
				<strong><?php _e('NOTE: make sure you are in "Visual" view, not "Text" view.', 'shortcode_emojis'); ?></strong>
			</p>
			<p><?php _e('Sample', 'shortcode_emojis'); ?>: <code>[sce emoji="happy" /]</code></p>
			<div class="sce_emoji_add">
				<?php //include(plugins_url( '/inc/emoji_dropdown.php',__FILE__ )); ?>

				<fieldset>
					<?php require 'inc/emoji_list.php'; ?>
					<table>
						<tbody>
							<tr>
								<td>
									<label for="sce_emoji_dropdown"><?php _e('Select an Emoji', 'shortcode_emojis'); ?>:</label>
								</td>
								<td>
									<select id="sce_emoji_dropdown" name="sce_emoji_dropdown">
										<option disabled selected></option>
										<?php foreach($shortcode_emojis as $item) {
											$file_name = str_replace('_anim','', $item); ?>
											<option value="<?php echo $file_name; ?>" data-class="sce_emoji-<?php echo $file_name;?>">
												<?php echo $file_name; ?>
											</option>
										<?php } ?>
									</select>
								</td>
								<td>
									<button class="button-primary add_sce_emoji"><?php _e('Add Shortcode', 'shortcode_emojis'); ?></button>
								</td>
								<td>
									<div id="selected_sce"></div>
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
<?php }


add_action('admin_footer','sce_emoji_add_shortcode_to_editor');
function sce_emoji_add_shortcode_to_editor() { ?>
	<script>
		jQuery('.add_sce_emoji').on('click',function() {
			var user_content = jQuery('#sce_emoji_dropdown').val();
			var shortcode = '[sce emoji="'+user_content+'"/]';
			if( !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
				jQuery('textarea#content').val(shortcode);
			} else {
				tinyMCE.execCommand('mceInsertContent', false, shortcode);
			}
			self.parent.tb_remove();
		});
	</script>
<?php }


function sce_emoji_includes() {
	wp_enqueue_style( 'sce-emojis-css', plugin_dir_url( __FILE__ ) . 'inc/style.css.php' );
	wp_enqueue_style( 'sce-emojis-keyframes', plugin_dir_url( __FILE__ ) . 'inc/keyframes.css' );
}
add_action('wp_enqueue_scripts','sce_emoji_includes');


function sce_emoji_func( $atts ) {
	$a = shortcode_atts( array( 'emoji' => array() ), $atts );
	return "<span class=\"sce_emoji-{$a['emoji']}\" title=\"{$a['emoji']}\"></span>";
}
add_shortcode( 'sce', 'sce_emoji_func' );