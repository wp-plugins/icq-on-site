<?php
/**
 * Plugin Name: ICQ On-Site
 * Plugin URI: http://wordpress.org/extend/plugins/icq-on-site/
 * Description: ICQ On-Site is a fast, high performance and most user-friendly application. A Live chat solution that will increase engagement and the exposure of your site. Let your users chat with their friends and share your pages with them.
 * Version: Beta1.3
 * Author: Keren Ramot
 */

function icqonsite_js() {
	$lang = get_option('icq_option_language') ? get_option('icq_option_language') : 'en';
	echo '<script src="http://c.icq.com/siteim/icqbar/js/partners/initbar_'.$lang.'.js" language="javascript" type="text/javascript"  charset="utf-8"></script>';
}

add_action( 'wp_head', 'icqonsite_js' );


// create custom plugin settings menu
add_action('admin_menu', 'icq_create_menu');

function icq_create_menu() {
	//create new top-level menu
	add_menu_page('ICQ Plugin Settings', 'ICQ Settings', 'administrator', __FILE__, 'baw_settings_page',plugins_url('/img/icon.png', __FILE__));
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}

 
function register_mysettings() {
	//register our settings
	register_setting( 'icq-settings-group', 'icq_option_language' );
}


function baw_settings_page() {
	$selected_option = get_option('icq_option_language') ? get_option('icq_option_language') : 'en';
	$url = "http://www.icq.com/siteim/icqbar/js/partners/json/supported_langs.json";
	$jsonString = file_get_contents($url);
	$decoded = json_decode($jsonString);
	$html_lang_options = '';
	foreach ($decoded->lang as $key => $value){
		$html_lang_options .= '<option value="'.$key.'" >'.ucwords($value).'</option>';
	}
	?>
	<div class="wrap">
		<h2>ICQ On-Site</h2>
		<br/>
		<form method="post" action="options.php" name="icqLangForm">
			<?php settings_fields( 'icq-settings-group' ); ?>
			<div>
				Language:&nbsp;&nbsp;
				<select name="icq_option_language" id="icq_option_language">
					<?php echo $html_lang_options ?>
				</select>
				&nbsp;&nbsp;
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			<div>
		</form>
		<script>
			var getLangOptions = <?php echo file_get_contents($url) ?>;
			var myselect = document.getElementById("icq_option_language");
			var icqThisHtml = '';
			for(var i in getLangOptions.lang){
				thisValue = i; 
				thisOption = getLangOptions.lang[i];
				icqThisHtml += '<option value="'+thisValue+'" >'+thisOption+'</option>';
			};
			
			var langOptionSelected = "<?php echo get_option('icq_option_language'); ?>";
			if(langOptionSelected!=""){
				for (var i=0; i<myselect.options.length; i++){
					if (myselect.options[i].value==langOptionSelected){
						myselect.options[i].selected = true;
						break;
					}
				}	
				
			};
		</script>
	</div>
<?php }



