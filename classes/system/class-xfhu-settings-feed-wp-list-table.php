<?php // https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html https://wp-kama.ru/function/wp_list_table
class XFHU_Settings_Feed_WP_List_Table extends WP_List_Table {
	private $feed_id;

	function __construct($feed_id) {
		$this->feed_id = $feed_id;

		global $status, $page;
		parent::__construct( array(
			'plural'	=> '', 		// По умолчанию: '' ($this->screen->base); Название для множественного числа, используется во всяких заголовках, например в css классах, в заметках, например 'posts', тогда 'posts' будет добавлен в класс table.
			'singular'	=> '', 		// По умолчанию: ''; Название для единственного числа, например 'post'. 
			'ajax'		=> false,	// По умолчанию: false; Должна ли поддерживать таблица AJAX. Если true, класс будет вызывать метод _js_vars() в подвале, чтобы передать нужные переменные любому скрипту обрабатывающему AJAX события.
			'screen'	=> null, 	// По умолчанию: null; Строка содержащая название хука, нужного для определения текущей страницы. Если null, то будет установлен текущий экран.
		) );
		add_action('admin_footer', array($this, 'admin_header')); // меняем ширину колонок	
	}

	/*	Сейчас у таблицы стандартные стили WordPress. Чтобы это исправить, вам нужно адаптировать классы CSS, которые были 
	*	автоматически применены к каждому столбцу. Название класса состоит из строки «column-» и ключевого имени 
	* 	массива $columns, например «column-isbn» или «column-author».
	*	В качестве примера мы переопределим ширину столбцов (для простоты, стили прописаны непосредственно в HTML разделе head)
	*/
	function admin_header() {
/*		echo '<style type="text/css">'; 
		echo '#xfhu_google_attribute, .column-xfhu_google_attribute {width: 7%;}';
		echo '</style>';*/
	}

	/*	Метод get_columns() необходим для маркировки столбцов внизу и вверху таблицы. 
	*	Ключи в массиве должны быть теми же, что и в массиве данных, 
	*	иначе соответствующие столбцы не будут отображены.
	*/
	function get_columns() {
		$columns = array(
//			'cb'							=> '<input type="checkbox" />', // флажок сортировки. см get_bulk_actions и column_cb
			'xfhu_google_attribute'		=> __('Google attribute', 'xfhu'),
			'xfhu_attribute_description'	=> __('Attribute description', 'xfhu'),
			'xfhu_value'					=> __('Value', 'xfhu'),
			'xfhu_default_value'			=> __('Default value', 'xfhu'),
		);
		return $columns;
	}
	/*	
	*	Метод вытаскивает из БД данные, которые будут лежать в таблице
	*	$this->table_data();
	*/
	private function table_data() {
		$result_arr = array();

		$feed_id = $this->get_feed_id();

		$result_arr[] = array(
			'xfhu_google_attribute' 		=> sprintf("<span class='xfhu_bold'>%1\$s</span><br/>[%2\$s]", 'ID', 'g:id'),
			'xfhu_attribute_description' 	=> __('Product ID', 'xfhu'),
			'xfhu_value' 					=> __('For default the value is automatically added to your feed from WooCommerce Product ID or Variable ID', 'xfhu'),
			'xfhu_default_value'			=> $this->get_select_html_v2('xfhu_instead_of_id', $feed_id, array(
													'default' => __('Default', 'xfhu'),
													'sku' => __('Sku', 'xfhu'),
												)),
		);

		return $result_arr;
	}

	private function get_input_html($opt_name, $feed_id = '1', $type_placeholder = 'type1') {
		$opt_value = xfhu_optionGET($opt_name, $feed_id, 'set_arr');

		switch ($type_placeholder) {
			case 'type1':
				$placeholder = __('Name post_meta', 'xfhu');
				break;
			case 'type2':
				$placeholder = __('Default value', 'xfhu');
				break;
			case 'type3':
				$placeholder = __('Value', 'xfhu') .' / '. __('Name post_meta', 'xfhu');
				break;
			default:
				$placeholder = __('Name post_meta', 'xfhu');
		}

		return '<input type="text" maxlength="25" name="'.$opt_name.'" id="'.$opt_name.'" value="'.$opt_value.'" placeholder="'.$placeholder.'" />';
	}
	
	private function get_select_html_v2($opt_name, $feed_id = '1', $otions_arr = array()) {
		$opt_value = xfhu_optionGET($opt_name, $feed_id, 'set_arr');

		$res = '<select name="'.$opt_name.'" id="'.$opt_name.'">';
		foreach ($otions_arr as $key => $value) {
			$res .= '<option value="'.$key.'" '.selected($opt_value, $key, false).'>'.$value.'</option>';
		}
		$res .= '</select>';
		return $res;
	}

	private function get_select_desc_html($opt_name, $feed_id = '1', $otions_arr = array()) {
		$opt_value = xfhu_optionGET($opt_name, $feed_id, 'set_arr');

		$res = '<select name="'.$opt_name.'" id="'.$opt_name.'">
					<option value="excerpt" '.selected($opt_value, 'excerpt', false).'>'. __('Only Excerpt description', 'xfhu').'</option>
					<option value="full" '.selected($opt_value, 'full', false).'>'. __('Only Full description', 'xfhu').'</option>
					<option value="excerptfull" '.selected($opt_value, 'excerptfull', false).'>'. __('Excerpt or Full description', 'xfhu').'</option>
					<option value="fullexcerpt" '.selected($opt_value, 'fullexcerpt', false).'>'. __('Full or Excerpt description', 'xfhu').'</option>
					<option value="excerptplusfull" '.selected($opt_value, 'excerptplusfull', false).'>'. __('Excerpt plus Full description', 'xfhu').'</option>
					<option value="fullplusexcerpt" '.selected($opt_value, 'fullplusexcerpt', false).'>'. __('Full plus Excerpt description', 'xfhu').'</option>';
					$res = apply_filters('xfhu_append_select_xfhu_desc_filter', $res, $opt_value, $feed_id); 
		$res .= '</select>';
		return $res;
	}

