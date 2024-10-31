<?php
/*
Plugin Name: WPS Comming soon page
Description: IndiaNic coming soon page plugin with 2 layouts
Version: 1.0
Author:sandip.chhaya@indianic.com
*/
class WPS_Comingsoon {

	var $pluginPath;
	var $pluginUrl;
	var $rootPath;
	var $wpdb;


	function __construct() {

		global $wpdb;
		$this->wpdb = $wpdb;
		$this->ds = DIRECTORY_SEPARATOR;
		$this->pluginPath = dirname(__FILE__) . $this->ds;
		$this->rootPath = dirname(dirname(dirname(dirname(__FILE__))));
		$this->pluginUrl = WP_PLUGIN_URL ."/".trim(dirname(plugin_basename(__FILE__)));

		// Admin side action and filters
		add_action('admin_menu', array($this, 'wps_coomingsoon_register_menu'));
		add_action('admin_init', array($this,'wps_coupon_add_admin_JS_CSS'));
		add_action('wp_ajax_nopriv_unlock_page', array($this,'unlock_page') );
		add_action('wp_ajax_unlock_page', array($this,'unlock_page') );
	}
	
	

	/** Register plugin menus **/
	function wps_coomingsoon_register_menu() {
		add_options_page('Coming Soon Page Settings','Coming Soon','manage_options','options_cooming_soon',array($this, 'wps_comingsoon_options'));
	}

