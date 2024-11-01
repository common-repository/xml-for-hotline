<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once ABSPATH . 'wp-admin/includes/plugin.php'; // без этого не будет работать вне адмники is_plugin_active
function xfhu_feed_header( $feed_id = '1' ) {
	xfhu_error_log( 'FEED № ' . $feed_id . '; Стартовала xfhu_feed_header; Файл: offer.php; Строка: ' . __LINE__, 0 );

	$result_xml = '';
	$result_xml .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
	$result_xml .= '<price>' . PHP_EOL;

	$unixtime = current_time( 'Y-m-d H:i' ); // время в unix формате 
	xfhu_optionUPD( 'xfhu_date_sborki', $unixtime, $feed_id, 'yes', 'set_arr' );
	$result_xml .= '<date>' . $unixtime . '</date>' . PHP_EOL;
	$xfhu_firmName = stripslashes( xfhu_optionGET( 'xfhu_firmName', $feed_id, 'set_arr' ) );
	$result_xml .= '<firmName>' . $xfhu_firmName . '</firmName>' . PHP_EOL;
	$xfhu_firmId = xfhu_optionGET( 'xfhu_firmId', $feed_id, 'set_arr' );
	$result_xml .= '<firmId>' . $xfhu_firmId . '</firmId>' . PHP_EOL;
	$xfhu_rate = xfhu_optionGET( 'xfhu_rate', $feed_id, 'set_arr' );
	$result_xml .= '<rate>' . $xfhu_rate . '</rate>' . PHP_EOL;

	$xfhu_use_delivery = xfhu_optionGET( 'xfhu_use_delivery', $feed_id, 'set_arr' );
	if ( $xfhu_use_delivery === 'on' ) {
		$xfhu_delivery_number = xfhu_optionGET( 'xfhu_delivery_number', $feed_id, 'set_arr' );
		if ( $xfhu_delivery_number !== '' ) {
			$z = (int) $xfhu_delivery_number + 1;
			for ( $i = 1; $i < $z; $i++ ) {
				$xfhu_delivery_option_name = 'xfhu_delivery_arr' . $i;
				$xfhu_delivery_arr = xfhu_optionGET( $xfhu_delivery_option_name, $feed_id );
				if ( $xfhu_delivery_arr !== '' ) {
					$xfhu_delivery_arr = unserialize( $xfhu_delivery_arr );
					$delivery_id = $xfhu_delivery_arr[0];
					$delivery_type = $xfhu_delivery_arr[1]; /* обязтаельный */
					$delivery_cost = $xfhu_delivery_arr[2];
					$delivery_free_from_cost = $xfhu_delivery_arr[3];
					$delivery_time = $xfhu_delivery_arr[4]; /* обязтаельный */
					$delivery_incheckout = $xfhu_delivery_arr[5];
					$delivery_region = $xfhu_delivery_arr[6];
					$delivery_carrier = $xfhu_delivery_arr[7];


					xfhu_error_log( '$xfhu_delivery_arr', 0 );
					xfhu_error_log( $xfhu_delivery_arr, 0 );
				} else {
					continue;
				}
				if ( $delivery_type == '' || $delivery_time == '' ) {
					xfhu_error_log( 'FEED № ' . $feed_id . '; WARNING: Способ доставки пропущен! $delivery_id  = ' . $delivery_id . ', $delivery_type = ' . $delivery_type . ', $delivery_time = ' . $delivery_time . '; Файл: offer.php; Строка: ' . __LINE__, 0 );
				} else {
					$delivery_id_xml = ' id="' . $delivery_id . '"';
					$delivery_type_xml = ' type="' . $delivery_type . '"';
					if ( $delivery_carrier == '' ) {
						$delivery_carrier_xml = '';
					} else {
						$delivery_carrier_xml = ' carrier="' . $delivery_carrier . '"';
					}
					if ( $delivery_cost == '' ) {
						$delivery_cost_xml = '';
					} else {
						$delivery_cost_xml = ' cost="' . $delivery_cost . '"';
					}
					if ( $delivery_free_from_cost == '' ) {
						$delivery_free_from_cost_xml = '';
					} else {
						$delivery_free_from_cost_xml = ' freeFrom="' . $delivery_free_from_cost . '"';
					}
					$delivery_time_xml = ' time="' . $delivery_time . '"';
					if ( $delivery_incheckout == '' ) {
						$delivery_incheckout_xml = '';
					} else {
						$delivery_incheckout_xml = ' inCheckout="' . $delivery_incheckout . '"';
					}
					if ( $delivery_region == '' ) {
						$delivery_region_xml = '';
					} else {
						$delivery_region_xml = ' region="' . $delivery_region . '"';
					}
					$result_xml .= '<delivery' . $delivery_id_xml . $delivery_type_xml . $delivery_cost_xml . $delivery_free_from_cost_xml . $delivery_time_xml . $delivery_incheckout_xml . $delivery_region_xml . $delivery_carrier_xml . ' />' . PHP_EOL;
				}
			}
		}
	}

	/* общие параметры */
	$res = get_woocommerce_currency(); // получаем валюта магазина
	switch ( $res ) {
		case "USD":
			$currencyId_xml = "USD";
			break;
		default:
			$currencyId_xml = "UAH";
	}

	$args_terms_arr = array(
		'hide_empty' => 0,
		'orderby' => 'name',
		'taxonomy' => 'product_cat'
	);
	$args_terms_arr = apply_filters( 'xfhu_args_terms_arr_filter', $args_terms_arr, $feed_id );
	$terms = get_terms( $args_terms_arr );

	$count = count( $terms );
	$result_xml .= '<categories>' . PHP_EOL;
	if ( $count > 0 ) {
		$result_categories_xml = '';
		foreach ( $terms as $term ) {
			$result_categories_xml .= '<category>' . PHP_EOL;
			$result_categories_xml .= '<id>' . $term->term_id . '</id>' . PHP_EOL;
			if ( $term->parent !== 0 ) {
				$result_categories_xml .= '<parentId>' . $term->parent . '</parentId>' . PHP_EOL;
			}
			$result_categories_xml .= '<name>' . $term->name . '</name>' . PHP_EOL;
			$result_categories_xml = apply_filters( 'xfhu_append_category_xml_filter', $result_categories_xml, $terms, $feed_id );
			$result_categories_xml .= '</category>' . PHP_EOL;
		}
		$result_categories_xml = apply_filters( 'xfhu_result_categories_xml_filter', $result_categories_xml, $terms, $feed_id );
		$result_xml .= $result_categories_xml;
		unset( $result_categories_xml );
	}
	$result_xml = apply_filters( 'xfhu_append_categories_filter', $result_xml, $feed_id );
	$result_xml .= '</categories>' . PHP_EOL;

	/* end общие параметры */
	do_action( 'xfhu_before_items' );

	/* индивидуальные параметры товара */
	$result_xml .= '<items>' . PHP_EOL;
	return $result_xml;
}
function xfhu_unit( $post_id, $feed_id = '1' ) {
	xfhu_error_log( 'FEED № ' . $feed_id . '; Стартовала xfhu_unit. $post_id = ' . $post_id . '; Файл: offer.php; Строка: ' . __LINE__, 0 );
	$result_xml = '';
	$ids_in_xml = '';
	$skip_flag = false;

	$product = wc_get_product( $post_id );
	if ( $product == null ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к get_post вернула null; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}

	if ( $product->is_type( 'grouped' ) ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к сгруппированный; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}

	// что выгружать
	if ( $product->is_type( 'variable' ) ) {
		$xfhu_whot_export = xfhu_optionGET( 'xfhu_whot_export', $feed_id, 'set_arr' );
		if ( $xfhu_whot_export === 'simple' ) {
			xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к вариативный; Файл: offer.php; Строка: ' . __LINE__, 0 );
			return $result_xml;
		}
	}

	if ( get_post_meta( $post_id, 'xfhup_removefromxml', true ) === 'on' ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен принудительно; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}
	$skip_flag = apply_filters( 'xfhu_skip_flag', $skip_flag, $post_id, $product, $feed_id ); /* c версии 1.1.5 */
	if ( $skip_flag === true ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен по флагу; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}

	$vendor_xml = '';
	$vendor = xfhu_optionGET( 'xfhu_vendor', $feed_id, 'set_arr' );
	if ( class_exists( 'Perfect_Woocommerce_Brands' ) && $vendor === 'sfpwb' ) {
		$barnd_terms = get_the_terms( $product->get_id(), 'pwb-brand' );
		if ( $barnd_terms !== false ) {
			foreach ( $barnd_terms as $barnd_term ) {
				$vendor_xml = '<vendor>' . $barnd_term->name . '</vendor>' . PHP_EOL;
				break;
			}
		}
	} else if ( ( is_plugin_active( 'premmerce-woocommerce-brands/premmerce-brands.php' ) ) && ( $vendor === 'premmercebrandsplugin' ) ) {
		$barnd_terms = get_the_terms( $product->get_id(), 'product_brand' );
		if ( $barnd_terms !== false ) {
			foreach ( $barnd_terms as $barnd_term ) {
				$vendor_xml = '<vendor>' . $barnd_term->name . '</vendor>' . PHP_EOL;
				break;
			}
		}
	} else {
		if ( $vendor !== 'disabled' ) {
			$vendor = (int) $vendor;
			$vendor_res = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $vendor ) );
			if ( ! empty( $vendor_res ) ) {
				$vendor_xml = '<vendor>' . $vendor_res . '</vendor>' . PHP_EOL;
			}
		}
	}
	if ( $vendor_xml === '' && ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к у него нет vendor; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}

	/* общие данные для вариативных и обычных товаров */
	$res = get_woocommerce_currency(); // получаем валюта магазина
	switch ( $res ) {
		case "USD":
			$currencyId_xml = "USD";
			break;
		default:
			$currencyId_xml = "UAH";
	}
	$currencyId_xml = apply_filters( 'xfhu_currencyId_xml', $currencyId_xml, $feed_id );

	$result_xml_name = htmlspecialchars( $product->get_title(), ENT_NOQUOTES ); // название товара
	$result_xml_name = apply_filters( 'xfhu_change_name', $result_xml_name, $post_id, $product, $feed_id );

	// описание
	$xfhu_desc = xfhu_optionGET( 'xfhu_desc', $feed_id, 'set_arr' );
	$result_xml_desc = '';
	switch ( $xfhu_desc ) {
		case "full":
			$description_xml = $product->get_description();
			break;
		case "excerpt":
			$description_xml = $product->get_short_description();
			break;
		case "fullexcerpt":
			$description_xml = $product->get_description();
			if ( empty( $description_xml ) ) {
				$description_xml = $product->get_short_description();
			}
			break;
		case "excerptfull":
			$description_xml = $product->get_short_description();
			if ( empty( $description_xml ) ) {
				$description_xml = $product->get_description();
			}
			break;
		case "fullplusexcerpt":
			$description_xml = $product->get_description() . '<br/>' . $product->get_short_description();
			break;
		case "excerptplusfull":
			$description_xml = $product->get_short_description() . '<br/>' . $product->get_description();
			break;
		default:
			$description_xml = $product->get_description();
	}
	$result_xml_desc = '';
	$description_xml = apply_filters( 'xfhu_description_xml_filter', $description_xml, $post_id, $product, $feed_id ); /* с версии 1.1.6 */
	if ( ! empty( $description_xml ) ) {
		$enable_tags = '<p>,<h3>,<ul>,<li>,<br/>,<br>';

		$enable_tags = apply_filters( 'xfhu_enable_tags_filter', $enable_tags, $feed_id );
		$description_xml = strip_tags( $description_xml, $enable_tags );
		$description_xml = str_replace( '<br>', '<br/>', $description_xml );
		$description_xml = strip_shortcodes( $description_xml );
		$description_xml = apply_filters( 'xfhu_description_filter', $description_xml, $post_id, $product, $feed_id );

		$description_xml = trim( $description_xml );
		if ( $description_xml !== '' ) {
			$result_xml_desc = '<description><![CDATA[' . $description_xml . ']]></description>' . PHP_EOL;
		}
	}

	// echo "Категории ".$product->get_categories();
	$result_xml_cat = '';
	$catpostid = '';
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$catWPSEO = new WPSEO_Primary_Term( 'product_cat', $post_id );
		$catidWPSEO = $catWPSEO->get_primary_term();
		if ( $catidWPSEO !== false ) {
			$CurCategoryId = $catidWPSEO;
			$result_xml_cat .= '<categoryId>' . $catidWPSEO . '</categoryId>' . PHP_EOL;
		} else {
			$termini = get_the_terms( $post_id, 'product_cat' );
			if ( $termini !== false ) {
				foreach ( $termini as $termin ) {
					$catpostid = $termin->term_taxonomy_id;
					$result_xml_cat .= '<categoryId>' . $termin->term_taxonomy_id . '</categoryId>' . PHP_EOL;
					$CurCategoryId = $termin->term_taxonomy_id; // запоминаем id категории для товара
					break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
				}
			} else { // если база битая. фиксим id категорий
				xfhu_error_log( 'FEED № ' . $feed_id . '; Warning: Для товара $post_id = ' . $post_id . ' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: ' . __LINE__, 0 );
				$product_cats = wp_get_post_terms( $post_id, 'product_cat', array( "fields" => "ids" ) );
				// Раскомментировать строку ниже для автопочинки категорий в БД (место 1 из 2)
				// wp_set_object_terms($post_id, $product_cats, 'product_cat');
				if ( is_array( $product_cats ) && count( $product_cats ) ) {
					$catpostid = $product_cats[0];
					$result_xml_cat .= '<categoryId>' . $catpostid . '</categoryId>' . PHP_EOL;
					$CurCategoryId = $product_cats[0]; // запоминаем id категории для товара
					xfhu_error_log( 'FEED № ' . $feed_id . '; Warning: Для товара $post_id = ' . $post_id . ' база наверняка битая. wp_get_post_terms вернула массив. $catpostid = ' . $catpostid . '; Файл: offer.php; Строка: ' . __LINE__, 0 );
				}
			}
		}
	} else {
		$termini = get_the_terms( $post_id, 'product_cat' );
		if ( $termini !== false ) {
			foreach ( $termini as $termin ) {
				$catpostid = $termin->term_taxonomy_id;
				$result_xml_cat .= '<categoryId>' . $termin->term_taxonomy_id . '</categoryId>' . PHP_EOL;
				$CurCategoryId = $termin->term_taxonomy_id; // запоминаем id категории для товара
				break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
			}
		} else { // если база битая. фиксим id категорий
			xfhu_error_log( 'FEED № ' . $feed_id . '; Warning: Для товара $post_id = ' . $post_id . ' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: ' . __LINE__, 0 );
			$product_cats = wp_get_post_terms( $post_id, 'product_cat', array( "fields" => "ids" ) );
			// Раскомментировать строку ниже для автопочинки категорий в БД (место 2 из 2)	 
			// wp_set_object_terms($post_id, $product_cats, 'product_cat');	 
			if ( is_array( $product_cats ) && count( $product_cats ) ) {
				$catpostid = $product_cats[0];
				$result_xml_cat .= '<categoryId>' . $catpostid . '</categoryId>' . PHP_EOL;
				$CurCategoryId = $product_cats[0]; // запоминаем id категории для товара
				xfhu_error_log( 'FEED № ' . $feed_id . '; Warning: Для товара $post_id = ' . $post_id . ' база наверняка битая. wp_get_post_terms вернула массив. $catpostid = ' . $catpostid . '; Файл: offer.php; Строка: ' . __LINE__, 0 );
			}
		}
	}
	$result_xml_cat = apply_filters( 'xfhu_after_cat_filter', $result_xml_cat, $post_id, $feed_id );
	if ( $result_xml_cat == '' ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к нет категорий; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}
	/* $termin->ID - понятное дело, ID элемента
	 * $termin->slug - ярлык элемента
	 * $termin->term_group - значение term group
	 * $termin->term_taxonomy_id - ID самой таксономии
	 * $termin->taxonomy - название таксономии
	 * $termin->description - описание элемента
	 * $termin->parent - ID родительского элемента
	 * $termin->count - количество содержащихся в нем постов
	 */
	/* end общие данные для вариативных и обычных товаров */

	if ( get_post_meta( $post_id, '_xfhu_condition', true ) !== '' ) {
		$condition = get_post_meta( $post_id, '_xfhu_condition', true );
		if ( $condition === 'disabled' ) {
			$xfhu_condition_xml = '';
		} else {
			$xfhu_condition_xml = '<condition>' . $condition . '</condition>' . PHP_EOL;
		}
	} else {
		$xfhu_condition_xml = '';
	}

	if ( get_post_meta( $post_id, '_xfhu_custom', true ) !== '' ) {
		$custom = get_post_meta( $post_id, '_xfhu_custom', true );
		if ( $custom == '' ) {
			$xfhu_custom_xml = '';
		} else {
			$xfhu_custom_xml = '<custom>' . $custom . '</custom>' . PHP_EOL;
		}
	} else {
		$xfhu_custom_xml = '';
	}

	/* Вариации */
	// если вариация - нам нет смысла выгружать общее предложение
	if ( $product->is_type( 'variable' ) ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; У нас вариативный товар. Файл: offer.php; Строка: ' . __LINE__, 0 );
		$xfhu_var_desc_priority = xfhu_optionGET( 'xfhu_var_desc_priority', $feed_id, 'set_arr' );

		$variations = array();
		if ( $product->is_type( 'variable' ) ) {
			$variations = $product->get_available_variations();
			$variation_count = count( $variations );
		}
		for ( $i = 0; $i < $variation_count; $i++ ) {

			$offer_id = ( ( $product->is_type( 'variable' ) ) ? $variations[ $i ]['variation_id'] : $product->get_id() );
			$offer = new WC_Product_Variation( $offer_id ); // получим вариацию
			/*
			 * $offer->get_price() - актуальная цена (равна sale_price или regular_price если sale_price пуст)
			 * $offer->get_regular_price() - обычная цена
			 * $offer->get_sale_price() - цена скидки
			 */

			$price_xml = $offer->get_price(); // цена вариации
			$price_xml = apply_filters( 'xfhu_variable_price_filter', $price_xml, $product, $offer, $offer_id, $feed_id );
			// если цены нет - пропускаем вариацию
			if ( $price_xml == 0 || empty( $price_xml ) ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Вариация товара с postId = ' . $post_id . ' пропущена т.к нет цены; Файл: offer.php; Строка: ' . __LINE__, 0 );
				continue;
			}

			if ( class_exists( 'XmlforHotlinePro' ) ) {
				if ( ( xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' ) !== false ) && ( xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' ) !== '' ) ) {
					$xfhup_compare_value = xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' );
					$xfhup_compare = xfhu_optionGET( 'xfhup_compare', $feed_id, 'set_arr' );
					if ( $xfhup_compare == '>=' ) {
						if ( $price_xml < $xfhup_compare_value ) {
							continue;
						}
					} else {
						if ( $price_xml >= $xfhup_compare_value ) {
							continue;
						}
					}
				}
			}

			// пропуск вариаций, которых нет в наличии
			$xfhu_skip_missing_products = xfhu_optionGET( 'xfhu_skip_missing_products', $feed_id, 'set_arr' );
			if ( $xfhu_skip_missing_products == 'on' ) {
				if ( $offer->is_in_stock() == false ) {
					xfhu_error_log( 'FEED № ' . $feed_id . '; Вариация товара с postId = ' . $post_id . ' пропущена т.к ее нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
					continue;
				}
			}

			// пропускаем вариации на предзаказ
			$skip_backorders_products = xfhu_optionGET( 'xfhu_skip_backorders_products', $feed_id, 'set_arr' );
			if ( $skip_backorders_products == 'on' ) {
				if ( $offer->get_manage_stock() == true ) { // включено управление запасом
					if ( ( $offer->get_stock_quantity() < 1 ) && ( $offer->get_backorders() !== 'no' ) ) {
						xfhu_error_log( 'FEED № ' . $feed_id . '; Вариация товара с postId = ' . $post_id . ' пропущена т.к запрещен предзаказ и включено управление запасом; Файл: offer.php; Строка: ' . __LINE__, 0 );
						continue;
					}
				}
			}

			$thumb_xml = get_the_post_thumbnail_url( $offer->get_id(), 'full' );
			if ( empty( $thumb_xml ) ) {
				// убираем default.png из фида
				$no_default_png_products = xfhu_optionGET( 'xfhu_no_default_png_products', $feed_id, 'set_arr' );
				if ( ( $no_default_png_products === 'on' ) && ( ! has_post_thumbnail( $post_id ) ) ) {
					$picture_xml = '';
				} else {
					$thumb_id = get_post_thumbnail_id( $post_id );
					$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
					$thumb_xml = $thumb_url[0]; /* урл оригинал миниатюры товара */
					$picture_xml = '<image>' . xfhu_deleteGET( $thumb_xml ) . '</image>' . PHP_EOL;
				}
			} else {
				$picture_xml = '<image>' . xfhu_deleteGET( $thumb_xml ) . '</image>' . PHP_EOL;
			}
			$picture_xml = apply_filters( 'xfhu_pic_variable_offer_filter', $picture_xml, $product, $feed_id, $offer );

			// пропускаем вариации без картинок
			$xfhu_skip_products_without_pic = xfhu_optionGET( 'xfhu_skip_products_without_pic', $feed_id, 'set_arr' );
			if ( ( $xfhu_skip_products_without_pic === 'on' ) && ( $picture_xml == '' ) ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Вариация товара с postId = ' . $post_id . ' пропущена т.к нет картинки даже в галерее; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml; /*continue;*/
			}

			$skip_flag = apply_filters( 'xfhu_skip_flag_variable', $skip_flag, $post_id, $product, $offer, $feed_id ); /* c версии 1.1.5 */
			if ( $skip_flag === true ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Вариативный товар с postId = ' . $post_id . ', offer_id = ' . $offer_id . ' пропущен по флагу; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml;
			}
			if ( $skip_flag === 'continue' ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Вариация товара с с postId = ' . $post_id . ', offer_id = ' . $offer_id . ' пропущена по флагу; Файл: offer.php; Строка: ' . __LINE__, 0 );
				$skip_flag = false;
				continue;
			}

			if ( $vendor === 'disabled' || $vendor === 'sfpwb' || $vendor === 'premmercebrandsplugin' ) {
			} else {
				$vendor = (int) $vendor;
				$vendor_res_variable = $offer->get_attribute( wc_attribute_taxonomy_name_by_id( $vendor ) );
				if ( ! empty( $vendor_res_variable ) ) {
					$vendor_xml = '<vendor>' . $vendor_res_variable . '</vendor>' . PHP_EOL;
				}
			}

			if ( $vendor_xml === '' ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Вариативный товар с postId = ' . $post_id . ' пропущен т.к у него нет vendor; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml;
			}

			if ( $offer->get_manage_stock() === true ) { // включено управление запасом
				if ( $offer->get_stock_quantity() > 0 ) {
					$available = 'В наличии';
				} else {
					if ( $offer->get_backorders() === 'no' ) { // предзаказ запрещен
						xfhu_error_log( 'FEED № ' . $feed_id . '; Вариативный товар с postId = ' . $post_id . '. Вариация с id = ' . $offer->get_id() . ' пропущена т.к Hotline не разрешает размешать товары у себя, если их нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
						continue;
					} else {
						$available = 'Под заказ';
					}
				}
			} else { // отключено управление запасом
				if ( $offer->get_stock_status() === 'instock' ) {
					$available = 'В наличии';
				} else if ( $offer->get_stock_status() === 'outofstock' ) {
					xfhu_error_log( 'FEED № ' . $feed_id . '; Вариативный товар с postId = ' . $post_id . '. Вариация с id = ' . $offer->get_id() . ' пропущена т.к Hotline не разрешает размешать товары у себя, если их нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
					continue;
				} else { // onbackorder - предзаказ разрешен
					$available = 'Под заказ';
				}
			}

			$result_xml .= '<item>' . PHP_EOL;
			// массив категорий для которых разрешен group_id
			$xfhu_allow_group_id_arr = unserialize( xfhu_optionGET( 'xfhu_allow_group_id_arr', $feed_id ) );
			if ( empty( $xfhu_allow_group_id_arr ) ) {
				$result_xml .= '<id>' . $offer->get_id() . '</id>' . PHP_EOL;
			} else {
				// массив с group_id заполнен
				$CurCategoryId = (string) $CurCategoryId;
				// если id текущей категории совпал со списком категорий с group_id			  
				if ( in_array( $CurCategoryId, $xfhu_allow_group_id_arr ) ) {
					$result_xml .= '<id>' . $offer->get_id() . '</id>' . PHP_EOL;
					$result_xml .= '<group_id>' . $product->get_id() . '</group_id>' . PHP_EOL;
				} else {
					$result_xml .= '<id>' . $offer->get_id() . '</id>' . PHP_EOL;
				}
			}
			$result_xml .= $result_xml_cat;
			$xfhu_stock_days = xfhu_optionGET( 'xfhu_stock_days_default', $feed_id, 'set_arr' );
			if ( $xfhu_stock_days !== '' && $available === 'Под заказ' ) {
				$result_xml .= '<stock days="' . $xfhu_stock_days . '">' . $available . '</stock>' . PHP_EOL;
			} else {
				$result_xml .= '<stock>' . $available . '</stock>' . PHP_EOL;
			}

			$xfhu_pickup_options_days = xfhu_optionGET( 'xfhu_pickup_options_days_default', $feed_id, 'set_arr' );
			if ( $xfhu_pickup_options_days !== '' ) {
				$result_xml .= '<shipping>' . $xfhu_pickup_options_days . '</shipping>' . PHP_EOL;
				/*
				$result_xml .= '<pickup-options>'.PHP_EOL;
				$result_xml .= '<option days="'.$xfhu_pickup_options_days.'"/>'.PHP_EOL;
				$result_xml .= '</pickup-options>'.PHP_EOL;
				*/
			} else {
				// $result_xml .= '';
			}

			// code	 
			$xfhu_code = xfhu_optionGET( 'xfhu_code', $feed_id, 'set_arr' );
			switch ( $xfhu_code ) { /* disabled, sku, или id */
				case "disabled":
					// выгружать штрихкод нет нужды
					break;
				case "post_meta":
					$xfhu_code_post_meta_id = xfhu_optionGET( 'xfhu_code_post_meta', $feed_id, 'set_arr' );
					$xfhu_code_post_meta_id = trim( $xfhu_code_post_meta_id );
					if ( get_post_meta( $post_id, $xfhu_code_post_meta_id, true ) !== '' ) {
						$xfhu_code_xml = get_post_meta( $post_id, $xfhu_code_post_meta_id, true );
						$xfhu_code_xml = xfhu_replace_symbol( $xfhu_code_xml, $feed_id );
						$result_xml .= "<code>" . $xfhu_code_xml . "</code>" . PHP_EOL;
					}
					break;
				case "sku":
					// выгружать из артикула
					$sku_xml = $offer->get_sku(); // артикул
					if ( ! empty( $sku_xml ) ) {
						$sku_xml = xfhu_replace_symbol( $sku_xml, $feed_id );
						$result_xml .= "<code>" . $sku_xml . "</code>" . PHP_EOL;
					} else {
						// своего артикула у вариации нет. Пробуем подставить общий sku
						$sku_xml = $product->get_sku();
						if ( ! empty( $sku_xml ) ) {
							$sku_xml = xfhu_replace_symbol( $sku_xml, $feed_id );
							$result_xml .= "<code>" . $sku_xml . "</code>" . PHP_EOL;
						}
					}
					break;
				default:
					$xfhu_code = (int) $xfhu_code;
					$xfhu_code_xml = $offer->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_code ) );
					if ( ! empty( $xfhu_code_xml ) ) {
						$xfhu_code_xml = xfhu_replace_symbol( $xfhu_code_xml, $feed_id );
						$result_xml .= '<code>' . urldecode( $xfhu_code_xml ) . '</code>' . PHP_EOL;
					} else {
						$xfhu_code_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_code ) );
						if ( ! empty( $xfhu_code_xml ) ) {
							$xfhu_code_xml = xfhu_replace_symbol( $xfhu_code_xml, $feed_id );
							$result_xml .= '<code>' . urldecode( $xfhu_code_xml ) . '</code>' . PHP_EOL;
						}
					}
			}

			// guarantee
			$xfhu_guarantee = xfhu_optionGET( 'xfhu_guarantee', $feed_id, 'set_arr' );
			if ( $xfhu_guarantee === 'enabled' ) {
				$xfhu_guarantee_type = xfhu_optionGET( 'xfhu_guarantee_type', $feed_id, 'set_arr' );
				$xfhu_guarantee_value = xfhu_optionGET( 'xfhu_guarantee_value', $feed_id, 'set_arr' );
				switch ( $xfhu_guarantee_value ) {
					case "post_meta":
						$xfhu_guarantee_post_meta_id = xfhu_optionGET( 'xfhu_guarantee_post_meta', $feed_id, 'set_arr' );
						$xfhu_guarantee_post_meta_id = trim( $xfhu_guarantee_post_meta_id );
						if ( get_post_meta( $post_id, $xfhu_guarantee_post_meta_id, true ) !== '' ) {
							$xfhu_guarantee_value_xml = get_post_meta( $post_id, $xfhu_guarantee_post_meta_id, true );
							$result_xml .= '<guarantee type="' . $xfhu_guarantee_type . '">' . $xfhu_guarantee_value_xml . '</guarantee>' . PHP_EOL;
						}
						break;
					default:
						$xfhu_guarantee_value = (int) $xfhu_guarantee_value;
						$xfhu_guarantee_value_xml = $offer->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_guarantee_value ) );
						if ( ! empty( $xfhu_guarantee_value_xml ) ) {
							$result_xml .= '<guarantee type="' . $xfhu_guarantee_type . '">' . urldecode( $xfhu_guarantee_value_xml ) . '</guarantee>' . PHP_EOL;
						} else {
							$xfhu_guarantee_value_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_guarantee_value ) );
							if ( ! empty( $xfhu_guarantee_value_xml ) ) {
								$result_xml .= '<guarantee type="' . $xfhu_guarantee_type . '">' . urldecode( $xfhu_guarantee_value_xml ) . '</guarantee>' . PHP_EOL;
							}
						}
				}
			}

			// штрихкод
			$xfhu_barcode = xfhu_optionGET( 'xfhu_barcode', $feed_id, 'set_arr' );
			switch ( $xfhu_barcode ) { /* disabled, sku, или id */
				case "disabled":
					// выгружать штрихкод нет нужды
					break;
				case "sku":
					// выгружать из артикула
					$sku_xml = $offer->get_sku(); // артикул
					if ( ! empty( $sku_xml ) ) {
						$result_xml .= '<barcode>' . $sku_xml . '</barcode>' . PHP_EOL;
					} else {
						// своего артикула у вариации нет. Пробуем подставить общий sku
						$sku_xml = $product->get_sku();
						if ( ! empty( $sku_xml ) ) {
							$result_xml .= '<barcode>' . $sku_xml . '</barcode>' . PHP_EOL;
						}
					}
					break;
				default:
					$xfhu_barcode = (int) $xfhu_barcode;
					$xfhu_barcode_xml = $offer->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_barcode ) );
					if ( ! empty( $xfhu_barcode_xml ) ) {
						$result_xml .= '<barcode>' . urldecode( $xfhu_barcode_xml ) . '</barcode>' . PHP_EOL;
					} else {
						$xfhu_barcode_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_barcode ) );
						if ( ! empty( $xfhu_barcode_xml ) ) {
							$result_xml .= '<barcode>' . urldecode( $xfhu_barcode_xml ) . '</barcode>' . PHP_EOL;
						}
					}
			}

			$result_xml .= $vendor_xml;
			$result_xml_name = apply_filters( 'xfhu_change_name_variable', $result_xml_name, $post_id, $product, $offer, $feed_id );
			$result_xml .= "<name>" . htmlspecialchars( $result_xml_name, ENT_NOQUOTES ) . "</name>" . PHP_EOL;

			// Описание.		
			if ( $xfhu_var_desc_priority === 'on' || empty( $description_xml ) ) {
				switch ( $xfhu_desc ) {
					case "excerptplusfull":
						$description_xml = $product->get_short_description() . '<br/>' . $offer->get_description();
						break;
					case "fullplusexcerpt":
						$description_xml = $offer->get_description() . '<br/>' . $product->get_short_description();
						break;
					default:
						$description_xml = $offer->get_description();
				}
			}
			if ( ! empty( $description_xml ) ) {
				$enable_tags = '<p>,<h3>,<ul>,<li>,<br/>,<br>';
				$enable_tags = apply_filters( 'xfhu_enable_tags_filter', $enable_tags, $feed_id );
				$description_xml = strip_tags( $description_xml, $enable_tags );
				$description_xml = str_replace( '<br>', '<br/>', $description_xml );
				$description_xml = strip_shortcodes( $description_xml );
				$description_xml = apply_filters( 'xfhu_description_filter', $description_xml, $post_id, $product, $offer, $feed_id );
				$description_xml = trim( $description_xml );
				if ( $description_xml !== '' ) {
					$result_xml .= '<description><![CDATA[' . $description_xml . ']]></description>' . PHP_EOL;
				}
			} else {
				// если у вариации нет своего описания - пробуем подставить общее
				if ( ! empty( $result_xml_desc ) ) {
					$result_xml .= $result_xml_desc;
				}
			}

			$result_xml .= $picture_xml;

			$result_url = htmlspecialchars( get_permalink( $offer->get_id() ) ); // урл товара
			$xfhu_clear_get = xfhu_optionGET( 'xfhu_clear_get', $feed_id, 'set_arr' );
			if ( $xfhu_clear_get === 'yes' ) {
				$result_url = xfhu_deleteGET( $result_url, 'url' );
			}
			$result_url = apply_filters( 'xfhu_url_filter', $result_url, $product, $CurCategoryId, $feed_id );
			$result_url = apply_filters( 'xfhu_variable_url_filter', $result_url, $product, $offer, $CurCategoryId, $feed_id );
			$result_xml .= "<url>" . $result_url . "</url>" . PHP_EOL;

			$price_xml = $offer->get_price();
			if ( $currencyId_xml === 'UAH' ) {
				$result_xml .= '<priceRUAH>' . $price_xml . '</priceRUAH>' . PHP_EOL;
			} else {
				$result_xml .= '<priceRUSD>' . $price_xml . '</priceRUSD>' . PHP_EOL;
			}
			// старая цена
			$xfhu_oldprice = xfhu_optionGET( 'xfhu_oldprice', $feed_id, 'set_arr' );
			if ( $xfhu_oldprice === 'yes' ) {
				$sale_price = (float) $offer->get_sale_price();
				$price_xml = (float) $price_xml;
				if ( $sale_price > 0 ) {
					if ( $price_xml === $sale_price ) {
						$oldprice_xml = $offer->get_regular_price();
						$oldprice_name_tag = 'oldprice';
						$oldprice_name_tag = apply_filters( 'xfhu_oldprice_name_tag_filter', $oldprice_name_tag, $feed_id );
						$result_xml .= "<" . $oldprice_name_tag . ">" . $oldprice_xml . "</" . $oldprice_name_tag . ">" . PHP_EOL;
					}
				}
			}

			$result_xml .= $xfhu_condition_xml;
			$result_xml .= $xfhu_custom_xml;

			// Param в вариациях
			$params_arr = unserialize( xfhu_optionGET( 'xfhu_params_arr', $feed_id ) );
			if ( ! empty( $params_arr ) ) {
				$attributes = $product->get_attributes(); // получили все атрибуты товара
				foreach ( $attributes as $param ) {
					if ( $param->get_variation() == false ) {
						// это обычный атрибут
						$param_val = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $param->get_id() ) );
					} else {
						$param_val = $offer->get_attribute( wc_attribute_taxonomy_name_by_id( $param->get_id() ) );
					}
					// если этот параметр не нужно выгружать - пропускаем
					$variation_id_string = (string) $param->get_id(); // важно, т.к. в настройках id как строки
					if ( ! in_array( $variation_id_string, $params_arr, true ) ) {
						continue;
					}
					$param_name = wc_attribute_label( wc_attribute_taxonomy_name_by_id( $param->get_id() ) );
					// если пустое имя атрибута или значение - пропускаем
					if ( empty( $param_name ) || empty( $param_val ) ) {
						continue;
					}
					$result_xml .= '<param name="' . $param_name . '">' . ucfirst( urldecode( $param_val ) ) . '</param>' . PHP_EOL;
					// $param_at_name .= ucfirst(urldecode($param_val)).' ';
				}
			}
			$result_xml = apply_filters( 'xfhu_append_item_variable', $result_xml, $post_id, $product, $offer, $feed_id );

			$result_xml .= '</item>' . PHP_EOL;

			do_action( 'xfhu_after_variable_offer' );

			$ids_in_xml .= $post_id . ';' . $offer_id . ';' . $price_xml . ';' . $CurCategoryId . PHP_EOL;

			$stop_flag = false;
			$stop_flag = apply_filters( 'xfhu_after_variable_offer_stop_flag', $stop_flag, $i, $variation_count, $offer_id, $offer, $feed_id );
			if ( $stop_flag == true ) {
				break;
			}
		} // end for ($i = 0; $i<$variation_count; $i++) 
		xfhu_error_log( 'FEED № ' . $feed_id . '; Все вариации выгрузили. ' . $ids_in_xml . ' Файл: offer.php; Строка: ' . __LINE__, 0 );

		return array( $result_xml, $ids_in_xml ); // все вариации выгрузили	
	} // end if ($product->is_type('variable'))	 
	/* end Вариации */

	/* Обычный товар */
	// если цена не указана - пропускаем товар
	$price_xml = $product->get_price();
	$price_xml = apply_filters( 'xfhu_simple_price_filter', $price_xml, $product, $feed_id ); /* с версии 1.1.12 */
	if ( $price_xml == 0 || empty( $price_xml ) ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к нет цены; Файл: Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml;
	}
	if ( class_exists( 'XmlforHotlinePro' ) ) {
		if ( ( xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' ) !== false ) && ( xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' ) !== '' ) ) {
			$xfhup_compare_value = xfhu_optionGET( 'xfhup_compare_value', $feed_id, 'set_arr' );
			$xfhup_compare = xfhu_optionGET( 'xfhup_compare', $feed_id, 'set_arr' );
			if ( $xfhup_compare == '>=' ) {
				if ( $price_xml < $xfhup_compare_value ) {
					return $result_xml;
				}
			} else {
				if ( $price_xml >= $xfhup_compare_value ) {
					return $result_xml;
				}
			}
		}
	}

	// пропуск товаров, которых нет в наличии
	$xfhu_skip_missing_products = xfhu_optionGET( 'xfhu_skip_missing_products', $feed_id, 'set_arr' );
	if ( $xfhu_skip_missing_products == 'on' ) {
		if ( $product->is_in_stock() == false ) {
			xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
			return $result_xml;
		}
	}

	// пропускаем товары на предзаказ
	$skip_backorders_products = xfhu_optionGET( 'xfhu_skip_backorders_products', $feed_id, 'set_arr' );
	if ( $skip_backorders_products == 'on' ) {
		if ( $product->get_manage_stock() == true ) { // включено управление запасом  
			if ( ( $product->get_stock_quantity() < 1 ) && ( $product->get_backorders() !== 'no' ) ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к запрещен предзаказ и включено управление запасом; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml; /*continue;*/
			}
		} else {
			if ( $product->get_stock_status() !== 'instock' ) {
				xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к запрещен предзаказ; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml; /*continue;*/
			}
		}
	}

	if ( $product->get_manage_stock() === true ) { // включено управление запасом
		if ( $product->get_stock_quantity() > 0 ) {
			$available = 'В наличии';
		} else {
			if ( $product->get_backorders() === 'no' ) { // предзаказ запрещен
				xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к Hotline не разрешает размешать товары у себя, если их нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
				return $result_xml;
			} else {
				$available = 'Под заказ';
			}
		}
	} else { // отключено управление запасом
		if ( $product->get_stock_status() === 'instock' ) {
			$available = 'В наличии';
		} else if ( $product->get_stock_status() === 'outofstock' ) {
			xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к Hotline не разрешает размешать товары у себя, если их нет в наличии; Файл: offer.php; Строка: ' . __LINE__, 0 );
			return $result_xml;
		} else { // onbackorder - предзаказ разрешен
			$available = 'Под заказ';
		}
	}

	// убираем default.png из фида
	$no_default_png_products = xfhu_optionGET( 'xfhu_no_default_png_products', $feed_id, 'set_arr' );
	if ( ( $no_default_png_products === 'on' ) && ( ! has_post_thumbnail( $post_id ) ) ) {
		$picture_xml = '';
	} else {
		$thumb_id = get_post_thumbnail_id( $post_id );
		$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
		$thumb_xml = $thumb_url[0]; /* урл оригинал миниатюры товара */
		$picture_xml = '<image>' . xfhu_deleteGET( $thumb_xml ) . '</image>' . PHP_EOL;
	}
	$picture_xml = apply_filters( 'xfhu_pic_simple_offer_filter', $picture_xml, $product, $feed_id );

	// пропускаем товары без картинок
	$xfhu_skip_products_without_pic = xfhu_optionGET( 'xfhu_skip_products_without_pic', $feed_id, 'set_arr' );
	if ( ( $xfhu_skip_products_without_pic === 'on' ) && ( $picture_xml == '' ) ) {
		xfhu_error_log( 'FEED № ' . $feed_id . '; Товар с postId = ' . $post_id . ' пропущен т.к нет картинки даже в галерее; Файл: offer.php; Строка: ' . __LINE__, 0 );
		return $result_xml; /*continue;*/
	}

	$result_xml .= '<item>' . PHP_EOL;
	$result_xml .= '<id>' . $post_id . '</id>' . PHP_EOL;
	$result_xml .= $result_xml_cat;

	$xfhu_stock_days = xfhu_optionGET( 'xfhu_stock_days_default', $feed_id, 'set_arr' );
	if ( $xfhu_stock_days !== '' && $available === 'Под заказ' ) {
		$result_xml .= '<stock days="' . $xfhu_stock_days . '">' . $available . '</stock>' . PHP_EOL;
	} else {
		$result_xml .= '<stock>' . $available . '</stock>' . PHP_EOL;
	}
	$xfhu_pickup_options_days = xfhu_optionGET( 'xfhu_pickup_options_days_default', $feed_id, 'set_arr' );
	if ( $xfhu_pickup_options_days !== '' ) {
		$result_xml .= '<shipping>'.$xfhu_pickup_options_days.'</shipping>'.PHP_EOL;
		/*
		$result_xml .= '<pickup-options>'.PHP_EOL;
		$result_xml .= '<option days="'.$xfhu_pickup_options_days.'"/>'.PHP_EOL;
		$result_xml .= '</pickup-options>'.PHP_EOL;
		*/
	} else {
		// $result_xml .= '';
	}

	// code
	$xfhu_code = xfhu_optionGET( 'xfhu_code', $feed_id, 'set_arr' );
	switch ( $xfhu_code ) { /* disabled, sku, или id */
		case "disabled":
			// выгружать штрихкод нет нужды
			break;
		case "post_meta":
			$xfhu_code_post_meta_id = xfhu_optionGET( 'xfhu_code_post_meta', $feed_id, 'set_arr' );
			$xfhu_code_post_meta_id = trim( $xfhu_code_post_meta_id );
			if ( get_post_meta( $post_id, $xfhu_code_post_meta_id, true ) !== '' ) {
				$xfhu_code_xml = get_post_meta( $post_id, $xfhu_code_post_meta_id, true );
				$xfhu_code_xml = xfhu_replace_symbol( $xfhu_code_xml, $feed_id );
				$result_xml .= "<code>" . $xfhu_code_xml . "</code>" . PHP_EOL;
			}
			break;
		case "sku":
			// выгружать из артикула
			$sku_xml = $product->get_sku();
			if ( ! empty( $sku_xml ) ) {
				$sku_xml = xfhu_replace_symbol( $sku_xml, $feed_id );
				$result_xml .= "<code>" . $sku_xml . "</code>" . PHP_EOL;
			}
			break;
		default:
			$xfhu_code = (int) $xfhu_code;
			$xfhu_code_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_code ) );
			if ( ! empty( $xfhu_code_xml ) ) {
				$xfhu_code_xml = xfhu_replace_symbol( $xfhu_code_xml, $feed_id );
				$result_xml .= '<code>' . urldecode( $xfhu_code_xml ) . '</code>' . PHP_EOL;
			}
	}

	// guarantee
	$xfhu_guarantee = xfhu_optionGET( 'xfhu_guarantee', $feed_id, 'set_arr' );
	if ( $xfhu_guarantee === 'enabled' ) {
		$xfhu_guarantee_type = xfhu_optionGET( 'xfhu_guarantee_type', $feed_id, 'set_arr' );
		$xfhu_guarantee_value = xfhu_optionGET( 'xfhu_guarantee_value', $feed_id, 'set_arr' );
		switch ( $xfhu_guarantee_value ) {
			case "post_meta":
				$xfhu_guarantee_post_meta_id = xfhu_optionGET( 'xfhu_guarantee_post_meta', $feed_id, 'set_arr' );
				$xfhu_guarantee_post_meta_id = trim( $xfhu_guarantee_post_meta_id );
				if ( get_post_meta( $post_id, $xfhu_guarantee_post_meta_id, true ) !== '' ) {
					$xfhu_guarantee_value_xml = get_post_meta( $post_id, $xfhu_guarantee_post_meta_id, true );
					$result_xml .= '<guarantee type="' . $xfhu_guarantee_type . '">' . $xfhu_guarantee_value_xml . '</guarantee>' . PHP_EOL;
				}
				break;
			default:
				$xfhu_guarantee_value = (int) $xfhu_guarantee_value;
				$xfhu_guarantee_value_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_guarantee_value ) );
				if ( ! empty( $xfhu_guarantee_value_xml ) ) {
					$result_xml .= '<guarantee type="' . $xfhu_guarantee_type . '">' . urldecode( $xfhu_guarantee_value_xml ) . '</guarantee>' . PHP_EOL;
				}
		}
	}


	// штрихкод
	$xfhu_barcode = xfhu_optionGET( 'xfhu_barcode', $feed_id, 'set_arr' );
	switch ( $xfhu_barcode ) { /* disabled, sku, или id */
		case "disabled":
			// выгружать штрихкод нет нужды
			break;
		case "sku":
			// выгружать из артикула
			$sku_xml = $product->get_sku();
			if ( ! empty( $sku_xml ) ) {
				$result_xml .= "<barcode>" . $sku_xml . "</barcode>" . PHP_EOL;
			}
			break;
		default:
			$xfhu_barcode = (int) $xfhu_barcode;
			$xfhu_barcode_xml = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $xfhu_barcode ) );
			if ( ! empty( $xfhu_barcode_xml ) ) {
				$result_xml .= '<barcode>' . urldecode( $xfhu_barcode_xml ) . '</barcode>' . PHP_EOL;
			}
	}

	$result_xml .= $vendor_xml;
	$result_xml_name = apply_filters( 'xfhu_change_name_simple', $result_xml_name, $post_id, $product, $feed_id );
	$result_xml .= "<name>" . htmlspecialchars( $result_xml_name, ENT_NOQUOTES ) . "</name>" . PHP_EOL;
	$result_xml .= $result_xml_desc;
	$result_xml .= $picture_xml;

	$result_url = htmlspecialchars( get_permalink( $product->get_id() ) ); // урл товара
	$xfhu_clear_get = xfhu_optionGET( 'xfhu_clear_get', $feed_id, 'set_arr' );
	if ( $xfhu_clear_get === 'yes' ) {
		$result_url = xfhu_deleteGET( $result_url, 'url' );
	}
	$result_url = apply_filters( 'xfhu_url_filter', $result_url, $product, $CurCategoryId, $feed_id );
	$result_xml .= "<url>" . $result_url . "</url>" . PHP_EOL;

	// $price_xml = $product->get_price();
	if ( $currencyId_xml === 'UAH' ) {
		$result_xml .= '<priceRUAH>' . $price_xml . '</priceRUAH>' . PHP_EOL;
	} else {
		$result_xml .= '<priceRUSD>' . $price_xml . '</priceRUSD>' . PHP_EOL;
	}
	// старая цена
	$xfhu_oldprice = xfhu_optionGET( 'xfhu_oldprice', $feed_id, 'set_arr' );
	if ( $xfhu_oldprice === 'yes' ) {
		$sale_price = (float) $product->get_sale_price();
		$price_xml = (float) $price_xml;
		if ( $sale_price > 0 ) {
			if ( $price_xml === $sale_price ) {
				$oldprice_xml = $product->get_regular_price();
				$oldprice_name_tag = 'oldprice';
				$oldprice_name_tag = apply_filters( 'xfhu_oldprice_name_tag_filter', $oldprice_name_tag, $feed_id );
				$result_xml .= "<" . $oldprice_name_tag . ">" . $oldprice_xml . "</" . $oldprice_name_tag . ">" . PHP_EOL;
			}
		}
	}

	$result_xml .= $xfhu_condition_xml;
	$result_xml .= $xfhu_custom_xml;

	$params_arr = unserialize( xfhu_optionGET( 'xfhu_params_arr', $feed_id ) );
	if ( ! empty( $params_arr ) ) {
		$attributes = $product->get_attributes();
		foreach ( $attributes as $param ) {
			// проверка на вариативность атрибута не нужна
			$param_val = $product->get_attribute( wc_attribute_taxonomy_name_by_id( $param->get_id() ) );
			// если этот параметр не нужно выгружать - пропускаем
			$variation_id_string = (string) $param->get_id(); // важно, т.к. в настройках id как строки
			if ( ! in_array( $variation_id_string, $params_arr, true ) ) {
				continue;
			}
			$param_name = wc_attribute_label( wc_attribute_taxonomy_name_by_id( $param->get_id() ) );
			// если пустое имя атрибута или значение - пропускаем
			if ( empty( $param_name ) || empty( $param_val ) ) {
				continue;
			}
			$result_xml .= '<param name="' . $param_name . '">' . ucfirst( urldecode( $param_val ) ) . '</param>' . PHP_EOL;
		}
	}

	/*
	if ((get_post_meta($post_id, 'xfhu_condition', true) !== '') && (get_post_meta($post_id, 'xfhu_condition', true) !== 'off') && (get_post_meta($post_id, 'xfhu_reason', true) !== '')) {
	   $xfhu_condition = get_post_meta($post_id, 'xfhu_condition', true);
	   $xfhu_reason = get_post_meta($post_id, 'xfhu_reason', true);	
	   $result_xml .= '<condition type="'.$xfhu_condition.'">'.PHP_EOL;
		   $result_xml .= '<reason>'.$xfhu_reason.'</reason>'.PHP_EOL;
	   $result_xml .= '</condition>'.PHP_EOL;	
	} */
	$result_xml = apply_filters( 'xfhu_append_item_simple', $result_xml, $post_id, $product, $feed_id );

	$result_xml .= '</item>' . PHP_EOL;

	do_action( 'xfhu_after_simple_offer' );

	$ids_in_xml .= $post_id . ';' . $post_id . ';' . $price_xml . ';' . $CurCategoryId . PHP_EOL;

	return array( $result_xml, $ids_in_xml );
} // end function xfhu_unit($post_id) 