<?php if (!defined('ABSPATH')) {exit;}
/*
* С версии 1.0.0
* Добавлен параметр $n
* Записывает или обновляет файл фида.
* Возвращает всегда true
*/
function xfhu_write_file($result_xml, $cc, $feed_id = '1') {
 /* $cc = 'w+' или 'a'; */	 
 xfhu_error_log('FEED № '.$feed_id.'; Стартовала xfhu_write_file c параметром cc = '.$cc.'; Файл: functions.php; Строка: '.__LINE__, 0);
 $filename = urldecode(xfhu_optionGET('xfhu_file_file', $feed_id, 'set_arr'));
 if ($feed_id === '1') {$prefFeed = '';} else {$prefFeed = $feed_id;}

 if ($filename == '') {	
	$upload_dir = (object)wp_get_upload_dir(); // $upload_dir->basedir
	$filename = $upload_dir->basedir."/xml-for-hotline/".$prefFeed."feed-hotline-0-tmp.xml"; // $upload_dir->path
 }
		
 // if ((validate_file($filename) === 0)&&(file_exists($filename))) {
 if (file_exists($filename)) {
	// файл есть
	if (!$handle = fopen($filename, $cc)) {
		xfhu_error_log('FEED № '.$feed_id.'; Не могу открыть файл '.$filename.'; Файл: functions.php; Строка: '.__LINE__, 0);
		xfhu_errors_log('FEED № '.$feed_id.'; Не могу открыть файл '.$filename.'; Файл: functions.php; Строка: '.__LINE__, 0);
	}
	if (fwrite($handle, $result_xml) === FALSE) {
		xfhu_error_log('FEED № '.$feed_id.'; Не могу произвести запись в файл '.$handle.'; Файл: functions.php; Строка: '.__LINE__, 0);
		xfhu_errors_log('FEED № '.$feed_id.'; Не могу произвести запись в файл '.$handle.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		xfhu_error_log('FEED № '.$feed_id.'; Ура! Записали; Файл: Файл: functions.php; Строка: '.__LINE__, 0);
		xfhu_error_log($filename, 0);
		return true;
	}
	fclose($handle);
 } else {
	xfhu_error_log('FEED № '.$feed_id.'; Файла $filename = '.$filename.' еще нет. Файл: functions.php; Строка: '.__LINE__, 0);
	// файла еще нет
	// попытаемся создать файл
	if (is_multisite()) {
		$upload = wp_upload_bits($prefFeed.'feed-hotline-'.get_current_blog_id().'-tmp.xml', null, $result_xml ); // загружаем shop2_295221-xml в папку загрузок
	} else {
		$upload = wp_upload_bits($prefFeed.'feed-hotline-0-tmp.xml', null, $result_xml ); // загружаем shop2_295221-xml в папку загрузок
	}
	/*
	*	для работы с csv или xml требуется в плагине разрешить загрузку таких файлов
	*	$upload['file'] => '/var/www/wordpress/wp-content/uploads/2010/03/feed-xml.xml', // путь
	*	$upload['url'] => 'http://site.ru/wp-content/uploads/2010/03/feed-xml.xml', // урл
	*	$upload['error'] => false, // сюда записывается сообщение об ошибке в случае ошибки
	*/
	// проверим получилась ли запись
	if ($upload['error']) {
		xfhu_error_log('FEED № '.$feed_id.'; Запись вызвала ошибку: '. $upload['error'].'; Файл: functions.php; Строка: '.__LINE__, 0);
		$err = 'FEED № '.$feed_id.'; Запись вызвала ошибку: '. $upload['error'].'; Файл: functions.php; Строка: '.__LINE__ ;
		xfhu_errors_log($err);
	} else {
		xfhu_optionUPD('xfhu_file_file', urlencode($upload['file']), $feed_id, 'yes', 'set_arr');
		xfhu_error_log('FEED № '.$feed_id.'; Запись удалась! Путь файла: '. $upload['file'] .'; УРЛ файла: '. $upload['url'], 0);
		return true;
	}		
 }
}
/*
* С версии 1.0.0
* Перименовывает временный файл фида в основной.
* Возвращает false/true
*/
function xfhu_rename_file($feed_id = '1') {
 xfhu_error_log('FEED № '.$feed_id.'; Cтартовала xfhu_rename_file; Файл: functions.php; Строка: '.__LINE__, 0);	
 if ($feed_id === '1') {$prefFeed = '';} else {$prefFeed = $feed_id;}	
 /* Перименовывает временный файл в основной. Возвращает true/false */
 if (is_multisite()) {
	$upload_dir = (object)wp_get_upload_dir();
	$filenamenew = $upload_dir->basedir."/xml-for-hotline/".$prefFeed."feed-hotline-".get_current_blog_id().".xml";
	$filenamenewurl = $upload_dir->baseurl."/xml-for-hotline/".$prefFeed."feed-hotline-".get_current_blog_id().".xml";		
	// $filenamenew = BLOGUPLOADDIR."feed-hotline-".get_current_blog_id().".xml";
	// надо придумать как поулчить урл загрузок конкретного блога
 } else {
	$upload_dir = (object)wp_get_upload_dir();
	/*
	*   'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
	*	'url'     => 'http://site.ru/wp-content/uploads/2016/04',
	*	'subdir'  => '/2016/04',
	*	'basedir' => '/home/site.ru/public_html/wp-content/uploads',
	*	'baseurl' => 'http://site.ru/wp-content/uploads',
	*	'error'   => false,
	*/
	$filenamenew = $upload_dir->basedir."/xml-for-hotline/".$prefFeed."feed-hotline-0.xml";
	$filenamenewurl = $upload_dir->baseurl."/xml-for-hotline/".$prefFeed."feed-hotline-0.xml";
 }
 $filenameold = urldecode(xfhu_optionGET('xfhu_file_file', $feed_id, 'set_arr'));

 xfhu_error_log('FEED № '.$feed_id.'; $filenameold = '.$filenameold.'; Файл: functions.php; Строка: '.__LINE__, 0);
 xfhu_error_log('FEED № '.$feed_id.'; $filenamenew = '.$filenamenew.'; Файл: functions.php; Строка: '.__LINE__, 0);

 if (rename($filenameold, $filenamenew) === FALSE) {
	xfhu_error_log('FEED № '.$feed_id.'; Не могу переименовать файл из '.$filenameold.' в '.$filenamenew.'! Файл: functions.php; Строка: '.__LINE__, 0);
	return false;
 } else {
	xfhu_optionUPD('xfhu_file_url', urlencode($filenamenewurl), $feed_id, 'yes', 'set_arr');
	xfhu_error_log('FEED № '.$feed_id.'; Файл переименован! Файл: functions.php; Строка: '.__LINE__, 0);
	return true;
 }
}
/*
* С версии 1.0.0
* Возвращает URL без get-параметров или возвращаем только get-параметры
*/	
function xfhu_deleteGET($url, $whot = 'url') {
 $url = str_replace("&amp;", "&", $url); // Заменяем сущности на амперсанд, если требуется
 list($url_part, $get_part) = array_pad(explode("?", $url), 2, ""); // Разбиваем URL на 2 части: до знака ? и после
 if ($whot == 'url') {
	return $url_part; // Возвращаем URL без get-параметров (до знака вопроса)
 } else if ($whot == 'get') {
	return $get_part; // Возвращаем get-параметры (без знака вопроса)
 } else {
	return false;
 }
}
/*
* С версии 1.0.0
* Записывает текст ошибки, чтобы потом можно было отправить в отчет
*/
function xfhu_errors_log($message) {
 if (is_multisite()) {
	update_blog_option(get_current_blog_id(), 'xfhu_errors', $message);
 } else {
	update_option('xfhu_errors', $message);
 }
}
/*
* С версии 1.0.0
* Возвращает версию Woocommerce (string) или (null)
*/ 
function xfhu_get_woo_version_number() {
 // If get_plugins() isn't available, require it
 if (!function_exists('get_plugins')) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php');
 }
 // Create the plugins folder and file variables
 $plugin_folder = get_plugins('/' . 'woocommerce');
 $plugin_file = 'woocommerce.php';
	
 // If the plugin version number is set, return it 
 if (isset( $plugin_folder[$plugin_file]['Version'] ) ) {
	return $plugin_folder[$plugin_file]['Version'];
 } else {	
	return NULL;
 }
}
/*
* С версии 1.0.0
* Возвращает дерево таксономий, обернутое в <option></option>
*/
function xfhu_cat_tree($TermName='', $termID, $value_arr, $separator='', $parent_shown=true) {
 /* 
 * $value_arr - массив id отмеченных ранее select-ов
 */
 $result = '';
 $args = 'hierarchical=1&taxonomy='.$TermName.'&hide_empty=0&orderby=id&parent=';
 if ($parent_shown) {
	$term = get_term($termID , $TermName); 
	$selected = '';
	if (!empty($value_arr)) {
	 foreach ($value_arr as $value) {		
	  if ($value == $term->term_id) {
		$selected = 'selected'; break;
	  }
	 }
	}
	// $result = $separator.$term->name.'('.$term->term_id.')<br/>';
	$result = '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'xfhu'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';
	$parent_shown = false;
 }
 $separator .= '-';  
 $terms = get_terms($TermName, $args . $termID);
 if (count($terms) > 0) {
	foreach ($terms as $term) {
	 $selected = '';
	 if (!empty($value_arr)) {
	  foreach ($value_arr as $value) {
	   if ($value == $term->term_id) {
		$selected = 'selected'; break;
	   }
	  }
	 }
	 $result .= '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'xfhu'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';
	 // $result .=  $separator.$term->name.'('.$term->term_id.')<br/>';
	 $result .= xfhu_cat_tree($TermName, $term->term_id, $value_arr, $separator, $parent_shown);
	}
 }
 return $result; 
}
/*
* @since 1.0.0
*
* @param string $optName (require)
* @param string $value (require)
* @param string $n (not require)
* @param string $autoload (not require) (yes/no) (@since 1.2.0)
* @param string $type (not require) (@since 1.2.0)
* @param string $source_settings_name (not require) (@since 1.2.0)
*
* @return true/false
* Возвращает то, что может быть результатом add_blog_option, add_option
*/
function xfhu_optionADD($option_name, $value = '', $n = '', $autoload = 'yes', $type = 'option', $source_settings_name = '') {
	if ($option_name == '') {return false;}
	switch ($type) {
		case "set_arr":
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET('xfhu_settings_arr');
			$xfhu_settings_arr[$n][$option_name] = $value;
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), 'xfhu_settings_arr', $xfhu_settings_arr);
			} else {
				return update_option('xfhu_settings_arr', $xfhu_settings_arr, $autoload);
			}
		break;
		case "custom_set_arr":
			if ($source_settings_name === '') {return false;}
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET($source_settings_name);
			$xfhu_settings_arr[$n][$option_name] = $value;
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), $source_settings_name, $xfhu_settings_arr);
			} else {
				return update_option($source_settings_name, $xfhu_settings_arr, $autoload);
			}
		break;
		default:
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return add_blog_option(get_current_blog_id(), $option_name, $value);
			} else {
				return add_option($option_name, $value, '', $autoload);
			}
	}
}
/*
* @since 1.0.0
*
* @param string $optName (require)
* @param string $value (require)
* @param string $n (not require)
* @param string $autoload (not require) (yes/no) (@since 1.2.0)
* @param string $type (not require) (@since 1.2.0)
* @param string $source_settings_name (not require) (@since 1.2.0)
*
* @return true/false
* Возвращает то, что может быть результатом update_blog_option, update_option
*/
function xfhu_optionUPD($option_name, $value = '', $n = '', $autoload = 'yes', $type = '', $source_settings_name = '') {
	if ($option_name == '') {return false;}
	switch ($type) {
		case "set_arr": 
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET('xfhu_settings_arr');
			$xfhu_settings_arr[$n][$option_name] = $value;
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), 'xfhu_settings_arr', $xfhu_settings_arr);
			} else {
				return update_option('xfhu_settings_arr', $xfhu_settings_arr, $autoload);
			}
		break;
		case "custom_set_arr": 
			if ($source_settings_name === '') {return false;}
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET($source_settings_name);
			$xfhu_settings_arr[$n][$option_name] = $value;
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), $source_settings_name, $xfhu_settings_arr);
			} else {
				return update_option($source_settings_name, $xfhu_settings_arr, $autoload);
			}
		break;
		default:
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), $option_name, $value);
			} else {
				return update_option($option_name, $value, $autoload);
			}
	}
}
/*
* @since 1.0.0
*
* @param string $optName (require)
* @param string $n (not require)
* @param string $type (not require) (@since 1.2.0)
* @param string $source_settings_name (not require) (@since 1.2.0)
*
* @return Значение опции или false
* Возвращает то, что может быть результатом get_blog_option, get_option
*/
function xfhu_optionGET($option_name, $n = '', $type = '', $source_settings_name = '') {
	if (defined('xfhup_VER')) {$pro_ver_number = xfhup_VER;} else {$pro_ver_number = '1.2.0';}
	if (version_compare($pro_ver_number, '1.2.0', '<')) { // если версия PRO ниже 1.2.0
		if ($option_name === 'xfhup_compare_value') {
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return get_blog_option(get_current_blog_id(), $option_name);
			} else {
				return get_option($option_name);
			}
		}
		if ($option_name === 'xfhup_compare') {
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return get_blog_option(get_current_blog_id(), $option_name);
			} else {
				return get_option($option_name);
			}
		}
	}

	if ($option_name == '') {return false;}	
	switch ($type) {
		case "set_arr": 
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET('xfhu_settings_arr');
			if (isset($xfhu_settings_arr[$n][$option_name])) {
				return $xfhu_settings_arr[$n][$option_name];
			} else {
				return false;
			}
		break;
		case "custom_set_arr":
			if ($source_settings_name === '') {return false;}
			if ($n === '') {$n = '1';}
			$xfhu_settings_arr = xfhu_optionGET($source_settings_name);
			if (isset($xfhu_settings_arr[$n][$option_name])) {
				return $xfhu_settings_arr[$n][$option_name];
			} else {
				return false;
			}
		break;
		case "for_update_option":
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return get_blog_option(get_current_blog_id(), $option_name);
			} else {
				return get_option($option_name);
			}		
		break;
		default:
			/* for old premium versions */
			if ($option_name === 'xfhu_desc') {return xfhu_optionGET($option_name, $n, 'set_arr');}		
			if ($option_name === 'xfhu_no_default_png_products') {return xfhu_optionGET($option_name, $n, 'set_arr');}
			if ($option_name === 'xfhu_whot_export') {return xfhu_optionGET($option_name, $n, 'set_arr');}
			/* for old premium versions */
			if ($n === '1') {$n = '';}
			$option_name = $option_name.$n;
			if (is_multisite()) { 
				return get_blog_option(get_current_blog_id(), $option_name);
			} else {
				return get_option($option_name);
			}
	}
}
/*
* @since 1.0.0
*
* @param string $optName (require)
* @param string $n (not require)
* @param string $type (not require) (@since 1.2.0)
* @param string $source_settings_name (not require) (@since 1.2.0)
*
* @return true/false
* Возвращает то, что может быть результатом delete_blog_option, delete_option
*/
function xfhu_optionDEL($option_name, $n = '', $type = '', $source_settings_name = '') {
	if ($option_name == '') {return false;}	 
	switch ($type) {
		case "set_arr": 
			if ($n === '') {$n = '1';} 
			$xfhu_settings_arr = xfhu_optionGET('xfhu_settings_arr');
			unset($xfhu_settings_arr[$n][$option_name]);
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), 'xfhu_settings_arr', $xfhu_settings_arr);
			} else {
				return update_option('xfhu_settings_arr', $xfhu_settings_arr);
			}
		break;
		case "custom_set_arr": 
			if ($source_settings_name === '') {return false;}
			if ($n === '') {$n = '1';} 
			$xfhu_settings_arr = xfhu_optionGET($source_settings_name);
			unset($xfhu_settings_arr[$n][$option_name]);
			if (is_multisite()) { 
				return update_blog_option(get_current_blog_id(), $source_settings_name, $xfhu_settings_arr);
			} else {
				return update_option($source_settings_name, $xfhu_settings_arr);
			}
		break;
		default:
		if ($n === '1') {$n = '';} 
		$option_name = $option_name.$n;
		if (is_multisite()) { 
			return delete_blog_option(get_current_blog_id(), $option_name);
		} else {
			return delete_option($option_name);
		}
	}
} 
/*
* @since 1.0.0
* 
* Создает tmp файл-кэш товара
*/
function xfhu_wf($result_xml, $postId, $feed_id = '1', $ids_in_xml = '') {
	$upload_dir = (object)wp_get_upload_dir();
	$name_dir = $upload_dir->basedir.'/xml-for-hotline/feed'.$feed_id;
	if (!is_dir($name_dir)) {
		error_log('WARNING: Папкт $name_dir ='.$name_dir.' нет; Файл: functions.php; Строка: '.__LINE__, 0);
		if (!mkdir($name_dir)) {
			error_log('ERROR: Создать папку $name_dir ='.$name_dir.' не вышло; Файл: functions.php; Строка: '.__LINE__, 0);
		}
	}
	if (is_dir($name_dir)) {
		$filename = $name_dir.'/'.$postId.'.tmp';
		$fp = fopen($filename, "w");
		fwrite($fp, $result_xml); // записываем в файл текст
		fclose($fp); // закрываем
	
		$filename = $name_dir.'/'.$postId.'-in.tmp';
		$fp = fopen($filename, "w");
		fwrite($fp, $ids_in_xml);
		fclose($fp);		
	} else {
		error_log('ERROR: Нет папки xfhu! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	}
}
/*
* @since 1.0.0
* Функция склейки/сборки
*/
function xfhu_gluing($id_arr, $feed_id = '1') {
 /*	
 * $id_arr[$i]['ID'] - ID товара
 * $id_arr[$i]['post_modified_gmt'] - Время обновления карточки товара
 * global $wpdb;
 * $res = $wpdb->get_results("SELECT ID, post_modified_gmt FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish'");	
 */	
 xfhu_error_log('FEED № '.$feed_id.'; Стартовала xfhu_gluing; Файл: functions.php; Строка: '.__LINE__, 0);
 if ($feed_id === '1') {$prefFeed = '';} else {$prefFeed = $feed_id;} 

 $upload_dir = (object)wp_get_upload_dir();
 $name_dir = $upload_dir->basedir.'/xml-for-hotline';
 if (!is_dir($name_dir)) {
  if (!mkdir($name_dir)) {
	 error_log('ERROR: Ошибка создания папки '.$name_dir.'; Файл: xml-for-hotline.php; Строка: '.__LINE__, 0);
	 //return false;
  }
 }

 $upload_dir = (object)wp_get_upload_dir();
 $name_dir = $upload_dir->basedir.'/xml-for-hotline/feed'.$feed_id;
 if (!is_dir($name_dir)) {
	if (!mkdir($name_dir)) {
		error_log('FEED № '.$feed_id.'; Нет папки xfhu! И создать не вышло! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		error_log('FEED № '.$feed_id.'; Создали папку xfhu! Файл: functions.php; Строка: '.__LINE__, 0);
	}
 }
 
 $xfhu_file_file = urldecode(xfhu_optionGET('xfhu_file_file', $feed_id, 'set_arr'));
 $xfhu_file_ids_in_xml = urldecode(xfhu_optionGET('xfhu_file_ids_in_xml', $feed_id, 'set_arr'));

 $xfhu_date_save_set = xfhu_optionGET('xfhu_date_save_set', $feed_id, 'set_arr');
 clearstatcache(); // очищаем кэш дат файлов
 // $prod_id
 foreach ($id_arr as $product) {
	$filename = $name_dir.'/'.$product['ID'].'.tmp';
	$filenameIn = $name_dir.'/'.$product['ID'].'-in.tmp';
	xfhu_error_log('FEED № '.$feed_id.'; RAM '.round(memory_get_usage()/1024, 1).' Кб. ID товара/файл = '.$product['ID'].'.tmp; Файл: functions.php; Строка: '.__LINE__, 0);
	if (is_file($filename) && is_file($filenameIn)) { // if (file_exists($filename)) {
		$last_upd_file = filemtime($filename); // 1318189167			
		if (($last_upd_file < strtotime($product['post_modified_gmt'])) || ($xfhu_date_save_set > $last_upd_file)) {
			// Файл кэша обновлен раньше чем время модификации товара
			// или файл обновлен раньше чем время обновления настроек фида
			xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Файл кэша '.$filename.' обновлен РАНЬШЕ чем время модификации товара или время сохранения настроек фида! Файл: functions.php; Строка: '.__LINE__, 0);	
			$result_xml_unit = xfhu_unit($product['ID'], $feed_id);
			if (is_array($result_xml_unit)) {
				$result_xml = $result_xml_unit[0];
				$ids_in_xml = $result_xml_unit[1];
			} else {
				$result_xml = $result_xml_unit;
				$ids_in_xml = '';
			}	
			xfhu_wf($result_xml, $product['ID'], $feed_id, $ids_in_xml);
			file_put_contents($xfhu_file_file, $result_xml, FILE_APPEND);			
			file_put_contents($xfhu_file_ids_in_xml, $ids_in_xml, FILE_APPEND);
		} else {
			// Файл кэша обновлен позже чем время модификации товара
			// или файл обновлен позже чем время обновления настроек фида
			xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Файл кэша '.$filename.' обновлен ПОЗЖЕ чем время модификации товара или время сохранения настроек фида; Файл: functions.php; Строка: '.__LINE__, 0);
			xfhu_error_log('FEED № '.$feed_id.'; Пристыковываем файл кэша без изменений; Файл: functions.php; Строка: '.__LINE__, 0);
			$result_xml = file_get_contents($filename);
			file_put_contents($xfhu_file_file, $result_xml, FILE_APPEND);
			$ids_in_xml = file_get_contents($filenameIn);
			file_put_contents($xfhu_file_ids_in_xml, $ids_in_xml, FILE_APPEND);
		}
	} else { // Файла нет
		xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Файла кэша товара '.$filename.' ещё нет! Создаем... Файл: functions.php; Строка: '.__LINE__, 0);		
		$result_xml_unit = xfhu_unit($product['ID'], $feed_id);
		if (is_array($result_xml_unit)) {
			$result_xml = $result_xml_unit[0];
			$ids_in_xml = $result_xml_unit[1];
		} else {
			$result_xml = $result_xml_unit;
			$ids_in_xml = '';
		}
		xfhu_wf($result_xml, $product['ID'], $feed_id, $ids_in_xml);
		xfhu_error_log('FEED № '.$feed_id.'; Создали! Файл: functions.php; Строка: '.__LINE__, 0);
		file_put_contents($xfhu_file_file, $result_xml, FILE_APPEND);
		file_put_contents($xfhu_file_ids_in_xml, $ids_in_xml, FILE_APPEND);
	}
 }
} // end function xfhu_gluing()
/*
* @since 1.0.0
* Функция склейки
*/
function xfhu_onlygluing($feed_id = '1') {
 xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Стартовала xfhu_onlygluing; Файл: functions.php; Строка: '.__LINE__, 0); 	
 do_action('xfhu_before_construct', 'cache');
 $result_xml = xfhu_feed_header($feed_id);
 /* создаем файл или перезаписываем старый удалив содержимое */
 $result = xfhu_write_file($result_xml, 'w+', $feed_id);
 if ($result !== true) {
	xfhu_error_log('FEED № '.$feed_id.'; xfhu_write_file вернула ошибку! $result ='.$result.'; Файл: functions.php; Строка: '.__LINE__, 0);
 } 
 
 xfhu_optionUPD('xfhu_status_sborki', '-1', $feed_id); 
 $whot_export = xfhu_optionGET('xfhu_whot_export', $feed_id, 'set_arr');

 $result_xml = '';
 $step_export = -1;
 $prod_id_arr = array(); 
 
 if ($whot_export === 'xfhup_vygruzhat') {
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $step_export, // сколько выводить товаров
		// 'offset' => $offset,
		'relation' => 'AND',
		'fields'  => 'ids',
		'meta_query' => array(
			array(
				'key' => 'xfhup_vygruzhat',
				'value' => 'on'
			)
		)
	);	
 } else { //  if ($whot_export == 'all' || $whot_export == 'simple')
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $step_export, // сколько выводить товаров
		// 'offset' => $offset,
		'relation' => 'AND',
		'fields'  => 'ids'
	);
 }

 $args = apply_filters('xfhu_query_arg_filter', $args, $feed_id);
 xfhu_error_log('FEED № '.$feed_id.'; NOTICE: xfhu_onlygluing до запуска WP_Query RAM '.round(memory_get_usage()/1024, 1) . ' Кб; Файл: functions.php; Строка: '.__LINE__, 0); 
 $featured_query = new WP_Query($args);
 xfhu_error_log('FEED № '.$feed_id.'; NOTICE: xfhu_onlygluing после запуска WP_Query RAM '.round(memory_get_usage()/1024, 1) . ' Кб; Файл: functions.php; Строка: '.__LINE__, 0); 
 
 global $wpdb;
 if ($featured_query->have_posts()) { 
	for ($i = 0; $i < count($featured_query->posts); $i++) {
		/*	
		*	если не юзаем 'fields'  => 'ids'
		*	$prod_id_arr[$i]['ID'] = $featured_query->posts[$i]->ID;
		*	$prod_id_arr[$i]['post_modified_gmt'] = $featured_query->posts[$i]->post_modified_gmt;
		*/
		$curID = $featured_query->posts[$i];
		$prod_id_arr[$i]['ID'] = $curID;

		$res = $wpdb->get_results($wpdb->prepare("SELECT post_modified_gmt FROM $wpdb->posts WHERE id=%d", $curID), ARRAY_A);
		$prod_id_arr[$i]['post_modified_gmt'] = $res[0]['post_modified_gmt']; 	
		// get_post_modified_time('Y-m-j H:i:s', true, $featured_query->posts[$i]);
	}
	wp_reset_query(); /* Remember to reset */
	unset($featured_query); // чутка освободим память
 }
 if (!empty($prod_id_arr)) {
	xfhu_error_log('FEED № '.$feed_id.'; NOTICE: xfhu_onlygluing передала управление xfhu_gluing; Файл: functions.php; Строка: '.__LINE__, 0);
	xfhu_gluing($prod_id_arr, $feed_id);
 }
 
 // если постов нет, пишем концовку файла
 xfhu_error_log('FEED № '.$feed_id.'; Постов больше нет, пишем концовку файла; Файл: functions.php; Строка: '.__LINE__, 0); 
 $result_xml = apply_filters('xfhu_after_offers_filter', $result_xml, $feed_id);
 $result_xml .= "</items>". PHP_EOL ."</price>";
 /* создаем файл или перезаписываем старый удалив содержимое */
 $result = xfhu_write_file($result_xml, 'a', $feed_id);
 xfhu_rename_file($feed_id);	 
 // выставляем статус сборки в "готово"
 $status_sborki = -1;
 if ($result == true) {
	xfhu_optionGET('xfhu_status_sborki', $status_sborki, $feed_id);	
	// останавливаем крон сборки
	wp_clear_scheduled_hook('xfhu_cron_sborki');
	do_action('xfhu_after_construct', 'cache');
 } else {
	xfhu_error_log('FEED № '.$feed_id.'; xfhu_write_file вернула ошибку! Я не смог записать концовку файла... $result ='.$result.'; Файл: functions.php; Строка: '.__LINE__, 0);
	do_action('xfhu_after_construct', 'false');
 }
} // end function xfhu_onlygluing()
/*
* С версии 1.0.0
* Записывает файл логов /wp-content/uploads/xml-for-hotline/xml-for-hotline.log
*/
function xfhu_error_log($text, $i) {
 if (xfhu_KEEPLOGS !== 'on') {return;}
 $upload_dir = (object)wp_get_upload_dir();
 $name_dir = $upload_dir->basedir."/xml-for-hotline";
 // подготовим массив для записи в файл логов
 if (is_array($text)) {$r = xfhu_array_to_log($text); unset($text); $text = $r;}
 if (is_dir($name_dir)) {
	$filename = $name_dir.'/xml-for-hotline.log';
	file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);		
 } else {
	if (!mkdir($name_dir)) {
		error_log('Нет папки xfhu! И создать не вышло! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		error_log('Создали папку xfhu!; Файл: functions.php; Строка: '.__LINE__, 0);
		$filename = $name_dir.'/xml-for-hotline.log';
		file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);
	}
 } 
 return;
}
/*
* С версии 1.0.0
* Позволяте писать в логи массив /wp-content/uploads/xml-for-hotline/xml-for-hotline.log
*/
function xfhu_array_to_log($text, $i=0, $res = '') {
 $tab = ''; for ($x = 0; $x<$i; $x++) {$tab = '---'.$tab;}
 if (is_array($text)) { 
  $i++;
  foreach ($text as $key => $value) {
	if (is_array($value)) {	// массив
		$res .= PHP_EOL .$tab."[$key] => ";
		$res .= $tab.xfhu_array_to_log($value, $i);
	} else { // не массив
		$res .= PHP_EOL .$tab."[$key] => ". $value;
	}
  }
 } else {
	$res .= PHP_EOL .$tab.$text;
 }
 return $res;
}
/*
* С версии 1.0.0
* получить все атрибуты вукомерца 
*/
function xfhu_get_attributes() {
 $result = array();
 $attribute_taxonomies = wc_get_attribute_taxonomies();
 if (count($attribute_taxonomies) > 0) {
	$i = 0;
    foreach($attribute_taxonomies as $one_tax ) {
		/**
		* $one_tax->attribute_id => 6
		* $one_tax->attribute_name] => слаг (на инглише или русском)
		* $one_tax->attribute_label] => Еще один атрибут (это как раз название)
		* $one_tax->attribute_type] => select 
		* $one_tax->attribute_orderby] => menu_order
		* $one_tax->attribute_public] => 0			
		*/
		$result[$i]['id'] = $one_tax->attribute_id;
		$result[$i]['name'] = $one_tax->attribute_label;
		$i++;
    }
 }
 return $result;
}
 