	/** Sort code setting function **/
	function wps_comingsoon_options()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __("You do not have sufficient permissions to access this page.","coupontext") );
		}

		//wp_enqueue_script( 'jquery-ui-timepicker', plugins_url('/js/jquery-ui-timepicker-addon.js', __FILE__), array('jquery') );
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui', plugins_url('/js/jquery-ui.css', __FILE__)); /* As there is no default style in WP, we needs to include */
		wp_enqueue_script( 'wps-time-picker');
		/* As there is no default style in WP, we needs to include the same from external source */
	?>
		        
	<?php if(get_option('online_date') == '') {?>
	<script>
	jQuery(document).ready(function() {
		var currentDate = new Date();
		jQuery('#online_date').datepicker({dateFormat: 'yy-mm-dd',minDate: '0'}).datepicker('setDate', new Date());
		jQuery('#online_time').timepicker();
	});
	</script>
	<?php } else {?>
	<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {

		jQuery('#online_time').timepicker();
		jQuery('#online_date').datepicker({dateFormat: 'yy-mm-dd',minDate: '0'});
	});
	</script>
	<?php } ?>	
		<div class="wrap"><?php screen_icon(); ?></div><h2><?php _e('Coming Soon.... Page Settings');?></h2>

		<form method="post" action="options.php">
		<?php settings_fields('wpscmsoon-settings-group'); ?>
		
		<table class="form-table">
		<tbody>
		<tr valign="top">
		<th scope="row"><?php _e('Status');?></th>
		<td> <fieldset><legend class="screen-reader-text"><span><?php _e('Status');?></span></legend><label for="page_status">
		<input name="page_status" type="checkbox" id="page_status" value="1" <?php checked('1', get_option('page_status')); ?> />
		<?php _e('Checked to set coming soon page');?></label>
		</fieldset></td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="blogdescription"><?php _e('Front Site Access Keyword');?></label></th>
		<td>
		<input name="front_side_keyword" id="front_side_keyword" value="<?php echo get_option('front_side_keyword');?>" class="regular-text" type="text">
		<p class="description"><?php _e(" [front_side_keyword='your input value'] you needs to pass this string into URL")?></p>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><label for="blogname"><?php _e('Description');?></label></th>
		<td>
		<textarea name="desc_coming_soon" id="desc_coming_soon" class="large-text code" rows="3"><?php echo esc_textarea( get_option('desc_coming_soon') ); ?></textarea></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><label for="blogdescription"><?php _e('Online Date');?></label></th>
		<td>
		<input name="online_date" id="online_date" value="<?php echo get_option('online_date');?>" class="regular-text" type="text" size="10" >
		<input name="online_time" id="online_time" value="<?php echo get_option('online_time');?>" class="regular-text" type="text" size="10" >
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="siteurl"><b><?php _e('Display Settings:');?></b></label></th>
		<td>
			<table class="form-table">
			<tbody>
				<tr valign="top">
				<th scope="row"><label for="blogname"><?php _e('Theme')?></label></th>
				<td>
					<?php $setlight = (get_option('thmemeset') == 'light')?'selected="selected"':'';?>
					<?php $setdark = (get_option('thmemeset') == 'dark')? 'selected="selected"':'';?>
					<?php $setdefault= (get_option('thmemeset') == '')? 'selected="selected"':'';?>
					
					<select name="thmemeset" id="thmemeset">
					<option <?php echo $setlight; $setdefault;?> value="light"><?php _e('Light')?></option>
					<option value="dark" <?php echo $setdark;?>><?php _e('Dark')?></option>
					</select>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><label for="blogname"><?php _e('Title')?></label></th>
				<td>
					<input name="title_comingsoon" id="title_comingsoon" value="<?php echo get_option('title_comingsoon');?>" class="regular-text" type="text">
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><label for="blogname"><?php _e('Logo Path')?></label></th>
				<td>
					<input name="logo_path" id="logo_path" value="<?php echo get_option('logo_path');?>" class="regular-text" type="text">
					<input type="button" id="logo_button" value="Add Logo File" class="button button-primary button-large"/> 
					<p class="description"><?php _e('Select logo from default media library')?></p>
				</td>
				</tr>
				
				</tr>
				<tr valign="top">
				<th scope="row"><label for="blogname"><?php _e('Favicon')?></label></th>
				<td>
					<input name="favicon_comingsoon" id="favicon_comingsoon" value="<?php echo get_option('favicon_comingsoon');?>" class="regular-text" type="text">
					<input type="button" id="favicon_button" value="Add Favicon File" class="button button-primary button-large"/> 
					<p class="description"><?php _e('Select favicon icon from default media library')?></p>
				</td>
				</tr>
				
			</tbody>
			</table>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="home"><?php _e('Meta Title')?></label></th>
		<td><input name="meta_title" id="meta_title" value="<?php echo get_option('meta_title');?>" class="regular-text" type="text"></td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="admin_email"><?php _e('Meta Description')?> </label></th>
		<td><input name="meta_desc" id="meta_desc" value="<?php echo get_option('meta_desc');?>" class="regular-text" type="text">
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><label for="admin_email"><?php _e('Meta Keywords')?> </label></th>
		<td><input name="meta_keywords" id="meta_keywords" value="<?php echo get_option('meta_keywords');?>" class="regular-text" type="text">
		</td>
		</tr>
		<tr valign="top"><th><h3>Social Icons</h3></th>
		<td>
		<table class="form-table permalink-structure">
			<tbody>
			<tr>
				<th><label><input name="set_fb" type="checkbox" value="1" <?php checked('1', get_option('set_fb'));?>>&nbsp;<?php _e('Facebook')?></label></th>
				<td>Account Name:<input name="set_facebook" id="set_facebook" value="<?php echo get_option('set_facebook');?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th><label><input name="set_tw" type="checkbox" value="1" <?php checked('1', get_option('set_tw'));?>>&nbsp;<?php _e('Twitter')?></label></th>
				<td>Account Name:<input name="set_twitter" id="set_twitter" value="<?php echo get_option('set_twitter');?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th><label><input name="set_go" type="checkbox" value="1" <?php checked('1', get_option('set_go'));?>>&nbsp;<?php _e('Google+')?></label></th>
				<td>Account Name:<input name="set_googleplus" id="set_googleplus" value="<?php echo get_option('set_googleplus');?>" class="regular-text" type="text"></td>
			</tr>
		
		</tbody></table></td>
		</tr>
		</tbody></table>
		<script type="text/javascript">
		jQuery("#favicon_button").click(function() {
			window.send_to_editor = function(html) {
				imgurl = jQuery("img",html).attr("src");
				jQuery("#favicon_comingsoon").val(imgurl);
				tb_remove();
			}
			tb_show("", "media-upload.php?post_id=1&type=image&TB_iframe=true");
			return false;
		});

		jQuery("#logo_button").click(function() {
			window.send_to_editor = function(html) {
				imgurl = jQuery("img",html).attr("src");
				jQuery("#logo_path").val(imgurl);
				tb_remove();
			}
			tb_show("", "media-upload.php?post_id=1&type=image&TB_iframe=true");
			return false;
		});

		</script>	
		<?php do_settings_sections( 'wpscmsoon-settings-group' ); ?>
		<?php submit_button(); ?>
		
		<div class="clear"></div>

		<?php
	}

	/** Add plugin admin JS and CSS  **/
	function wps_coupon_add_admin_JS_CSS(){

		/*  Script for event edit page */
		wp_register_script( 'wps-time-picker', plugins_url( '/js/jquery-ui-timepicker.js', __FILE__ ),array(
		'jquery',
		'jquery-ui-datepicker',
		),isset($version),true);

		register_setting( 'wpscmsoon-settings-group', 'page_status' );
		register_setting( 'wpscmsoon-settings-group', 'desc_coming_soon' );
		register_setting( 'wpscmsoon-settings-group', 'online_date' );
		register_setting( 'wpscmsoon-settings-group', 'online_time' );
		register_setting( 'wpscmsoon-settings-group', 'thmemeset' );
		register_setting( 'wpscmsoon-settings-group', 'logo_path' );
		register_setting( 'wpscmsoon-settings-group', 'title_comingsoon' );
		register_setting( 'wpscmsoon-settings-group', 'favicon_comingsoon' );
		register_setting( 'wpscmsoon-settings-group', 'meta_title' );
		register_setting( 'wpscmsoon-settings-group', 'meta_desc' );
		register_setting( 'wpscmsoon-settings-group', 'meta_keywords' );
		register_setting( 'wpscmsoon-settings-group', 'front_side_keyword' );
		register_setting( 'wpscmsoon-settings-group', 'set_fb' );
		register_setting( 'wpscmsoon-settings-group', 'set_facebook' );
		register_setting( 'wpscmsoon-settings-group', 'set_tw' );
		register_setting( 'wpscmsoon-settings-group', 'set_twitter' );
		register_setting( 'wpscmsoon-settings-group', 'set_go' );
		register_setting( 'wpscmsoon-settings-group', 'set_googleplus' );

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

	}
	
	function unlock_page(){
		update_option( 'page_status', 0 );
		wp_redirect( home_url() ); exit;
	}
	
} // class over

