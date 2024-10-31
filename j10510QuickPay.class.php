<?php
/**
* Jomres CMS QuickPay Plugin
* @author Deligence Technologies Pvt Ltd. <sales@deligence.com>
* @version Jomres 9 
* @package Jomres
* @copyright	2016 Deligence Technologies Pvt Ltd.
* GPL2 license.
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################

class j10510QuickPay {
	function __construct()
		{
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		
		$plugin = "QuickPay";
		
		$settingArray=array();
		
		$settingArray['merchantid'] = array (
			"default" => "",
			"setting_title" => jr_gettext('MERCHANT_ID','Merchant Id'),
			"setting_description" => jr_gettext('MERCHANT_ID_DESC','Merchant Id'),
			"format" => "input"
			) ;
			
		$settingArray['agreementid'] = array (
			"default" => "",
			"setting_title" => jr_gettext('AGREEMENT_ID','Agreement Id'),
			"setting_description" => jr_gettext('AGREEMENT_ID_DESC','Agreement Id of QuickPay Account'),
			"format" => "input"
			) ;
			
		$settingArray['payment_window_api_key'] = array (
			"default" => "",
			"setting_title" => jr_gettext('PAYMENT_WIND_KEY','Payment Window Api key'),
			"setting_description" => jr_gettext('PAYMENT_WIND_KEY_DESC','Payment window api key of QuickPay Account'),
			"format" => "input"
			) ;

		$this->retVals = array ( "notes" => $notes , "settings" => $settingArray );
		}

	function getRetVals()
		{
		return $this->retVals;
		}
	}
?>