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
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################

class plugin_info_PaymentGatewayForQuickPay
	{
	function __construct()
	{
		$this->data=array(
			"name"=>"PaymentGatewayForQuickPay",
			"marketing"=>" Add QuickPay gateway functionality.",
			"version"=>(float)"4.4",
			"description"=> " Add QuickPay gateway functionality. ",
			"lastupdate"=>"2017/07/24",
			"min_jomres_ver"=>"9.3.0",
			"manual_link"=>'',
			'change_log'=>'',
			'highlight'=>'',
			'image'=>'',
			'demo_url'=>''
			);
		}
	}
?>