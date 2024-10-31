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

class j00610QuickPay 
	{
	function __construct()
		{
			
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch){$this->template_touchable=true; return;}
		
		$ePointFilepath=get_showtime('ePointFilepath');
		$tmpBookingHandler =jomres_getSingleton('jomres_temp_booking_handler');
		$property_uid=$tmpBookingHandler->getBookingPropertyId();
		$mrConfig		  = getPropertySpecificSettings($property_uid);
		
		$bookingdata = gettempBookingdata();
		
		gateway_log("Booking number ".$tmpBookingHandler->tmpbooking['booking_number'] );
		
		$this->messagelog 	= array();

		$settings = get_plugin_settings("QuickPay",$property_uid);
		
		$log_level = "DEBUG";
		
		if (file_exists($log_path . "gateway_logs".JRDS.'QuickPay.log')) 
			{
			unlink($log_path . "gateway_logs".JRDS.'QuickPay.log');
			}
		$log_level = "FINE";
				
		
			// Determine if the user approved the payment or not
			if (isset($_GET['action']) && $_GET['action'] == 'success'){
				
					if (!session_id()){
    					session_start();
						
					}
					if($_SESSION['complete'] != 'y'){
						ob_start();
						$tmpBookingHandler->updateBookingField('depositpaidsuccessfully',true);
						$result=insertInternetBooking(get_showtime('jomressession'),true,true);
						/*$booking_number=$_GET['booking_number'];
						$query="select a.id from #__jomresportal_invoices as a INNER JOIN #__jomres_contracts as b ON a.id=b.invoice_uid and b.tag=$booking_number";
						$invoice_id = doSelectSql($query,1);
						jr_import( 'jrportal_invoice' );
						$invoice = new jrportal_invoice();
						$invoice->id = $invoice_id;
						$invoice->getInvoice();
						$invoice->mark_invoice_paid();*/
						if (!$result){
							system_log( "<b>QuickPay payment completed successfully but booking insert failed</b>");
						}else{
							system_log( "<b>QuickPay booking inserted successfully</b>");
						}
						 $page = ob_get_contents();
						 $_SESSION['complete']='y';
						 $_SESSION['complete_msg']=$page;
						 ob_end_clean();
						 echo $_SESSION['complete_msg'];
					} else {
						echo $_SESSION['complete_msg'];
						session_destroy();
					}
						
				} else if (isset($_GET['action']) && $_GET['action'] == 'cancel'){
					$output = array ( "CANCELLED" => "QuickPay payment has been failed");
					$pageoutput[]=$output;
					$tmpl = new patTemplate();
					$tmpl->setRoot( $ePointFilepath);
					$tmpl->readTemplatesFromInput( 'payment_cancelled.html' );
					$tmpl->addRows( 'pageoutput', $pageoutput );
					$tmpl->displayParsedTemplate();
					gateway_log("QuickPay payment has been failed");
					return false;
				}
		}
	
	
	function getRetVals()
		{
		return null;
		}
	}
?>