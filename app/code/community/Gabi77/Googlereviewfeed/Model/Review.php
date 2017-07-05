<?php
/**
 * @category   Gabi77
 * @package    Gabi77_Googleconfiance
 * @copyright  Copyright (c) 2015 Gabi77 (http://www.gabi77.com)
 * @author     Gabriel Janez <contact@gabi77.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Gabi77_Googlereviewfeed_Model_Review
{
  public function __construct() {
          $this->param = Mage::helper('googlereviewfeed')->parameterReviewfeed();
  }
	
  public function getReviews() {
  
	  $reviews = Mage::getModel('review/review')->getCollection()
	                   ->addStoreFilter(Mage::app()->getStore()->getId())
	                   ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
	                   ->setDateOrder('desc')
	                   ->addRateVotes();
	  
	  return $reviews;
  }
  
  /**
   * Product data ( url , sku , name , id ) 
   *
   * @return array
   *
   **/
  public function productInformation($id) {
  	
  	$_product = Mage::getModel('catalog/product')->load($id);

  	//$param = Mage::helper('googlereviewfeed')->parameterReviewfeed();
  	$type = $this->param['type'];
  	if($type == 'ID') {
  		$result = array('pname' => $_product->getName(),
  						'purl'	=> $_product->getProductUrl(),
  						'psku'	=> $_product->getId(),
  						'pid'	=> $_product->getId(),
  		);
  	} else {
  		$result = array('pname' => $_product->getName(),
  						'purl'	=> $_product->getProductUrl(),
  						'psku'	=> $_product->getSku(),
  						'pid'	=> $_product->getId(),
  		);
  	}
  	return $result;
  	
  }
  

  /**
   * All list reviews
   *
   * @return string
   *
   **/
  public function getAllReviews(){
  	
  	
	$_reviews = $this->getReviews();
  	$reviewsxml = '';
  	foreach ($_reviews as $item) {
  		$product = self::productInformation($item['entity_pk_value']);
  		//echo $item->getRatingVotes();
  		//die;
  		$reviewsxml .= '<review>
		            <!-- minimal sample - no optional elements/attributes -->
  					<review_id>'.$item->getReviewID().'</review_id>
		            <reviewer>
		                <name><![CDATA['.$item->getNickname().']]></name>
		            </reviewer>
		            <review_timestamp>'.str_replace(" ","T",$this->formatCreatedDate($item->getCreatedAt(), "Y/m/d H:i:s")).'Z</review_timestamp>
		            <content><![CDATA['.$item->getDetail().']]></content>
		            <review_url type="singleton">'.$product['purl'].'#comment</review_url>
		            <ratings>
		                <overall min="1" max="5">'.$this->getReviewFinalPercentage($item->getRatingVotes()).'</overall>
		            </ratings>
		            <products>
		                <product>
		                    <product_ids>
		                        <skus>
		                            <sku>'.$product['psku'].'</sku>
		                        </skus>
		                    </product_ids>
		                    <product_name><![CDATA['.$product['pname'].']]></product_name>
		                    <product_url>'.$product['purl'].'</product_url>
		                </product>
		            </products>
		        </review>';
  		
  	}
  	return $reviewsxml;
  	
  }
  
  /**
   * percentage for reviews (0/5)
   *
   * @return string
   *
   **/

  public function getReviewFinalPercentage($votes){
  
  	$cumulativeRating = 0;
  	$j=0;
  	foreach( $votes as $vote ) {
  		$cumulativeRating +=$vote->getPercent();
  		$j++;
  	}
  
  	$finalPercentage = 0;
  	if ($cumulativeRating != 0){
  		$finalPercentage = ($cumulativeRating/$j);
  	}
  
  	return $finalPercentage / 20;
  }
  
  /**
   * Format date 
   *
   * @return string
   *
   **/

  public function formatCreatedDate($date, $format){
  
  	$date = strtotime($date);
  	$reviewDate = date($format, $date);
  
  	return $reviewDate;
  }
}
