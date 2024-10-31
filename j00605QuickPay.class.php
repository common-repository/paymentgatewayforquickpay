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
class j00605QuickPay {
	function __construct($componentArgs)
		{
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		$tmpBookingHandler =jomres_getSingleton('jomres_temp_booking_handler');
		$property_uid=$tmpBookingHandler->getBookingPropertyId();
		$mrConfig		  = getPropertySpecificSettings($property_uid);
		$jomresConfig_sitename = get_showtime('sitename');

		$settings = get_plugin_settings("QuickPay",$property_uid);
		
		$log_path = JOMRES_SYSTEMLOG_PATH . "gateway_logs";
		if ( !is_dir( $log_path ) )
			{
			mkdir ( $log_path );
			}

		$log_level = "DEBUG";
		
		
		
		$merchantid=$settings['merchantid'];
		$agreementid=$settings['agreementid'];
		$payment_window_api_key=$settings['payment_window_api_key'];

		
		if (file_exists($log_path . "gateway_logs".JRDS.'QuickPay.log')) 
			{
			unlink($log_path . "gateway_logs".JRDS.'QuickPay.log');
			}
			$log_level = "FINE";

		if ( $merchantid != "" && $agreementid != "" && $payment_window_api_key != '')
			{
			
			$siteConfig = jomres_getSingleton('jomres_config_site_singleton');
			$jrConfig=$siteConfig->get();

			gateway_log(serialize($bookingdata));
			$bookingdata=$componentArgs['bookingdata'];
			$plugin="QuickPay";
			$query="SELECT setting,value FROM #__jomres_pluginsettings WHERE prid = '".(int)$bookingdata['property_uid']."' AND plugin = '".$plugin."' ";
			$settingsList=doSelectSql($query);

			foreach ($settingsList as $set)
				{
				$settingArray[$set->setting]=$set->value;
				}
			if ($jrConfig['useGlobalCurrency'] =="1")
				{
				$settingArray['currencycode']=$jrConfig['globalCurrencyCode'];
				}
				
			$paypal_settings =jomres_getSingleton('jrportal_paypal_settings');
			$paypal_settings->get_paypal_settings();
			
			
			if (count($settingArray)>0)
				{
				
				$this->quickpay_url = 'https://payment.quickpay.net';

				$this_script = JOMRES_SITEPAGE_URL_NOSEF.'&task=completebk&plugin='.$plugin.'&jsid='.get_showtime('jomressession');
				
				$bookingDeets=gettempBookingdata();

				$guestDeets=getbookingguestdata();
				$guestCountry=$guestDeets['country'];
				$quickpayLang="EN";

				switch ($guestCountry) 
					{
					case "NL":
						$quickpayLang="NL";
						break;
					case "DE":
					case "AT":
						$quickpayLang="DE";
						break;
					case "IT":
						$quickpayLang="IT";
						break;
					case "FR":
					case "CH":
					case "LU":
					case "BE":
						$quickpayLang="FR";
						break;
					case "PL":
						$quickpayLang="PL";
						break;
					case "ES":
					case "PT":
					case "MX":
						$quickpayLang="ES";
						break;
					case "GB":
					case "US":
					default:
						$quickpayLang="EN";
						break;
					}
				
				$deposit_required=$bookingDeets['deposit_required'];
				$booking_number=$bookingDeets['booking_number'];

				$transactionName='Paypal Transaction from '.$jomresConfig_sitename.' - '.$booking_number;
				
				$paymentKey=$booking_number;
				
				$continueurl=$this_script.'&action=success';
				$cancelurl=$this_script.'&action=cancel';
				$callbackurl=$this_script.'&action=notify';
				
				 $form_params = array(
				   "version"      => "v10",
				   "merchant_id"  => $merchantid,
				   "agreement_id" => $agreementid,
				   "order_id"     => $paymentKey,
				   "amount"       => number_format($deposit_required,2, '.', '')*100,
				   "currency"     => $settingArray['currencycode'],
				   "autocapture" => 1,
				   "language" => $quickpayLang,
				   "continueurl" => $continueurl,
				   "cancelurl"   => $cancelurl,
				   "callbackurl" =>$callbackurl,
				 );
								 

				$this->add_field('version', $form_params["version"]);
				$this->add_field('merchant_id',$form_params["merchant_id"]);			 
				$this->add_field('agreement_id',$form_params["agreement_id"]);
				$this->add_field('order_id',$form_params["order_id"]);
				$this->add_field('amount', $form_params["amount"]);
				$this->add_field('currency', $form_params["currency"]);
				$this->add_field('autocapture', $form_params["autocapture"]);
				$this->add_field('language', $form_params["language"]);
				$this->add_field('continueurl', $form_params["continueurl"]);
				$this->add_field('cancelurl',$form_params["cancelurl"]);
				$this->add_field('callbackurl',$form_params["callbackurl"]);
				
				$this->add_field('checksum', $this->sign($form_params, $payment_window_api_key));
				$this->submit_paypal_post();
				}
			}
		}
		
		
		function sign($params, $api_key) {
   		 ksort($params);
    	 $base = implode(" ", $params);
  		 return hash_hmac("sha256", $base, $api_key);
 		}
	
	#
	/**
	#
	 * Adds a field and value to the 'fields' variable
	#
	 */
	function add_field($field, $value)
		{
		$this->fields["$field"] = $value;
		}

	#
	/**
	#
	 * Submits the booking information to QuickPay
	#
	 */
	function submit_paypal_post()
		{
		?>
		<script>
		jomresJquery(document).ready(function() {
			document.forms['quickpay_form'].submit();
		});
		</script>
		<?php
		echo "<center><h2>Please wait, your order is being processed and you";
		echo " will be redirected to the QuickPay website.</h2></center>\n";
		echo "<form method=\"post\" name=\"quickpay_form\" ";
		echo "action=\"".$this->quickpay_url."\">\n";
		foreach ($this->fields as $name => $value) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "quickpay within 5 seconds...<br/><br/>\n";
		echo "<input name=\"submitbutton\" type=\"submit\" value=\"ClICK HERE\"></center>\n";
		echo "</form>\n";
		}
	function getRetVals()
		{
		return null;
		}
	}

?>