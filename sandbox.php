<?php if (!defined('ABSPATH')) {exit;}
function xfhu_run_sandbox() { 
	$x = 1; // установите 0, чтобы использовать песочницу и вернуть исключение
	if ($x === 0) { 
		echo __('The sandbox is working. The result will appear below', 'xfhu').':<br/>';
	}
	/* вставьте ваш код ниже */
	// Example:
	// $product = wc_get_product(8303);
	// echo $product->get_price();
	

	/* дальше не редактируем */
	if (!$x) {
		echo '<br/>';
		throw new Exception( __('The sandbox is working correctly', 'xfhu') );
	}
	echo 1/$x;
}