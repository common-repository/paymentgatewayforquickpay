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
class j10509QuickPay
	{
	function __construct($componentArgs)
		{
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=true; return;
			}
		$plugin = "QuickPay";
		$image = '<img src="'.get_showtime('eLiveSite').'j00510'.$plugin.'.gif" alt="$plugin" />';

		if ( !$componentArgs['show_anyway'] )
			{
			$invoice_id = (int) $componentArgs ['invoice_id'];
			if ( $invoice_id == 0 )
				{
				$this->retVals = false;
				}
			else
				{
				$invoice = jomres_singleton_abstract::getInstance( 'basic_invoice_details' );
				$invoice->gatherData($invoice_id);
				if ( (int)$invoice->subscription_id > 0 || (int) $invoice->is_commission > 0 )
					{
					$this->retVals= array ("name" => "QuickPay" , "friendlyname" => $gatewayname=jr_gettext('_JOMRES_CUSTOMTEXT_GATEWAYNAME'.$plugin,ucwords($plugin),false,false) ,"image"=>$image);
					}
				else // It's a booking invoice, let's check that this property offers settings for this gateway
					{
					$settings = get_plugin_settings("QuickPay",(int)$invoice->property_uid);

					if ( array_key_exists('client_id' , $settings ) && array_key_exists('secret', $settings )  )
						{}
					else
						$this->retVals = false;
					}
				}
			}
		else
			{
			$this->retVals= array ("name" => "QuickPay" , "friendlyname" => $gatewayname=jr_gettext('_JOMRES_CUSTOMTEXT_GATEWAYNAME'.$plugin,ucwords($plugin),false,false), "image"=>$image );
			}
		}

	function touch_template_language()
		{
		$plugin="QuickPay";
		echo jr_gettext('_JOMRES_CUSTOMTEXT_GATEWAYNAME'.$plugin,ucwords($plugin));
		}

	function getRetVals()
		{
		return $this->retVals;
		}
	}

?>