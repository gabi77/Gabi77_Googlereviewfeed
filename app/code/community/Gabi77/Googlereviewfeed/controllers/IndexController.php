<?php
/** 
 * @category Gabi77 
 * @package Gabi77_Googlereviewfeed 
 * @copyright Copyright (c) 2015 gabi77 (http://www.gabi77.com) 
 * @author Gabriel Janez <contact@gabi77.com> 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0) 
 * 
*/ 
class Gabi77_Googlereviewfeed_IndexController extends Mage_Core_Controller_Front_Action {
	
	 public function indexAction() {
        set_time_limit(0);
        ini_set('memory_limit', '1G'); 
		//$this->loadLayout (); // Va chercher les elements à afficher
		//$this->renderLayout (); // Affiche les elements
		
		//echo "Génération de mon catalogue produit";
		
		echo '<?xml version="1.0" encoding="UTF-8"?>
		<feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning"
		 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		 xsi:noNamespaceSchemaLocation=
		 "http://www.google.com/shopping/reviews/schema/product/2.1/product_reviews.xsd">
		    <aggregator>
		        <name>'.Mage::getStoreConfig('googlereviewfeed/general_settings/aggregator').'</name>
		    </aggregator>
		    <publisher>
		        <name>'.Mage::getStoreConfig('googlereviewfeed/general_settings/publisher').'</name>
		        <favicon>'.Mage::getStoreConfig('googlereviewfeed/general_settings/favicon').'</favicon>
		    </publisher>
		    <reviews>
		        '.Mage::getModel('googlereviewfeed/review')->getAllReviews().'
		    </reviews>
		</feed>';
	} 	
}