<?php if (!defined('ABSPATH')) {exit;}
function xfhu_debug_page() { 
 wp_clean_plugins_cache();
 wp_clean_update_cache();
 add_filter('pre_site_transient_update_plugins', '__return_null');
 wp_update_plugins();
 remove_filter('pre_site_transient_update_plugins', '__return_null');
 if (isset($_REQUEST['xfhu_submit_debug_page'])) {
	if (!empty($_POST) && check_admin_referer('xfhu_nonce_action','xfhu_nonce_field')) {
		if (isset($_POST['xfhu_keeplogs'])) {
			xfhu_optionUPD('xfhu_keeplogs', sanitize_text_field($_POST['xfhu_keeplogs']));
			xfhu_error_log('NOTICE: Логи успешно включены; Файл: debug.php; Строка: '.__LINE__, 0);
		} else {
			xfhu_error_log('NOTICE: Логи отключены; Файл: debug.php; Строка: '.__LINE__, 0);
			xfhu_optionUPD('xfhu_keeplogs', '0');
		}
		if (isset($_POST['xfhu_disable_notices'])) {
			xfhu_optionUPD('xfhu_disable_notices', sanitize_text_field($_POST['xfhu_disable_notices']));
		} else {
			xfhu_optionUPD('xfhu_disable_notices', '0');
		}
		if (isset($_POST['xfhu_enable_five_min'])) {
			xfhu_optionUPD('xfhu_enable_five_min', sanitize_text_field($_POST['xfhu_enable_five_min']));
		} else {
			xfhu_optionUPD('xfhu_enable_five_min', '0');
		}		
	}
 }	
 $xfhu_keeplogs = xfhu_optionGET('xfhu_keeplogs');
 $xfhu_disable_notices = xfhu_optionGET('xfhu_disable_notices');
 $xfhu_enable_five_min = xfhu_optionGET('xfhu_enable_five_min');
 ?>
 <div class="wrap"><h1><?php _e('Debug page', 'xfhu'); ?> v.<?php echo xfhu_optionGET('xfhu_version'); ?></h1>
  <div id="dashboard-widgets-wrap"><div id="dashboard-widgets" class="metabox-holder">
  	<div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">
     <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">	 
	 <div class="postbox">
	   <div class="inside">
		<h1><?php _e('Logs', 'xfhu'); ?></h1>
		<p><?php if ($xfhu_keeplogs === 'on') {
 			$upload_dir = wp_get_upload_dir();
			echo '<strong>'. __('Log-file here', 'xfhu').':</strong><br /><a href="'.$upload_dir['baseurl'].'/xml-for-hotline/xml-for-hotline.log" target="_blank">'.$upload_dir['basedir'].'/xml-for-hotline/xml-for-hotline.log</a>';			
		} ?></p>		
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="xfhu_keeplogs"><?php _e('Keep logs', 'xfhu'); ?></label><br />
				<input class="button" id="xfhu_submit_clear_logs" type="submit" name="xfhu_submit_clear_logs" value="<?php _e('Clear logs', 'xfhu'); ?>" />
			</th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_keeplogs" id="xfhu_keeplogs" <?php checked($xfhu_keeplogs, 'on' ); ?>/><br />
				<span class="description"><?php _e('Do not check this box if you are not a developer', 'xfhu'); ?>!</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_disable_notices"><?php _e('Disable notices', 'xfhu'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_disable_notices" id="xfhu_disable_notices" <?php checked($xfhu_disable_notices, 'on' ); ?>/><br />
				<span class="description"><?php _e('Disable notices about XML-construct', 'xfhu'); ?>!</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="xfhu_enable_five_min"><?php _e('Enable', 'xfhu'); ?> five_min</label></th>
			<td class="overalldesc">
				<input type="checkbox" name="xfhu_enable_five_min" id="xfhu_enable_five_min" <?php checked($xfhu_enable_five_min, 'on' ); ?>/><br />
				<span class="description"><?php _e('Enable the five minute interval for CRON', 'xfhu'); ?></span>
			</td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"></td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"><?php wp_nonce_field('xfhu_nonce_action', 'xfhu_nonce_field'); ?><input id="button-primary" class="button-primary" type="submit" name="xfhu_submit_debug_page" value="<?php _e( 'Save', 'xfhu'); ?>" /><br />
			<span class="description"><?php _e('Click to save the settings', 'xfhu'); ?></span></td>
		 </tr>         
        </tbody></table>
       </div>
     </div>
     </form>
	</div></div>
  	<div id="postbox-container-2" class="postbox-container"><div class="meta-box-sortables">
  	 <div class="postbox">
	  <div class="inside">
		<h1><?php _e('Reset plugin settings', 'xfhu'); ?></h1>
		<p><?php _e('Reset plugin settings can be useful in the event of a problem', 'xfhu'); ?>.</p>
		<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('xfhu_nonce_action_reset', 'xfhu_nonce_field_reset'); ?><input class="button-primary" type="submit" name="xfhu_submit_reset" value="<?php _e('Reset plugin settings', 'xfhu'); ?>" />	 
		</form>
	  </div>
	 </div>	
	 <div class="postbox">
	  <h2 class="hndle"><?php _e('Request simulation', 'xfhu'); ?></h2>
	  <div class="inside">		
		<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
		 <?php 	 
		 if (isset($_POST['xfhu_num_feed'])) {$numFeed = sanitize_text_field($_POST['xfhu_num_feed']);} else {$numFeed = '1';} 
		 if (isset($_POST['xfhu_simulated_post_id'])) {$xfhu_simulated_post_id = sanitize_text_field($_POST['xfhu_simulated_post_id']);} else {$xfhu_simulated_post_id = '';}
		 $resust_simulated = '';
		 if (isset($_REQUEST['xfhu_submit_simulated'])) {
			if (!empty($_POST) && check_admin_referer('xfhu_nonce_action_simulated', 'xfhu_nonce_field_simulated')) {		 
				$postId = (int)$xfhu_simulated_post_id;
				$simulated_header = xfhu_feed_header($numFeed);
				$simulated = xfhu_unit($postId, $numFeed);
				if (is_array($simulated)) {
					$resust_simulated = $simulated_header.$simulated[0];
					$resust_simulated = apply_filters('xfhu_after_offers_filter', $resust_simulated, $numFeed);
					$resust_simulated .= "</items>". PHP_EOL ."</price>";				
				} else {
					$resust_simulated = $simulated_header.$simulated;
					$resust_simulated = apply_filters('xfhu_after_offers_filter', $resust_simulated, $numFeed);
					$resust_simulated .= "</items>". PHP_EOL ."</price>";
				}
			}
		 } ?>		
		 <table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="xfhu_simulated_post_id">postId</label></th>
			<td class="overalldesc">
				<input type="number" min="1" name="xfhu_simulated_post_id" value="<?php echo $xfhu_simulated_post_id; ?>">
			</td>
		 </tr>			
		 <tr>
			<th scope="row"><label for="xfhu_enable_five_min">numFeed</label></th>
			<td class="overalldesc">
				<select style="width: 100%" name="xfhu_num_feed" id="xfhu_num_feed">
					<?php if (is_multisite()) {$cur_blog_id = get_current_blog_id();} else {$cur_blog_id = '0';}		
					$allNumFeed = (int)xfhu_ALLNUMFEED; $ii = '1';
					for ($i = 1; $i<$allNumFeed+1; $i++) : ?>
					<option value="<?php echo $i; ?>" <?php selected($numFeed, $i); ?>><?php _e('Feed', 'xfhu'); ?> <?php echo $i; ?>: feed-hotline-<?php echo $cur_blog_id; ?>.xml <?php $assignment = xfhu_optionGET('xfhu_feed_assignment', $ii); if ($assignment === '') {} else {echo '('.$assignment.')';} ?></option>
					<?php $ii++; endfor; ?>
				</select>
			</td>
		 </tr>			
		 <tr>
			<th scope="row" colspan="2"><textarea rows="16" style="width: 100%;"><?php echo htmlspecialchars($resust_simulated); ?></textarea></th>
		 </tr>			       
		 </tbody></table>
		 <?php wp_nonce_field('xfhu_nonce_action_simulated', 'xfhu_nonce_field_simulated'); ?><input class="button-primary" type="submit" name="xfhu_submit_simulated" value="<?php _e('Simulated', 'xfhu'); ?>" />
		</form>			
	  </div>
	 </div>	 
	</div></div>
	 <div id="postbox-container-3" class="postbox-container"><div class="meta-box-sortables">
	 <div class="postbox">
	  <div class="inside">
	  	<h1><?php _e('Possible problems', 'xfhu'); ?></h1>
		  <?php
		  	$possibleProblems = ''; $possibleProblemsCount = 0; $conflictWithPlugins = 0; $conflictWithPluginsList = ''; 
			$check_global_attr_count = wc_get_attribute_taxonomies();
			if (count($check_global_attr_count) < 1) {
				$possibleProblemsCount++;
				$possibleProblems .= '<li>'. __('Your site has no global attributes! This may affect the quality of the YML feed. This can also cause difficulties when setting up the plugin', 'xfhu'). '. <a href="https://icopydoc.ru/globalnyj-i-lokalnyj-atributy-v-woocommerce/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=debug-page&utm_term=possible-problems">'. __('Please read the recommendations', 'xfhu'). '</a>.</li>';
			}			
			if (is_plugin_active('snow-storm/snow-storm.php')) {
				$possibleProblemsCount++;
				$conflictWithPlugins++;
				$conflictWithPluginsList .= 'Snow Storm<br/>';
			}
			if (is_plugin_active('email-subscribers/email-subscribers.php')) {
				$possibleProblemsCount++;
				$conflictWithPlugins++;
				$conflictWithPluginsList .= 'Email Subscribers & Newsletters<br/>';
			}
			if ($conflictWithPlugins > 0) {
				$possibleProblemsCount++;
				$possibleProblems .= '<li><p>'. __('Most likely, these plugins negatively affect the operation of', 'xfhu'). ' XML for Hotline:</p>'.$conflictWithPluginsList.'<p>'. __('If you are a developer of one of the plugins from the list above, please contact me', 'xfhu').': <a href="mailto:support@icopydoc.ru">support@icopydoc.ru</a>.</p></li>';
			}
			if ($possibleProblemsCount > 0) {
				echo '<ol>'.$possibleProblems.'</ol>';
			} else {
				echo '<p>'. __('Self-diagnosis functions did not reveal potential problems', 'xfhu').'.</p>';
			}
			unset($possibleProblems);
			unset($possibleProblemsCount);
			unset($check_global_attr_count); 
			unset($conflictWithPlugins); 
			unset($conflictWithPluginsList); 
		  ?>
	  </div>
     </div>	 
	 <div class="postbox">
	  <div class="inside">
	  	<h1><?php _e('Sandbox', 'xfhu'); ?></h1>
			<?php
				require_once plugin_dir_path(__FILE__).'/sandbox.php';
				try {
					xfhu_run_sandbox();
				} catch (Exception $e) {
					echo 'Exception: ',  $e->getMessage(), "\n";
				}
			?>
		</div>
     </div>
  	</div></div>	  	 
  	<div id="postbox-container-4" class="postbox-container"><div class="meta-box-sortables">
	 <?php do_action('xfhu_before_support_project'); ?>
	  <div class="postbox">
	  <div class="inside">
		<h1><?php _e('Send data about the work of the plugin', 'xfhu'); ?></h1>
		<p><?php _e('Sending statistics you help make the plugin even better', 'xfhu'); ?>! <?php _e('The following data will be transferred', 'xfhu'); ?>:</p>
		<ul>
			<li>- <?php _e('Site URL', 'xfhu'); ?></li>
			<li>- <?php _e('File generation status', 'xfhu'); ?></li>
			<li>- <?php _e('URL XML-feed', 'xfhu'); ?></li>
			<li>- <?php _e('Is the multisite mode enabled', 'xfhu'); ?>?</li>
		</ul>
		<p><?php _e('The plugin helped you download the products to the Hotline', 'xfhu'); ?>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
		 <p>
			<input type="radio" name="xfhu_its_ok" value="yes"><?php _e('Yes', 'xfhu'); ?><br />
			<input type="radio" name="xfhu_its_ok" value="no"><?php _e('No', 'xfhu'); ?><br />
		 </p>
		 <p><?php _e("If you don't mind to be contacted in case of problems, please enter your email address", "xfhu"); ?>. <span style="font-weight: 700;"><?php _e('And if you want a response, be sure to include your email address', 'xfhu'); ?></span>.</p>
		 <p><input type="email" name="xfhu_email"></p>
		 <p><?php _e("Your message", "xfhu"); ?>:</p>
		 <p><textarea rows="5" cols="40" name="xfhu_message" placeholder="<?php _e('Enter your text to send me a message (You can write me in Russian or English). I check my email several times a day', 'xfhu'); ?>"></textarea></p>
		 <?php wp_nonce_field('xfhu_nonce_action_send_stat', 'xfhu_nonce_field_send_stat'); ?><input class="button-primary" type="submit" name="xfhu_submit_send_stat" value="<?php _e('Send data', 'xfhu'); ?>" />
		</form>
	  </div>
	 </div>	  
  	</div></div>
  </div></div>
 </div>
<?php
} /* end функция страницы debug-а xfhu_debug_page */