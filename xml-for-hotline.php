<?php
/**
 * Plugin Name: XML for Hotline
 * Requires Plugins: woocommerce
 * Plugin URI: https://icopydoc.ru/category/documentation/xml-for-hotline/
 * Description: Connect your store to Hotline.ua and increase sales!
 * Version: 1.3.7
 * Requires at least: 4.5
 * Requires PHP: 7.4.0
 * Author: Maxim Glazunov
 * Author URI: https://icopydoc.ru
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: xfhu
 * Domain Path: /languages
 * Tags: xml, hotline, market, export, woocommerce
 * WC requires at least: 3.0.0
 * WC tested up to: 9.1.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Copyright 2018-2024 (Author emails: djdiplomat@yandex.ru, support@icopydoc.ru)
 */
defined( 'ABSPATH' ) || exit;

$nr = false;
// Check php version
if ( version_compare( phpversion(), '7.4.0', '<' ) ) { // не совпали версии
	add_action( 'admin_notices', function () {
		warning_notice( 'notice notice-error',
			sprintf(
				'<strong style="font-weight: 700;">%1$s</strong> %2$s 7.4.0 %3$s %4$s',
				'YML for Yandex Market',
				__( 'plugin requires a php version of at least', 'xml-for-hotline' ),
				__( 'You have the version installed', 'xml-for-hotline' ),
				phpversion()
			)
		);
	} );
	$nr = true;
}

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', [] ) ) )
	&& ! ( is_multisite()
		&& array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', [] ) ) )
) {
	add_action( 'admin_notices', 'xfhu_warning_notice' );
	return;
} else {
	// поддержка HPOS
	add_action( 'before_woocommerce_init', function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );
}

if ( ! function_exists( 'warning_notice' ) ) {
	/**
	 * Display a notice in the admin Plugins page. Usually used in a @hook 'admin_notices'
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $class - Optional
	 * @param string $message - Optional
	 * 
	 * @return void
	 */
	function warning_notice( $class = 'notice', $message = '' ) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
}

// Define constants
define( 'XFHU_PLUGIN_VERSION', '1.3.7' );

$upload_dir = wp_get_upload_dir();
// http://site.ru/wp-content/uploads
define( 'XFHU_SITE_UPLOADS_URL', $upload_dir['baseurl'] );

// /home/site.ru/public_html/wp-content/uploads
define( 'XFHU_SITE_UPLOADS_DIR_PATH', $upload_dir['basedir'] );

// http://site.ru/wp-content/uploads/xml-for-hotline
define( 'XFHU_PLUGIN_UPLOADS_DIR_URL', $upload_dir['baseurl'] . '/xml-for-hotline' );

// /home/site.ru/public_html/wp-content/uploads/xml-for-hotline
define( 'XFHU_PLUGIN_UPLOADS_DIR_PATH', $upload_dir['basedir'] . '/xml-for-hotline' );
unset( $upload_dir );

