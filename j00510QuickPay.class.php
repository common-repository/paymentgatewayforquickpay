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

class j00510QuickPay {
	function __construct()
		{
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=true; return;
			}
		$plugin="QuickPay";
		$button="<IMG SRC=\"".get_showtime('eLiveSite')."j00510".$plugin.".gif\" border=\"0\">";
		$defaultProperty=getDefaultProperty();
		$ePointFilepath = get_showtime('ePointFilepath');

		// Default settings
		$settingArray=array();
		$settingArray['active']="0";
		$settingArray['currencycode']="GBP";

		$yesno = array();
		$yesno[] = jomresHTML::makeOption( '0', jr_gettext('_JOMRES_COM_MR_NO',_JOMRES_COM_MR_NO,FALSE) );
		$yesno[] = jomresHTML::makeOption( '1', jr_gettext('_JOMRES_COM_MR_YES',_JOMRES_COM_MR_YES,FALSE) );

		
		$query="SELECT setting,value FROM #__jomres_pluginsettings WHERE prid = '".(int)$defaultProperty."' AND plugin = '$plugin' ";
		$settingsList=doSelectSql($query);
		foreach ($settingsList as $set)
			{
			$settingArray[$set->setting]=$set->value;
			}
		
		$output['JR_GATEWAY_CONFIG_ACTIVE']=jr_gettext('_JOMRES_CUSTOMTEXT_GATEWAY_CONFIG_ACTIVE'.$plugin,"Active");
		$output['ACTIVE'] = jomresHTML::selectList( $yesno, 'active', 'class="inputbox" size="1"', 'value', 'text', $settingArray['active'] );
		$output['GATEWAY']=$plugin;
		$output['GATEWAYNAME']=ucwords($plugin);
		$output['GATEWAYLOGO']=$button;
		
		
		$output['ACTIVE'] = jomresHTML::selectList( $yesno, 'active', 'class="inputbox" size="1"', 'value', 'text', $settingArray['active'] );
		
		
		$output['CURRENCYCODE'] = $settingArray['currencycode'] ;
		
		
		
		
		$output['MERCHANTID'] = '<input type="text" name="merchantid" value="'.$settingArray['merchantid'].'" />';
		$output['AGREEMENTID'] = '<input type="text" name="agreementid" value="'.$settingArray['agreementid'].'" />';
		$output['PAYMENTKEY'] = '<input type="text" name="payment_window_api_key" value="'.$settingArray['payment_window_api_key'].'" />';
		
		$output['MERCHANT_ID'] = jr_gettext('MERCHANT_ID','Merchant Id');
		$output['AGREEMENT_ID'] = jr_gettext('AGREEMENT_ID','Agreement Id');
		$output['PAYMENT_WIND_KEY'] = jr_gettext('PAYMENT_WIND_KEY','Payment Window Api key');
		
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot( $ePointFilepath );
		$tmpl->readTemplatesFromInput( 'j00510'.$plugin.'.html' );
		$tmpl->addRows( 'edit_gateway', $pageoutput );
		$tmpl->displayParsedTemplate();
		}

	function touch_template_language()
		{
		$output=array();
		$plugin="QuickPay";
		
		foreach ($output as $o)
			{
			echo $o;
			echo "<br/>";
			}
		}
		
	function getRetVals()
		{
		return null;
		}
	}
