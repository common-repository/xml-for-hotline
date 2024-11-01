<?php if (!defined('ABSPATH')) {exit;}
function xfhu_extensions_page() { ?>
 <style>.button-primary {text-align: center; margin: 0 auto !important;}</style>
 <div id="xfhu_extensions" class="wrap"> 
  <h1 style="font-size: 32px; text-align: center; color: #5b2942;"><?php _e('Extensions for XML for Hotline', 'xfhu'); ?></h1> 
  <div id="dashboard-widgets-wrap"><div id="dashboard-widgets" class="metabox-holder">	
	<div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">
	 <div class="postbox">
	   <div class="inside">
		<table class="form-table"><tbody>
		 <tr>
		  <td class="overalldesc" style="font-size: 18px;">
		   <h1 style="text-align: center; color: #5b2942;">XML for Hotline Pro</h1>
		    <img style="max-width: 100%;" src="<?php echo xfhu_URL; ?>/img/ex1.jpg" alt="img" />
		   <ul>
			<li>&#10004; <?php _e('The ability to exclude products from certain categories', 'xfhu'); ?>;</li>
			<li>&#10004; <?php _e('Ability to exclude products by certain tags', 'xfhu'); ?>;</li>
			<li>&#10004; <?php _e('The ability to exclude products at a price', 'xfhu'); ?>;</li>			
			<li>&#10004; <?php _e('Ability to assign labels as categories', 'xfhu'); ?>;</li>
			<li>&#10004; <?php _e('Ability to download multiple images for products instead of one', 'xfhu'); ?>;</li>			
			<li>&#10004; <?php _e('Ability to remove the Visual Composer shortcodes from the description', 'xfhu'); ?>;</li>			
			<li>&#10004; <?php _e('The ability to add one attribute to the beginning of the product name, add three attributes to the end of the product name', 'xfhu'); ?>;</li>	
			<li>&#10004; <?php _e('Support UTM tags', 'xfhu'); ?>:
			<br/>utm_source - <?php _e('any text you specify, for example', 'xfhu'); ?> hotline.ua
			<br/>utm_campaign - <?php _e('any text you specify, for example', 'xfhu'); ?> red
			<br/>utm_content = <?php _e('Category ID or Product ID', 'xfhu'); ?>
			<br/>utm_medium = cpc
			<br/>utm_term = <?php _e('product ID', 'xfhu'); ?></li>
			<li>&#10004; <?php _e('Dimension Grid Support. For example', 'xfhu'); ?>:<br/>
			<span style="font-size: 14px;">&lt;param name=&quot;Размер&quot; unit=&quot;RU&quot;&gt;48&lt;/param&gt;<br/>
			&lt;param name=&quot;Размер&quot; unit=&quot;Months&quot;&gt;12-18&lt;/param&gt;<br/>
			&lt;param name=&quot;Размер&quot; unit=&quot;Height&quot;&gt;104&lt;/param&gt;</span></li>
			<li>&#10004; <?php _e('Even more stable work', 'xfhu'); ?>!</li>
		   </ul>
		   <p style="text-align: center;"><a class="button-primary" href="https://icopydoc.ru/product/xml-for-hotline-pro/?utm_source=xml-for-hotline&utm_medium=organic&utm_campaign=in-plugin-xml-for-hotline&utm_content=extensions&utm_term=poluchit-xml-pro" target="_blank"><?php _e('Get XML for Hotline Pro Now', 'xfhu'); ?></a><br /></p>		   
		  </td>
		 </tr>
		</tbody></table>
		</div>
	 </div>
    </div></div>
  </div></div>
 </div>  
<?php
} /* end функция расширений xfhu_extensions_page */