// http://site.ru/wp-content/plugins/xml-for-hotline/
define( 'XFHU_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/xml-for-hotline/
define( 'XFHU_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/xml-for-hotline/xml-for-hotline.php
define( 'XFHU_PLUGIN_MAIN_FILE_PATH', __FILE__ );

// xml-for-hotline - псевдоним плагина
define( 'XFHU_PLUGIN_SLUG', wp_basename( dirname( __FILE__ ) ) );

// xml-for-hotline/xml-for-hotline.php - полный псевдоним плагина (папка плагина + имя главного файла)
define( 'XFHU_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// $nr = apply_filters('xfhu_f_nr', $nr);

/**
 * Display a notice in the admin Plugins page if the plugin is activated while WooCommerce is deactivated.
 *
 * @hook admin_notices
 * @since 1.0.0
 */
function xfhu_warning_notice() {
	$class = 'notice notice-error';
	$message = 'XML for Hotline ' . __( 'requires WooCommerce installed and activated', 'xfhu' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

require_once plugin_dir_path( __FILE__ ) . '/packages.php';
register_activation_hook( __FILE__, array( 'XmlforHotline', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'XmlforHotline', 'on_deactivation' ) );
add_action( 'plugins_loaded', array( 'XmlforHotline', 'init' ) );

final class XmlforHotline {
	private $site_uploads_url = XFHU_SITE_UPLOADS_URL; // http://site.ru/wp-content/uploads
	private $site_uploads_dir_path = XFHU_SITE_UPLOADS_DIR_PATH; // /home/site.ru/public_html/wp-content/uploads
	private $plugin_version = XFHU_PLUGIN_VERSION; // 1.0.0
	private $plugin_upload_dir_url = XFHU_PLUGIN_UPLOADS_DIR_URL; // http://site.ru/wp-content/uploads/xml-for-hotline/
	private $plugin_upload_dir_path = XFHU_PLUGIN_UPLOADS_DIR_PATH; // /home/site.ru/public_html/wp-content/uploads/xml-for-hotline/
	private $plugin_dir_url = XFHU_PLUGIN_DIR_URL; // http://site.ru/wp-content/plugins/xml-for-hotline/
	private $plugin_dir_path = XFHU_PLUGIN_DIR_PATH; // /home/p135/www/site.ru/wp-content/plugins/xml-for-hotline/
	private $plugin_main_file_path = XFHU_PLUGIN_MAIN_FILE_PATH; // /home/p135/www/site.ru/wp-content/plugins/xml-for-hotline/xml-for-hotline.php
	private $plugin_slug = XFHU_PLUGIN_SLUG; // xml-for-hotline - псевдоним плагина
	private $plugin_basename = XFHU_PLUGIN_BASENAME; // xml-for-hotline/xml-for-hotline.php - полный псевдоним плагина (папка плагина + имя главного файла)

	protected static $instance;
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	// Срабатывает при активации плагина (вызывается единожды)
	public static function on_activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$name_dir = XFHU_SITE_UPLOADS_DIR_PATH . '/xml-for-hotline';
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			}
		}
		$feed_id = '1'; // (string)
		if ( ! defined( 'xfhu_ALLNUMFEED' ) ) {
			define( 'xfhu_ALLNUMFEED', '3' );
		}
		$allNumFeed = (int) xfhu_ALLNUMFEED;
		for ( $i = 1; $i < $allNumFeed + 1; $i++ ) {
			$name_dir = XFHU_SITE_UPLOADS_DIR_PATH . '/xml-for-hotline/feed' . $feed_id;
			if ( ! is_dir( $name_dir ) ) {
				if ( ! mkdir( $name_dir ) ) {
					error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				}
			}
			xfhu_optionADD( 'xfhu_status_sborki', '-1', $feed_id ); // статус сборки файла
			xfhu_optionADD( 'xfhu_params_arr', serialize( array() ), $feed_id );
			xfhu_optionADD( 'xfhu_add_in_name_arr', serialize( array() ), $feed_id );
			xfhu_optionADD( 'xfhu_no_group_id_arr', serialize( array() ), $feed_id );

			$xfhu_registered_feeds_arr = array(
				0 => array( 'last_id' => $feed_id ),
				1 => array( 'id' => $feed_id )
			);
			$xfhu_settings_arr[ $feed_id ] = xfhu_set_default_feed_settings_arr();
			$feed_id++;
		}
		if ( is_multisite() ) {
			add_blog_option( get_current_blog_id(), 'xfhu_version', '1.3.7' );
			add_blog_option( get_current_blog_id(), 'xfhu_keeplogs', '0' );
			add_blog_option( get_current_blog_id(), 'xfhu_disable_notices', '0' );
			add_blog_option( get_current_blog_id(), 'xfhu_enable_five_min', '' );
			add_blog_option( get_current_blog_id(), 'xfhu_feed_content', '' );

			add_blog_option( get_current_blog_id(), 'xfhu_settings_arr', $xfhu_settings_arr );
			add_blog_option( get_current_blog_id(), 'xfhu_registered_feeds_arr', $xfhu_registered_feeds_arr );
		} else {
			add_option( 'xfhu_version', '1.3.7' );
			add_option( 'xfhu_keeplogs', '0' );
			add_option( 'xfhu_disable_notices', '0' );
			add_option( 'xfhu_enable_five_min', '0' );
			add_option( 'xfhu_feed_content', '' );

			add_option( 'xfhu_settings_arr', $xfhu_settings_arr );
			add_option( 'xfhu_registered_feeds_arr', $xfhu_registered_feeds_arr );
		}
	}

	// Срабатывает при отключении плагина (вызывается единожды)
	public static function on_deactivation() {
		$feed_id = '1'; // (string)
		if ( ! defined( 'xfhu_ALLNUMFEED' ) ) {
			define( 'xfhu_ALLNUMFEED', '3' );
		}
		$allNumFeed = (int) xfhu_ALLNUMFEED;
		for ( $i = 1; $i < $allNumFeed + 1; $i++ ) {
			wp_clear_scheduled_hook( 'xfhu_cron_period', array( $feed_id ) );
			wp_clear_scheduled_hook( 'xfhu_cron_sborki', array( $feed_id ) );
			$feed_id++;
		}
		deactivate_plugins( 'xml-for-hotline-pro/xml-for-hotline-pro.php' );
	}

	public function __construct() {
		// xfhu_DIR contains /home/p135/www/site.ru/wp-content/plugins/myplagin/
		define( 'xfhu_DIR', plugin_dir_path( __FILE__ ) );
		// xfhu_URL contains http://site.ru/wp-content/plugins/myplagin/
		define( 'xfhu_URL', plugin_dir_url( __FILE__ ) );
		// xfhu_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads
		$upload_dir = (object) wp_get_upload_dir();
		define( 'xfhu_UPLOAD_DIR', $upload_dir->basedir );
		// xfhu_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads/xml-for-hotline
		$name_dir = $upload_dir->basedir . "/xml-for-hotline";
		define( 'xfhu_NAME_DIR', $name_dir );
		$xfhu_keeplogs = xfhu_optionGET( 'xfhu_keeplogs' );
		define( 'xfhu_KEEPLOGS', $xfhu_keeplogs );
		define( 'xfhu_VER', '1.3.7' );
		$xfhu_version = xfhu_optionGET( 'xfhu_version' );
		if ( $xfhu_version !== xfhu_VER ) {
			$this->xfhu_set_new_options();
		} // автообновим настройки, если нужно	
		if ( ! defined( 'xfhu_ALLNUMFEED' ) ) {
			define( 'xfhu_ALLNUMFEED', '3' );
		}

		load_plugin_textdomain( 'xfhu', false, $this->plugin_slug . '/languages/' ); // load translation
//		$this->check_options_upd(); // проверим, нужны ли обновления опций плагина
		$this->init_hooks(); // подключим хуки
	}

	public function init_hooks() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		/* блок подсказок */
		add_action( 'admin_enqueue_scripts', array( $this, 'xfhu_turn_on_wp_pointer' ), 10 );
		add_filter( 'plugin_action_links', array( $this, 'xfhu_plugin_action_links' ), 10, 2 );
		add_action( 'admin_print_footer_scripts', array( $this, 'xfhu_include_wp_pointer' ), 10 );
		add_action( 'wp_ajax_xfhu_close_pointer', array(&$this, 'xfhu_ajax_find_products' ) ); // подключаем аякс
		/* end блок подсказок */

		add_filter( 'upload_mimes', array( $this, 'add_mime_types_func' ), 99, 1 );
		add_filter( 'cron_schedules', array( $this, 'add_cron_intervals_func' ), 10, 1 );

		add_action( 'xfhu_cron_sborki', array( $this, 'xfhu_do_this_seventy_sec' ), 10, 1 );
		add_action( 'xfhu_cron_period', array( $this, 'xfhu_do_this_event' ), 10, 1 );

		// индивидуальные опции доставки товара
		// add_action('add_meta_boxes', array($this, 'xfhu_add_custom_box'));
		add_action( 'save_post', array( $this, 'xfhu_save_post_product_function' ), 50, 3 );
		// пришлось юзать save_post вместо save_post_product ибо wc блочит обновы

		// https://wpruse.ru/woocommerce/custom-fields-in-products/
		// https://wpruse.ru/woocommerce/custom-fields-in-variations/
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'xfhu_added_wc_tabs' ), 10, 1 );
		add_action( 'admin_footer', array( $this, 'xfhu_art_added_tabs_icon' ), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'xfhu_art_added_tabs_panel' ), 10, 1 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'xfhu_art_woo_custom_fields_save' ), 10, 1 );

		add_action( 'admin_notices', array( $this, 'xfhu_admin_notices_function' ) );

		/* Регаем стили только для страницы настроек плагина */
		add_action( 'admin_init', function () {
			wp_register_style( 'xfhu-admin-css', plugins_url( 'css/xfhu_style.css', __FILE__ ) );
		}, 9999 );
	}

	public function check_options_upd() {
		$plugin_version = $this->get_plugin_version();
		if ( $plugin_version == false ) { // вероятно, у нас первичная установка плагина
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'xfhu_version', XFHU_PLUGIN_VERSION );
			} else {
				update_option( 'xfhu_version', XFHU_PLUGIN_VERSION );
			}
		} else if ( $plugin_version !== $this->plugin_version ) {
			add_action( 'init', array( $this, 'set_new_options' ), 10 ); // автообновим настройки, если нужно
		}
	}

	public function get_plugin_version() {
		if ( is_multisite() ) {
			$v = get_blog_option( get_current_blog_id(), 'xfhu_version' );
		} else {
			$v = get_option( 'xfhu_version' );
		}
		return $v;
	}

	public function listen_submits_func() {
		do_action( 'xfhu_listen_submits' );

	}

	public static function xfhu_admin_css_func() {
		/* Ставим css-файл в очередь на вывод */
		wp_enqueue_style( 'xfhu-admin-css' );
	}

	public static function xfhu_admin_head_css_func() {
		/* печатаем css в шапке админки */
		print '<style>/* Xml for Hotline */
			.metabox-holder .postbox-container .empty-container {height: auto !important;}
			.icp_img1 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl1.jpg);}
			.icp_img2 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl2.jpg);}
			.icp_img3 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl3.jpg);}
			.icp_img4 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl4.jpg);}
			.icp_img5 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl5.jpg);}
			.icp_img6 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl6.jpg);}
			.icp_img7 {background-image: url(' . XFHU_PLUGIN_DIR_URL . '/img/sl7.jpg);}
		</style>';
	}

	public static function xfhu_set_new_options() {
		wp_clean_plugins_cache();
		wp_clean_update_cache();
		add_filter( 'pre_site_transient_update_plugins', '__return_null' );
		wp_update_plugins();
		remove_filter( 'pre_site_transient_update_plugins', '__return_null' );

		$feed_id = '1'; // (string)
		if ( ! defined( 'xfhu_ALLNUMFEED' ) ) {
			define( 'xfhu_ALLNUMFEED', '3' );
		}
		if ( is_multisite() ) {
			if ( get_blog_option( get_current_blog_id(), 'xfhu_settings_arr' ) === false ) {
				$allNumFeed = (int) xfhu_ALLNUMFEED;
				xfhu_add_settings_arr( $allNumFeed );
			}
		} else {
			if ( get_option( 'xfhu_settings_arr' ) === false ) {
				$allNumFeed = (int) xfhu_ALLNUMFEED;
				xfhu_add_settings_arr( $allNumFeed );
			}
		}

		$xfhu_settings_arr = xfhu_optionGET( 'xfhu_settings_arr' );
		$xfhu_settings_arr_keys_arr = array_keys( $xfhu_settings_arr );
		for ( $i = 0; $i < count( $xfhu_settings_arr_keys_arr ); $i++ ) {
			$feed_id = (string) $xfhu_settings_arr_keys_arr[ $i ];

			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_feed_assignment'] ) ) {
				xfhu_optionUPD( 'xfhu_feed_assignment', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_code_post_meta'] ) ) {
				xfhu_optionUPD( 'xfhu_code_post_meta', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_guarantee'] ) ) {
				xfhu_optionUPD( 'xfhu_guarantee', 'disabled', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_guarantee_type'] ) ) {
				xfhu_optionUPD( 'xfhu_guarantee_type', 'manufacturer', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_guarantee_value'] ) ) {
				xfhu_optionUPD( 'xfhu_guarantee_value', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_guarantee_post_meta'] ) ) {
				xfhu_optionUPD( 'xfhu_guarantee_post_meta', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_behavior_stip_symbol'] ) ) {
				xfhu_optionUPD( 'xfhu_behavior_stip_symbol', 'default', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_stock_days_default'] ) ) {
				xfhu_optionUPD( 'xfhu_stock_days_default', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_pickup_options_days_default'] ) ) {
				xfhu_optionUPD( 'xfhu_pickup_options_days_default', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_var_desc_priority'] ) ) {
				xfhu_optionUPD( 'xfhu_var_desc_priority', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_use_delivery'] ) ) {
				xfhu_optionUPD( 'xfhu_use_delivery', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_delivery_number'] ) ) {
				xfhu_optionUPD( 'xfhu_delivery_number', '1', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_skip_missing_products'] ) ) {
				xfhu_optionUPD( 'xfhu_skip_missing_products', '', $feed_id, 'yes', 'set_arr' );
			}
			if ( ! isset( $xfhu_settings_arr[ $feed_id ]['xfhu_skip_backorders_products'] ) ) {
				xfhu_optionUPD( 'xfhu_skip_backorders_products', '', $feed_id, 'yes', 'set_arr' );
			}
		}

		if ( defined( 'xfhu_VER' ) ) {
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'xfhu_version', xfhu_VER );
			} else {
				update_option( 'xfhu_version', xfhu_VER );
			}
		}
	}

	// Добавляем пункты меню
	public function add_admin_menu() {
		$page_suffix = add_menu_page( null, __( 'Export Hotline', 'xfhu' ), 'manage_options', 'xfhuexport', 'xfhu_export_page', 'dashicons-redo', 51 );
		require_once xfhu_DIR . '/export.php'; // Подключаем файл настроек
		// создаём хук, чтобы стили выводились только на странице настроек
		add_action( 'admin_print_styles-' . $page_suffix, array( $this, 'xfhu_admin_css_func' ) );
		add_action( 'admin_print_styles-' . $page_suffix, array( $this, 'xfhu_admin_head_css_func' ) );

		add_submenu_page( 'xfhuexport', __( 'Debug', 'xfhu' ), __( 'Debug page', 'xfhu' ), 'manage_options', 'xfhudebug', 'xfhu_debug_page' );
		require_once xfhu_DIR . '/debug.php';
		$page_subsuffix = add_submenu_page( 'xfhuexport', __( 'Add Extensions', 'xfhu' ), __( 'Extensions', 'xfhu' ), 'manage_options', 'xfhuextensions', 'xfhu_extensions_page' );
		require_once xfhu_DIR . '/extensions.php';
		add_action( 'admin_print_styles-' . $page_subsuffix, array( $this, 'xfhu_admin_css_func' ) );
	}

	// Разрешим загрузку xml и csv файлов
	public function add_mime_types_func( $mimes ) {
		$mimes['csv'] = 'text/csv';
		$mimes['xml'] = 'text/xml';
		return $mimes;
	}

	// добавляем интервалы крон в 70 секунд и 6 часов 
	public function add_cron_intervals_func( $schedules ) {
		$schedules['fifty_sec'] = array(
			'interval' => 61, // 50
			'display' => '61 sec'
		);
		$schedules['seventy_sec'] = array(
			'interval' => 70,
			'display' => '70 sec'
		);
		$schedules['five_min'] = array(
			'interval' => 300,
			'display' => '5 min'
		);
		$schedules['six_hours'] = array(
			'interval' => 21600,
			'display' => '6 hours'
		);
		$schedules['every_two_days'] = array(
			'interval' => 172800,
			'display' => __( 'Every two days', 'xfhu' )
		);
		return $schedules;
	}

	/* блок подсказок */
	public static function xfhu_turn_on_wp_pointer() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}

	public static function xfhu_plugin_action_links( $actions, $plugin_file ) {
		if ( false === strpos( $plugin_file, basename( __FILE__ ) ) ) {
			// проверка, что у нас текущий плагин
			return $actions;
		}
		$settings_link = '<a href="/wp-admin/admin.php?page=xfhuexport">' . __( 'Settings', 'xfhu' ) . '</a>';
		array_unshift( $actions, $settings_link );
		return $actions;
	}

	public static function xfhu_include_wp_pointer() {
		// https://misha.blog/wordpress/wp-pointer.html
		$pointer_position_arr = array( 'top', 'top' ); /* куда указывает стрелка: left - влево, right - вправо, top - вверх, bottom - вниз */
		$pointer_title_arr = array( __( 'Change value', 'xfhu' ), __( 'Vendor', 'xfhu' ) );	// заголовок
		$pointer_content_arr = array( __( 'To create a XML-feed must not be "Disabled"', 'xfhu' ), __( 'Make sure your products have a', 'xfhu' ) . ' <a href="https://icopydoc.ru/global-and-local-attributes-in-woocommerce/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=wp-pointer&utm_term=global-attribute">' . __( 'global attribute', 'xfhu' ) . '</a>, ' . __( 'that stores the value vendor', 'xfhu' ) . '. ' . __( 'Otherwise, the XML-feed will be empty', 'xfhu' ) . '!' ); // основное сообщение
		$pointer_css_id_arr = array( 'xfhu_run_cron', 'xfhu_vendor' ); // ID (CSS) элемента, к которому нужно прилепить подсказку xfhu_run_cron
		$pointer_id_arr = array( 1, 2 );  // ID всплыв. подсказки
		?>
		<script type="text/javascript">//<![CDATA[
			jQuery(document).ready(function ($) {
				<?php for ( $i = 0; $i < count( $pointer_position_arr ); $i++ ) :
					if ( get_user_meta( get_current_user_id(), 'deny_' . $pointer_id_arr[ $i ], true ) == 1 ) {
						continue;
					} ?>
					$('#<?php echo $pointer_css_id_arr[ $i ] ?>').pointer({
						content: '<?php echo '<h3>' . $pointer_title_arr[ $i ] . '</h3><p>' . $pointer_content_arr[ $i ] . '</p>' ?>',
						position: '<?php echo $pointer_position_arr[ $i ]; ?>',
						close: function () {
							data = {
								action: 'xfhu_close_pointer', // add_action('wp_ajax_xfhu_close_pointer', 'my_action_callback');
								pointer_id: '<?php echo $pointer_id_arr[ $i ]; ?>',
								user_id: '<?php echo get_current_user_id(); ?>'
							};
							$.ajax({ // старт аякс обработки
								type: 'POST',
								dataType: "json",
								url: ajaxurl,
								data: data, // brpv_ajax_php_func - функция в php файле, в которой происходит обработка аякс запроса
								// data, а точнее $_REQUEST['data'] хранит массив с именами и значениями полей формы
								beforeSend: function () {
									// происходит непосредственно перед отправкой запроса на сервер.
									console.log('отработала beforeSend');
									// console.log(pointer_id);
								},
								error: function (response) {
									// происходит в случае неудачного выполнения запроса.
									console.log('отработала error');
									console.table(response);
								},
								/*	вот эта штука может не работать. Из-за нее могут не возвращаться данные из php
								dataFilter : function(resp) {
									// происходит в момент прибытия данных с сервера. Позволяет обработать "сырые" данные, присланные сервером.
									console.log('отработала dataFilter');
								}, */
								success: function (response) {
									// происходит в случае удачного завершения запроса
									console.log('отработала success');
									console.table(response); /* ОТЛАДОЧНАЯ ИНФОРМАЦИЯ. Что вернулось? */
									//var contact = parseJSON(response);
									if (response.status == "true") {
										console.log('response.status == true');
									} else {
										console.log('pointer_id и user_id не дошли до запроса - false');
										// блокируем кнопку создания поста
									}
								},
								complete: function () {
									// происходит в случае любого завершения запроса
									console.log('отработала complete');
								}
							});
							action: 'dismiss-wp-pointer'
						}
					}).pointer('open'); <?php endfor; ?>
			});//]]></script><?php
	}

	// аякс обработчик закрытой подсказки 
	public function xfhu_ajax_find_products() {
		if ( isset( $_POST['pointer_id'] ) && ! empty( $_POST['pointer_id'] ) && isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ) {
			$pointer_id = strip_tags( $_POST['pointer_id'] );
			$user_id = strip_tags( $_POST['user_id'] );
			update_user_meta( $user_id, 'deny_' . $pointer_id, '1' );
			$res['pointer_id'] = $pointer_id;
			$res['user_id'] = $user_id;
			$res['status'] = "true";
		} else {
			$res['status'] = "false";
		}
		echo json_encode( $res );
		die();
	}
	/* end блок подсказок */

	// Сохраняем данные блока, когда пост сохраняется
	function xfhu_save_post_product_function( $post_id, $post, $update ) {
		xfhu_error_log( 'Стартовала функция xfhu_save_post_product_function! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );

		if ( $post->post_type !== 'product' ) {
			return;
		} // если это не товар вукомерц
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		} // если это ревизия
		// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
		// если это автосохранение ничего не делаем
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// проверяем права юзера
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Все ОК. Теперь, нужно найти и сохранить данные
		// Очищаем значение поля input.
		xfhu_error_log( 'Работает функция xfhu_save_post_product_function! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );

		// Убедимся что поле установлено.
		/* if (isset($_POST['xfhu_cost'])) {
						   $xfhu_credit_template = sanitize_text_field($_POST['xfhu_credit_template']);	
						   // Обновляем данные в базе данных
						   update_post_meta($post_id, 'xfhu_credit_template', $xfhu_credit_template);				
					   } */

		$feed_id = '1'; // (string) создадим строковую переменную
		// нужно ли запускать обновление фида при перезаписи файла
		$allNumFeed = (int) xfhu_ALLNUMFEED;
		for ( $i = 1; $i < $allNumFeed + 1; $i++ ) {

			xfhu_error_log( 'FEED № ' . $feed_id . '; Шаг $i = ' . $i . ' цикла по формированию кэша файлов; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );

			$result_xml_unit = xfhu_unit( $post_id, $feed_id ); // формируем фид товара
			if ( is_array( $result_xml_unit ) ) {
				$result_xml = $result_xml_unit[0];
				$ids_in_xml = $result_xml_unit[1];
			} else {
				$result_xml = $result_xml_unit;
				$ids_in_xml = '';
			}
			xfhu_wf( $result_xml, $post_id, $feed_id, $ids_in_xml ); // записываем кэш-файл

			$xfhu_ufup = xfhu_optionGET( 'xfhu_ufup', $feed_id, 'set_arr' );
			if ( $xfhu_ufup !== 'on' ) {
				$feed_id++;
				continue; /*return;*/
			}
			$status_sborki = (int) xfhu_optionGET( 'xfhu_status_sborki', $feed_id );
			if ( $status_sborki > -1 ) {
				$feed_id++;
				continue; /*return;*/
			} // если идет сборка фида - пропуск

			$xfhu_date_save_set = xfhu_optionGET( 'xfhu_date_save_set', $feed_id, 'set_arr' );
			$xfhu_date_sborki = xfhu_optionGET( 'xfhu_date_sborki', $feed_id, 'set_arr' );

			if ( $feed_id === '1' ) {
				$prefFeed = '';
			} else {
				$prefFeed = $feed_id;
			}
			if ( is_multisite() ) {
				/*
				 *	wp_get_upload_dir();
				 *   'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
				 *	'url'     => 'http://site.ru/wp-content/uploads/2016/04',
				 *	'subdir'  => '/2016/04',
				 *	'basedir' => '/home/site.ru/public_html/wp-content/uploads',
				 *	'baseurl' => 'http://site.ru/wp-content/uploads',
				 *	'error'   => false,
				 */
				$upload_dir = (object) wp_get_upload_dir();
				$filenamefeed = $upload_dir->basedir . "/xml-for-hotline/" . $prefFeed . "feed-hotline-" . get_current_blog_id() . ".xml";
			} else {
				$upload_dir = (object) wp_get_upload_dir();
				$filenamefeed = $upload_dir->basedir . "/xml-for-hotline/" . $prefFeed . "feed-hotline-0.xml";
			}
			if ( ! file_exists( $filenamefeed ) ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; WARNING: Файла filenamefeed = ' . $filenamefeed . ' не существует! Пропускаем быструю сборку; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				$feed_id++;
				continue; /*return;*/
			} // файла с фидом нет

			clearstatcache(); // очищаем кэш дат файлов
			$last_upd_file = filemtime( $filenamefeed );
			xfhu_error_log( 'FEED № ' . $feed_id . '; $xfhu_date_save_set=' . $xfhu_date_save_set . ';$filenamefeed=' . $filenamefeed, 0 );
			xfhu_error_log( 'FEED № ' . $feed_id . '; Начинаем сравнивать даты! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			if ( $xfhu_date_save_set > $last_upd_file ) {
				// настройки фида сохранялись позже, чем создан фид		
				// нужно полностью пересобрать фид
				xfhu_error_log( 'FEED № ' . $feed_id . '; NOTICE: Настройки фида сохранялись позже, чем создан фид; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				$xfhu_status_cron = xfhu_optionGET( 'xfhu_status_cron', $feed_id, 'set_arr' );
				$recurrence = $xfhu_status_cron;
				wp_clear_scheduled_hook( 'xfhu_cron_period', array( $feed_id ) );
				wp_schedule_event( time(), $recurrence, 'xfhu_cron_period', array( $feed_id ) );
				xfhu_error_log( 'FEED № ' . $feed_id . '; xfhu_cron_period внесен в список заданий! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			} else { // нужно лишь обновить цены	
				xfhu_error_log( 'FEED № ' . $feed_id . '; NOTICE: Настройки фида сохранялись раньше, чем создан фид. Нужно лишь обновить цены; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				xfhu_clear_file_ids_in_xml( $feed_id ); /* С версии 3.1.0 */
				xfhu_onlygluing( $feed_id );
			}
			$feed_id++;
		}
		return;
	}

	/* функции крона */
	public function xfhu_do_this_seventy_sec( $feed_id = '1' ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Крон xfhu_do_this_seventy_sec запущен; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
		$this->xfhu_construct_xml( $feed_id ); // делаем что-либо каждые 70 сек
	}
	public function xfhu_do_this_event( $feed_id = '1' ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Крон xfhu_do_this_event включен. Делаем что-то каждый час; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
		$step_export = (int) xfhu_optionGET( 'xfhu_step_export', $feed_id, 'set_arr' );
		if ( $step_export === 0 ) {
			$step_export = 500;
		}
		xfhu_optionUPD( 'xfhu_status_sborki', $step_export, $feed_id );

		wp_clear_scheduled_hook( 'xfhu_cron_sborki', array( $feed_id ) );

		// Возвращает nul/false. null когда планирование завершено. false в случае неудачи.
		$res = wp_schedule_event( time(), 'seventy_sec', 'xfhu_cron_sborki', array( $feed_id ) );
		if ( $res === false ) {
			xfhu_error_log( 'FEED № ' . $feed_id . '; ERROR: Не удалось запланировань CRON seventy_sec; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
		} else {
			xfhu_error_log( 'FEED № ' . $feed_id . '; CRON seventy_sec успешно запланирован; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
		}
	}
	/* end функции крона */

	public static function xfhu_added_wc_tabs( $tabs ) {
		$tabs['xfhu_special_panel'] = array(
			'label' => __( 'Hotline', 'xfhu' ), // название вкладки
			'target' => 'xfhu_added_wc_tabs', // идентификатор вкладки
			'class' => array( 'hide_if_grouped' ), // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 70, // приоритет вывода
		);
		return $tabs;
	}

	public static function xfhu_art_added_tabs_icon() {
		// https://rawgit.com/woothemes/woocommerce-icons/master/demo.html 
		?>
		<style>
			#woocommerce-coupon-data ul.wc-tabs li.xfhu_special_panel_options a::before,
			#woocommerce-product-data ul.wc-tabs li.xfhu_special_panel_options a::before,
			.woocommerce ul.wc-tabs li.xfhu_special_panel_options a::before {
				font-family: WooCommerce;
				content: "\e014";
			}
		</style>
		<?php
	}

	public static function xfhu_art_added_tabs_panel() {
		global $post; ?>
		<div id="xfhu_added_wc_tabs" class="panel woocommerce_options_panel">
			<?php do_action( 'xfhu_before_options_group', $post ); ?>
			<div class="options_group">
				<h2><strong><?php _e( 'Individual product settings for XML-feed hotline.ua', 'xfhu' ); ?></strong></h2>
				<?php do_action( 'xfhu_prepend_options_group', $post ); ?>
				<?php
				woocommerce_wp_select( array(
					'id' => '_xfhu_condition',
					'label' => __( 'Product condition', 'xfhu' ),
					'options' => array(
						'disabled' => __( 'Disabled', 'xfhu' ),
						'0' => __( 'New', 'xfhu' ),
						'1' => __( 'Refurbished', 'xfhu' ),
						'2' => __( 'Reject', 'xfhu' ),
						'3' => __( 'Used', 'xfhu' ),
					),
				) );

				// цифровое поле
				woocommerce_wp_text_input( array(
					'id' => '_xfhu_custom',
					'label' => __( 'Custom', 'xfhu' ),
					'placeholder' => '1',
					'description' => __( 'Only numbers are entered', 'xfhu' ),
					'type' => 'number',
					'custom_attributes' => array(
						'step' => 'any',
						'min' => '0',
					),
				) ); ?>
				<?php do_action( 'xfhu_append_options_group', $post ); ?>
			</div>
			<?php do_action( 'xfhu_after_options_group', $post ); ?>
		</div>
		<?php
	}

	public static function xfhu_art_woo_custom_fields_save( $post_id ) {
		// Сохранение текстового поля
		if ( isset( $_POST['_xfhu_condition'] ) ) {
			update_post_meta( $post_id, '_xfhu_condition', sanitize_text_field( $_POST['_xfhu_condition'] ) );
		}
		if ( isset( $_POST['_xfhu_custom'] ) ) {
			update_post_meta( $post_id, '_xfhu_custom', sanitize_text_field( $_POST['_xfhu_custom'] ) );
		}
	}

	// Вывод различных notices
	public function xfhu_admin_notices_function() {
		$feed_id = '1'; // (string) создадим строковую переменную
		// нужно ли запускать обновление фида при перезаписи файла
		$allNumFeed = (int) xfhu_ALLNUMFEED;

		$xfhu_disable_notices = xfhu_optionGET( 'xfhu_disable_notices' );
		if ( $xfhu_disable_notices !== 'on' ) {
			for ( $i = 1; $i < $allNumFeed + 1; $i++ ) {
				$status_sborki = xfhu_optionGET( 'xfhu_status_sborki', $feed_id );
				if ( $status_sborki == false ) {
					$feed_id++;
					continue;
				} else {
					$status_sborki = (int) $status_sborki;
				}
				if ( $status_sborki !== -1 ) {
					$count_posts = wp_count_posts( 'product' );
					$vsegotovarov = $count_posts->publish;
					$step_export = (int) xfhu_optionGET( 'xfhu_step_export', $feed_id, 'set_arr' );
					if ( $step_export === 0 ) {
						$step_export = 500;
					}
					$vobrabotke = $status_sborki - $step_export;
					if ( $vsegotovarov > $vobrabotke ) {
						$vyvod = 'FEED № ' . $feed_id . ' ' . __( 'Progress', 'xfhu' ) . ': ' . $vobrabotke . ' ' . __( 'from', 'xfhu' ) . ' ' . $vsegotovarov . ' ' . __( 'products', 'xfhu' ) . '.<br />' . __( 'If the progress indicators have not changed within 20 minutes, try reducing the "Step of export" in the plugin settings', 'xfhu' );
					} else {
						$vyvod = 'FEED № ' . $feed_id . ' ' . __( 'Prior to the completion of less than 70 seconds', 'xfhu' );
					}
					print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'We are working on automatic file creation. xml will be developed soon', 'xfhu' ) . '. ' . $vyvod . '.</p></div>';
				}
				$feed_id++;
			}
		}

		if ( xfhu_optionGET( 'xfhu_magazin_type', $feed_id, 'set_arr' ) === 'woocommerce' ) {
			if ( ! class_exists( 'WooCommerce' ) ) {
				print '<div class="notice error is-dismissible"><p>' . __( 'WooCommerce is not active', 'xfhu' ) . '!</p></div>';
			}
		}

		if ( isset( $_REQUEST['xfhu_submit_action'] ) ) {
			$run_text = '';
			if ( sanitize_text_field( $_POST['xfhu_run_cron'] ) !== 'off' ) {
				$run_text = '. ' . __( 'Creating the feed is running. You can continue working with the website', 'xfhu' );
			}
			print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'Updated', 'xfhu' ) . $run_text . '.</p></div>';
		}

		if ( isset( $_REQUEST['xfhu_submit_debug_page'] ) ) {
			print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'Updated', 'xfhu' ) . '.</p></div>';
		}

		if ( isset( $_REQUEST['xfhu_submit_clear_logs'] ) ) {
			$upload_dir = (object) wp_get_upload_dir();
			$name_dir = $upload_dir->basedir . "/xml-for-hotline";
			$filename = $name_dir . '/xml-for-hotline.log';
			if ( file_exists( $filename ) ) {
				$res = unlink( $filename );
			} else {
				$res = false;
			}
			if ( $res == true ) {
				print '<div class="notice notice-success is-dismissible"><p>' . __( 'Logs were cleared', 'xfhu' ) . '.</p></div>';
			} else {
				print '<div class="notice notice-warning is-dismissible"><p>' . __( 'Error accessing log file. The log file may have been deleted previously', 'xfhu' ) . '.</p></div>';
			}
		}

		/* сброс настроек */
		if ( isset( $_REQUEST['xfhu_submit_reset'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfhu_nonce_action_reset', 'xfhu_nonce_field_reset' ) ) {
				$this->on_uninstall();
				$this->on_activation();
				print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'The settings have been reset', 'xfhu' ) . '.</p></div>';
			}
		} /* end сброс настроек */

		/* отправка отчёта */
		if ( isset( $_REQUEST['xfhu_submit_send_stat'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfhu_nonce_action_send_stat', 'xfhu_nonce_field_send_stat' ) ) {
				if ( is_multisite() ) {
					$xfhu_is_multisite = 'включен';
					$xfhu_keeplogs = get_blog_option( get_current_blog_id(), 'xfhu_keeplogs' );
				} else {
					$xfhu_is_multisite = 'отключен';
					$xfhu_keeplogs = get_option( 'xfhu_keeplogs' );
				}
				$feed_id = '1'; // (string)
				$mail_content = "Версия плагина: " . xfhu_VER . PHP_EOL;
				$mail_content .= "Версия WP: " . get_bloginfo( 'version' ) . PHP_EOL;
				$woo_version = xfhu_get_woo_version_number();
				$mail_content .= "Версия WC: " . $woo_version . PHP_EOL;
				$mail_content .= "Режим мультисайта: " . $xfhu_is_multisite . PHP_EOL;
				$mail_content .= "Вести логи: " . $xfhu_keeplogs . PHP_EOL;
				$mail_content .= "Расположение логов: " . xfhu_UPLOAD_DIR . '/xfhu.log' . PHP_EOL;
				if ( ! class_exists( 'XmlforHotlinePro' ) ) {
					$mail_content .= "Pro: не активна" . PHP_EOL;
				} else {
					if ( ! defined( 'xfhup_VER' ) ) {
						define( 'xfhup_VER', 'н/д' );
					}
					$order_id = xfhu_optionGET( 'xfhup_order_id' );
					$order_email = xfhu_optionGET( 'xfhup_order_email' );
					$mail_content .= "Pro: активна (v " . xfhup_VER . " (#" . $order_id . " / " . $order_email . "))" . PHP_EOL;
				}
				if ( isset( $_REQUEST['xfhu_its_ok'] ) ) {
					$mail_content .= PHP_EOL . "Помог ли плагин: " . sanitize_text_field( $_REQUEST['xfhu_its_ok'] );
				}
				if ( isset( $_POST['xfhu_email'] ) ) {
					$mail_content .= PHP_EOL . "Почта: " . sanitize_text_field( $_POST['xfhu_email'] );
				}
				if ( isset( $_POST['xfhu_message'] ) ) {
					$mail_content .= PHP_EOL . "Сообщение: " . sanitize_text_field( $_POST['xfhu_message'] );
				}
				$argsp = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );
				$products = new WP_Query( $argsp );
				$vsegotovarov = $products->found_posts;
				$mail_content .= PHP_EOL . "Число товаров на выгрузку: " . $vsegotovarov;
				$allNumFeed = (int) xfhu_ALLNUMFEED;
				for ( $i = 1; $i < $allNumFeed + 1; $i++ ) {
					$status_sborki = (int) xfhu_optionGET( 'xfhu_status_sborki', $feed_id );
					$xfhu_file_url = urldecode( xfhu_optionGET( 'xfhu_file_url', $feed_id, 'set_arr' ) );
					$xfhu_file_file = urldecode( xfhu_optionGET( 'xfhu_file_file', $feed_id, 'set_arr' ) );
					$xfhu_whot_export = xfhu_optionGET( 'xfhu_whot_export', $feed_id, 'set_arr' );
					$xfhu_skip_missing_products = xfhu_optionGET( 'xfhu_skip_missing_products', $feed_id, 'set_arr' );
					$xfhu_skip_backorders_products = xfhu_optionGET( 'xfhu_skip_backorders_products', $feed_id, 'set_arr' );
					$xfhu_status_cron = xfhu_optionGET( 'xfhu_status_cron', $feed_id, 'set_arr' );
					$xfhu_ufup = xfhu_optionGET( 'xfhu_ufup', $feed_id, 'set_arr' );
					$xfhu_date_sborki = xfhu_optionGET( 'xfhu_date_sborki', $feed_id, 'set_arr' );
					$xfhu_main_product = xfhu_optionGET( 'xfhu_main_product', $feed_id, 'set_arr' );
					$xfhu_errors = xfhu_optionGET( 'xfhu_errors', $feed_id, 'set_arr' );

					$mail_content .= PHP_EOL . "ФИД №: " . $i . PHP_EOL . PHP_EOL;
					$mail_content .= "status_sborki: " . $status_sborki . PHP_EOL;
					$mail_content .= "УРЛ: " . get_site_url() . PHP_EOL;
					$mail_content .= "УРЛ YML-фида: " . $xfhu_file_url . PHP_EOL;
					$mail_content .= "Временный файл: " . $xfhu_file_file . PHP_EOL;
					$mail_content .= "Что экспортировать: " . $xfhu_whot_export . PHP_EOL;
					$mail_content .= "Автоматическое создание файла: " . $xfhu_status_cron . PHP_EOL;
					$mail_content .= "Обновить фид при обновлении карточки товара: " . $xfhu_ufup . PHP_EOL;
					$mail_content .= "Дата последней сборки XML: " . $xfhu_date_sborki . PHP_EOL;
					$mail_content .= "Что продаёт: " . $xfhu_main_product . PHP_EOL;
					$mail_content .= "Ошибки: " . $xfhu_errors . PHP_EOL;
					$feed_id++;
				}
				wp_mail( 'support@icopydoc.ru', 'Отчёт YML for Hotline', $mail_content );
				print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'The data has been sent. Thank you', 'xfhu' ) . '.</p></div>';
			}
		} /* end отправка отчёта */
	}

	// сборка
	public static function xfhu_construct_xml( $feed_id = '1' ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Стартовала xfhu_construct_xml. Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );

		$result_xml = '';
		$status_sborki = (int) xfhu_optionGET( 'xfhu_status_sborki', $feed_id );

		// файл уже собран. На всякий случай отключим крон сборки
		if ( $status_sborki == -1 ) {
			wp_clear_scheduled_hook( 'xfhu_cron_sborki', array( $feed_id ) );
			return;
		}

		$xfhu_date_save_set = xfhu_optionGET( 'xfhu_date_save_set', $feed_id, 'set_arr' );
		if ( $xfhu_date_save_set == '' ) {
			$unixtime = current_time( 'timestamp', 1 ); // 1335808087 - временная зона GMT(Unix формат)
			xfhu_optionUPD( 'xfhu_date_save_set', $unixtime, $feed_id );
		}
		$xfhu_date_sborki = xfhu_optionGET( 'xfhu_date_sborki', $feed_id, 'set_arr' );

		if ( $feed_id === '1' ) {
			$prefFeed = '';
		} else {
			$prefFeed = $feed_id;
		}
		if ( is_multisite() ) {
			/*
			 * wp_get_upload_dir();
			 * 'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
			 * 'url'     => 'http://site.ru/wp-content/uploads/2016/04',
			 * 'subdir'  => '/2016/04',
			 * 'basedir' => '/home/site.ru/public_html/wp-content/uploads',
			 * 'baseurl' => 'http://site.ru/wp-content/uploads',
			 * 'error'   => false,
			 */
			$upload_dir = (object) wp_get_upload_dir();
			$filenamefeed = $upload_dir->basedir . "/xml-for-hotline/" . $prefFeed . "feed-hotline-" . get_current_blog_id() . ".xml";
		} else {
			$upload_dir = (object) wp_get_upload_dir();
			$filenamefeed = $upload_dir->basedir . "/xml-for-hotline/" . $prefFeed . "feed-hotline-0.xml";
		}
		if ( file_exists( $filenamefeed ) ) {
			xfhu_error_log( 'FEED № ' . $feed_id . '; Файл с фидом ' . $filenamefeed . ' есть. Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			// return; // файла с фидом нет
			clearstatcache(); // очищаем кэш дат файлов
			$last_upd_file = filemtime( $filenamefeed );
			xfhu_error_log( 'FEED № ' . $feed_id . '; $xfhu_date_save_set=' . $xfhu_date_save_set . '; $filenamefeed=' . $filenamefeed, 0 );
			xfhu_error_log( 'FEED № ' . $feed_id . '; Начинаем сравнивать даты! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			if ( $xfhu_date_save_set < $last_upd_file ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; NOTICE: Нужно лишь обновить цены во всём фиде! Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				xfhu_clear_file_ids_in_xml( $feed_id ); /* С версии 3.1.0 */
				xfhu_onlygluing( $feed_id );
				return;
			}
		}
		// далее исходим из того, что файла с фидом нет, либо нужна полная сборка

		$step_export = (int) xfhu_optionGET( 'xfhu_step_export', $feed_id, 'set_arr' );
		if ( $step_export == 0 ) {
			$step_export = 500;
		}

		if ( $status_sborki == $step_export ) { // начинаем сборку файла
			do_action( 'xfhu_before_construct', 'full' ); // сборка стартовала
			$result_xml = xfhu_feed_header( $feed_id );
			/* создаем файл или перезаписываем старый удалив содержимое */
			$result = xfhu_write_file( $result_xml, 'w+', $feed_id );
			if ( $result !== true ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; xfhu_write_file вернула ошибку! $result =' . $result . '; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				return;
			} else {
				xfhu_error_log( 'FEED № ' . $feed_id . '; xfhu_write_file отработала успешно; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
			}
			xfhu_clear_file_ids_in_xml( $feed_id );
		}
		if ( $status_sborki > 1 ) {
			$result_xml = '';
			$offset = $status_sborki - $step_export;
			$whot_export = xfhu_optionGET( 'xfhu_whot_export', $feed_id, 'set_arr' );
			if ( $whot_export === 'xfhup_vygruzhat' ) {
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => $step_export,
					'offset' => $offset,
					'relation' => 'AND',
					'meta_query' => array(
						array(
							'key' => 'xfhup_vygruzhat',
							'value' => 'on'
						)
					)
				);
			} else { // if ($whot_export == 'all' || $whot_export == 'simple')
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => $step_export, // сколько выводить товаров
					'offset' => $offset,
					'relation' => 'AND'
				);
			}

			$args = apply_filters( 'xfhu_query_arg_filter', $args, $feed_id );
			$featured_query = new WP_Query( $args );
			$prod_id_arr = array();
			if ( $featured_query->have_posts() ) {
				for ( $i = 0; $i < count( $featured_query->posts ); $i++ ) {
					// $prod_id_arr[] .= $featured_query->posts[$i]->ID;
					$prod_id_arr[ $i ]['ID'] = $featured_query->posts[ $i ]->ID;
					$prod_id_arr[ $i ]['post_modified_gmt'] = $featured_query->posts[ $i ]->post_modified_gmt;
				}
				wp_reset_query(); /* Remember to reset */
				unset( $featured_query ); // чутка освободим память
				xfhu_gluing( $prod_id_arr, $feed_id );
				$status_sborki = $status_sborki + $step_export;
				xfhu_error_log( 'FEED № ' . $feed_id . '; status_sborki увеличен на ' . $step_export . ' и равен ' . $status_sborki . '; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				xfhu_optionUPD( 'xfhu_status_sborki', $status_sborki, $feed_id );
			} else {
				// если постов нет, пишем концовку файла
				xfhu_error_log( 'FEED № ' . $feed_id . '; Постов больше нет, пишем концовку файла; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				$result_xml = apply_filters( 'xfhu_after_offers_filter', $result_xml, $feed_id );
				$result_xml .= "</items>" . PHP_EOL . "</price>";
				/* создаем файл или перезаписываем старый удалив содержимое */
				$result = xfhu_write_file( $result_xml, 'a', $feed_id );
				xfhu_error_log( 'FEED № ' . $feed_id . '; Файл фида готов. Осталось только переименовать временный файл в основной; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				xfhu_rename_file( $feed_id );
				// выставляем статус сборки в "готово"
				$status_sborki = -1;
				if ( $result === true ) {
					xfhu_optionUPD( 'xfhu_status_sborki', $status_sborki, $feed_id );
					// останавливаем крон сборки
					wp_clear_scheduled_hook( 'xfhu_cron_sborki', array( $feed_id ) );
					do_action( 'xfhu_after_construct', 'full' ); // сборка закончена
					xfhu_error_log( 'FEED № ' . $feed_id . '; SUCCESS: Сборка успешно завершена; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
				} else {
					xfhu_error_log( 'FEED № ' . $feed_id . '; ERROR: На завершающем этапе xfhu_write_file вернула ошибку! Я не смог записать концовку файла... $result =' . $result . '; Файл: xml-for-hotline.php; Строка: ' . __LINE__, 0 );
					do_action( 'xfhu_after_construct', 'false' ); // сборка закончена
					return;
				}
			} // end if ($featured_query->have_posts())
		} // end if ($status_sborki > 1)
	} // end public static function xfhu_construct_xml
} /* end class XmlforHotline */