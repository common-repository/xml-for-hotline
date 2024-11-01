<?php if (!defined('WP_UNINSTALL_PLUGIN')) {exit;}
if (is_multisite()) {		
	delete_blog_option(get_current_blog_id(), 'xfhu_version');
	delete_blog_option(get_current_blog_id(), 'xfhu_keeplogs');
	delete_blog_option(get_current_blog_id(), 'xfhu_disable_notices');
	delete_blog_option(get_current_blog_id(), 'xfhu_enable_five_min');			
} else {
	delete_option('xfhu_version');
	delete_option('xfhu_keeplogs');
	delete_option('xfhu_disable_notices');
	delete_option('xfhu_enable_five_min');
}

/*
$numFeed = '1'; // (string)
$allNumFeed = (int)xfhu_ALLNUMFEED;
for ($i = 1; $i<$allNumFeed+1; $i++) {
	xfhu_optionDEL('xfhu_skip_products_without_pic', $numFeed);
	xfhu_optionDEL('xfhu_status_cron',$numFeed);
	xfhu_optionDEL('xfhu_step_export', $numFeed);
	xfhu_optionDEL('xfhu_status_sborki', $numFeed);
	xfhu_optionDEL('xfhu_date_sborki', $numFeed);
	xfhu_optionDEL('xfhu_type_sborki', $numFeed);
	xfhu_optionDEL('xfhu_file_url', $numFeed);
	xfhu_optionDEL('xfhu_file_file', $numFeed);
	xfhu_optionDEL('xfhu_file_ids_in_xml', $numFeed);
	xfhu_optionDEL('xfhu_magazin_type', $numFeed);
	xfhu_optionDEL('xfhu_date_save_set', $numFeed);
	xfhu_optionDEL('xfhu_errors', $numFeed);

	xfhu_optionDEL('xfhu_run_cron', $numFeed);
	xfhu_optionDEL('xfhu_ufup', $numFeed);
	xfhu_optionDEL('xfhu_feed_assignment', $numFeed);
	xfhu_optionDEL('xfhu_whot_export', $numFeed); 
	xfhu_optionDEL('xfhu_desc', $numFeed);
	xfhu_optionDEL('xfhu_var_desc_priority', $numFeed);		
	xfhu_optionDEL('xfhu_firmName', $numFeed);
	xfhu_optionDEL('xfhu_firmId', $numFeed);
	xfhu_optionDEL('xfhu_rate', $numFeed);
	xfhu_optionDEL('xfhu_stock_days_default', $numFeed);
	xfhu_optionDEL('xfhu_main_product', $numFeed);
	xfhu_optionDEL('xfhu_allow_group_id_arr', $numFeed);
	xfhu_optionDEL('xfhu_clear_get', $numFeed);
	xfhu_optionDEL('xfhu_behavior_stip_symbol', $numFeed);
	xfhu_optionDEL('xfhu_no_default_png_products', $numFeed);
	xfhu_optionDEL('xfhu_skip_products_without_pic', $numFeed);
	xfhu_optionDEL('xfhu_oldprice', $numFeed);
	xfhu_optionDEL('xfhu_skip_backorders_products', $numFeed);
	xfhu_optionDEL('xfhu_skip_missing_products', $numFeed);
	xfhu_optionDEL('xfhu_vendor', $numFeed);		
	xfhu_optionDEL('xfhu_code', $numFeed);
	xfhu_optionDEL('xfhu_code_post_meta', $numFeed);
	xfhu_optionDEL('xfhu_guarantee', $numFeed);
	xfhu_optionDEL('xfhu_guarantee_type', $numFeed);
	xfhu_optionDEL('xfhu_guarantee_value', $numFeed);
	xfhu_optionDEL('xfhu_guarantee_post_meta', $numFeed);								
	xfhu_optionDEL('xfhu_barcode', $numFeed);
	xfhu_optionDEL('xfhu_manufacture', $numFeed);
	xfhu_optionDEL('xfhu_params_arr', $numFeed);
	xfhu_optionDEL('xfhu_use_delivery', $numFeed);
	xfhu_optionDEL('xfhu_delivery_number', $numFeed);		
	$numFeed++;
}
*/
?>