	private function get_select_html($opt_name, $feed_id = '1', $otions_arr = array()) {
		$opt_value = xfhu_optionGET($opt_name, $feed_id, 'set_arr');

		$res = '<select name="'.$opt_name.'" id="'.$opt_name.'">
					<option value="disabled" '.selected($opt_value, 'disabled', false).'>'. __('Disabled', 'xfhu').'</option>';

					if (isset($otions_arr['yes'])) {
						$res .= '<option value="yes" '.selected($opt_value, 'yes', false).'>'. __('Yes', 'xfhu').'</option>';
					}

					if (isset($otions_arr['no'])) {
						$res .= '<option value="no" '.selected($opt_value, 'no', false).'>'. __('No', 'xfhu').'</option>';
					}

					if (isset($otions_arr['sku'])) {
						$res .= '<option value="sku" '. selected($opt_value, 'sku', false).'>'. __('Substitute from SKU', 'xfhu').'</option>';
					}

					if (isset($otions_arr['post_meta'])) {
						$res .= '<option value="post_meta" '. selected($opt_value, 'post_meta', false).'>'. __('Substitute from post meta', 'xfhu').'</option>';
					}

					if (isset($otions_arr['default_value'])) {
						$res .= '<option value="default_value" '.selected($opt_value, 'default_value', false).'>'. __('Default value from field', 'xfhu').' "'.__('Default value', 'xfhu').'"</option>';
					}

					if (class_exists('WooCommerce_Germanized')) {
						if (isset($otions_arr['germanized'])) {
							$res .= '<option value="germanized" '. selected($opt_value, 'germanized', false).'>'. __('Substitute from', 'xfhu'). 'WooCommerce Germanized</option>';
						}
					}	
					
					if (isset($otions_arr['brands'])) {
						if (is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php') || is_plugin_active('perfect-woocommerce-brands/main.php') || class_exists('Perfect_Woocommerce_Brands')) {
							$res .= '<option value="sfpwb" '. selected($opt_value, 'sfpwb', false).'>'. __('Substitute from', 'xfhu'). 'Perfect Woocommerce Brands</option>';
						}
						if (is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) {
							$res .= '<option value="premmercebrandsplugin" '. selected($opt_value, 'premmercebrandsplugin', false).'>'. __('Substitute from', 'xfhu'). 'Premmerce Brands for WooCommerce</option>';
						}
						if (is_plugin_active('woocommerce-brands/woocommerce-brands.php')) {
							$res .= '<option value="woocommerce_brands" '. selected($opt_value, 'woocommerce_brands', false).'>'. __('Substitute from', 'xfhu'). 'WooCommerce Brands</option>';
						}
						if (class_exists('woo_brands')) {
							$res .= '<option value="woo_brands" '. selected($opt_value, 'woo_brands', false).'>'. __('Substitute from', 'xfhu'). 'Woocomerce Brands Pro</option>';
						}	
					}

					foreach (xfhu_get_attributes() as $attribute) {
						$res .= '<option value="'.$attribute['id'].'" '.selected($opt_value, $attribute['id'], false).'>'.$attribute['name'].'</option>';
					}
		$res .= '</select>';
		return $res;
	}
	/*
	*	prepare_items определяет два массива, управляющие работой таблицы:
	*	$hidden определяет скрытые столбцы https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html#screen-options
	*	$sortable определяет, может ли таблица быть отсортирована по этому столбцу.
	*
	*/
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns(); // вызов сортировки
		$this->_column_headers = array($columns, $hidden, $sortable);
		// блок пагинации пропущен
		$this->items = $this->table_data();
	}
	/*
	* 	Данные таблицы.
	*	Наконец, метод назначает данные из примера на переменную представления данных класса — items.
	*	Прежде чем отобразить каждый столбец, WordPress ищет методы типа column_{key_name}, например, function column_xfhu_attribute_description. 
	*	Такой метод должен быть указан для каждого столбца. Но чтобы не создавать эти методы для всех столбцов в отдельности, 
	*	можно использовать column_default. Эта функция обработает все столбцы, для которых не определён специальный метод:
	*/ 
	function column_default($item, $column_name) {
		switch($column_name) {
			case 'xfhu_google_attribute':
			case 'xfhu_attribute_description':
			case 'xfhu_value':
			case 'xfhu_default_value':
				return $item[$column_name];
			default:
				return print_r($item, true) ; // Мы отображаем целый массив во избежание проблем
		}
	}
	// Флажки для строк должны быть определены отдельно. Как упоминалось выше, есть метод column_{column} для отображения столбца. cb-столбец – особый случай:
/*	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="checkbox_xml_file[]" value="%s" />', $item['xfhu_google_attribute']
		);
	}*/

	private function get_feed_id() {
		return $this->feed_id;
	}
}
?>