$page_status = get_option('page_status');
$front_side_keyword = get_option('front_side_keyword');

if(!isset($front_side_keyword) &&  $front_side_keyword == $_REQUEST['front_side_keyword']) { $page_status = 0; }
if($page_status == 1 && basename($_SERVER['SCRIPT_NAME']) != 'wp-login.php' && !is_admin()) {
	/** Initialized first action **/
	add_action('plugins_loaded', 'wps_comingsoon_page_template');
	function wps_comingsoon_page_template()
	{
		$wps_comingsoon = new WPS_Comingsoon();
		$urlPath = $wps_comingsoon->pluginUrl;
		$siteUrl = get_option("siteurl");
		$page_template = dirname( __FILE__ ) . '/page-wpscomingsoon.php';
		include_once($page_template);
		exit();
	}
}
add_action("init", "register_wps_comingsoon_plugin");
function register_wps_comingsoon_plugin() {
	new WPS_Comingsoon();
}

register_activation_hook(__FILE__, 'wps_coomingsoon_install');
global $jal_db_version;
$jal_db_version = "1.0";

/** Basic installation **/
function wps_comingsoon_install() {
	global $wpdb;
	global $jal_db_version;
}
$installed_ver = get_option("jal_db_version");
if ($installed_ver != $jal_db_version) {
	wps_comingsoon_install();
}