/*
* @since 1.0.0
* С версии  1.0.0
*
* @param string $feed_id (not require)
*
* @return nothing
* Создает пустой файл ids_in_xml.tmp или очищает уже имеющийся
*/
function xfhu_clear_file_ids_in_xml($feed_id = '1') {
	$xfhu_file_ids_in_xml = urldecode(xfhu_optionGET('xfhu_file_ids_in_xml', $feed_id, 'set_arr'));
	if (!is_file($xfhu_file_ids_in_xml)) {
		xfhu_error_log('FEED № '.$feed_id.'; WARNING: Файла c idшниками $xfhu_file_ids_in_xml = '.$xfhu_file_ids_in_xml.' нет! Создадим пустой; Файл: function.php; Строка: '.__LINE__, 0);

		$upload_dir = (object)wp_get_upload_dir();
		$name_dir = $upload_dir->basedir."/xml-for-hotline";

		$xfhu_file_ids_in_xml = $name_dir .'/feed'.$feed_id.'/ids_in_xml.tmp';		
		$res = file_put_contents($xfhu_file_ids_in_xml, '');
		if ($res !== false) {
			xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Файл c idшниками $xfhu_file_ids_in_xml = '.$xfhu_file_ids_in_xml.' успешно создан; Файл: function.php; Строка: '.__LINE__, 0);
			xfhu_optionUPD('xfhu_file_ids_in_xml', urlencode($xfhu_file_ids_in_xml), $feed_id, 'yes', 'set_arr');
		} else {
			xfhu_error_log('FEED № '.$feed_id.'; ERROR: Ошибка создания файла $xfhu_file_ids_in_xml = '.$xfhu_file_ids_in_xml.'; Файл: function.php; Строка: '.__LINE__, 0);
		}
	} else {
		xfhu_error_log('FEED № '.$feed_id.'; NOTICE: Обнуляем файл $xfhu_file_ids_in_xml = '.$xfhu_file_ids_in_xml.'; Файл: function.php; Строка: '.__LINE__, 0);
		file_put_contents($xfhu_file_ids_in_xml, '');
	}
}
/*
* @since 1.1.0
*
* @return formatted string
*/
function xfhu_formatSize($bytes) {
	if ($bytes >= 1073741824) {
		   $bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576) {
		   $bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024) {
	   $bytes = number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1) {
		$bytes = $bytes . ' байты';
	}
	elseif ($bytes == 1) {
	   $bytes = $bytes . ' байт';
	}
	else {
	   $bytes = '0 байтов';
	}
	return $bytes;
}
/*
* @since 1.1.5
*
* @return formatted string
*/
function xfhu_replace_symbol($string, $feed_id = '1') {
	$xfhu_behavior_stip_symbol = xfhu_optionGET('xfhu_behavior_stip_symbol', $feed_id, 'set_arr');	
	switch ($xfhu_behavior_stip_symbol) {
	   case "del":	
		   $string = str_replace("&", '', $string);
	   break;
	   case "slash":
		   $string = str_replace("&", '/', $string);
	   break;
	   case "amp":
		   $string = htmlspecialchars($string);
	   break;
	   default:
		   $string = htmlspecialchars($string);
	}
	return $string;
}
/*
* @since 1.2.0
*
* @param string $dir (require)
*
* @return nothing
*/
function xfhu_remove_directory($dir) {
	if ($objs = glob($dir."/*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? xfhu_remove_directory($obj) : unlink($obj);
		}
	}
	rmdir($dir);
}
/*
* @since 1.2.0
*
* @return int
* Возвращает количетсво всех фидов
*/
function xfhu_number_all_feeds() {
	$xfhu_settings_arr = xfhu_optionGET('xfhu_settings_arr');
	if ($xfhu_settings_arr === false) {
		return -1;
	} else {
		return count($xfhu_settings_arr);
	}
}

