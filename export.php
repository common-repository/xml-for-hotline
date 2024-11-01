<?php if (!defined('ABSPATH')) {exit;} // Защита от прямого вызова скрипта
function xfhu_export_page() { 
 $feed_id = '1'; // (string)
 if (isset($_REQUEST['xfhu_submit_send_select_feed'])) {
  if (!empty($_POST) && check_admin_referer('xfhu_nonce_action_send_select_feed', 'xfhu_nonce_field_send_select_feed')) {
	$feed_id = $_POST['xfhu_num_feed'];
  } 
 }

 $status_sborki = (int)xfhu_optionGET('xfhu_status_sborki', $feed_id);
 if (isset($_REQUEST['xfhu_submit_action'])) {
  if (!empty($_POST) && check_admin_referer('xfhu_nonce_action', 'xfhu_nonce_field')) {
	do_action('xfhu_prepend_submit_action', $feed_id);  
	
	$feed_id = sanitize_text_field($_POST['xfhu_num_feed_for_save']);
	
	$unixtime = current_time('timestamp', 1); // 1335808087 - временная зона GMT (Unix формат)
	xfhu_optionUPD('xfhu_date_save_set', $unixtime, $feed_id, 'yes', 'set_arr');

	if (isset($_POST['xfhu_ufup'])) {
		xfhu_optionUPD('xfhu_ufup', sanitize_text_field($_POST['xfhu_ufup']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_ufup', '0', $feed_id, 'yes', 'set_arr');
	} 
	xfhu_optionUPD('xfhu_whot_export', sanitize_text_field($_POST['xfhu_whot_export']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_feed_assignment', sanitize_text_field($_POST['xfhu_feed_assignment']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_firmName', sanitize_text_field($_POST['xfhu_firmName']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_firmId', sanitize_text_field($_POST['xfhu_firmId']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_rate', sanitize_text_field( $_POST['xfhu_rate']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_stock_days_default', sanitize_text_field( $_POST['xfhu_stock_days_default']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_pickup_options_days_default', sanitize_text_field( $_POST['xfhu_pickup_options_days_default']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_desc', sanitize_text_field($_POST['xfhu_desc']), $feed_id, 'yes', 'set_arr');
	if (isset($_POST['xfhu_var_desc_priority'])) {
		xfhu_optionUPD('xfhu_var_desc_priority', sanitize_text_field($_POST['xfhu_var_desc_priority']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_var_desc_priority', '0', $feed_id, 'yes', 'set_arr');
	}	
	xfhu_optionUPD('xfhu_main_product', sanitize_text_field($_POST['xfhu_main_product']), $feed_id, 'yes', 'set_arr');
	if (isset($_POST['xfhu_allow_group_id_arr'])) {
		xfhu_optionUPD('xfhu_allow_group_id_arr', serialize($_POST['xfhu_allow_group_id_arr']), $feed_id);
	} else {xfhu_optionUPD('xfhu_allow_group_id_arr', serialize(array()), $feed_id);}
	xfhu_optionUPD('xfhu_clear_get', sanitize_text_field($_POST['xfhu_clear_get']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_behavior_stip_symbol', sanitize_text_field($_POST['xfhu_behavior_stip_symbol']), $feed_id, 'yes', 'set_arr');
	if (isset($_POST['xfhu_no_default_png_products'])) {
		xfhu_optionUPD('xfhu_no_default_png_products', sanitize_text_field($_POST['xfhu_no_default_png_products']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_no_default_png_products', '0', $feed_id, 'yes', 'set_arr');
	}
	if (isset($_POST['xfhu_skip_products_without_pic'])) {
		xfhu_optionUPD('xfhu_skip_products_without_pic', sanitize_text_field($_POST['xfhu_skip_products_without_pic']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_skip_products_without_pic', '0', $feed_id, 'yes', 'set_arr');
	}
	if (isset($_POST['xfhu_skip_missing_products'])) {
		xfhu_optionUPD('xfhu_skip_missing_products', sanitize_text_field($_POST['xfhu_skip_missing_products']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_skip_missing_products', '0', $feed_id, 'yes', 'set_arr');
	}
	if (isset($_POST['xfhu_skip_backorders_products'])) {
		xfhu_optionUPD('xfhu_skip_backorders_products', sanitize_text_field($_POST['xfhu_skip_backorders_products']), $feed_id, 'yes', 'set_arr');
	} else {
		xfhu_optionUPD('xfhu_skip_backorders_products', '0', $feed_id, 'yes', 'set_arr');
	}
	xfhu_optionUPD('xfhu_oldprice', sanitize_text_field($_POST['xfhu_oldprice']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_vendor', sanitize_text_field($_POST['xfhu_vendor']), $feed_id, 'yes', 'set_arr');			
	xfhu_optionUPD('xfhu_code', sanitize_text_field($_POST['xfhu_code']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_code_post_meta', sanitize_text_field($_POST['xfhu_code_post_meta']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_guarantee', sanitize_text_field($_POST['xfhu_guarantee']), $feed_id, 'yes', 'set_arr');	
	xfhu_optionUPD('xfhu_guarantee_type', sanitize_text_field($_POST['xfhu_guarantee_type']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_guarantee_value', sanitize_text_field($_POST['xfhu_guarantee_value']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_guarantee_post_meta', sanitize_text_field($_POST['xfhu_guarantee_post_meta']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_barcode', sanitize_text_field($_POST['xfhu_barcode']), $feed_id, 'yes', 'set_arr');
	xfhu_optionUPD('xfhu_manufacture', sanitize_text_field($_POST['xfhu_manufacture']), $feed_id, 'yes', 'set_arr');
	if (isset($_POST['xfhu_params_arr'])) {
		xfhu_optionUPD('xfhu_params_arr', serialize($_POST['xfhu_params_arr']), $feed_id);
	} else {xfhu_optionUPD('xfhu_params_arr', serialize(array()), $feed_id);}

	if (isset($_POST['xfhu_use_delivery'])) {
		xfhu_optionUPD('xfhu_use_delivery', sanitize_text_field($_POST['xfhu_use_delivery']), $feed_id, 'yes', 'set_arr');
	} else {xfhu_optionUPD('xfhu_use_delivery', '', $feed_id, 'yes', 'set_arr');}

	if (isset($_POST['xfhu_delivery_number'])) {
		xfhu_optionUPD('xfhu_delivery_number', sanitize_text_field($_POST['xfhu_delivery_number']), $feed_id, 'yes', 'set_arr');
		$z = (int)sanitize_text_field($_POST['xfhu_delivery_number']) + 1;
		$field_names_arr = array('xfhu_delivery_id', 'xfhu_delivery_type', 'xfhu_delivery_cost', 'xfhu_delivery_free_from_cost', 'xfhu_delivery_time', 'xfhu_delivery_incheckout', 'xfhu_delivery_region',	'xfhu_delivery_carrier');
		for ($i = 1; $i<$z; $i++) { 
			$res_arr = array();
			for ($ii = 0; $ii<count($field_names_arr); $ii++) {
				$cur_field_name = $field_names_arr[$ii].$i;
				if (isset($_POST[$cur_field_name])) {
					$res_arr[$ii] = sanitize_text_field($_POST[$cur_field_name]);
				} else {
					if ($ii > 0) {$res_arr[$ii] = '';} else {$res_arr[$ii] = $i;}
				}
			}
			$cur_field_name = 'xfhu_delivery_arr'.$i;
			xfhu_optionUPD($cur_field_name, serialize($res_arr), $feed_id);
	   	}
	}
	
	xfhu_optionUPD('xfhu_step_export', sanitize_text_field($_POST['xfhu_step_export']), $feed_id, 'yes', 'set_arr');	
	$arr_maybe = array("off", "five_min", "hourly", "six_hours", "twicedaily", "daily");
	$xfhu_run_cron = sanitize_text_field($_POST['xfhu_run_cron']);
	if (in_array($xfhu_run_cron, $arr_maybe)) {		
		xfhu_optionUPD('xfhu_status_cron', $xfhu_run_cron, $feed_id, 'yes', 'set_arr');
		if ($xfhu_run_cron === 'off') {
			// отключаем крон
			wp_clear_scheduled_hook('xfhu_cron_period', array($feed_id));
			xfhu_optionUPD('xfhu_status_cron', 'off', $feed_id, 'yes', 'set_arr');
			
			wp_clear_scheduled_hook('xfhu_cron_sborki', array($feed_id));
			xfhu_optionUPD('xfhu_status_sborki', '-1', $feed_id);
		} else {
			$recurrence = $xfhu_run_cron;
			wp_clear_scheduled_hook('xfhu_cron_period', array($feed_id));
			wp_schedule_event(time(), $recurrence, 'xfhu_cron_period', array($feed_id));
			xfhu_error_log('FEED № '.$feed_id.'; xfhu_cron_period внесен в список заданий; Файл: export.php; Строка: '.__LINE__, 0);
		}
	} else {
		xfhu_error_log('Крон '.$xfhu_run_cron.' не зарегистрирован. Файл: export.php; Строка: '.__LINE__, 0);
	}
  }
 } 

 $xfhu_status_cron = xfhu_optionGET('xfhu_status_cron', $feed_id, 'set_arr');
 $xfhu_ufup = xfhu_optionGET('xfhu_ufup', $feed_id, 'set_arr');
 $xfhu_whot_export = xfhu_optionGET('xfhu_whot_export', $feed_id, 'set_arr'); 
 $xfhu_feed_assignment = xfhu_optionGET('xfhu_feed_assignment', $feed_id, 'set_arr'); 
 $xfhu_desc = xfhu_optionGET('xfhu_desc', $feed_id, 'set_arr');
 $xfhu_var_desc_priority = xfhu_optionGET('xfhu_var_desc_priority', $feed_id, 'set_arr');
 $xfhu_firmName = stripslashes(htmlspecialchars(xfhu_optionGET('xfhu_firmName', $feed_id, 'set_arr')));
 $xfhu_firmId = stripslashes(htmlspecialchars(xfhu_optionGET('xfhu_firmId', $feed_id, 'set_arr')));
 $xfhu_rate = xfhu_optionGET('xfhu_rate', $feed_id, 'set_arr');
 $xfhu_stock_days_default = xfhu_optionGET('xfhu_stock_days_default', $feed_id, 'set_arr');
 $xfhu_pickup_options_days_default = xfhu_optionGET('xfhu_pickup_options_days_default', $feed_id, 'set_arr');
 $xfhu_main_product = xfhu_optionGET('xfhu_main_product', $feed_id, 'set_arr');
 $xfhu_step_export = xfhu_optionGET('xfhu_step_export', $feed_id, 'set_arr');
 $xfhu_allow_group_id_arr = unserialize(xfhu_optionGET('xfhu_allow_group_id_arr', $feed_id));
 $xfhu_clear_get = xfhu_optionGET('xfhu_clear_get', $feed_id, 'set_arr');
 $xfhu_behavior_stip_symbol = xfhu_optionGET('xfhu_behavior_stip_symbol', $feed_id, 'set_arr');
 $xfhu_no_default_png_products = xfhu_optionGET('xfhu_no_default_png_products', $feed_id, 'set_arr');
 $xfhu_skip_products_without_pic = xfhu_optionGET('xfhu_skip_products_without_pic', $feed_id, 'set_arr');
 $xfhu_skip_missing_products = xfhu_optionGET('xfhu_skip_missing_products', $feed_id, 'set_arr');
 $xfhu_skip_backorders_products = xfhu_optionGET('xfhu_skip_backorders_products', $feed_id, 'set_arr'); 
 $xfhu_oldprice = xfhu_optionGET('xfhu_oldprice', $feed_id, 'set_arr');
 $xfhu_vendor = xfhu_optionGET('xfhu_vendor', $feed_id, 'set_arr'); 
 $xfhu_code = xfhu_optionGET('xfhu_code', $feed_id, 'set_arr');
 $xfhu_code_post_meta = xfhu_optionGET('xfhu_code_post_meta', $feed_id, 'set_arr');
 $xfhu_guarantee = xfhu_optionGET('xfhu_guarantee', $feed_id, 'set_arr');	
 $xfhu_guarantee_type = xfhu_optionGET('xfhu_guarantee_type', $feed_id, 'set_arr');
 $xfhu_guarantee_value = xfhu_optionGET('xfhu_guarantee_value', $feed_id, 'set_arr');
 $xfhu_guarantee_post_meta = xfhu_optionGET('xfhu_guarantee_post_meta', $feed_id, 'set_arr');
 $xfhu_barcode = xfhu_optionGET('xfhu_barcode', $feed_id, 'set_arr');
 $xfhu_manufacture = xfhu_optionGET('xfhu_manufacture', $feed_id, 'set_arr');

 $params_arr = unserialize(xfhu_optionGET('xfhu_params_arr', $feed_id));
 $xfhu_use_delivery = xfhu_optionGET('xfhu_use_delivery', $feed_id, 'set_arr');
 $xfhu_delivery_number = xfhu_optionGET('xfhu_delivery_number', $feed_id, 'set_arr');

 $xfhu_file_url = urldecode(xfhu_optionGET('xfhu_file_url', $feed_id, 'set_arr'));
 $xfhu_date_sborki = xfhu_optionGET('xfhu_date_sborki', $feed_id, 'set_arr');
?>
<div class="wrap">
 <h1><?php _e('Export Hotline', 'xfhu'); ?></h1>
 <div class="notice notice-info">
  <p><span class="xfhu_bold">XML for Hotline Pro</span> - <?php _e('a necessary extension for those who want to', 'xfhu'); ?> <span class="xfhu_bold" style="color: green;"><?php _e('save on advertising budget', 'xfhu'); ?></span> <?php _e('on Hotline', 'xfhu'); ?>! <a href="https://icopydoc.ru/product/xml-for-hotline-pro/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=settings&utm_term=about-xml-pro"><?php _e('Learn More', 'xfhu'); ?></a>.</p>
 </div>
 <?php if ($xfhu_vendor === 'disabled') : ?>
  <div class="notice notice-warning"> 
	<p><span class="xfhu_bold"><?php _e('Attention', 'xfhu'); ?>!</span> <?php _e('For the current feed in the field', 'xfhu'); ?> "<span class="xfhu_bold"><?php _e('Vendor', 'xfhu'); ?></span>" <?php _e('is set to', 'xfhu'); ?> "<span class="xfhu_bold"><?php _e('Disabled', 'xfhu'); ?></span>". <?php _e('Change this value, otherwise the products will not be included in the XML feed', 'xfhu'); ?>. <a href="https://icopydoc.ru/kak-sozdat-xml-dlya-hotline-v-woocommerce-instruktsiya/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=settings&utm_term=notice-instruction"><?php _e('Learn More', 'xfhu'); ?></a>.</p>
  </div>
 <?php endif; do_action('xfhu_before_poststuff', $feed_id); ?>
 <div id="poststuff"><div id="post-body" class="columns-2">
  <div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">
  	<?php do_action('xfhu_prepend_container_1', $feed_id); ?>
	<div class="postbox"> 
	 <div class="inside">	
	  <p style="text-align: center;"><strong style="color: green;"><?php _e('Instruction', 'xfhu'); ?>:</strong> <a href="https://icopydoc.ru/kak-sozdat-xml-dlya-hotline-v-woocommerce-instruktsiya/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=settings&utm_term=main-instruction" target="_blank"><?php _e('How to create a XML-feed', 'xfhu'); ?></a>.</p>
	  <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">	
		<select style="width: 100%" name="xfhu_num_feed" id="xfhu_num_feed">
			<?php if (is_multisite()) {$cur_blog_id = get_current_blog_id();} else {$cur_blog_id = '0';}		
			$allNumFeed = (int)xfhu_ALLNUMFEED; $ii = '1';
			for ($i = 1; $i<$allNumFeed+1; $i++) : ?>
			<option value="<?php echo $i; ?>" <?php selected($feed_id, $i); ?>><?php _e('Feed', 'xfhu'); ?> <?php echo $i; ?>: feed-hotline-<?php echo $cur_blog_id; ?>.xml <?php $assignment = xfhu_optionGET('xfhu_feed_assignment', $ii, 'set_arr'); if ($assignment === '') {} else {echo '('.$assignment.')';} ?></option>
			<?php $ii++; endfor; ?>
		</select>
		<?php wp_nonce_field('xfhu_nonce_action_send_select_feed', 'xfhu_nonce_field_send_select_feed'); ?>
		<input style="width: 100%; margin: 10px 0 10px 0;" class="button" type="submit" name="xfhu_submit_send_select_feed" value="<?php _e('Select feed', 'xfhu'); ?>" />
	  </form>
  	 </div>
	</div>
	<?php do_action('xfhu_before_support_project'); ?>
	<div class="postbox">
	 <h2 class="hndle"><?php _e('Please support the project', 'xfhu'); ?>!</h2>
	 <div class="inside">	  
		<p><?php _e('Thank you for using the plugin', 'xfhu'); ?> <strong>XML for Hotline</strong></p>
		<p><?php _e('Please help make the plugin better', 'xfhu'); ?> <a href="https://docs.google.com/forms/d/e/1FAIpQLSfa-Xz2LQLvG5cUspQq9hH8vDvWz9ZjWuevDsytXhhbz6Ig7A/viewform" target="_blank" ><?php _e('answering 6 questions', 'xfhu'); ?>!</a></p>
		<p><?php _e('If this plugin useful to you, please support the project one way', 'xfhu'); ?>:</p>
		<ul class="xfhu_ul">
			<li><a href="//wordpress.org/support/plugin/xml-for-hotline/reviews/" target="_blank"><?php _e('Leave a comment on the plugin page', 'xfhu'); ?></a>.</li>
			<li><?php _e('Support the project financially', 'xfhu'); ?>. <a href="https://yasobe.ru/na/xml_for_hotline" target="_blank"> <?php _e('Donate now', 'xfhu'); ?></a>.</li>
			<li><?php _e('Noticed a bug or have an idea how to improve the quality of the plugin', 'xfhu'); ?>? <a href="mailto:support@icopydoc.ru"><?php _e('Let me know', 'xfhu'); ?></a>.</li>
		</ul>
		<p><?php _e('The author of the plugin Maxim Glazunov', 'xfhu'); ?>.</p>
		<p><span style="color: red;"><?php _e('Accept orders for individual revision of the plugin', 'xfhu'); ?></span>:<br /><a href="mailto:support@icopydoc.ru"><?php _e('Leave a request', 'xfhu'); ?></a>.</p>
	  </div>
	</div>		
	<?php do_action('xfhu_between_container_1', $feed_id); ?>
	 <input type="hidden" name="xfhu_num_feed_for_save" value="<?php echo $feed_id; ?>">
	 <div class="postbox">
	  <h2 class="hndle"><?php _e('Send data about the work of the plugin', 'xfhu'); ?></h2>
	  <div class="inside">
		<p><?php _e('Sending statistics you help make the plugin even better', 'xfhu'); ?>! <?php _e('The following data will be transferred', 'xfhu'); ?>:</p>
		<ul id="xfhu_ul">
			<li><?php _e('URL XML-feed', 'xfhu'); ?>;</li>
			<li><?php _e('File generation status', 'xfhu'); ?>;</li>
			<li><?php _e('Is the multisite mode enabled', 'xfhu'); ?>?</li>
		</ul>
		<p><?php _e('The plugin helped you download the products to the Hotline', 'xfhu'); ?>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
		 <p>
			<input type="radio" name="xfhu_its_ok" value="yes"><?php _e('Yes', 'xfhu'); ?><br />
			<input type="radio" name="xfhu_its_ok" value="no"><?php _e('No', 'xfhu'); ?>
		 </p>
		 <p><?php _e("If you don't mind to be contacted in case of problems, please enter your email address", "xfhu"); ?>. <span class="xfhu_bold"><?php _e('And if you want a response, be sure to include your email address', 'xfhu'); ?></span>.</p>
		 <p><input type="email" name="xfhu_email"></p>
		 <p><?php _e("Your message", "xfhu"); ?>:</p>
		 <p><textarea rows="6" cols="32" name="xfhu_message" placeholder="<?php _e('Enter your text to send me a message (You can write me in Russian or English). I check my email several times a day', 'xfhu'); ?>"></textarea></p>
		 <?php wp_nonce_field('xfhu_nonce_action_send_stat', 'xfhu_nonce_field_send_stat'); ?><input class="button-primary" type="submit" name="xfhu_submit_send_stat" value="<?php _e('Send data', 'xfhu'); ?>" />
		</form>
	  </div>
	 </div>
	<?php do_action('xfhu_append_container_1', $feed_id); ?>
  </div></div>

  <div id="postbox-container-2" class="postbox-container"><div class="meta-box-sortables">
  	<?php do_action('xfhu_prepend_container_2', $feed_id); ?>
	  <div class="postbox">
	 <h2 class="hndle"><?php _e('Feed', 'xfhu'); ?> <?php echo $feed_id; ?>: <?php if ($feed_id !== '1') {echo $feed_id;} ?>feed-hotline-<?php echo $cur_blog_id; ?>.xml <?php $assignment = xfhu_optionGET('xfhu_feed_assignment', $feed_id, 'set_arr'); if ($assignment === '') {} else {echo '('.$assignment.')';} ?> <?php if (empty($xfhu_file_url)) : ?><?php _e('not created yet', 'xfhu'); ?><?php else : ?><?php if ($status_sborki !== -1) : ?><?php _e('updating', 'xfhu'); ?><?php else : ?><?php _e('created', 'xfhu'); ?><?php endif; ?><?php endif; ?></h2>	
	 <div class="inside">
		<?php if (empty($xfhu_file_url)) : ?> 
			<?php if ($status_sborki !== -1) : ?>
				<p><?php _e('We are working on automatic file creation. XML will be developed soon', 'xfhu'); ?>.</p>
			<?php else : ?>		
				<p><?php _e('In order to do that, select another menu entry (which differs from "off") in the box called "Automatic file creation". You can also change values in other boxes if necessary, then press "Save"', 'xfhu'); ?>.</p>
				<p><?php _e('After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message', 'xfhu'); ?>.</p>
			<?php endif; ?>
		<?php else : ?>
			<?php if ($status_sborki !== -1) : ?>
				<p><?php _e('We are working on automatic file creation. XML will be developed soon', 'xfhu'); ?>.</p>
			<?php else : ?>
				<p><strong><?php _e('Your XML feed here', 'xfhu'); ?>:</strong><br/><a target="_blank" href="<?php echo $xfhu_file_url; ?>"><?php echo $xfhu_file_url; ?></a>
				<br/><?php _e('File size', 'xfhu'); ?>: <?php clearstatcache();
				if ($feed_id === '1') {$prefFeed = '';} else {$prefFeed = $feed_id;}
				$upload_dir = (object)wp_get_upload_dir();
				if (is_multisite()) {
					$filename = $upload_dir->basedir."/xml-for-hotline/".$prefFeed."feed-hotline-".get_current_blog_id().".xml";
				} else {
					$filename = $upload_dir->basedir."/xml-for-hotline/".$prefFeed."feed-hotline-0.xml";				
				}
				if (is_file($filename)) {echo xfhu_formatSize(filesize($filename));} else {echo '0 KB';} ?>
				<br/><?php _e('Generated', 'xfhu'); ?>: <?php echo $xfhu_date_sborki; ?></p>
			<?php endif; ?>		
		<?php endif; ?>
		<p><?php _e('Please note that Hotline checks XML no more than 3 times a day! This means that the changes on the Hotline are not instantaneous', 'xfhu'); ?>!</p>
	  </div>
	</div>	  
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
	 <?php do_action('xfhu_prepend_form_container_2', $feed_id); ?>
	 <input type="hidden" name="xfhu_num_feed_for_save" value="<?php echo $feed_id; ?>">
	 <div class="postbox">
	  <h2 class="hndle"><?php _e('Main parameters', 'xfhu'); ?></h2>
	   <div class="inside">	    
		<table class="form-table"><tbody>
		<tr>
			<th scope="row"><label for="xfhu_run_cron"><?php _e('Automatic file creation', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_run_cron" id="xfhu_run_cron">
					<option value="off" <?php selected($xfhu_status_cron, 'off'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
					<?php $xfhu_enable_five_min = xfhu_optionGET('xfhu_enable_five_min'); if ($xfhu_enable_five_min === 'on') : ?>
					<option value="five_min" <?php selected($xfhu_status_cron, 'five_min' );?> ><?php _e('Every five minutes', 'xfhu'); ?></option>
					<?php endif; ?>
					<option value="hourly" <?php selected($xfhu_status_cron, 'hourly' );?> ><?php _e('Hourly', 'xfhu'); ?></option>
					<option value="six_hours" <?php selected($xfhu_status_cron, 'six_hours' ); ?> ><?php _e('Every six hours', 'xfhu'); ?></option>
					<option value="twicedaily" <?php selected($xfhu_status_cron, 'twicedaily' );?> ><?php _e('Twice a day', 'xfhu'); ?></option>
					<option value="daily" <?php selected($xfhu_status_cron, 'daily' );?> ><?php _e('Daily', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php _e('The refresh interval on your feed', 'xfhu'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_ufup"><?php _e('Update feed when updating products', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_ufup" id="xfhu_ufup" <?php checked($xfhu_ufup, 'on' ); ?>/>
			</td>
		 </tr>
		 <?php do_action('xfhu_after_ufup_option', $feed_id); ?>
		 <tr>
			<th scope="row"><label for="xfhu_feed_assignment"><?php _e('Feed assignment', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="text" maxlength="20" name="xfhu_feed_assignment" id="xfhu_feed_assignment" value="<?php echo $xfhu_feed_assignment; ?>" placeholder="<?php _e('For Hotline', 'xfhu');?>" /><br />
				<span class="description"><?php _e('Not used in feed. Inner note for your convenience', 'xfhu'); ?>.</span>
			</td>
		 </tr>		 
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_whot_export"><?php _e('Whot export', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_whot_export" id="xfhu_whot_export">
					<option value="all" <?php selected($xfhu_whot_export, 'all'); ?>><?php _e('Simple & Variable products', 'xfhu'); ?></option>
					<option value="simple" <?php selected($xfhu_whot_export, 'simple'); ?>><?php _e('Only simple products', 'xfhu'); ?></option>
					<?php do_action('xfhu_after_whot_export_option', $xfhu_whot_export, $feed_id); ?>
				</select><br />
				<span class="description"><?php _e('Whot export', 'xfhu'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_desc"><?php _e('Description of the product', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_desc" id="xfhu_desc">
				<option value="excerpt" <?php selected($xfhu_desc, 'excerpt'); ?>><?php _e('Only Excerpt description', 'xfhu'); ?></option>
				<option value="full" <?php selected($xfhu_desc, 'full'); ?>><?php _e('Only Full description', 'xfhu'); ?></option>
				<option value="excerptfull" <?php selected($xfhu_desc, 'excerptfull'); ?>><?php _e('Excerpt or Full description', 'xfhu'); ?></option>
				<option value="fullexcerpt" <?php selected($xfhu_desc, 'fullexcerpt'); ?>><?php _e('Full or Excerpt description', 'xfhu'); ?></option>
				<option value="excerptplusfull" <?php selected($xfhu_desc, 'excerptplusfull'); ?>><?php _e('Excerpt plus Full description', 'xfhu'); ?></option>
				<option value="fullplusexcerpt" <?php selected($xfhu_desc, 'fullplusexcerpt'); ?>><?php _e('Full plus Excerpt description', 'xfhu'); ?></option>
				<?php do_action('xfhu_append_select_xfhu_desc', $xfhu_desc, $feed_id); ?>
				</select><br />
				<?php do_action('xfhu_after_select_xfhu_desc', $xfhu_desc, $feed_id); ?>
				<span class="description"><?php _e('The source of the description', 'xfhu'); ?>
				</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_var_desc_priority"><?php _e('The varition description takes precedence over others', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_var_desc_priority" id="xfhu_var_desc_priority" <?php checked($xfhu_var_desc_priority, 'on'); ?>/>
			</td>
		 </tr>		  
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_firmName"><?php _e('Shop name', 'xfhu'); ?></label></th>
			<td class="overalldesc">
			 <input maxlength="20" type="text" name="xfhu_firmName" id="xfhu_firmName" value="<?php echo $xfhu_firmName; ?>" /><br />
			 <span class="description"><?php _e('Required element', 'xfhu'); ?> <strong>firmName</strong>. <?php _e('The short name of the store should not exceed 20 characters', 'xfhu'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_firmId">firmId</label></th>
			<td class="overalldesc">
				<input type="text" name="xfhu_firmId" id="xfhu_firmId" value="<?php echo $xfhu_firmId; ?>" /><br />
				<span class="description"><?php _e('Required element', 'xfhu'); ?> <strong>firmId</strong>. <?php _e('The unique ID (code) of the store is indicated in your account on the site hotline.ua and in the texts of mail notifications', 'xfhu'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_rate"><?php _e('Dollar rate', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="text" name="xfhu_rate" id="xfhu_rate" value="<?php echo $xfhu_rate; ?>" /><br />
				<span class="description"><?php _e('Required element', 'xfhu'); ?> <?php _e('if you sell goods for dollars', 'xfhu'); ?> <strong>rate</strong>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_stock_days_default">stock days</label></th>
			<td class="overalldesc">
				<input type="text" name="xfhu_stock_days_default" id="xfhu_stock_days_default" value="<?php echo $xfhu_stock_days_default; ?>" /><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>days</strong> <?php _e('of tag', 'xfhu'); ?> <strong>stock</strong>. <?php _e('The number of days from the order of the proucts by the buyer to the start of the delivery process', 'xfhu'); ?>. <a href="//hotline.ua/about/pricelists_specs/#tr1" target="_blank"><?php _e('Read more', 'xfhu'); ?></a></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_pickup_options_days_default">pickup-options days</label></th>
			<td class="overalldesc">
				<input type="text" name="xfhu_pickup_options_days_default" id="xfhu_pickup_options_days_default" value="<?php echo $xfhu_pickup_options_days_default; ?>" /><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>days</strong> <?php _e('of tag', 'xfhu'); ?> <strong>pickup-options</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_main_product"><?php _e('What kind of products do you sell?', 'xfhu'); ?></label></th>
			<td class="overalldesc">
					<select name="xfhu_main_product" id="xfhu_main_product">
					<option value="electronics" <?php selected($xfhu_main_product, 'electronics'); ?>><?php _e('Electronics', 'xfhu'); ?></option>
					<option value="computer" <?php selected($xfhu_main_product, 'computer'); ?>><?php _e('Computer techologies', 'xfhu'); ?></option>
					<option value="clothes_and_shoes" <?php selected($xfhu_main_product, 'clothes_and_shoes'); ?>><?php _e('Clothes and shoes', 'xfhu'); ?></option>
					<option value="auto_parts" <?php selected($xfhu_main_product, 'auto_parts'); ?>><?php _e('Auto parts', 'xfhu'); ?></option>
					<option value="products_for_children" <?php selected($xfhu_main_product, 'products_for_children'); ?>><?php _e('Products for children', 'xfhu'); ?></option>
					<option value="sporting_goods" <?php selected($xfhu_main_product, 'sporting_goods'); ?>><?php _e('Sporting goods', 'xfhu'); ?></option>
					<option value="goods_for_pets" <?php selected($xfhu_main_product, 'goods_for_pets'); ?>><?php _e('Goods for pets', 'xfhu'); ?></option>
					<option value="sexshop" <?php selected($xfhu_main_product, 'sexshop'); ?>><?php _e('Sex shop (Adult products)', 'xfhu'); ?></option>
					<option value="books" <?php selected($xfhu_main_product, 'books'); ?>><?php _e('Books', 'xfhu'); ?></option>
					<option value="health" <?php selected($xfhu_main_product, 'health'); ?>><?php _e('Health products', 'xfhu'); ?></option>	
					<option value="food" <?php selected($xfhu_main_product, 'food'); ?>><?php _e('Food', 'xfhu'); ?></option>
					<option value="construction_materials" <?php selected($xfhu_main_product, 'construction_materials'); ?>><?php _e('Construction Materials', 'xfhu'); ?></option>
					<option value="other" <?php selected($xfhu_main_product, 'other'); ?>><?php _e('Other', 'xfhu'); ?></option>					
				</select><br />
				<span class="description"><?php _e('Specify the main category', 'xfhu'); ?></span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_step_export"><?php _e('Step of export', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_step_export" id="xfhu_step_export">
				<option value="80" <?php selected($xfhu_step_export, '80'); ?>>80</option>
				<option value="200" <?php selected($xfhu_step_export, '200'); ?>>200</option>
				<option value="300" <?php selected($xfhu_step_export, '300'); ?>>300</option>
				<option value="450" <?php selected($xfhu_step_export, '450'); ?>>450</option>
				<option value="500" <?php selected($xfhu_step_export, '500'); ?>>500</option>
				<option value="800" <?php selected($xfhu_step_export, '800'); ?>>800</option>
				<option value="1000" <?php selected($xfhu_step_export, '1000'); ?>>1000</option>
				<?php do_action('xfhu_step_export_option', $feed_id); ?>
				</select><br />
				<span class="description"><?php _e('The value affects the speed of file creation', 'xfhu'); ?>. <?php _e('If you have any problems with the generation of the file - try to reduce the value in this field', 'xfhu'); ?>. <?php _e('More than 500 can only be installed on powerful servers', 'xfhu'); ?>.</span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_allow_group_id_arr"><?php _e('Categories of variable products for which group_id is allowed', 'xfhu'); ?></label></th>
			<td class="overalldesc">
			 <select id="xfhu_allow_group_id_arr" style="width: 100%;" name="xfhu_allow_group_id_arr[]" size="8" multiple>
				<?php foreach (get_terms('product_cat', array('hide_empty'=>0, 'parent'=>0)) as $term) {echo xfhu_cat_tree($term->taxonomy, $term->term_id, $xfhu_allow_group_id_arr); } ?>
			 </select><br />
			 <span class="description"><?php _e('Categories of products for which <group_id> is allowed', 'xfhu'); ?>.</span>
			</td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="xfhu_clear_get"><?php _e('Clear URL from GET-paramrs', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_clear_get" id="xfhu_clear_get">
				<option value="no" <?php selected($xfhu_clear_get, 'no'); ?>><?php _e('No', 'xfhu'); ?></option>
				<option value="yes" <?php selected($xfhu_clear_get, 'yes'); ?>><?php _e('Yes', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php /*_e('This option may be useful when setting up Turbo pages', 'xfhu');*/ ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_behavior_stip_symbol"><?php _e('In attributes', 'xfhu'); ?> code <?php _e('ampersand', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_behavior_stip_symbol" id="xfhu_behavior_stip_symbol">
					<option value="default" <?php selected($xfhu_behavior_stip_symbol, 'default'); ?>><?php _e('Default', 'xfhu'); ?></option>
					<option value="del" <?php selected($xfhu_behavior_stip_symbol, 'del'); ?>><?php _e('Delete', 'xfhu'); ?></option>
					<option value="slash" <?php selected($xfhu_behavior_stip_symbol, 'slash'); ?>><?php _e('Replace with', 'xfhu'); ?> /</option>
					<option value="amp" <?php selected($xfhu_behavior_stip_symbol, 'amp'); ?>><?php _e('Replace with', 'xfhu'); ?> amp;</option>
				</select><br />
				<span class="description"><?php _e('Default', 'xfhu'); ?> "<?php _e('Delete', 'xfhu'); ?>"</span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_no_default_png_products"><?php _e('Remove default.png from XML', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_no_default_png_products" id="xfhu_no_default_png_products" <?php checked($xfhu_no_default_png_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_skip_products_without_pic"><?php _e('Skip products without pictures', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_skip_products_without_pic" id="xfhu_skip_products_without_pic" <?php checked($xfhu_skip_products_without_pic, 'on' ); ?>/>
			</td>
		 </tr>
		 <?php do_action('xfhu_after_skip_products_without_pic', $feed_id); ?>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_skip_missing_products"><?php _e('Skip missing products', 'xfhu'); ?> (<?php _e('except for products for which a pre-order is permitted', 'xfhu'); ?>.)</label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_skip_missing_products" id="xfhu_skip_missing_products" <?php checked($xfhu_skip_missing_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_skip_backorders_products"><?php _e('Skip backorders products', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_skip_backorders_products" id="xfhu_skip_backorders_products" <?php checked($xfhu_skip_backorders_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_oldprice"><?php _e('Old price', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_oldprice" id="xfhu_oldprice">
					<option value="yes" <?php selected($xfhu_oldprice, 'yes'); ?>><?php _e('Yes', 'xfhu'); ?></option>
					<option value="no" <?php selected($xfhu_oldprice, 'no'); ?>><?php _e('No', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'xfhu'); ?> <strong>oldprice</strong>.</span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_vendor"><?php _e('Vendor', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_vendor" id="xfhu_vendor">
				<option value="disabled" <?php selected($xfhu_vendor, 'disabled'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
				<?php if (class_exists('Perfect_Woocommerce_Brands')) : ?>
				<option value="sfpwb" <?php selected($xfhu_vendor, 'sfpwb'); ?>><?php _e('Substitute from', 'xfhu'); ?> Perfect Woocommerce Brands</option>
				<?php endif; ?>
				<?php if (is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) : ?>
				<option value="premmercebrandsplugin" <?php selected($xfhu_vendor, 'premmercebrandsplugin'); ?>><?php _e('Substitute from', 'xfhu'); ?> Premmerce Brands for WooCommerce</option>
				<?php endif; ?>
				<?php foreach (xfhu_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected($xfhu_vendor, $attribute['id']); ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Required element', 'xfhu'); ?> <strong>vendor</strong></span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_code"><?php _e('Model code', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_code" id="xfhu_code">
				<option value="disabled" <?php selected($xfhu_code, 'disabled'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
				<option value="sku" <?php selected($xfhu_code, 'sku'); ?>><?php _e('Substitute from SKU', 'xfhu'); ?></option>
				<option value="post_meta" <?php selected($xfhu_code, 'post_meta'); ?>><?php _e('Substitute from post meta', 'xfhu'); ?></option>
				<?php foreach (xfhu_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected($xfhu_code, $attribute['id']); ?>><?php echo $attribute['name']; ?></option><?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('If selected', 'xfhu'); ?> <span class="xfhu_bold">"<?php _e('Substitute from post meta', 'xfhu'); ?>"</span> <?php _e('do not forget to fill out this field', 'xfhu'); ?>:</span><br />
				<input placeholder="<?php _e('Name post_meta', 'xfhu'); ?>" type="text" name="xfhu_code_post_meta" id="xfhu_code_post_meta" value="<?php echo $xfhu_code_post_meta; ?>" /><br />				
				<span class="description"><?php _e('Optional element', 'xfhu'); ?> <strong>code</strong> - <?php _e('model code (vendor code from the manufacturer)', 'xfhu'); ?></span>
			</td>
		 </tr>
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_guarantee"><?php _e('Guarantee', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_guarantee" id="xfhu_guarantee">
					<option value="disabled" <?php selected($xfhu_guarantee, 'disabled'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
					<option value="enabled" <?php selected($xfhu_guarantee, 'enabled'); ?>><?php _e('Enabled', 'xfhu'); ?></option>
				</select><br/>
				<?php _e('Guarantee type', 'xfhu'); ?>:<br/>
				<select name="xfhu_guarantee_type" id="xfhu_guarantee_type">
					<option value="manufacturer" <?php selected($xfhu_guarantee_type, 'manufacturer'); ?>><?php _e('Manufacturer', 'xfhu'); ?></option>				
					<option value="shop" <?php selected($xfhu_guarantee_type, 'shop'); ?>><?php _e('Shop', 'xfhu'); ?></option>
				</select><br/>
				<?php _e('Guarantee value', 'xfhu'); ?>:<br/>
				<select name="xfhu_guarantee_value" id="xfhu_guarantee_value">
					<option value="post_meta" <?php selected($xfhu_guarantee_value, 'post_meta'); ?>><?php _e('Substitute from post meta', 'xfhu'); ?></option>				
					<?php foreach (xfhu_get_attributes() as $attribute) : ?>
					<option value="<?php echo $attribute['id']; ?>" <?php selected($xfhu_guarantee_value, $attribute['id']); ?>><?php echo $attribute['name']; ?></option><?php endforeach; ?>
				</select><br/>
				<span class="description"><?php _e('If selected', 'xfhu'); ?> <span class="xfhu_bold">"<?php _e('Substitute from post meta', 'xfhu'); ?>"</span> <?php _e('do not forget to fill out this field', 'xfhu'); ?>:</span><br />
				<input placeholder="<?php _e('Name post_meta', 'xfhu'); ?>" type="text" name="xfhu_guarantee_post_meta" id="xfhu_guarantee_post_meta" value="<?php echo $xfhu_guarantee_post_meta; ?>" /><br />				
				<span class="description"><?php _e('Optional element', 'xfhu'); ?> <strong>guarantee</strong></span>
			</td>
		 </tr>		  
		 <tr class="xfhu_tr">
			<th scope="row"><label for="xfhu_barcode"><?php _e('Barcode', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_barcode" id="xfhu_barcode">
				<option value="disabled" <?php selected($xfhu_barcode, 'disabled'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
				<option value="sku" <?php selected($xfhu_barcode, 'sku'); ?>><?php _e('Substitute from SKU', 'xfhu'); ?></option>
				<?php foreach (xfhu_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected($xfhu_barcode, $attribute['id']); ?>><?php echo $attribute['name']; ?></option><?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'xfhu'); ?> <strong>barcode</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_manufacture"><?php _e('Country of manufacture', 'xfhu'); ?></label>
			</th>
			<td class="overalldesc">
				<select name="xfhu_manufacture" id="xfhu_manufacture">
				<option value="off" <?php selected($xfhu_manufacture, 'disabled'); ?>><?php _e('Disabled', 'xfhu'); ?></option>
				<?php foreach (xfhu_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected($xfhu_manufacture, $attribute['id']); ?>><?php echo $attribute['name']; ?></option>	<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'xfhu'); ?></span>
			</td>
		 </tr>
		 <?php do_action('xfhu_before_params_arr', $feed_id); ?>	
		 <tr>
			<th scope="row"><label for="xfhu_params_arr"><?php _e('Include these attributes in the values Param', 'xfhu'); ?></label>
			</th>
			<td class="overalldesc">
			 <select id="xfhu_params_arr" style="width: 100%;" name="xfhu_params_arr[]" size="8" multiple>
				<?php foreach (xfhu_get_attributes() as $attribute) : ?>
					<option value="<?php echo $attribute['id']; ?>"<?php if (!empty($params_arr)) {foreach ($params_arr as $value) {selected($value, $attribute['id']);}} ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
			 </select><br />
			 <span class="description"><?php _e('Optional element', 'xfhu'); ?> <strong>param</strong></span><br />
			 <span class="description" style="color: blue;"><?php _e('Hint', 'xfhu'); ?>:</span> <span class="description"><?php _e('To select multiple values, hold down the (ctrl) button on Windows or (cmd) on a Mac. To deselect, press and hold (ctrl) or (cmd), click on the marked items', 'xfhu'); ?></span>
			</td>
		 </tr>
		</tbody></table>
	   </div>
	 </div>	

	 <?php do_action('xfhu_before_pad', $feed_id); ?>
	 <div class="postbox">
	   <h2 class="hndle"><?php _e('Delivery', 'xfhu'); ?></h2>
	   <div class="inside">	    
		<input type="checkbox" name="xfhu_use_delivery" id="xfhu_use_delivery" <?php checked($xfhu_use_delivery, 'on' ); ?>/><?php _e('Add delivery information to feed', 'xfhu'); ?><br />		
		<p><i><strong><?php _e('Note', 'xfhu'); ?>:</strong> <?php _e('Before adding shipping information to your feed, you must disable the same option in your Hotline account', 'xfhu'); ?>! <a target="_blank" href="//beta.hotline.ua/about/delivery_method_xml/"><?php _e('Read more', 'xfhu'); ?></a></i></p>
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_number"><?php _e('Number of delivery types', 'xfhu'); ?></label></th>
			<td class="overalldesc">
			 <input min="1" max="7" type="number" name="xfhu_delivery_number" id="xfhu_delivery_number" value="<?php echo $xfhu_delivery_number; ?>" />
			</td>
		 </tr>
		 <?php 
		 $z = (int)$xfhu_delivery_number + 1;
		 for ($i = 1; $i<$z; $i++) : 
			$xfhu_delivery_option_name = 'xfhu_delivery_arr'.$i;
			$xfhu_delivery_arr = xfhu_optionGET($xfhu_delivery_option_name, $feed_id);
			if ($xfhu_delivery_arr !== '') {
				$xfhu_delivery_arr = unserialize($xfhu_delivery_arr);
				$xfhu_delivery_type = $xfhu_delivery_arr[1];
				$xfhu_delivery_cost = $xfhu_delivery_arr[2];
				$xfhu_delivery_free_from_cost = $xfhu_delivery_arr[3];
				$xfhu_delivery_time = $xfhu_delivery_arr[4];
				$xfhu_delivery_incheckout = $xfhu_delivery_arr[5];
				$xfhu_delivery_region = $xfhu_delivery_arr[6];
				$xfhu_delivery_carrier = $xfhu_delivery_arr[7];
			} else {
				$xfhu_delivery_type = '';
				$xfhu_delivery_cost = '';
				$xfhu_delivery_free_from_cost = '';
				$xfhu_delivery_time = '';
				$xfhu_delivery_incheckout = '';
				$xfhu_delivery_region = '';
				$xfhu_delivery_carrier = '';			
			}
		 ?>
		 <tr class="xfhu_tr">
			<th scope="row"><span style="font-size: 24px;">Delivery ID #<?php echo $i; ?></span></th>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="xfhu_delivery_type<?php echo $i; ?>"><?php _e('Delivery type', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_delivery_type<?php echo $i; ?>" id="xfhu_delivery_type<?php echo $i; ?>">
				 <option value="pickup" <?php selected($xfhu_delivery_type, 'pickup'); ?>><?php _e('Pickup', 'xfhu'); ?></option>
				 <option value="warehouse" <?php selected($xfhu_delivery_type, 'warehouse'); ?>><?php _e('From warehouse', 'xfhu'); ?></option>
				 <option value="address" <?php selected($xfhu_delivery_type, 'address'); ?>><?php _e('Express delivery', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php _e('Required attribute', 'xfhu'); ?> <strong>type</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_cost<?php echo $i; ?>"><?php _e('Delivery cost', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input min="0" step="0.01" type="number" name="xfhu_delivery_cost<?php echo $i; ?>" id="xfhu_delivery_cost<?php echo $i; ?>" value="<?php echo $xfhu_delivery_cost; ?>" /><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>cost</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_free_from_cos<?php echo $i; ?>t"><?php _e('Free delivery from', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input min="0" step="0.01" type="number" name="xfhu_delivery_free_from_cost<?php echo $i; ?>" id="xfhu_delivery_free_from_cost<?php echo $i; ?>" value="<?php echo $xfhu_delivery_free_from_cost; ?>" /><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>freeFrom</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_time<?php echo $i; ?>"><?php _e('Delivery time', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_delivery_time<?php echo $i; ?>" id="xfhu_delivery_time<?php echo $i; ?>">
				 <option value="1" <?php selected($xfhu_delivery_time, '1'); ?>>1-3 <?php echo _x('days', '1-3', 'xfhu'); ?></option>
				 <option value="2" <?php selected($xfhu_delivery_time, '2'); ?>>4-9 <?php echo _x('days', '4-9', 'xfhu'); ?></option>
				 <option value="3" <?php selected($xfhu_delivery_time, '3'); ?>>10-14 <?php echo _x('days', '10-14', 'xfhu'); ?></option>
				 <option value="4" <?php selected($xfhu_delivery_time, '4'); ?>>0-24 <?php _e('Hours', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php _e('Required attribute', 'xfhu'); ?> <strong>time</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_incheckout<?php echo $i; ?>"><?php _e('Delivery cost is included in the order amount', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_delivery_incheckout<?php echo $i; ?>" id="xfhu_delivery_incheckout<?php echo $i; ?>">
				 <option value="true" <?php selected($xfhu_delivery_incheckout, 'true'); ?>><?php _e('true', 'xfhu'); ?></option>
				 <option value="false" <?php selected($xfhu_delivery_incheckout, 'false'); ?>><?php _e('false', 'xfhu'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>inCheckout</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="xfhu_delivery_region<?php echo $i; ?>"><?php _e('Region', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="text" name="xfhu_delivery_region<?php echo $i; ?>" id="xfhu_delivery_region<?php echo $i; ?>" value="<?php echo $xfhu_delivery_region; ?>" /><br />
				<span class="description"><?php _e('Optional attribute', 'xfhu'); ?> <strong>region</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_delivery_carrier<?php echo $i; ?>"><?php _e('Delivery carrier', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<select name="xfhu_delivery_carrier<?php echo $i; ?>" id="xfhu_delivery_carrier<?php echo $i; ?>">
				 <option value="SLF" <?php selected($xfhu_delivery_carrier, 'SLF'); ?>><?php _e('On our own', 'xfhu'); ?></option>
				 <option value="CAT" <?php selected($xfhu_delivery_carrier, 'CAT'); ?>>CAT</option>
				 <option value="DF" <?php selected($xfhu_delivery_carrier, 'DF'); ?>>Delfast</option>
				 <option value="DHL" <?php selected($xfhu_delivery_carrier, 'DHL'); ?>>DHL</option>
				 <option value="IP" <?php selected($xfhu_delivery_carrier, 'IP'); ?>>InPost 24/7</option>
				 <option value="IPT" <?php selected($xfhu_delivery_carrier, 'IPT'); ?>>IPT</option>
				 <option value="JIN" <?php selected($xfhu_delivery_carrier, 'JIN'); ?>>JIN</option>
				 <option value="ND" <?php selected($xfhu_delivery_carrier, 'ND'); ?>>nextDay</option>
				 <option value="PP" <?php selected($xfhu_delivery_carrier, 'PP'); ?>>PickPoint</option>
				 <option value="TMM" <?php selected($xfhu_delivery_carrier, 'TMM'); ?>>TMM Express</option>
				 <option value="AL" <?php selected($xfhu_delivery_carrier, 'AL'); ?>>Автолюкс</option>
				 <option value="VC" <?php selected($xfhu_delivery_carrier, 'VC'); ?>>Ваш Час</option>
				 <option value="VP" <?php selected($xfhu_delivery_carrier, 'VP'); ?>>Ваша Почта</option>
				 <option value="GU" <?php selected($xfhu_delivery_carrier, 'GU'); ?>>Гюнсел</option>
				 <option value="DA" <?php selected($xfhu_delivery_carrier, 'DA'); ?>>Деливери</option>
				 <option value="ЕЕ" <?php selected($xfhu_delivery_carrier, 'ЕЕ'); ?>>ЕвроЭкспресс</option>
				 <option value="ZD" <?php selected($xfhu_delivery_carrier, 'ZD'); ?>>Зручна доставка</option>
				 <option value="CE" <?php selected($xfhu_delivery_carrier, 'CE'); ?>>Карго Экспресс</option>
				 <option value="KSD" <?php selected($xfhu_delivery_carrier, 'KSD'); ?>>КСД</option>
				 <option value="ME" <?php selected($xfhu_delivery_carrier, 'ME'); ?>>Мист Экспресс</option>
				 <option value="NP" <?php selected($xfhu_delivery_carrier, 'NP'); ?>>Новая почта</option>
				 <option value="NE" <?php selected($xfhu_delivery_carrier, 'NE'); ?>>Ночной Экспресс</option>
				 <option value="PE" <?php selected($xfhu_delivery_carrier, 'PE'); ?>>Пони Экспресс</option>
				 <option value="PB" <?php selected($xfhu_delivery_carrier, 'PB'); ?>>ПриватБанк</option>
				 <option value="MET" <?php selected($xfhu_delivery_carrier, 'MET'); ?>>СЦ ТОЧКА</option>
				 <option value="UPG" <?php selected($xfhu_delivery_carrier, 'UPG'); ?>>Украинская почтовая группа</option>
				 <option value="UP" <?php selected($xfhu_delivery_carrier, 'UP'); ?>>Укрпочта</option>
				 <option value="EM" <?php selected($xfhu_delivery_carrier, 'EM'); ?>>Экспресс Мейл</option>
				 <option value="YT" <?php selected($xfhu_delivery_carrier, 'YT'); ?>>ЯрТранс Лоджистик</option>
				</select><br />
				<span class="description"><?php _e('Required attribute', 'xfhu'); ?> <strong>carrier</strong> <?php _e('of element', 'xfhu'); ?> <strong>delivery</strong></span>
			</td>
		 </tr> 
		 <?php endfor; ?>
		</tbody></table>
	   </div>
	 </div>	

	 <?php do_action('xfhu_before_button_primary_submit', $feed_id); ?>	 
	 <div class="postbox">
	  <div class="inside">
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"><?php wp_nonce_field('xfhu_nonce_action','xfhu_nonce_field'); ?><input id="button-primary" class="button-primary" type="submit" name="xfhu_submit_action" value="<?php _e( 'Save', 'xfhu'); ?>" /><br />
			<span class="description"><?php _e('Click to save the settings', 'xfhu'); ?></span></td>
		 </tr>
		</tbody></table>
	  </div>
	 </div>	 
	 <?php do_action('xfhu_append_form_container_2', $feed_id); ?>
	</form>
	<?php do_action('xfhu_append_container_2', $feed_id); ?>
  </div></div>
 </div><!-- /post-body --><br class="clear"></div><!-- /poststuff -->
 <?php do_action('xfhu_after_poststuff', $feed_id); ?>

 <div id="icp_slides" class="clear">
  <div class="icp_wrap">
	<input type="radio" name="icp_slides" id="icp_point1">
	<input type="radio" name="icp_slides" id="icp_point2">
	<input type="radio" name="icp_slides" id="icp_point3" checked>
	<input type="radio" name="icp_slides" id="icp_point4">
	<input type="radio" name="icp_slides" id="icp_point5">
	<input type="radio" name="icp_slides" id="icp_point6">
	<input type="radio" name="icp_slides" id="icp_point7">
	<div class="icp_slider">
		<div class="icp_slides icp_img1"><a href="//wordpress.org/plugins/yml-for-yandex-market/" target="_blank"></a></div>
		<div class="icp_slides icp_img2"><a href="//wordpress.org/plugins/import-products-to-ok-ru/" target="_blank"></a></div>
		<div class="icp_slides icp_img3"><a href="//wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank"></a></div>
		<div class="icp_slides icp_img4"><a href="//wordpress.org/plugins/gift-upon-purchase-for-woocommerce/" target="_blank"></a></div>
		<div class="icp_slides icp_img5"><a href="//wordpress.org/plugins/xml-for-avito/" target="_blank"></a></div>
		<div class="icp_slides icp_img6"><a href="//wordpress.org/plugins/xml-for-o-yandex/" target="_blank"></a></div>
		<div class="icp_slides icp_img7"><a href="//wordpress.org/plugins/import-from-yml/" target="_blank"></a></div>
	</div>
	<div class="icp_control">
		<label for="icp_point1"></label>
		<label for="icp_point2"></label>
		<label for="icp_point3"></label>
		<label for="icp_point4"></label>
		<label for="icp_point5"></label>
		<label for="icp_point6"></label>
		<label for="icp_point7"></label>
	</div>
  </div> 
 </div>
 <?php do_action('xfhu_after_icp_slides', $feed_id); ?>

 <div class="metabox-holder">
  <div class="postbox">
  	<h2 class="hndle"><?php _e('My plugins that may interest you', 'xfhu'); ?></h2>
	<div class="inside">
		<p><span class="xfhu_bold">XML for Google Merchant Center</span> - <?php _e('Сreates a XML-feed to upload to Google Merchant Center', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p> 
		<p><span class="xfhu_bold">YML for Yandex Market</span> - <?php _e('Сreates a YML-feed for importing your products to Yandex Market', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/yml-for-yandex-market/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">Import from YML</span> - <?php _e('Imports products from YML to your shop', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/import-from-yml/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">Integrate myTarget for WooCommerce</span> - <?php _e('This plugin helps setting up myTarget counter for dynamic remarketing for WooCommerce', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/wc-mytarget/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">XML for Hotline</span> - <?php _e('Сreates a XML-feed for importing your products to Hotline', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/xml-for-hotline/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">Gift upon purchase for WooCommerce</span> - <?php _e('This plugin will add a marketing tool that will allow you to give gifts to the buyer upon purchase', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/gift-upon-purchase-for-woocommerce/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">Import products to ok.ru</span> - <?php _e('With this plugin, you can import products to your group on ok.ru', 'xfhu'); ?>. <a href="https://wordpress.org/plugins/import-products-to-ok-ru/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">XML for Avito</span> - <?php _e('Сreates a XML-feed for importing your products to', 'xfhu'); ?> Avito. <a href="https://wordpress.org/plugins/xml-for-avito/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
		<p><span class="xfhu_bold">XML for O.Yandex (Яндекс Объявления)</span> - <?php _e('Сreates a XML-feed for importing your products to', 'xfhu'); ?> Яндекс.Объявления. <a href="https://wordpress.org/plugins/xml-for-o-yandex/" target="_blank"><?php _e('Read more', 'xfhu'); ?></a>.</p>
	</div>
  </div>
 </div>
 <?php do_action('xfhu_append_wrap', $feed_id); ?>
</div><!-- /wrap -->
<?php
} /* end функция настроек xfhu_export_page */