function xfhu_add_settings_arr($allNumFeed) {
	$feed_id = '1';
	for ($i = 1; $i<$allNumFeed+1; $i++) {	 
	   wp_clear_scheduled_hook('xfhu_cron_period', array($feed_id));
	   wp_clear_scheduled_hook('xfhu_cron_sborki', array($feed_id));
	   $feed_id++;
	}
 
	$xfhu_settings_arr = array();
	$feed_id = '1';  
	for ($i = 1; $i<$allNumFeed+1; $i++) { 
		$xfhu_settings_arr[$feed_id]['xfhu_skip_products_without_pic'] = xfhu_optionGET('xfhu_skip_products_without_pic', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_status_cron'] = xfhu_optionGET('xfhu_status_cron', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_step_export'] = xfhu_optionGET('xfhu_step_export', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_status_sborki'] = xfhu_optionGET('xfhu_status_sborki', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_date_sborki'] = xfhu_optionGET('xfhu_date_sborki', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_type_sborki'] = xfhu_optionGET('xfhu_type_sborki', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_file_url'] = xfhu_optionGET('xfhu_file_url', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_file_file'] = xfhu_optionGET('xfhu_file_file', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_file_ids_in_xml'] = xfhu_optionGET('xfhu_file_ids_in_xml', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_magazin_type'] = xfhu_optionGET('xfhu_magazin_type', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_date_save_set'] = xfhu_optionGET('xfhu_date_save_set', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_errors'] = xfhu_optionGET('xfhu_errors', $feed_id, 'for_update_option');
	
		$xfhu_settings_arr[$feed_id]['xfhu_run_cron'] = xfhu_optionGET('xfhu_run_cron', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_ufup'] = xfhu_optionGET('xfhu_ufup', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_feed_assignment'] = xfhu_optionGET('xfhu_feed_assignment', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_whot_export'] = xfhu_optionGET('xfhu_whot_export', $feed_id, 'for_update_option'); 
		$xfhu_settings_arr[$feed_id]['xfhu_desc'] = xfhu_optionGET('xfhu_desc', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_var_desc_priority'] = xfhu_optionGET('xfhu_var_desc_priority', $feed_id, 'for_update_option');		
		$xfhu_settings_arr[$feed_id]['xfhu_firmName'] = xfhu_optionGET('xfhu_firmName', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_firmId'] = xfhu_optionGET('xfhu_firmId', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_rate'] = xfhu_optionGET('xfhu_rate', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_stock_days_default'] = xfhu_optionGET('xfhu_stock_days_default', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_main_product'] = xfhu_optionGET('xfhu_main_product', $feed_id, 'for_update_option');
/* ! */	$xfhu_settings_arr[$feed_id]['xfhu_allow_group_id_arr'] = xfhu_optionGET('xfhu_allow_group_id_arr', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_clear_get'] = xfhu_optionGET('xfhu_clear_get', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_behavior_stip_symbol'] = xfhu_optionGET('xfhu_behavior_stip_symbol', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_no_default_png_products'] = xfhu_optionGET('xfhu_no_default_png_products', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_skip_products_without_pic'] = xfhu_optionGET('xfhu_skip_products_without_pic', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_oldprice'] = xfhu_optionGET('xfhu_oldprice', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_skip_backorders_products'] = xfhu_optionGET('xfhu_skip_backorders_products', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_skip_missing_products'] = xfhu_optionGET('xfhu_skip_missing_products', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_vendor'] = xfhu_optionGET('xfhu_vendor', $feed_id, 'for_update_option');		
		$xfhu_settings_arr[$feed_id]['xfhu_code'] = xfhu_optionGET('xfhu_code', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_code_post_meta'] = xfhu_optionGET('xfhu_code_post_meta', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_guarantee'] = xfhu_optionGET('xfhu_guarantee', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_guarantee_type'] = xfhu_optionGET('xfhu_guarantee_type', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_guarantee_value'] = xfhu_optionGET('xfhu_guarantee_value', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_guarantee_post_meta'] = xfhu_optionGET('xfhu_guarantee_post_meta', $feed_id, 'for_update_option');								
		$xfhu_settings_arr[$feed_id]['xfhu_barcode'] = xfhu_optionGET('xfhu_barcode', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_manufacture'] = xfhu_optionGET('xfhu_manufacture', $feed_id, 'for_update_option');
/* ! */	$xfhu_settings_arr[$feed_id]['xfhu_params_arr'] = xfhu_optionGET('xfhu_params_arr', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_use_delivery'] = xfhu_optionGET('xfhu_use_delivery', $feed_id, 'for_update_option');
		$xfhu_settings_arr[$feed_id]['xfhu_delivery_number'] = xfhu_optionGET('xfhu_delivery_number', $feed_id, 'for_update_option');	

		$feed_id++;  
		$xfhu_registered_feeds_arr = array(
			0 => array('last_id' => $i),
			1 => array('id' => $i)
		);
	}

	if (is_multisite()) {
		update_blog_option(get_current_blog_id(), 'xfhu_settings_arr', $xfhu_settings_arr);
		update_blog_option(get_current_blog_id(), 'xfhu_registered_feeds_arr', $xfhu_registered_feeds_arr);
	} else {
		update_option('xfhu_settings_arr', $xfhu_settings_arr);
		update_option('xfhu_registered_feeds_arr', $xfhu_registered_feeds_arr);
	}
	$feed_id = '1';  
	for ($i = 1; $i<$allNumFeed+1; $i++) {		
		xfhu_optionDEL('xfhu_skip_products_without_pic', $feed_id);
		xfhu_optionDEL('xfhu_status_cron',$feed_id);
		xfhu_optionDEL('xfhu_step_export', $feed_id);
		xfhu_optionDEL('xfhu_status_sborki', $feed_id);
		xfhu_optionDEL('xfhu_date_sborki', $feed_id);
		xfhu_optionDEL('xfhu_type_sborki', $feed_id);
		xfhu_optionDEL('xfhu_file_url', $feed_id);
		xfhu_optionDEL('xfhu_file_file', $feed_id);
		xfhu_optionDEL('xfhu_file_ids_in_xml', $feed_id);
		xfhu_optionDEL('xfhu_magazin_type', $feed_id);
		xfhu_optionDEL('xfhu_date_save_set', $feed_id);
		xfhu_optionDEL('xfhu_errors', $feed_id);
	
		xfhu_optionDEL('xfhu_run_cron', $feed_id);
		xfhu_optionDEL('xfhu_ufup', $feed_id);
		xfhu_optionDEL('xfhu_feed_assignment', $feed_id);
		xfhu_optionDEL('xfhu_whot_export', $feed_id); 
		xfhu_optionDEL('xfhu_desc', $feed_id);
		xfhu_optionDEL('xfhu_var_desc_priority', $feed_id);		
		xfhu_optionDEL('xfhu_firmName', $feed_id);
		xfhu_optionDEL('xfhu_firmId', $feed_id);
		xfhu_optionDEL('xfhu_rate', $feed_id);
		xfhu_optionDEL('xfhu_stock_days_default', $feed_id);
		xfhu_optionDEL('xfhu_main_product', $feed_id);
		xfhu_optionDEL('xfhu_allow_group_id_arr', $feed_id);
		xfhu_optionDEL('xfhu_clear_get', $feed_id);
		xfhu_optionDEL('xfhu_behavior_stip_symbol', $feed_id);
		xfhu_optionDEL('xfhu_no_default_png_products', $feed_id);
		xfhu_optionDEL('xfhu_skip_products_without_pic', $feed_id);
		xfhu_optionDEL('xfhu_oldprice', $feed_id);
		xfhu_optionDEL('xfhu_skip_backorders_products', $feed_id);
		xfhu_optionDEL('xfhu_skip_missing_products', $feed_id);
		xfhu_optionDEL('xfhu_vendor', $feed_id);		
		xfhu_optionDEL('xfhu_code', $feed_id);
		xfhu_optionDEL('xfhu_code_post_meta', $feed_id);
		xfhu_optionDEL('xfhu_guarantee', $feed_id);
		xfhu_optionDEL('xfhu_guarantee_type', $feed_id);
		xfhu_optionDEL('xfhu_guarantee_value', $feed_id);
		xfhu_optionDEL('xfhu_guarantee_post_meta', $feed_id);								
		xfhu_optionDEL('xfhu_barcode', $feed_id);
		xfhu_optionDEL('xfhu_manufacture', $feed_id);
		xfhu_optionDEL('xfhu_params_arr', $feed_id);
		xfhu_optionDEL('xfhu_use_delivery', $feed_id);
		xfhu_optionDEL('xfhu_delivery_number', $feed_id);

		$feed_id++;
	}

	// перезапустим крон-задачи
	for ($i = 1; $i < xfhu_number_all_feeds(); $i++) {
		$feed_id = (string)$i;
		$status_sborki = (int)xfhu_optionGET('xfhu_status_sborki', $feed_id);
		$xfhu_status_cron = xfhu_optionGET('xfhu_status_cron', $feed_id, 'set_arr');
		if ($xfhu_status_cron === 'off') {continue;}
		$recurrence = $xfhu_status_cron;
		wp_clear_scheduled_hook('xfhu_cron_period', array($feed_id));
		wp_schedule_event(time(), $recurrence, 'xfhu_cron_period', array($feed_id));
		xfhu_error_log('FEED № '.$feed_id.'; xfhu_cron_period внесен в список заданий; Файл: export.php; Строка: '.__LINE__, 0);
	}
}
/*
* @since 1.3.0
*
* @return array
* Возвращает массив настроек фида по умолчанию
*/
function xfhu_set_default_feed_settings_arr($whot = 'feed') {
	if ($whot === 'feed') {
		$blog_title = get_bloginfo('name');
		$blog_title = substr($blog_title, 0, 20);
		$result_arr = array(
			'xfhu_status_cronc' => 'off',
			'xfhu_step_exportc' => '500',
//			'xfhu_status_sborkic' => '-1', // статус сборки файла
			'xfhu_date_sborkic' => 'unknown', 
			'xfhu_type_sborkic' => 'xml',
			'xfhu_file_urlc' => '', 
			'xfhu_file_filec' => '', 
			'xfhu_file_ids_in_xmlc' => '',		
			'xfhu_magazin_typec' => 'woocommerce', 
			'xfhu_date_save_setc' => 'unknown', 
			'xfhu_errorsc' => '',
		
			'xfhu_run_cronc' => 'off',
			'xfhu_ufupc' => '0',
			'xfhu_feed_assignmentc' => '',
			'xfhu_whot_exportc' => 'all', 
			'xfhu_descc' => 'fullexcerpt',
			'xfhu_var_desc_priorityc' => '',

			'xfhu_firmName', $xfhu_firmName,
			'xfhu_firmIdc' => '',
			'xfhu_ratec' => '',
			'xfhu_stock_days_defaultc' => '',
			'xfhu_main_productc' => '', 
			'xfhu_allow_group_id_arr', serialize(array()),
			'xfhu_clear_getc' => 'no',
			'xfhu_behavior_stip_symbolc' => 'default',
			'xfhu_no_default_png_productsc' => '0',
			'xfhu_skip_products_without_picc' => '0',
			'xfhu_oldpricec' => 'no',
			'xfhu_skip_backorders_productsc' => '',
			'xfhu_skip_missing_productsc' => '',
			'xfhu_vendorc' => 'disabled',		
			'xfhu_codec' => 'disabled',
			'xfhu_code_post_metac' => '',
			'xfhu_guaranteec' => 'disabled',
			'xfhu_guarantee_typec' => 'manufacturer',
			'xfhu_guarantee_valuec' => '',
			'xfhu_guarantee_post_metac' => '',		
			'xfhu_barcodec' => 'disabled',
			'xfhu_manufacturec' => 'disabled',
//			'xfhu_params_arr', serialize(array()),
			'xfhu_use_deliveryc' => '',
			'xfhu_delivery_numberc' => '1',

		);
		do_action('xfhu_set_default_feed_settings_result_arr_action', $result_arr, $whot); 
		$result_arr = apply_filters('xfhu_set_default_feed_settings_result_arr_filter', $result_arr, $whot); 
		return $result_arr;
	} 
}